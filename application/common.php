<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 生成JSON数据返回值
 */
function JSONReturn($result)
{
    return json_encode($result,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
}


/**
 * [payLog 支付日志log]
 * @param  [type] $mark        [备注]
 * @param  [type] $log_content [内容]
 * @param  string $keyp [名]
 * @return [type]              [description]
 */
function mylog($mark, $log_content, $keyp = "")
{
    $max_size = 30000000;
    if ($keyp == "") {
        $log_filename = \think\facade\Env::get('runtime_path') . '/log/' . date('Ym-d') . ".log";
    } else {
        $log_filename = \think\facade\Env::get('runtime_path') . '/log/' . $keyp . ".log";
    }

    if (file_exists($log_filename) && (abs(filesize($log_filename)) > $max_size)) {
        rename($log_filename, dirname($log_filename) . '/' . date('Ym-d-His') . $keyp . ".log");
    }

    $t = microtime(true);
    $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
    $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
    if (is_array($log_content)) {
        $log_content = JSONReturn($log_content);
    }

    file_put_contents($log_filename, '   ' . $d->format('Y-m-d H:i:s u') . " key：" . $mark . "\r\n" . $log_content . "\r\n------------------------ --------------------------\r\n", FILE_APPEND);
}