<?php

namespace app\command;

use app\admin\model\SmsPhoneModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Config;

class AutoUnusualSMSPhone extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('unusual:sms-phone')
            ->setDescription('Auto Unusual SMS Phone');;

    }

    protected function execute(Input $input, Output $output)
    {
        // 获取配置时间
        $phone_time_interval = Config::get('setting.unusual_phone_time');
        $can_time = date("Y-m-d H:i:s", strtotime("-{$phone_time_interval} minute"));

        // 设置异常
        $result = SmsPhoneModel::where('last_get_time', '<', $can_time)
            ->where('last_get_time', 'not null')
            ->update(['status' => 2]);

        // 指令输出
        if ($result) {
            $output->writeln('finish update count : ' . $result);
        } else {
            $output->writeln('dont have be overdue phone');
        }
    }
}
