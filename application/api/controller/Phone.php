<?php

namespace app\api\controller;

use app\api\model\PhoneModel;
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
        if ($result) {
            throw new PhoneException(['msg' => '手机SN已存在']);
        }
        $params['status'] = 1;
        PhoneModel::create($params);

        throw new SuccessMessage(['msg' => '添加成功']);
    }
}
