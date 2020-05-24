<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class BuildKeygen extends Command {
    protected function configure() {
        // 指令配置
        $this->setName('keygen')->setDescription("生成env配置");
    }

    protected static function generateRandomString($length = 10) {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function execute(Input $input, Output $output) {
        $envPath = env('root_path') . ".env";

        if (!file_exists($envPath)) {
            $str = static::generateRandomString(15);

            $data = <<<EOF
app_name=snake后台管理系统
app_debug=true
salt=$str

[database]
type=mysql
hostname=192.168.99.100
database=snake
username=root
password=root
hostport=3306
prefix=s_
EOF;
            file_put_contents($envPath, $data);
            // 指令输出
            $output->info('env 生成成功');
            return;
        }

        $output->warning("不需要多次执行！");
    }
}
