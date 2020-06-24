<?php

namespace app\api\controller;

use app\api\model\PhoneModel;
use app\api\validate\GetAccountValidate;
use app\api\validate\PhoneValidate;
use app\lib\exception\PhoneException;
use app\lib\exception\SuccessMessage;
use think\Controller;

class Phone extends Controller
{
    public function sendPhoneInfo()
    {
        $params = (new PhoneValidate())->goCheck();

        // 验证是否已经存在
        $result = PhoneModel::checkPhone($params['phone_sn']);
        if (!$result) {
            throw new PhoneException(['msg' => '手机SN不存在']);
        } elseif ($result['status'] == 2) {
            throw new PhoneException(['msg' => '手机已停用']);
        }

        PhoneModel::update($params, ['phone_sn' => $params['phone_sn']]);

        throw new SuccessMessage(['msg' => '更新手机信息成功']);
    }

    public function getAccountBySN()
    {
        $params = (new GetAccountValidate())->goCheck();

        // 验证是否已经存在
        $result = PhoneModel::checkPhone($params['phone_sn']);

        if (!$result) {
            throw new PhoneException(['msg' => '手机SN不存在']);
        } elseif (empty($result['account_name'])) {
            throw new PhoneException(['msg' => '手机没有注册成功信息']);
        }

        // 返回账户数据
        return [
            'status' => 1,
            'msg' => '成功获取账号信息',
            'account_name' => $result['account_name'],
            'account_pass' => $result['account_pass'],
        ];

    }
}
