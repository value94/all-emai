<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class GetMachineValidate extends BaseValidate
{
    protected $rule = [
        'force' => 'require|in:1,0',
    ];

}