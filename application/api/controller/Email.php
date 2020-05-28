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
    public function getEmail()
    {
        // 数据验证
        $params = (new GetEmailValidate())->goCheck();

        // 获取一个未使用邮箱
        $email_data = EmailModel::getOneNotUseEmail();

        // 存储机器数据
        /* $params['email_id'] = $email_data['id'];
         MachineModel::create($params);*/

        return ['status' => 1, 'msg' => '成功获取邮箱', 'email_name' => $email_data['email_name']];
    }

    public function getCode()
    {
        // 数据验证
        $params = (new GetCodeValidate())->goCheck();
        // 获取邮箱数据
        $email_data = EmailModel::getEmailByWhere(['email_name' => $params['email_name']]);

        // 登录邮箱
        // 为进一步操作创建 PhpImap 实例
        $mailbox = new Mailbox(
            '{' . $email_data['imapsvr'] . ':993/imap/ssl' . '}INBOX', // IMAP server and mailbox folder
            $email_data['email_name'], // Username for the before configured mailbox
            $email_data['email_password'], // Password for the before configured username
            '', // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );
        // 设置不接收附件
        $mailbox->setAttachmentsIgnore(true);

        // 获取所有邮件
        try {
            $mailsIds = $mailbox->searchMailbox('ALL');
        } catch (PhpImap\Exceptions\ConnectionException $ex) {
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
                mylog('获取到 Apple 邮件 : ', $email_content, 'email_error.log');

                if ($pat_array && isset($pat_array[0][0])) {
                    $code = substr($pat_array[0][0], 6, 6);
                } else {
                    mylog('邮件 : ', '没有匹配到 Apple邮件中的验证码', 'email_error.log');
                }
            } else {
                mylog('邮件 : ', '没有收到 Apple 邮件', 'email_error.log');
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
        if ($params['reg_status'] == 1) {
            MachineModel::update([
                'use_status' => 1,
                'email_id' => $check['id']
            ], ['udid' => $params['udid']]);
            EmailModel::update(['use_status' => 1], ['email_name' => $params['email_name']]);
        }

        throw new  SuccessMessage(['msg' => '保存状态成功']);
    }


}