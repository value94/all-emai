<?php

namespace app\api\model;

use app\lib\exception\RegAccountException;
use think\Model;

class RegAccountModel extends Model
{
    protected $table = 's_reg_account';

    public static function getOneNotUseRegAccount()
    {
        $reg_data = self::where('reg_status', '=', 2)->order('id asc')->find();
        if ($reg_data) {
            self::update(['reg_status' => 3], ['id' => $reg_data['id']]);
            return $reg_data;
        } else {
            throw new RegAccountException(['msg' => '没有可用账号']);
        }
    }

    public static function checkRegAccount($account_name)
    {
        return self::where('account_name', '=', $account_name)->find();
    }

}
