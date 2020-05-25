<?php
/**
 * Created by Voke.
 * User: x_x94
 * Date: 2018/1/24
 * Time: 16:54
 * Email:x_x9403@163.com
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;

class ExceptionHandler extends Handle
{
    //声明要返回的数据
    private $code;
    private $msg;
    private $errorCode;
    private $status;
    //需要返回客户端当前请求的URL路径

    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
            $this->status = $e->status;
        }
        else{
            if (config('app_debug'))
            {
                return parent::render($e);
            }
            else
            {
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);//记录错误日志
            }

        }
        //获取客户端请求的url
        $requst  = Request::instance();
        $result = [
            'msg' => $this->msg,
            'status' => $this->status,
            'error_code' => $this->errorCode,
            'request_url' => $requst->url(),
        ];
        return json($result, $this->code);
    }
    //记录自定义错误日志
    private function recordErrorLog(\Exception $e){
        Log::init([
            'type' => 'File',
            'path'  => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}