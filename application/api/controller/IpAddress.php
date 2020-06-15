<?php

namespace app\api\controller;

use think\Controller;

class IpAddress extends Controller
{
    // 获取未使用 ip
    public function getAvailableIP()
    {
        // 验证参数
//        $params = (new GetIPValidate())->goCheck();
        // 获取一个单位时间未使用 ip
        $ip_data = \app\api\model\IpAddress::getAvailableIP();
        // 返回数据
        return ['status' => 1, 'msg' => '成功获取IP', 'ip' => $ip_data['ip']];
    }

}
