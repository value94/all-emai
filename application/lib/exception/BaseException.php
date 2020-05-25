<?php
/**
 * Created by Voke.
 * User: x_x94
 * Date: 2018/1/24
 * Time: 16:55
 * Email:x_x9403@163.com
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    // HTTP 状态码 400,200
    public $code = 400;

    // 错误信息
    public $msg = '参数错误';

    //自定义错误码
    public $errorCode = 10000;

    public $status = 1;

    public function __construct($params = [])
    {
        if (!is_array($params))
        {
            return;
//            throw new Exception('参数必须是数组');
        }
        if (array_key_exists('code', $params))
        {
            $this->code = $params['code'];
        }

        if (array_key_exists('msg', $params))
        {
            $this->msg = $params['msg'];
        }

        if (array_key_exists('errorCode', $params))
        {
            $this->errorCode = $params['errorCode'];
        }
        if (array_key_exists('status', $params))
        {
            $this->status = $params['status'];
        }
    }
}