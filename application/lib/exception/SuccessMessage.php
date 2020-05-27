<?php
/**
 * Created by Voke.
 * User: x_x94
 * Date: 2018/3/19
 * Time: 20:22
 * Email:x_x9403@163.com
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    // HTTP 状态码 400,200
    public $code = 200;

    // 错误信息
    public $msg = '操作成功';

    //自定义错误码
    public $errorCode = 0;

    //自动定义状态
    public $status = 1;
}