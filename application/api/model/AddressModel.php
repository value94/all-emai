<?php

namespace app\api\model;

use think\Model;

class AddressModel extends Model
{
    protected $table = 's_address';

    public static function getOneAddress()
    {
        return self::where(['use_status' => 0])
            ->field('id,country,province,city,street_one,street_two,street_three,postal_code')
            ->order('used_count')
            ->order('id')
            ->find();
    }
}
