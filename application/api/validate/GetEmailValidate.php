<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class GetEmailValidate extends BaseValidate
{
    protected $rule = [
        'phone_sn' => 'require',
        /*'HWModelStr' => 'require',
        'ProductType' => 'require',
        'bt' => 'require',
        'imei' => 'require',
        'sn' => 'require',
        'udid' => 'require',
        'wifi' => 'require',*/
    ];

}