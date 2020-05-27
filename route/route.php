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