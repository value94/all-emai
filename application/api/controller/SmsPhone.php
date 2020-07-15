<?php

namespace app\api\controller;

use app\api\model\SmsModel;
use app\api\model\SmsPhoneModel;
use app\api\validate\GetTaskTokenValidate;
use app\api\validate\SmsPhoneValidate;
use app\lib\exception\SmsPhoneException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;

class SmsPhone extends Controller
{
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

        $phone_time_interval = Config::get('setting.release_phone_time');

        // 缓存token
        Cache::set('sms_' . $sms_phone['phone_num'], 1, $phone_time_interval * 60);

        // 返回数据
        $return_data = [
            'status' => 1,
            'error_code' => 0,
            'msg' => '',
            'token' => $token,
            'phone_num' => $sms_phone['phone_num'],
            'device_num' => $sms_phone['device_num'],
        ];

        // 更新设备状态
        SmsPhoneModel::update(['status' => 1], ['id' => $sms_phone['id']]);
        // 自增获取次数
        SmsPhoneModel::where(['id' => $sms_phone['id']])->setInc('get_phone_count');
        // 添加任务记录
        SmsModel::create([
            'token' => $token,
            'sms_phone_id' => $sms_phone['id'],
            'get_phone_num' => $sms_phone['phone_num'],
            'receiving_phone_num' => $params['phone_sn']
        ]);

        return $return_data;
    }

    // 验证是否需要查询短信
    public function getTaskToken()
    {
        // 验证参数
        $params = (new GetTaskTokenValidate())->goCheck();

        // 验证手机号
//        $check_phone = SmsPhoneModel::checkSmsPhone($params['phone_num']);
        $check_phone = Cache::get('sms_' . $params['phone_num']);

        if (!$check_phone) {
            throw new SmsPhoneException(['msg' => "No need to get SMS"]);
        }

        throw new SuccessMessage(['msg' => 'Need to get SMS']);


    }

    // 获取当前毫秒时间
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

        return $msectime;
    }
}
