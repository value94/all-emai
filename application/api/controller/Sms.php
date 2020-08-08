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
    // 步骤3:上传短信验证码
    public function uploadSMS()
    {
        // 获取参数
        $params = (new UploadSmsValidate())->goCheck();

        // 验证token
        $sms_data = SmsModel::checkSms($params['token']);
        if (!$sms_data) {
            throw new SmsException(['msg' => 'token not exist']);
        }

        // 更新数据
        $result = SmsModel::where('token', $params['token'])->update([
            'code' => $params['code'],
            'receiving_status' => $params['status'],
            'fail_reason' => $params['fail_reason'],
            'upload_sms_time' => date('Y-m-d H:i:s')
        ]);

        // 自增上传次数
        SmsPhoneModel::where('id', '=', $sms_data['sms_phone_id'])->setInc('received_sms_count');


        if ($result) {
            throw new SuccessMessage(['msg' => 'Upload SMS data successfully']);
        } else {
            throw new SuccessMessage(['msg' => 'SMS data saving failed. Please try again', 'status' => 0]);
        }


    }

    // 步骤4:返回短信验证码
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

            // 自增获取次数
            SmsPhoneModel::where(['id' => $sms_data['sms_phone_id']])->setInc('get_sms_count');

            // 自增成功获取次数
            if ($sms_data['receiving_status'] == 1) {
                SmsPhoneModel::where(['id' => $sms_data['sms_phone_id']])->setInc('success_sms_count');
            }
            // 删除token缓存
            Cache::rm('sms_' . $sms_data['receiving_phone_sn']);

            return [
                'status' => 1,
                'msg' => 'Success',
                'code' => (string)$sms_data['code'],
                'upload_sms_time' => $sms_data['upload_sms_time']
            ];
        }
    }
}
