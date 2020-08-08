<?php

namespace app\command;

use app\admin\model\SmsPhoneModel;
use app\api\model\SmsModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Config;

class AutoReleaseSMSPhoneStatus extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('release:sms-phone')
            ->setDescription('Auto Release SMS Phone');;

    }

    protected function execute(Input $input, Output $output)
    {
        // 获取配置时间
        $phone_time_interval = Config::get('setting.release_phone_time');
        $can_time = date("Y-m-d H:i:s", strtotime("-{$phone_time_interval} minute"));

        // 获取需要释放的设备
        $sms_phone = SmsPhoneModel::where([
            ['status', '=', 1],
            ['update_time', '<', $can_time]
        ])->select();


        if ($sms_phone) {
            foreach ($sms_phone as $item) {

                // 设置短信状态失败
                SmsModel::where('sms_phone_id', '=', $item['id'])
                    ->where('receiving_status', '=', null)
                    ->where('create_time', '<', $can_time)
                    ->update(['receiving_status' => 0, 'fail_reason' => '返回超时']);

                // 释放设备
                SmsPhoneModel::where(['id' => $item['id']])->update(['status' => 0]);
            }

            $output->writeln('finish update count : ' . count($sms_phone));
        } else {
            $output->writeln('dont have be overdue phone');
        }
    }
}
