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

use app\admin\model\CheckAppleModel;
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

        // 总过检，，
        $all_check = CheckAppleModel::where(['check_status' => [0, 1]])->count('id');
        // 过检失败
        $fail_check = CheckAppleModel::where(['check_status' => 0])->count('id');
        // 过检成功
        $success_check = CheckAppleModel::where(['check_status' => 1])->count('id');
        // 剩余过检数
        $left_check = CheckAppleModel::where(['use_status' => 0])->count('id');
        // 今天过检总数，成功，失败总数
        $today_check = CheckAppleModel::where(['check_status' => [0, 1]])
            ->whereTime('update_time', 'today')
            ->count('id');
        $today_fail_check = CheckAppleModel::where(['check_status' => 0])
            ->whereTime('update_time', 'today')
            ->count('id');
        // 上小时的过检总数，成功，失败数
        $hour_check = CheckAppleModel::where(['check_status' => [0, 1]])
            ->whereTime('update_time', '-1 hours')
            ->count('id');
        $hour_fail_check = CheckAppleModel::where(['check_status' => 0])
            ->whereTime('update_time', '-1 hour')
            ->count('id');

        $this->assign([
            'show_data' => [
                // 激活数据统计
                'all_reg' => $all_reg,
                'fail_reg' => $fail_reg,
                'success_reg' => $success_reg,
                'today_reg' => $today_reg,
                'today_fail_reg' => $today_fail_reg,
                'today_success_reg' => $today_reg - $today_fail_reg,
                'hour_reg' => $hour_reg,
                'hour_fail_reg' => $hour_fail_reg,
                'hour_success_reg' => $hour_reg - $hour_fail_reg,
                // 过检数据统计
                'all_check' => $all_check,
                'fail_check' => $fail_check,
                'success_check' => $success_check,
                'left_check' => $left_check,
                'today_check' => $today_check,
                'today_fail_check' => $today_fail_check,
                'today_success_check' => $today_check - $today_fail_check,
                'hour_check' => $hour_check,
                'hour_fail_check' => $hour_fail_check,
                'hour_success_check' => $hour_check - $hour_fail_check,

                'machine_left' => $machine_left,
                'email_left' => $email_left,

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
        // 查询 总过检数量，成功数量，失败数量
        $all_check = CheckAppleModel::where(['check_status' => [0, 1]])->where($where)->count('id');
        $fail_check = CheckAppleModel::where(['check_status' => 0])->where($where)->count('id');
        $success_check = CheckAppleModel::where(['check_status' => 1])->where($where)->count('id');
        return json([
            'code' => 1,
            'all_check' =>$all_check,
            'fail_check' =>$fail_check,
            'success_check' =>$success_check,
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
