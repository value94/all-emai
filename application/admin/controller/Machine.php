<?php

namespace app\admin\controller;

use app\admin\model\MachineModel;
use think\Request;

class Machine extends Base
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

            $Machine = new MachineModel();
            $selectResult = $Machine->getMachineByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['email_name'] = $vo['email']['email_name'];
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $Machine->getAllMachine($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '机器'
        ]);
        return $this->fetch();
    }

    // 根据参数获取 where 数组
    protected function getWhereByParams($params)
    {
        $where = [];
        // 机器 udid 搜索
        if (!empty($params['udid'])) {
            $where[] = ['udid', 'like', '%' . $params['udid'] . '%'];
        }
        // 状态搜索
        if ($params['use_status'] != '') {
            $where[] = ['use_status', '=', $params['use_status']];
        }
        //时间搜索
        if (!empty($params['start_time']) && !empty($params['end_time'])) {
            $where[] = ['update_time', 'between', [$params['start_time'], $params['end_time']]];
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

            $Machine = new MachineModel();
            $flag = $Machine->insertMachine($param);

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
        $Machine = new MachineModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $Machine->editMachine($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $Machine->getOneMachine($id)
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
            MachineModel::where('id', 'in', $params['ids'])->delete();
        } else {
            MachineModel::destroy($params['id']);
        }

        return json(msg(1, '', '删除成功'));
    }

    /**
     * 切换选中机器状态
     * @return mixed
     */
    public function switch_status()
    {
        if (request()->isAjax()) {
            $result = [
                'code' => 1,
                'msg' => '切换成功'
            ];

            $data = input('param.');
            // 切换成停止状态
            if (isset($data['use_status']) && $data['use_status'] == 2) {
                MachineModel::update(['use_status' => 2], ['id' => $data['machine_id']]);
                return $result;
            }
            // 切换成指定状态
            if (isset($data['change_use_status'])) {
                MachineModel::update(['use_status' => $data['change_use_status']], ['id' => $data['machine_id']]);
                return $result;
            }

            // 切换机器状态
            $used_id = MachineModel::where([
                ['use_status', 'in', '1,2'],
                ['id', 'in', $data['machine_id']]

            ])->column('id');

            $un_use = MachineModel::where([
                ['use_status', 'in', '0,2'],
                ['id', 'in', $data['machine_id']]
            ])->column('id');

            if (!empty($used_id)) {
                MachineModel::update(['use_status' => 0], ['id' => $used_id]);
            }

            if (!empty($un_use)) {
                MachineModel::update(['use_status' => 1], ['id' => $un_use]);
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
            MachineModel::where($where)->delete();
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
            MachineModel::where($where)->update(['use_status' => $params['change_use_status']]);
        } else {
            $result = [
                'code' => 0,
                'msg' => '请选择搜索条件'
            ];
        }
        return $result;
    }

    // 批量导入邮箱
    public function import_machine()
    {
        if (request()->isPost()) {
            // 获取表单上传文件
            $file = request()->file('machine_file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move('../uploads');
            if ($info) {
                // 获取数据,循环处理
                $path = $info->getPathname();
                // 读取数据
                $orders_data = $this->data_import($path);
                if (!$orders_data) {
                    $this->error('excel解析失败,请检查格式');
                }
            } else {
                // 上传失败获取错误信息
                $this->error('上传文件失败,请重试!');
            }
            $success_count = 0;
            $error_count = 0;
            // 添加数据
            foreach ($orders_data as $c) {
                // 判断邮箱是否已存在
                $machine = new MachineModel();
                $res = $machine->where('udid', '=', $c[4])->find();
                if ($res) {
                    $error_count++;
                    continue;
                } else {
                    $machine->sn = $c[0];
                    $machine->imei = $c[1];
                    $machine->bt = $c[2];
                    $machine->wifi = $c[3];
                    $machine->udid = $c[4];
                    $machine->PhoneModel = $c[5];
                    $machine->ModelNumber = $c[6];
                    $machine->HWModelStr = $c[7];
                    $machine->ProductType = $c[8];

                    $machine->save();
                    $success_count++;
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
                'auth' => 'machine/switch_status',
                'href' => "javascript:switch_status(" . $id . ")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-check-circle',
            ],
            '停止' => [
                'auth' => 'machine/switch_status',
                'href' => "javascript:switch_status(" . $id . ",2)",
                'btnStyle' => 'warning',
                'icon' => 'fa fa-close',
            ],
            '编辑' => [
                'auth' => 'machine/edit',
                'href' => url('machine/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'machine/delete',
                'href' => "javascript:machineDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
