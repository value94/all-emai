<?php

namespace app\admin\controller;

use app\admin\model\RegChannelModel;
use think\facade\Cache;

class RegChannel extends Base
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
                $where[] = ['name', 'like', '%' . $param['searchText'] . '%'];
            }
            $RegChannel = new RegChannelModel();
            $selectResult = $RegChannel->getRegChannelByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $RegChannel->getAllRegChannel($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '注册通道'
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

            $RegChannel = new RegChannelModel();
            $flag = $RegChannel->insertRegChannel($param);

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
    public function update($id)
    {
        $RegChannel = new RegChannelModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $RegChannel->editRegChannel($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $RegChannel->getOneRegChannel($id)
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
        $regModel = new RegChannelModel();
        $channel_data = $regModel->getOneRegChannel($id);
        if ($channel_data) {
            // 清除该通道缓存
            Cache::tag('channel')->clear();
            // 真实删除通道
            RegChannelModel::destroy($id, true);

            return json(msg(1, '', '删除成功'));
        }
        return json(msg(0, '', '没有该通道数据'));

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
                'auth' => 'regChannel/update',
                'href' => url('regChannel/update', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'regChannel/delete',
                'href' => "javascript:regChannelDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
