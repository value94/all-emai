<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:05
 */

namespace app\api\controller;


use app\api\model\EmailModel;
use app\api\model\MachineModel;
use app\api\model\PhoneModel;
use app\api\validate\GetCodeValidate;
use app\api\validate\GetEmailValidate;
use app\api\validate\SendRegResultValidate;
use app\lib\exception\EmailException;
use app\lib\exception\SuccessMessage;
use PhpImap;
use PhpImap\Mailbox;
use think\Controller;

class Email extends Controller
{
    // 获取未使用邮箱
    public function getEmail()
    {
        // 数据验证
        $params = (new GetEmailValidate())->goCheck();

        // 获取一个未使用邮箱
        $email_data = EmailModel::getOneNotUseEmail();

        // 存储手机数据
        $phone_data = PhoneModel::checkPhone($params['phone_sn']);
        if (!$phone_data) {
            throw new EmailException(['msg' => 'phone SN 不存在']);
        }

        // 绑定手机数据
        EmailModel::update([
            'phone_id' => $phone_data['id'],
            'phone_sn' => $phone_data['phone_sn'],
        ], ['id' => $email_data['id']]);

        return [
            'status' => 1,
            'msg' => '成功获取邮箱',
            'email_name' => $email_data['email_name'],
            'email_password' => $email_data['email_password'],
            'connection_method' => $email_data['email_type']['connection_method'] == 1 ? 'imap' : 'pop3',
            'agreement' => $email_data['email_type']['connection_method'] == 1 ? $email_data['email_type']['imapsvr'] : $email_data['email_type']['pop3svr'],
            'email_port' => $email_data['email_type']['connection_method'] == 1 ? $email_data['email_type']['imap_port'] : $email_data['email_type']['pop3_port'],
        ];
    }

    // 获取注册验证码
    public function getCode()
    {
        // 数据验证
        $params = (new GetCodeValidate())->goCheck();
        // 获取邮箱数据
        $email_data = EmailModel::getEmailByWhere(['email_name' => $params['email_name']]);
        if (!$email_data) {
            throw new EmailException(['errorCode' => 30001, 'msg' => '不存在该邮箱数据']);
        }
        $email_model = new EmailModel();
        // 登录邮箱
        try {
            if ($email_data['email_type']['connection_method'] == 1) {
                $imap_path = '{' . $email_data['email_type']['imapsvr'] . ':' . $email_data['email_type']['imap_port'] . '/imap/ssl' . '}INBOX';
            } else {
                $imap_path = '{' . $email_data['email_type']['pop3svr'] . ':' . $email_data['email_type']['pop3_port'] . '/imap/ssl' . '}INBOX';
            }

            $mailbox = new Mailbox(
                $imap_path, // IMAP server and mailbox folder
                $email_data['email_name'], // Username for the before configured mailbox
                $email_data['email_password'], // Password for the before configured username
                '', // Directory, where attachments will be saved (optional)
                'UTF-8' // Server encoding (optional)
            );

            //如果catch没有捕获到，才会执行到这里
            set_exception_handler(function () use ($email_model, $params) {
                $email_model->where(['email_name' => $params['email_name']])->update(['use_status' => 2]);
                // 捕捉到错误,账号密码不正确
                echo json_encode(['status' => 0, 'msg' => '该账号密码不正确', 'email_name' => $params['email_name']]);
                exit();
            });
        } catch (PhpImap\Exceptions\InvalidParameterException $ex) {
            throw new EmailException(['msg' => '连接 IMAP服务参数设置错误']);
        }

        // 设置不接收附件
        $mailbox->setAttachmentsIgnore(true);
        // 获取所有邮件
        try {
            $mailsIds = $mailbox->searchMailbox('ALL');
        } catch (PhpImap\Exceptions\ConnectionException $ex) {
            // 登录不上,设置状态异常 user_status => 2
            EmailModel::where(['email_name' => $params['email_name']])->save(['use_status' => 2]);
            throw new EmailException(['msg' => '该账号连接不上 imap 服务']);
        }


        // 遍历所有邮件
        $code = '';
        foreach ($mailsIds as $mail_id) {
            $email = $mailbox->getMail(
                $mail_id, // ID of the email, you want to get
                true // Do NOT mark emails as seen (optional)
            );
            // Apple
            // appleid@id.apple.com

            if ($email->fromName == 'Apple' && $email->fromAddress == 'appleid@id.apple.com') {
                if ($email->textHtml) {
                    $email_content = $email->textHtml;
                } else {
                    $email_content = $email->textPlain;
                }
                preg_match_all("/<p><b>(\d*)<\/b><\/p><\/div>/U", $email_content, $pat_array);
//                mylog('获取到 Apple 邮件 : ', $email_content, 'email_error.log');

                if ($pat_array && isset($pat_array[0][0])) {
                    $code = substr($pat_array[0][0], 6, 6);
                } else {
                    mylog('邮件 : ', $params['email_name'] . '没有匹配到 Apple邮件中的验证码', 'email_error.log');
                }
            } else {
                mylog('邮件 : ', $params['email_name'] . '没有收到 Apple 邮件', 'email_error.log');
            }
        }

        // 没有邮件
        if (!$mailsIds) {
            throw new EmailException(['msg' => '该账号收件箱空']);
        } else {
            if ($code) {
                return ['status' => 1, 'msg' => '成功获取验证码', 'code' => $code];
            } else {
                return ['status' => 0, 'msg' => '获取验证码失败,请重试', 'code' => $code];
            }
        }
    }

    // 返回注册结果
    public function sendRegResult()
    {
        // 数据验证
        $params = (new SendRegResultValidate())->goCheck();

        // 验证邮箱是否存在
        $check = EmailModel::checkEmail($params['email_name']);

        if (!$check) {
            throw new EmailException(['msg' => '邮箱不存在']);
        }
        // 存储数据
        EmailModel::update($params, ['email_name' => $params['email_name']]);
        // 修改机器状态
        if ($params['reg_status'] == 1 && !empty($params['udid'])) {
            MachineModel::update([
                'use_status' => 1,
                'email_id' => $check['id']
            ], ['udid' => $params['udid']]);
            EmailModel::update(['use_status' => 1], ['email_name' => $params['email_name']]);
        }

        // 修改手机信息
        if (!empty($params['phone_sn'])) {
            PhoneModel::update(['status' => 1], ['phone_sn' => $params['phone_sn']]);
            // 自增状态次数
            if ($params['reg_status'] == 1) {
                PhoneModel::where(['phone_sn' => $params['phone_sn']])->setInc('success_job_count', 1);
            } else {
                PhoneModel::where(['phone_sn' => $params['phone_sn']])->setInc('failed_job_count', 1);

            }
        }


        throw new  SuccessMessage(['msg' => '保存状态成功']);
    }

    function myException($exception)
    {
        echo "<b>Exception:</b> ", $exception->getMessage();
    }
}