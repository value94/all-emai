<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\UserModel;
use think\captcha\Captcha;
use think\Controller;

class Login extends Controller
{
    // 登录页面
    public function index()
    {
        return $this->fetch('/login');
    }

    protected static function check_verify($code, $id = '')
    {
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }

    // 登录操作

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function doLogin()
    {
        $userName = input("param.user_name");
        $password = input("param.password");
        $code     = input("param.code");

        $result = $this->validate(compact('userName', 'password', "code"), 'AdminValidate');
        if (true !== $result) {
            return json(msg(-1, '', $result));
        }

        if (!static::check_verify($code)) {
            return json(msg(-2, '', '验证码错误'));
        }

        $userModel = new UserModel();
        $hasUser   = $userModel->checkUser($userName);

        if (empty($hasUser)) {
            return json(msg(-3, '', '管理员不存在'));
        }

        if (md5($password . config('salt')) != $hasUser['password']) {
            return json(msg(-4, '', '密码错误'));
        }

        if (1 != $hasUser['status']) {
            return json(msg(-5, '', '该账号被禁用'));
        }

        session('username', $hasUser['user_name']);
        session('id', $hasUser['user_id']);
        session('head', $hasUser['head']);
        session('role', $hasUser['role_name']);  // 角色名
        session('role_id', $hasUser['role_id']);
        session('rule', $hasUser['rule']);

        // 更新管理员状态
        $param = [
            'login_times'     => $hasUser['login_times'] + 1,
            'last_login_ip'   => request()->ip(),
            'last_login_time' => time(),
        ];

        $res = $userModel->updateStatus($hasUser['id'], $param);
        if (1 != $res['code']) {
            return json(msg(-6, '', $res['msg']));
        }
        // ['code' => 1, 'data' => url('index/index'), 'msg' => '登录成功']
        return json(msg(1, url('index/index'), '登录成功'));
    }

    // 验证码

    /**
     * @return \think\Response
     */
    public function checkVerify()
    {
        $verify = new Captcha();

        $verify->imageH   = 32;
        $verify->imageW   = 100;
        $verify->length   = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;

        return $verify->entry();
    }

    // 退出操作
    public function loginOut()
    {
        session('username', null);
        session('id', null);
        session('head', null);
        session('role', null);  // 角色名
        session('role_id', null);
        session('rule', null);

        $this->redirect(url('index'));
    }
}
