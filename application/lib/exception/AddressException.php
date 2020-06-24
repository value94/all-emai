<?php
/**
 * Created by Voke.
 * User: x_x94
 * Date: 2018/1/25
 * Time: 10:40
 * Email:x_x9403@163.com
 */

namespace app\lib\exception;


class AddressException extends BaseException
{
    public $code = 200;
    public $msg = '获取地址错误';
    public $errorCode = 44000;
    public $status = 0;
}