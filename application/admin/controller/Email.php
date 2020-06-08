<?php

namespace app\admin\controller;

use app\admin\model\EmailModel;
use app\admin\model\EmailTypeModel;
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
            // 邮箱名搜索
            if (!empty($param['searchText'])) {
                $where[] = ['email_name', 'like', '%' . $param['searchText'] . '%'];
            }
            // 状态搜索
            if ($param['use_status'] != '') {
                $where[] = ['use_status', '=', $param['use_status']];
            }
            // 注册状态搜索
            if ($param['reg_status'] != '') {
                $where[] = ['reg_status', '=', $param['reg_status']];
            }
            // 导出状态搜索
            if ($param['is_get'] != '') {
                $where[] = ['is_get', '=', $param['is_get']];
            }
            //时间搜索
            if (!empty($param['start_time']) && !empty($param['end_time'])) {
                $where[] = ['update_time', 'between', [$param['start_time'], $param['end_time']]];
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
            $type_id = input('post.email_type');

            $email_type = EmailTypeModel::getEmailTypeById($type_id);
            $param['email_type_id'] = $type_id;
            $param['imapsvr'] = $email_type['imapsvr'];
            $param['pop3svr'] = $email_type['pop3svr'];
            $param['smtpsvr'] = $email_type['smtpsvr'];

            $Email = new EmailModel();
            $flag = $Email->insertEmail($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'email_type' => EmailTypeModel::getEmailType(),
        ]);

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

            $type_id = input('post.email_type');

            $email_type = EmailTypeModel::getEmailTypeById($type_id);
            $param['email_type_id'] = $type_id;
            $param['imapsvr'] = $email_type['imapsvr'];
            $param['pop3svr'] = $email_type['pop3svr'];
            $param['smtpsvr'] = $email_type['smtpsvr'];

            $flag = $Email->editEmail($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $Email->getOneEmail($id),
            'email_type' => EmailTypeModel::getEmailType(),
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
            $type_id = input('post.email_type');
            if (!$type_id) {
                $this->error('请选择邮箱类型');
            } else {
                $email_type = EmailTypeModel::getEmailTypeById($type_id);
            }

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
                $Email = new EmailModel();
                $res = $Email->where('email_name', '=', $c[0])->find();
                if ($res) {
                    $error_count++;
                    continue;
                } else {
                    $Email->email_name = $c[0];
                    $Email->email_password = $c[1];
                    $Email->email_type_id = $email_type->id;
                    $Email->imapsvr = $email_type->imapsvr;
                    $Email->pop3svr = $email_type->pop3svr;
                    $Email->smtpsvr = $email_type->smtpsvr;
                    $Email->save();
                    $success_count++;
                }
            }
            $this->success('批量添加成功,共导入' . $success_count . ' 条,已存在' . $error_count . ' 条');
        }
        $this->assign([
            'email_type' => EmailTypeModel::getEmailType(),
        ]);

        return $this->fetch();
    }

    /**
     * 切换选中邮箱状态
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
            // 切换emial 状态为停止
            if ($data['use_status'] == 2) {
                // 切换成停止状态
                EmailModel::update(['use_status' => 2], ['id' => $data['email_id']]);
                return $result;
            }

            // 切换机器状态
            $used_id = EmailModel::where([
                ['use_status', '=', '1'],
                ['id', 'in', $data['email_id']]
            ])->column('id');

            $un_use = EmailModel::where([
                ['use_status', '=', '0'],
                ['id', 'in', $data['email_id']]
            ])->column('id');

            if (!empty($used_id)) {
                EmailModel::update(['use_status' => 0], ['id' => $used_id]);
            }

            if (!empty($un_use)) {
                EmailModel::update(['use_status' => 1], ['id' => $un_use]);
            }

            return $result;
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
            '切换' => [
                'auth' => 'email/switch_status',
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
