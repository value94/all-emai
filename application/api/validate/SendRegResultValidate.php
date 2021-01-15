<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class SendRegResultValidate extends BaseValidate
{
    protected $rule = [
        "account_name" => "require",
        "phone_sn" => "require",
        "check_status" => "in:1,2,0",
        "reg_status" => "in:1,2,0"
    ];

}