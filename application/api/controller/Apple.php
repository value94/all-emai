<?php

namespace app\api\controller;

use app\api\model\AppleModel;
use app\api\validate\AppleValidate;
use app\lib\exception\AppleException;
use think\Controller;

class Apple extends Controller
{
    public function getAppleAccount()
    {
        // 校验获取参数
        //  $params = (new AppleValidate())->goCheck();

        // 获取一个未使用账号
        $account = AppleModel::getOneApple();
        if (!$account) {
            throw new AppleException(['msg' => '没有可用账号']);
        }

        // 增加使用次数
        AppleModel::where(['id' => $account['id']])->setInc('used_count');
        // 设置为已经使用
        AppleModel::update([
            'use_status' => 1,
            'used_time' => date('Y-m-d H:i:s')
        ], ['id' => $account['id']]);

        unset($account['id']);

        // 返回
        $account['status'] = 1;
        $account['msg'] = '成功获取备用账号';

        return $account;
    }

    public function setAccountCanUse()
    {
        // 校验获取参数
        $params = (new AppleValidate())->goCheck();

        // 验证账号是否存在
        $result = AppleModel::checkAppleExist($params['apple_account']);

        if (!$result) {
            throw new AppleException(['msg' => '账号不存在']);
        }

        // 更新账号状态
        AppleModel::update([
            'use_status' => 0,
            'used_time' => date('Y-m-d H:i:s')
        ], ['id' => $result['id']]);

        return [
            'status' => 1,
            'msg' => '设置账号可用成功',
            'error_code' => 0
        ];
    }

}