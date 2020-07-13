<?php
/**
 * Created by V.
 * Date: 2018/5/15
 * Time: 15:17
 */

namespace app\api\validate;


class VersionsValidate extends BaseValidate
{
    protected $rule = [
        'BankName' => 'require',
        'sign' => 'require',
        /*'Versions' => 'require'*/
    ];
}