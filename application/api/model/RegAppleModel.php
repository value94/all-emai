<?php

namespace app\api\model;

use think\Model;

class RegAppleModel extends Model
{
    protected $table = 's_reg_apple';

    public static function getOneRegApple()
    {
        return self::where(['use_status' => 0,'reg_status' => 2])
            ->field('id,apple_account,apple_pass')
            ->order('id')
            ->find();
    }

    public static function checkRegAppleExist($apple_account)
    {
        return self::where('apple_account', '=', $apple_account)->find();
    }
}
