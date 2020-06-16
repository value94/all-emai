<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class PhoneValidate extends BaseValidate
{
    protected $rule = [
        'phone_sn' => 'require',
        'program_version' => 'require',
//        'des' => 'require',
    ];

}