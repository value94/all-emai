<?php

namespace app\api\model;

use think\Model;

class CheckAppleModel extends Model
{
    protected $table = 's_check_apple';

    public static function getOneCheckApple()
    {
        return self::where(['use_status' => 0])//,'check_status' => 2
            ->field('id,apple_account,apple_pass')
            ->order('id')
            ->find();
    }

    public static function checkCheckAppleExist($apple_account)
    {
        return self::where('apple_account', '=', $apple_account)->find();
    }
}
