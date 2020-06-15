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
        // 今日和当前1小时总注册数，成功数，失败数
        $today_reg = EmailModel::where(['reg_status' => [0, 1]])
            ->whereTime('update_time', 'today')
            ->count('id');
        $today_fail_reg = EmailModel::where(['reg_status' => 0])
            ->whereTime('update_time', 'today')
            ->count('id');
        /*$today_success_reg = EmailModel::where(['reg_status' => 1])
            ->whereTime('update_time', 'today')
            ->count('id');*/

        $hour_reg = EmailModel::where(['reg_status' => [0, 1]])
            ->whereTime('update_time', '-1 hours')
            ->count('id');
        $hour_fail_reg = EmailModel::where(['reg_status' => 0])
            ->whereTime('update_time', '-1 hour')
            ->count('id');
        /*$hour_success_reg = EmailModel::where(['reg_status' => 1])
            ->whereTime('update_time', '-1 hour')
            ->count('id');*/
        $this->assign([
            'show_data' => [
                'all_reg' => $all_reg,
                'fail_reg' => $fail_reg,
                'success_reg' => $success_reg,
                'machine_left' => $machine_left,
                'email_left' => $email_left,
                'today_reg' => $today_reg,
                'today_fail_reg' => $today_fail_reg,
                'today_success_reg' => $today_reg - $today_fail_reg,
                'hour_reg' => $hour_reg,
                'hour_fail_reg' => $hour_fail_reg,
                'hour_success_reg' => $hour_reg - $hour_fail_reg
            ]
        ]);
        return $this->fetch('index');
    }

    // 首页搜索
    public function search()
    {
        $params = input('param.');
        // 执行搜索
        $where = $this->getWhereByParams($params);
        // 查询 总注册量，成功数量，失败数量
        $all_reg = EmailModel::where(['reg_status' => [0, 1]])->where($where)->count('id');
        $fail_reg = EmailModel::where(['reg_status' => 0])->where($where)->count('id');
        $success_reg = EmailModel::where(['reg_status' => 1])->where($where)->count('id');
        return json([
            'code' => 1,
            'all_reg' =>$all_reg,
            'fail_reg' =>$fail_reg,
            'success_reg' =>$success_reg,
        ]);
    }

    // 根据搜索条件获取 where 数组
    protected function getWhereByParams($params)
    {
        $where = [];
        //时间搜索
        if (!empty($params['start_time']) && !empty($params['end_time'])) {
            $where[] = ['update_time', 'between', [$params['start_time'], $params['end_time']]];
        }

        return $where;
    }
}
