<?php

namespace app\api\controller;

use app\api\model\IpAddressModel;
use app\api\validate\CheckIPValidate;
use app\lib\exception\IPException;
use app\lib\exception\SuccessMessage;
use think\Controller;

class IpAddress extends Controller
{
    // 获取未使用 ip
    public function getAvailableIP()
    {
        // 验证参数
//        $params = (new GetIPValidate())->goCheck();
        // 获取一个单位时间未使用 ip
        $ip_data = IpAddressModel::getAvailableIP();
        // 返回数据
        return ['status' => 1, 'msg' => '成功获取IP', 'ip' => $ip_data['ip']];
    }

    public function checkIP()
    {
        //验证参数
        $params = (new CheckIPValidate())->goCheck();

        $ip_data = IpAddressModel::checkIP($params['ip']);
        if ($ip_data) {
            // 更新 ip 的更新时间
            IpAddressModel::update(['update_time' => date('Y-m-d H:i:s')],['id' => $ip_data['id']]);
            throw new SuccessMessage(['msg' => 'ip可用']);
        } else {
            throw new IPException(['msg' => 'ip已过期']);
        }
    }

}
