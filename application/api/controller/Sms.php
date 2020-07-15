<?php

namespace app\api\controller;

use app\api\model\SmsModel;
use app\api\model\SmsPhoneModel;
use app\api\validate\GetSmsValidate;
use app\api\validate\UploadSmsValidate;
use app\lib\exception\SmsException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use think\facade\Cache;

class Sms extends Controller
{
    public function uploadSMS()
    {
        // 获取参数
        $params = (new UploadSmsValidate())->goCheck();

        // 验证手机
        $sms_phone = SmsModel::checkSms($params['token']);
        if (!$sms_phone) {
            throw new SmsException(['msg' => 'token not exist']);
        }

        // 更新数据
        $result = SmsModel::where('token', $params['token'])->update([
            'code' => $params['code'],
            'receiving_status' => $params['status'],
            'fail_reason' => $params['fail_reason'],
            'upload_sms_time' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            throw new SuccessMessage(['msg' => 'Upload SMS data successfully']);
        } else {
            throw new SuccessMessage(['msg' => 'SMS data saving failed. Please try again', 'status' => 0]);
        }


    }

    public function getSMSCode()
    {
        // 获取参数
        $params = (new GetSmsValidate())->goCheck();

        // 验证手机
        $sms_data = SmsModel::checkSms($params['token']);
        if (!$sms_data) {
            throw new SmsException(['msg' => 'token not exist']);
        }

        // 判断短信是否已经上传
        if (empty($sms_data['code'])) {
            throw new SuccessMessage([
                'msg' => 'The SMS has not been uploaded yet',
                'status' => 0
            ]);
        } else {
            // 更新获取短信状态
            SmsModel::where('token', $params['token'])->update([
                'sending_sms_time' => date('Y-m-d H:i:s')
            ]);
            // 释放手机
            SmsPhoneModel::where(['id' => $sms_data['sms_phone_id']])->update(['status' => 0]);

            // 删除token缓存
            Cache::clear('sms_' . $sms_data['receiving_phone_num']);

            return [
                'status' => 1,
                'msg' => 'Success',
                'code' => $sms_data['code'],
                'upload_sms_time' => $sms_data['upload_sms_time']
            ];
        }
    }
}
