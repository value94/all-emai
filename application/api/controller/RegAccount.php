<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:05
 */

namespace app\api\controller;


use app\api\model\RegAccountModel;
use app\api\validate\GetRegAccountValidate;
use app\api\validate\SendRegResultValidate;
use app\lib\exception\RegAccountException;
use app\lib\exception\SuccessMessage;
use think\Controller;

class RegAccount extends Controller
{
    // 获取未使用账号
    public function getAccount()
    {
        // 数据验证
//        $params = (new GetRegAccountValidate())->goCheck();

        // 获取一个未使用账号
        $account = RegAccountModel::getOneNotUseRegAccount();

        return [
            'status' => 1,
            'msg' => '成功获取账号',
            'account_name' => $account['account_name'],
            'email_password' => $account['account_password'],
            'birthday' => $account['birthday'],
            'question1' => $account['question1'],
            'answer1' => $account['answer1'],
            'question2' => $account['question2'],
            'answer2' => $account['answer2'],
            'question3' => $account['question3'],
            'answer3' => $account['answer3'],
        ];
    }


    // 返回注册结果
    public function sendRegResult()
    {
        // 数据验证
        $params = (new SendRegResultValidate())->goCheck();

        // 验证账号是否存在
        $check = RegAccountModel::checkRegAccount($params['account_name']);

        if (!$check) {
            throw new RegAccountException(['msg' => '账号不存在']);
        }

        // 存储数据
        RegAccountModel::update($params, ['account_name' => $params['account_name']]);


        throw new  SuccessMessage(['msg' => '保存状态成功']);
    }

    public function SetCheckAccountStatus()
    {
        // 数据验证
        $params = (new SendRegResultValidate())->goCheck();

        // 验证账号是否存在
        $check = RegAccountModel::checkRegAccount($params['account_name']);

        if (!$check) {
            throw new RegAccountException(['msg' => '账号不存在']);
        }

        // 存储数据
        RegAccountModel::update($params, ['account_name' => $params['account_name']]);


        throw new  SuccessMessage(['msg' => '保存状态成功']);
    }
}