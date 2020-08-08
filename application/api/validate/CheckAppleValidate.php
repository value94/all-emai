<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class CheckAppleValidate extends BaseValidate
{
    protected $rule = [
        'apple_account' => 'require',
        'check_status' => 'require',
    ];
}