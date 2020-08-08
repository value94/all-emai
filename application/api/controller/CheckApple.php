<?php

namespace app\api\controller;

use app\api\model\CheckAppleModel;
use app\api\validate\CheckAppleValidate;
use app\lib\exception\CheckAppleException;
use think\Controller;

class CheckApple extends Controller
{
    public function getCheckAppleAccount()
    {
        // 校验获取参数
        //  $params = (new CheckAppleValidate())->goCheck();

        // 获取一个未使用账号
        $account = CheckAppleModel::getOneCheckApple();
        
        if (!$account) {
            throw new CheckAppleException(['msg' => '没有可用账号']);
        }

        // 设置为已经使用
        CheckAppleModel::update(['use_status' => 1], ['id' => $account['id']]);

        unset($account['id']);

        // 返回
        $account['status'] = 1;
        $account['msg'] = '成功获取待过检账号';

        return $account;
    }

    public function setCheckAccountStatus()
    {
        // 校验获取参数
        $params = (new CheckAppleValidate())->goCheck();

        // 验证账号是否存在
        $result = CheckAppleModel::checkCheckAppleExist($params['apple_account']);

        if (!$result) {
            throw new CheckAppleException(['msg' => '账号不存在']);
        }

        // 更新账号状态
        CheckAppleModel::update([
            'check_status' => $params['check_status'],
            'fail_reason' => isset($params['fail_reason']) ? $params['fail_reason'] : null,
            'update_time' => date('Y-m-d H:i:s'),
        ], ['id' => $result['id']]);

        return [
            'status' => 1,
            'msg' => '返回账号状态成功',
            'error_code' => 0
        ];
    }
}