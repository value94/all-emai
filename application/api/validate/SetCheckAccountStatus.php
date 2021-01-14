<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class SetCheckAccountStatus extends BaseValidate
{
    protected $rule = [
        "apple_account" => "require",
        "check_status" => "require|in:1,0",
//        "fail_reason" => "require",
    ];

}