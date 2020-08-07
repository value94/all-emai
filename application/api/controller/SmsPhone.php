<?php

namespace app\api\controller;

use app\api\model\PhoneModel;
use app\api\model\SmsModel;
use app\api\model\SmsPhoneModel;
use app\api\validate\GetTaskTokenValidate;
use app\api\validate\SmsPhoneValidate;
use app\lib\exception\SmsPhoneException;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;

class SmsPhone extends Controller
{
    // 步骤1:获取一个空闲接收验证码手机
    public function getSmsPhone()
    {
        // 验证参数
        $params = (new SmsPhoneValidate())->goCheck();

        // 获取一个空闲手机
        $sms_phone = SmsPhoneModel::getOneNotUsingPhone();

        if (!$sms_phone) {
            throw new SmsPhoneException(['msg' => "do't have can use phone"]);
        }

        // 生成token
        $token = $sms_phone['phone_num'] . $this->msectime();

        // 缓存token,并设置过期时间
        $phone_time_interval = Config::get('setting.release_phone_time');
        Cache::set('sms_' . $sms_phone['phone_sn'], $token, $phone_time_interval * 60);

        // 返回数据
        $return_data = [
            'status' => 1,
            'error_code' => 0,
            'msg' => '',
            'token' => $token,
            'phone_num' => $sms_phone['phone_num'],
            'device_num' => $sms_phone['device_num'],
        ];

        // 设置短信手机状态为接码中
        SmsPhoneModel::update(['status' => 1], ['id' => $sms_phone['id']]);

        // 自增获取次数
        SmsPhoneModel::where(['id' => $sms_phone['id']])->setInc('get_phone_count');

        // 获取任务设备信息
        $job_phone = PhoneModel::checkPhone($params['phone_sn']);

        // 添加一条短信记录
        SmsModel::create([
            'token' => $token,
            'sms_phone_id' => $sms_phone['id'],
            'receiving_phone_sn' => $sms_phone['phone_sn'],
            'get_phone_num' => $sms_phone['device_num'],
            'receiving_phone_num' => empty($job_phone['number']) ? null : $job_phone['number']
        ]);

        return $return_data;
    }

    // 步骤2:短信手机验证是否需要查询短信
    public function getTaskToken()
    {
        // 验证参数
        $params = (new GetTaskTokenValidate())->goCheck();

        // 设置手机解除异常
        // 验证手机号
        $check_phone = Cache::get('sms_' . $params['phone_sn']);

        // 更新最后一次验证时间
        SmsPhoneModel::update([
            'last_get_time' => date('Y-m-d H:i:s'),
        ], ['phone_sn' => $params['phone_sn']]);

        if (!$check_phone) {
            // 设置手机解除异常
            SmsPhoneModel::update(['status' => 0,], [
                ['phone_sn', '=', $params['phone_sn']],
                ['status', 'neq', 3]
            ]);

            throw new SmsPhoneException([
                'msg' => "No need to get SMS",
                'errorCode' => 44100
            ]);
        }

        return [
            'token' => $check_phone,
            'msg' => 'Need to get SMS',
            "status" => 1,
            "error_code" => 0,
            "request_url" => "/api/GetTaskToken"
        ];
    }

    // 获取当前毫秒时间
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

        return $msectime;
    }
}
