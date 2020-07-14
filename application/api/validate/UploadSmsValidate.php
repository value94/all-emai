<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class UploadSmsValidate extends BaseValidate
{
    protected $rule = [
        'phone_sn' => 'require',
        'token' => 'require',
        'status' => 'require|in:0,1',
        'code' => 'require|integer|length:6',
//       'sms_content' => 'require'
//       fail_reason
    ];

}