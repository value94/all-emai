<?php

namespace app\command;

use app\admin\model\SmsPhoneModel;
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

        // 设置未运行
        $result = SmsPhoneModel::where([
            ['status', '=', 1],
            ['update_time', '<', $can_time]]
        )->update(['status' => 0]);

        // 指令输出
        if ($result) {
            $output->writeln('finish update count : ' . $result);
        } else {
            $output->writeln('dont have be overdue phone');
        }
    }
}
