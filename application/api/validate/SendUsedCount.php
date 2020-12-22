<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class SendUsedCount extends BaseValidate
{
    protected $rule = [
        'Serial' => 'require',
        'UsedCount' => 'require|number',
    ];

}