<?php

namespace app\command;

use app\api\model\PhoneModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Config;

class AutoUpdatePhoneStatus extends Command
{
    protected function configure()
    {
        // 查询指定时间没有更新的手机,设置为未运行
        // 指令配置
        $this->setName('update:phone-status')
            ->setDescription('auto set phone be overdue');;

    }

    protected function execute(Input $input, Output $output)
    {
        // 获取配置时间
        $phone_time_interval = Config::get('setting.phone_time_interval');
        $can_time = date("Y-m-d H:i:s", strtotime("-{$phone_time_interval} minute"));

        // 设置未运行
        $result = PhoneModel::where([
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
