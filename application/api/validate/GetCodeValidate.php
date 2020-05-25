<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:07
 */

namespace app\api\validate;


class GetCodeValidate extends BaseValidate
{
    protected $rule = [
        'email_name|邮箱名' => 'require',
    ];
}