<?php

namespace app\api\model;

use app\lib\exception\IPException;
use think\facade\Config;
use think\Model;

class IpAddress extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getAvailableIP()
    {
        // 获取一个 单位时间未使用 ip
        $email_time_interval = Config::get('setting.email_time_interval');
        $can_time = date("Y-m-d H:i:s", strtotime("-{$email_time_interval} hour"));

        $ip_data = self::where('use_status', '<>', 2)
            ->where('update_time', '<', $can_time)
            ->order('id asc')
            ->find();

        // 返回数据
        if ($ip_data) {
            self::where('id', '=', $ip_data['id'])->update(['update_time' => date("Y-m-d H:i:s")]);
            return $ip_data;
        } else {
            throw new IPException(['msg' => '没有可用IP']);
        }
    }
}
