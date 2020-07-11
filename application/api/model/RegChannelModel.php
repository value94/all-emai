<?php

namespace app\api\model;

use think\facade\Cache;
use think\Model;

class RegChannelModel extends Model
{
    protected $table = 's_channel';

    public static function getNextChannel($phone_sn)
    {
        // 缓存顺序列表
        $channel = self::order('rank asc')->field('id,channel_name,process_name,bid,remark')->select();
        $channel = json_decode(json_encode($channel), true);
        $phone_channel = (int)Cache::get('channel_' . $phone_sn);

        if ($phone_channel) {
            if (array_key_exists($phone_channel, $channel)) {
                // 设置下一个坐标
                Cache::set('channel_' . $phone_sn, $phone_channel + 1);
                return $channel[$phone_channel];
            } else {
                // 设置初始坐标
                Cache::set('channel_' . $phone_sn, 1);
                return $channel[0];
            }
        } else {
            // 设置初始坐标
            Cache::set('channel_' . $phone_sn, 1);
            return $channel[0];
        }
    }
}
