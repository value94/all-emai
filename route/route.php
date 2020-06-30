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

Route::post('api/GetAvailableIP', 'api/IpAddress/getAvailableIP');
Route::post('api/CheckIP', 'api/IpAddress/checkIP');

Route::post('api/GetAddress', 'api/Address/getAddress');

// 备用账号
Route::post('api/GetAppleAccount', 'api/Apple/getAppleAccount');
Route::post('api/SetAccountCanUse', 'api/Apple/setAccountCanUse');



Route::post('api/SendPhoneInfo', 'api/Phone/sendPhoneInfo');
Route::post('api/GetAccountBySN', 'api/Phone/getAccountBySN');