<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ChangeAddress extends Command
{
    protected function configure()
    {
        // 查询指定时间没有更新的手机,设置为未运行
        // 指令配置
        $this->setName('update:address')
            ->setDescription('Convert address information');;

    }

    protected function execute(Input $input, Output $output)
    {
        // 获取五级地址
        $village = Db::table('s_area_code')->where('level', '=', 5)->select();
        $address_data = [];

        // 循环处理上级地址数据
        if ($village) {
            foreach ($village as $v) {
                // 查询省市县镇 数据
                $town = Db::table('s_area_code')->where('code', '=', $v['pcode'])->find();
                if ($town) {
                    $county = Db::table('s_area_code')->where('code', '=', $town['pcode'])->find();
                    if ($county) {
                        $city = Db::table('s_area_code')->where('code', '=', $county['pcode'])->find();
                        if ($city) {
                            $province = Db::table('s_area_code')->where('code', '=', $city['pcode'])->find();
                            if ($province){
                                // 记录数据
                                $address_data = [
                                    'country' => '中国',
                                    'province' => $province['name'],
                                    'city' => $city['name'],
                                    'street_one' => $county['name'],
                                    'street_two' => $town['name'],
                                    'street_three' => $v['name'],
                                    'postal_code' => substr($v['code'], 4, 6),
                                ];
                                Db::table('s_address')->insert($address_data);
                                $output->writeln('插入一条!');
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            /*// 存储到数据库
            $result = Db::table('s_address')->insertAll($address_data);
            if ($result) {
                $output->writeln('完成转化,共' . $result . '条');
            }*/
        }
    }
}
