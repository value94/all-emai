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
        "email_name" => "require",
        "reg_status" => "require|in:1,2,0",
    ];

}