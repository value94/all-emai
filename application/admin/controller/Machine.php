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

            $where = [];
            if (!empty($param['searchText'])) {
                $where[] = ['HWModelStr', 'like', '%' . $param['searchText'] . '%'];
            }
            $Machine = new MachineModel();
            $selectResult = $Machine->getMachineByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
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
    public function delete($id)
    {
        MachineModel::destroy($id);
        return json(msg(1, '', '删除成功'));
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
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
