<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;

class PhoneModel extends Model
{
    use SoftDelete;
    protected $table = 's_phone';

    public static function checkPhone($phone_sn)
    {
        $result = self::where('phone_sn', $phone_sn)->find();
        if ($result) {
            // 更新状态
            self::update(['status' => 1], ['id' => $result['id']]);
        }
        return $result;
    }

}
