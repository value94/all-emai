<?php

namespace app\api\model;

use think\Model;

class RegChannelModel extends Model
{
    protected $table = 's_channel';

    public static function getNextChannel()
    {
        // 根据rank、used_count 获取一个通道
        $channel = self::order('used_count asc')->find();
        self::where('id', '=', $channel['id'])->setInc('used_count');

        return $channel;
    }
}
