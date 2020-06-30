<?php

namespace app\api\model;

use think\Model;

class AppleModel extends Model
{
    protected $table = 's_address';

    public static function getOneApple()
    {
        return self::where(['use_status' => 0])
            ->order('used_count')
            ->order('id')
            ->find();
    }

    public static function checkAppleExist($apple_account)
    {
        return self::where('apple_account', '=', $apple_account)->find();
    }
}
