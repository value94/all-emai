<?php
/**
 * Created by Voke.
 * User: x_x94
 * Date: 2018/1/25
 * Time: 10:40
 * Email:x_x9403@163.com
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 401;
    public $msg = '参数错误';
    public $errorCode = 10000;
    public $status = 0;
}