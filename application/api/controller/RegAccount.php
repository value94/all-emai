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
    private $apple_questions = [
        130 => '你少年时代最好的朋友叫什么名字？',
        131 => '你的第一个宠物叫什么名字？',
        132 => '你学会做的第一道菜是什么？',
        133 => '你第一次去电影院看的是哪一部电影？',
        134 => '你第一次坐飞机是去哪里？',
        135 => '你上小学时最喜欢的老师姓什么？',
        136 => '你的理想工作是什么？',
        137 => '你小时候最喜欢哪一本书？',
        138 => '你拥有的第一辆车是什么型号？',
        139 => '你童年时代的绰号是什么？',
        140 => '你在学生时代最喜欢哪个电影明星或角色？',
        141 => '你在学生时代最喜欢哪个歌手或乐队？',
        142 => '你的父母是在哪里认识的？',
        143 => '你的第一个上司叫什么名字？',
        144 => '您从小长大的那条街叫什么？',
        145 => '你去过的第一个海滨浴场是哪一个？',
        146 => '你购买的第一张专辑是什么？',
        147 => '您最喜欢哪个球队？',
    ];

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
            'question1' => $this->apple_questions[$account['question1']],
            'answer1' => $account['answer1'],
            'question2' => $this->apple_questions[$account['question2']],
            'answer2' => $account['answer2'],
            'question3' => $this->apple_questions[$account['question3']],
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