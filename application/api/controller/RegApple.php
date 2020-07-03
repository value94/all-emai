<?php

namespace app\api\controller;

use app\api\model\RegAppleModel;
use app\api\validate\RegAppleValidate;
use app\lib\exception\RegAppleException;
use think\Controller;

class RegApple extends Controller
{
    public function getRegAppleAccount()
    {
        // 校验获取参数
        //  $params = (new RegAppleValidate())->goCheck();

        // 获取一个未使用账号
        $account = RegAppleModel::getOneRegApple();
        if (!$account) {
            throw new RegAppleException(['msg' => '没有可用账号']);
        }

        // 设置为已经使用
        RegAppleModel::update(['use_status' => 1], ['id' => $account['id']]);

        unset($account['id']);

        // 返回
        $account['status'] = 1;
        $account['msg'] = '成功获取待激活账号';

        return $account;
    }

    public function setRegAccountStatus()
    {
        // 校验获取参数
        $params = (new RegAppleValidate())->goCheck();

        // 验证账号是否存在
        $result = RegAppleModel::checkRegAppleExist($params['apple_account']);

        if (!$result) {
            throw new RegAppleException(['msg' => '账号不存在']);
        }

        // 更新账号状态
        RegAppleModel::update([
            'reg_status' => $params['reg_status'],
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