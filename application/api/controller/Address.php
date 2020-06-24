<?php

namespace app\api\controller;

use app\api\model\AddressModel;
use app\lib\exception\AddressException;
use think\Controller;

class Address extends Controller
{
    // 获取一个地址
    public function getAddress()
    {
        // 验证参数
        // 获取一个地址
        $address = AddressModel::getOneAddress();
        if (!$address) {
            throw new AddressException(['msg' => '没有可用地址']);
        }

        // 增加使用次数
        AddressModel::where(['id' => $address['id']])->setInc('used_count');
        unset($address['id']);

        // 返回
        $address['status'] = 1;
        $address['msg'] = '成功获取地址';

        return $address;


    }
}