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

//公共文件，用来传入xls并下载
function downloadExcel($newExcel, $filename, $format)
{
    // $format只能为 Xlsx 或 Xls
    if ($format == 'Xlsx') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    } elseif ($format == 'Xls') {
        header('Content-Type: application/vnd.ms-excel');
    }
    header('Access-Control-Expose-Headers: Content-Disposition');
    header("Content-Disposition: attachment;filename="
        . $filename . date('Y-m-d') . '.' . strtolower($format));
    header('Cache-Control: max-age=0');
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($newExcel, $format);

    $objWriter->save('php://output');

    //通过php保存在本地的时候需要用到
    //$objWriter->save($dir.'/demo.xlsx');

    //以下为需要用到IE时候设置
    // If you're serving to IE 9, then the following may be needed
    //header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    //header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    //header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    //header('Pragma: public'); // HTTP/1.0
    exit;
}