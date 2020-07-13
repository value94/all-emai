<?php

namespace app\api\controller;

use app\api\model\PhoneModel;
use app\api\validate\GetAccountValidate;
use app\api\validate\PhoneValidate;
use app\lib\exception\PhoneException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use think\facade\Config;

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

        // 获取程序配置
        $config_data = Config::pull('setting');

        return [
            'msg' => '更新手机信息成功',
            'status' => 1,
            'error_code' => 0,
            'request_url' => '/api/SendPhoneInfo',
            'test_status' => $result['test_status'],
            'payment_method' => (int) $config_data['payment_method'],
            'change_machine' => (int) $config_data['change_machine'],
            'openVPN' => (int) $config_data['openVPN'],
            'clean_files' => (int) $config_data['clean_files'],
            'restart_process' => (int) $config_data['restart_process'],
            'account_operation' => (int) $config_data['account_operation'],
        ];
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
