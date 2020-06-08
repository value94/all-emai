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

use app\admin\model\EmailModel;
use app\admin\model\MachineModel;
use app\admin\model\NodeModel;

class Index extends Base
{
    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 获取权限菜单
        $node = new NodeModel();

        $this->assign([
            'menu' => $node->getMenu(session('rule')),
        ]);

        return $this->fetch('/index');
    }

    /**
     * 后台默认首页
     * @return mixed
     */
    public function indexPage()
    {
        // 查询 总注册量，成功数量，失败数量
        $all_reg = EmailModel::where(['reg_status' => [0, 1]])->count('id');
        $fail_reg = EmailModel::where(['reg_status' => 0])->count('id');
        $success_reg = EmailModel::where(['reg_status' => 1])->count('id');
        $machine_left = MachineModel::where(['use_status' => 0])->count('id');
        $email_left = EmailModel::where(['use_status' => 0])->count('id');

        $this->assign([
            'show_data' => [
                'all_reg' => $all_reg,
                'fail_reg' => $fail_reg,
                'success_reg' => $success_reg,
                'machine_left' => $machine_left,
                'email_left' => $email_left,
            ]
        ]);
        return $this->fetch('index');
    }
}
