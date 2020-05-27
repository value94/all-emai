<?php

namespace app\admin\controller;

use app\admin\model\EmailTypeModel;
use think\Request;

class EmailType extends Base
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
            $EmailType = new EmailTypeModel();
            $selectResult = $EmailType->getEmailTypeByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $EmailType->getAllEmailType($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '邮箱类型'
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

            $EmailType = new EmailTypeModel();
            $flag = $EmailType->insertEmailType($param);

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
        $EmailType = new EmailTypeModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $EmailType->editEmailType($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $EmailType->getOneEmailType($id)
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
        EmailTypeModel::destroy($id);
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
                'auth' => 'emailType/update',
                'href' => url('email_type/update', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            /*'删除' => [
                'auth' => 'email_type/delete',
                'href' => "javascript:email_typeDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],*/
        ];
    }
}
