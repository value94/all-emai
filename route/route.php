<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::any('api/getEmail', 'api/Email/getEmail');
Route::any('api/getCode', 'api/Email/getCode');
Route::any('api/SendRegResult', 'api/Email/sendRegResult');

Route::any('api/GetUnUsedMachine', 'api/Machine/getUnUsedMachine');
Route::get('api/GetNewDevice', 'api/Machine/GetNewDevice');
Route::post('api/SendDeviceCert', 'api/Machine/SendDeviceCert');
Route::post('api/SendUsedCount', 'api/Machine/SendUsedCount');
Route::get('api/GetFullDevice', 'api/Machine/GetFullDevice');
Route::get('api/BadDevice', 'api/Machine/BadDevice');


Route::post('api/GetAvailableIP', 'api/IpAddress/getAvailableIP');
Route::post('api/CheckIP', 'api/IpAddress/checkIP');

Route::post('api/GetAddress', 'api/Address/getAddress');

// 备用账号
Route::post('api/GetAppleAccount', 'api/Apple/getAppleAccount');
Route::post('api/SetAccountCanUse', 'api/Apple/setAccountCanUse');

// 待激活账号
Route::post('api/GetRegAppleAccount', 'api/RegApple/getRegAppleAccount');
Route::post('api/SetRegAccountStatus', 'api/RegApple/setRegAccountStatus');

// 带过检账号
Route::post('api/GetCheckAppleAccount', 'api/CheckApple/getCheckAppleAccount');
Route::post('api/SetCheckAccountStatus', 'api/CheckApple/setCheckAccountStatus');

// 程序版本
Route::post('api/CheckVersions', 'api/Versions/CheckVersions');

// sms手机
Route::post('api/GetSmsPhone', 'api/SmsPhone/getSmsPhone');
Route::post('api/GetTaskToken', 'api/SmsPhone/getTaskToken');
Route::post('api/UploadSMS', 'api/Sms/uploadSMS');
Route::post('api/GetSMSCode', 'api/Sms/getSMSCode');


Route::post('api/SendPhoneInfo', 'api/Phone/sendPhoneInfo');
Route::post('api/GetAccountBySN', 'api/Phone/getAccountBySN');