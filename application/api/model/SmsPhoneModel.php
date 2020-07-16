<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;

class SmsPhoneModel extends Model
{
    use SoftDelete;
    protected $table = 's_sms_phone';

    public static function checkSmsPhone($token)
    {
        return self::where('token', '=', $token)->find();
    }

    public static function getPhoneByNum($phone_num)
    {
        return self::where('phone_num', '=', $phone_num)->find();
    }

    // 获取一个空闲手机
    public static function getOneNotUsingPhone()
    {
        return self::where('status', '=', 0)->order('get_phone_count asc')->find();
    }

}
