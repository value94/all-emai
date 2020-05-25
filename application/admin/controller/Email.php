<?php

namespace app\admin\controller;

use app\admin\model\EmailModel;
use think\Request;

class Email extends Base
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
                $where[] = ['email_name', 'like', '%' . $param['searchText'] . '%'];
            }
            $Email = new EmailModel();
            $selectResult = $Email->getEmailByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $Email->getAllEmail($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '邮箱'
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

            $Email = new EmailModel();
            $flag = $Email->insertEmail($param);

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
        $Email = new EmailModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $Email->editEmail($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $Email->getOneEmail($id)
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
        EmailModel::destroy($id);
        return json(msg(1, '', '删除成功'));
    }

    /**
     * 批量导入邮箱
     * @return mixed
     */
    public function import_email()
    {
        if (request()->isPost()) {
            // 获取表单上传文件
            $file = request()->file('email_file');
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

            // 添加数据
            foreach ($orders_data as $c) {
                // 判断邮箱是否已存在
                $Email = new EmailModel();
                $res = $Email->where('email_name', '=', $c[0])->find();
                if ($res) {
                    continue;
                } else {
                    try {
                        $Email->email_name = $c[0];
                        $Email->email_password = $c[1];
                        $Email->pop3svr = $c[2];
                        $Email->smtpsvr = $c[3];
                        $Email->save();
                    } catch (\Exception $e) {
                        $this->error('添加失败,请重试');
                    }
                }
            }
            $this->success('批量添加成功');
        }
        return $this->fetch();
    }

    //创建一个读取excel数据，可用于入库
    function data_import($filename)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
            $sheet = $spreadsheet->getActiveSheet();
            $excel_array = $sheet->toArray();

            array_shift($excel_array);  //删除第一个数组(标题);

            return $excel_array;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $this->error('excel解析错误,请重试!');
        }
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
                'auth' => 'email/edit',
                'href' => url('email/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'email/delete',
                'href' => "javascript:emailDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
