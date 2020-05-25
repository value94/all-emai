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
        $params['email_id'] = $email_data['id'];
        MachineModel::create($params);

        return ['email_name' => $email_data['email_name']];
    }

    public function getCode()
    {
        // 数据验证
        $params = (new GetCodeValidate())->goCheck();

        // 获取邮箱数据
        $email_data = EmailModel::getEmailByWhere(['email_name' => $params['email_name']]);

        // 登录邮箱

        // 获取邮件

        // 解析邮件中的验证码

        // 返回验证码
    }
}