<?php

namespace app\admin\controller;

use app\admin\model\CheckAppleModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Db;

class CheckApple extends Base
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


            $CheckApple = new CheckAppleModel();
            $selectResult = $CheckApple->getCheckAppleByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $CheckApple->getAllCheckApple($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '待过检账号'
        ]);
        return $this->fetch();
    }

    // 根据搜索条件获取 where 数组
    protected function getWhereByParams($params)
    {
        $where = [];
        // check_apple过检账号名搜索
        if (!empty($params['apple_account'])) {
            $where[] = ['apple_account', 'like', '%' . $params['apple_account'] . '%'];
        }
        // 失败原因
        if (!empty($params['fail_reason'])) {
            $where[] = ['fail_reason', 'like', '%' . $params['fail_reason'] . '%'];
        }
        // 状态搜索
        if ($params['use_status'] != '') {
            $where[] = ['use_status', '=', $params['use_status']];
        }
        // 状态搜索
        if ($params['check_status'] != '') {
            $where[] = ['check_status', '=', $params['check_status']];
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

            $CheckApple = new CheckAppleModel();
            $flag = $CheckApple->insertCheckApple($param);

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
        $CheckApple = new CheckAppleModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $CheckApple->editCheckApple($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $CheckApple->getOneCheckApple($id),
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
        $param = input('param.');
        if (isset($param['ids'])) {
            CheckAppleModel::destroy($param['ids']);
        } else {
            CheckAppleModel::destroy($param['id']);
        }

        return json(msg(1, '', '删除成功'));
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
            CheckAppleModel::where($where)->delete();
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
            CheckAppleModel::where($where)->update(['use_status' => $params['change_use_status']]);
        } else {
            $result = [
                'code' => 0,
                'msg' => '请选择搜索条件'
            ];
        }
        return $result;
    }

    /**
     * 批量导入check_apple过检账号
     * @return mixed
     */
    public function import()
    {
        if (request()->isPost()) {
            // 获取表单上传文件
            $file = request()->file('ip_file');

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
                if (!$c[0]) {
                    continue;
                }
                $save_data = [
                    'apple_account' => $c[0],
                    'apple_pass' => $c[3],
                    'birth_day' => $c[4],
                    'question_1' => $c[5],
                    'answer_1' => $c[6],
                    'question_2' => $c[7],
                    'answer_2' => $c[8],
                    'question_3' => $c[9],
                    'answer_3' => $c[10],
                    'remark' => empty($c[11]) ? null : $c[11],
                    'create_time' => $create_time,
                    'update_time' => $update_time,
                ];
                $result = Db::table('s_check_apple')->insert($save_data, "IGNORE");
                if ($result == 1) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            $this->success('批量添加成功,共导入' . $success_count . ' 条,已存在' . $error_count . ' 条。');

        }

        return $this->fetch();
    }

    // 导出搜索的check_apple过检账号
    public function download_excel()
    {
        // 获取搜索参数
        $params = input('param.');

        $where = $this->getWhereByParams($params);

        // 导出文件
        if (!empty($where)) {
            // 搜索数据
            $CheckApple = new CheckAppleModel();
            // 判断是否有行数限制
            $rows = '';
            $offset = '';
            if (!empty($params['rows'])) {
                $offset = 0;
                $rows = $params['rows'];
            }
            $excel_data = $CheckApple->getCheckAppleByWhere($where, $offset, $rows);
            if ($excel_data) {
                // 创建表
                $newExcel = new Spreadsheet();  //创建一个新的excel文档
                $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
                $objSheet->setTitle('apple过检账号信息表');  //设置当前sheet的标题

                //设置宽度为true,不然太窄了
                $newExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

                //设置第一栏的标题
                $objSheet->setCellValue('A1', '账号')
                    ->setCellValue('D1', '密码')
                    ->setCellValue('E1', '出生年月')
                    ->setCellValue('F1', '密码问题1')
                    ->setCellValue('G1', '答案1')
                    ->setCellValue('H1', '密码问题2')
                    ->setCellValue('I1', '答案2')
                    ->setCellValue('J1', '密码问题3')
                    ->setCellValue('K1', '答案3')
                    ->setCellValue('L1', '备注');

                //第二行起，每一行的值,setCellValueExplicit是用来导出文本格式的。
                //->setCellValueExplicit('C' . $k, $val['admin_password']PHPExcel_Cell_DataType::TYPE_STRING),可以用来导出数字不变格式
                foreach ($excel_data as $k => $val) {
                    $k = $k + 2;
                    $objSheet->setCellValue('A' . $k, $val['apple_account'])
                        ->setCellValue('D' . $k, $val['apple_pass'])
                        ->setCellValue('E' . $k, $val['birth_day'])
                        ->setCellValue('F' . $k, $val['question_1'])
                        ->setCellValue('G' . $k, $val['answer_1'])
                        ->setCellValue('H' . $k, $val['question_2'])
                        ->setCellValue('I' . $k, $val['answer_2'])
                        ->setCellValue('J' . $k, $val['question_3'])
                        ->setCellValue('K' . $k, $val['answer_3'])
                        ->setCellValue('L' . $k, $val['remark']);
                }
                // 修改check_apple过检账号下载状态
                $CheckAppleModel = new CheckAppleModel();
                $CheckAppleModel->isAutoWriteTimestamp(false)->update(['is_get' => 1], $where);
                downloadExcel($newExcel, 'apple过检账号数据表', 'Xls');
            } else {
                $this->error('该搜索条件没有能导出的数据');
            }
        } else {
            $this->error('请选择搜索条件');
        }
    }

    /**
     * 切换选中check_apple过检账号状态
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
                CheckAppleModel::update(['use_status' => 2], ['id' => $data['id']]);
                return $result;
            }

            // 切换选中机器状态
            if (isset($data['change_use_status'])) {
                CheckAppleModel::update(['use_status' => $data['change_use_status']],
                    ['id' => $data['id']]);
                return $result;
            }

            // 切换机器状态
            $used_id = CheckAppleModel::where([
                ['use_status', 'in', '1,2'],
                ['id', 'in', $data['id']]
            ])->column('id');

            $un_use = CheckAppleModel::where([
                ['use_status', 'in', '0,2'],
                ['id', 'in', $data['id']]
            ])->column('id');

            if (!empty($used_id)) {
                CheckAppleModel::update(['use_status' => 0], ['id' => $used_id]);
            }

            if (!empty($un_use)) {
                CheckAppleModel::update(['use_status' => 1], ['id' => $un_use]);
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
            '停用' => [
                'auth' => 'checkApple/switch_status',
                'href' => "javascript:switch_status(" . $id . ",2)",
                'btnStyle' => 'warning',
                'icon' => 'fa fa-close',
            ],
            '编辑' => [
                'auth' => 'checkApple/edit',
                'href' => url('check_apple/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'checkApple/delete',
                'href' => "javascript:CheckAppleDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
