<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;

class SmsModel extends Model
{
    use SoftDelete;
    protected $table = 's_sms';

    public static function checkSms($token)
    {
        $result = self::where('token', $token)->find();
        /*if ($result) {
            // 更新状态
            self::update(['status' => 1], ['id' => $result['id']]);
        }*/
        return $result;
    }

    public static function getOneNotUsingPhone()
    {
        return self::where('status', '=', 0)->order('get_phone_count asc')->find();
    }

}
