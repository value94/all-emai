<?php

namespace app\admin\controller;

use app\admin\model\SmsPhoneModel;
use think\Db;

class SmsPhone extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = $this->getWhereByParams($param);

            $SmsPhone = new SmsPhoneModel();
            $selectResult = $SmsPhone->getSmsPhoneByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['fail_sms_count'] = $vo['received_sms_count'] - $vo['success_sms_count'];
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $SmsPhone->getAllSmsPhone($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '短信设备'
        ]);
        return $this->fetch();
    }

    // 根据参数获取 where 数组
    protected function getWhereByParams($params)
    {
        $where = [];

        // 手机号搜索
        if (!empty($params['phone_num'])) {
            $where[] = ['phone_num', 'like', '%' . $params['phone_num'] . '%'];
        }

        // 机器编号搜索
        if (!empty($params['device_num'])) {
            $where[] = ['device_num', 'like', '%' . $params['device_num'] . '%'];
        }

        // 状态搜索
        if ($params['status'] != '') {
            $where[] = ['status', '=', $params['status']];
        }

        //时间搜索
        if (!empty($params['start_time']) && !empty($params['end_time'])) {
            if ($params['time_field'] != '') {
                $time_field = [
                    1 => 'create_time',
                    2 => 'update_time',
                ];
                $where[] = [$time_field[$params['time_field']], 'between', [$params['start_time'], $params['end_time']]];
            } else {
                $where[] = ['update_time', 'between', [$params['start_time'], $params['end_time']]];
            }
        }

        return $where;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        if (request()->isPost()) {

            $param = input('post.');

            $SmsPhone = new SmsPhoneModel();
            // 查询是否被软删除
            $check = SmsPhoneModel::onlyTrashed()->find(function ($query) use ($param) {
                $query->where('device_num', '=', $param['device_num']);
            });
            if ($check) {
                $check->restore();
                $flag = [
                    'code' => 1,
                    'data' => '',
                    'msg' => '添加任务设备成功'
                ];
            } else {
                $flag = $SmsPhone->insertSmsPhone($param);
            }

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $SmsPhone = new SmsPhoneModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $SmsPhone->editSmsPhone($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
//            $this->success($flag['msg'],'../');
        }

        $this->assign([
            'data' => $SmsPhone->getOneSmsPhone($id)
        ]);
        return $this->fetch();
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete()
    {
        $params = input('param.');
        if (isset($params['ids'])) {
            SmsPhoneModel::destroy($params['ids']);
        } else {
            SmsPhoneModel::destroy($params['id']);
        }

        return json(msg(1, '', '删除成功'));
    }

    /**
     * 切换选中任务设备状态
     * @return mixed
     */
    public function switch_status()
    {
        if (request()->isAjax()) {
            $result = [
                'code' => 1,
                'msg' => '切换成功'
            ];

            $params = input('param.');

            // 切换成停止状态
            if (isset($params['status']) && $params['status'] == 2) {
                SmsPhoneModel::update(['status' => 2], ['id' => $params['id']]);
                return $result;
            }
            // 切换成指定状态
            if (isset($params['change_status'])) {
                SmsPhoneModel::update(['status' => $params['change_status']], ['id' => $params['id']]);
                return $result;
            }

            // 切换任务设备状态
            $used_id = SmsPhoneModel::where([
                ['status', 'in', '1,2'],
                ['id', 'in', $params['id']]

            ])->column('id');

            $un_use = SmsPhoneModel::where([
                ['status', 'in', '0,2'],
                ['id', 'in', $params['id']]
            ])->column('id');

            if (!empty($used_id)) {
                SmsPhoneModel::update(['status' => 0], ['id' => $used_id]);
            }

            if (!empty($un_use)) {
                SmsPhoneModel::update(['status' => 1], ['id' => $un_use]);
            }


            return $result;
        }
    }

    // 删除搜索数据
    public function delete_by_search()
    {
        $result = [
            'code' => 1,
            'msg' => '删除成功'
        ];
        // 获取搜索参数
        $params = input('param.');

        $where = $this->getWhereByParams($params);
        if (!empty($where)) {
            // 执行删除
            SmsPhoneModel::destroy(function ($query) use ($where) {
                $query->where($where);
            });
        } else {
            $result = [
                'code' => 0,
                'msg' => '请选择搜索条件'
            ];
        }
        return $result;
    }

    // 切换搜索数据
    public function switch_by_search()
    {
        $result = [
            'code' => 1,
            'msg' => '切换成功'
        ];
        // 获取搜索参数
        $params = input('param.');

        $where = $this->getWhereByParams($params);
        if (!empty($where)) {
            // 执行删除
            SmsPhoneModel::where($where)->update(['status' => $params['change_status']]);
        } else {
            $result = [
                'code' => 0,
                'msg' => '请选择搜索条件'
            ];
        }
        return $result;
    }

    // 批量导入手机
    public function import()
    {
        if (request()->isPost()) {
            // 获取表单上传文件
            $file = request()->file('phone_file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move('../uploads');
            if ($info) {
                // 获取数据,循环处理
                $path = $info->getPathname();
                // 读取数据
                $excel_data = $this->data_import($path);
                if (!$excel_data) {
                    $this->error('excel解析失败,请检查格式');
                }
            } else {
                // 上传失败获取错误信息
                $this->error('上传文件失败,请重试!');
            }
            $success_count = 0;
            $error_count = 0;
            $update_time = date('Y-m-d H:i:s');
            $create_time = date('Y-m-d H:i:s');
            // 添加数据
            foreach ($excel_data as $c) {
                // 判断行是否为空
                if (!$c[1]) {
                    continue;
                }
                $email_data = [
                    'device_num' => $c[0],
                    'phone_num' => $c[1],
                    'phone_sn' => $c[2],
                    'create_time' => $create_time,
                    'update_time' => $update_time,
                ];
                $result = Db::table('s_sms_phone')->insert($email_data, "IGNORE");
                if ($result == 1) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            $this->success('批量添加成功,共导入' . $success_count . ' 条,已存在' . $error_count . ' 条');
        }
        return $this->fetch();
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '切换' => [
                'auth' => 'smsPhone/switch_status',
                'href' => "javascript:switch_status(" . $id . ")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-check-circle',
            ],
            '停止' => [
                'auth' => 'smsPhone/switch_status',
                'href' => "javascript:switch_status(" . $id . ",2)",
                'btnStyle' => 'warning',
                'icon' => 'fa fa-close',
            ],
            '编辑' => [
                'auth' => 'smsPhone/edit',
                'href' => url('smsPhone/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'smsPhone/delete',
                'href' => "javascript:sms_phoneDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
