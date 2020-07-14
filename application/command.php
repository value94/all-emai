<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    'update:phone-status' => 'app\command\AutoUpdatePhoneStatus',
    'update:apple-status' => 'app\command\AutoSetAccountCanUse',
    'update:address' => 'app\command\ChangeAddress',
    'release:sms-phone' => 'app\command\AutoReleaseSMSPhoneStatus',
];
