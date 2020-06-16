<?php

namespace app\api\model;

use think\Model;

class PhoneModel extends Model
{
    protected $table = 's_phone';

    public static function checkPhone($phone_sn)
    {
        return self::where('phone_sn', $phone_sn)->find();
    }

}
