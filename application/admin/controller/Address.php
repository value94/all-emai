<?php

namespace app\admin\controller;

use app\admin\model\AddressModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Address extends Base
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


            $Address = new AddressModel();
            $selectResult = $Address->getAddressByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $Address->getAllAddress($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $this->assign([
            'title' => '地址'
        ]);
        return $this->fetch();
    }

    // 根据搜索条件获取 where 数组
    protected function getWhereByParams($params)
    {
        $where = [];
        // 地址名搜索
        if (!empty($params['country'])) {
            $where[] = ['country', 'like', '%' . $params['country'] . '%'];
        }
        if (!empty($params['province'])) {
            $where[] = ['province', 'like', '%' . $params['province'] . '%'];
        }
        if (!empty($params['city'])) {
            $where[] = ['city', 'like', '%' . $params['city'] . '%'];
        }
        // 状态搜索
        if ($params['use_status'] != '') {
            $where[] = ['use_status', '=', $params['use_status']];
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
            $type_id = input('post.Address_type');

            $Address_type = AddressTypeModel::getAddressTypeById($type_id);
            $param['Address_type_id'] = $type_id;
            $param['imapsvr'] = $Address_type['imapsvr'];
            $param['pop3svr'] = $Address_type['pop3svr'];
            $param['smtpsvr'] = $Address_type['smtpsvr'];

            $Address = new AddressModel();
            $flag = $Address->insertAddress($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'Address_type' => AddressTypeModel::getAddressType(),
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
        $Address = new AddressModel();

        if (request()->isPost()) {

            $param = input('post.');

            $flag = $Address->editAddress($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $this->assign([
            'data' => $Address->getOneAddress($id),
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
            AddressModel::destroy($param['ids']);
        } else {
            AddressModel::destroy($param['id']);
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
            AddressModel::where($where)->delete();
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
            AddressModel::where($where)->update(['use_status' => $params['change_use_status']]);
        } else {
            $result = [
                'code' => 0,
                'msg' => '请选择搜索条件'
            ];
        }
        return $result;
    }

    /**
     * 批量导入地址
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
            $all_count = 0;
            $data = [];
            // 添加数据
            foreach ($excel_data as $c) {
                // 判断行是否为空
                if (!$c[0]) {
                    continue;
                }
                $data[$all_count] = [
                    'country' => $c[0],
                    'province' => $c[1],
                    'city' => $c[2],
                    'street_one' => $c[3],
                    'street_two' => $c[4],
                    'street_three' => $c[5],
                    'postal_code' => $c[6],
                ];
                $all_count++;
            }

            $Address = new AddressModel();
            $Address->saveAll($data);

            $this->success('批量添加成功,共' . $all_count . ' 条,成功导入' . $all_count . ' 条');
        }

        return $this->fetch();
    }

    // 导出搜索的地址
    public function download_excel()
    {
        // 获取搜索参数
        $params = input('param.');

        $where = $this->getWhereByParams($params);

        // 导出文件
        if (!empty($where)) {
            // 搜索数据
            $Address = new AddressModel();
            // 判断是否有行数限制
            $rows = '';
            $offset = '';
            if (!empty($params['rows'])) {
                $offset = 0;
                $rows = $params['rows'];
            }
            $excel_data = $Address->getAddressByWhere($where, $offset, $rows);
            if ($excel_data) {
                // 创建表
                $newExcel = new Spreadsheet();  //创建一个新的excel文档
                $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
                $objSheet->setTitle('地址信息表');  //设置当前sheet的标题

                //设置宽度为true,不然太窄了
                $newExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $newExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $newExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $newExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                $newExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $newExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $newExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $newExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
                $newExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);

                //设置第一栏的标题
                $objSheet->setCellValue('A1', '地址')
                    ->setCellValue('B1', '')
                    ->setCellValue('C1', '')
                    ->setCellValue('D1', '密码')
                    ->setCellValue('E1', '出生年月')
                    ->setCellValue('F1', '问题1')
                    ->setCellValue('G1', '答案1')
                    ->setCellValue('H1', '问题2')
                    ->setCellValue('I1', '答案2')
                    ->setCellValue('J1', '问题3')
                    ->setCellValue('K1', '答案3');

                //第二行起，每一行的值,setCellValueExplicit是用来导出文本格式的。
                //->setCellValueExplicit('C' . $k, $val['admin_password']PHPExcel_Cell_DataType::TYPE_STRING),可以用来导出数字不变格式
                foreach ($excel_data as $k => $val) {
                    $k = $k + 2;
                    $objSheet->setCellValue('A' . $k, $val['Address_name'])
                        ->setCellValue('B' . $k, '')
                        ->setCellValue('C' . $k, '')
                        ->setCellValue('D' . $k, 'Tt778899')
                        ->setCellValue('E' . $k, '1981/1/1')
                        ->setCellValue('F' . $k, '你少年时代最好的朋友叫什么名字？')
                        ->setCellValue('G' . $k, 'aa1')
                        ->setCellValue('H' . $k, '你的理想工作是什么？')
                        ->setCellValue('I' . $k, 'aa2')
                        ->setCellValue('J' . $k, '你的父母是在哪里认识的？')
                        ->setCellValue('K' . $k, 'aa3');
                }
                // 修改地址下载状态
                $AddressModel = new AddressModel();
                $AddressModel->isAutoWriteTimestamp(false)->update(['is_get' => 1], $where);
                downloadExcel($newExcel, '地址数据表', 'Xls');
            } else {
                $this->error('该搜索条件没有能导出的数据');
            }
        } else {
            $this->error('请选择搜索条件');
        }
    }

    /**
     * 切换选中地址状态
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
                AddressModel::update(['use_status' => 2], ['id' => $data['id']]);
                return $result;
            }

            // 切换选中机器状态
            if (isset($data['change_use_status'])) {
                AddressModel::update(['use_status' => $data['change_use_status']],
                    ['id' => $data['id']]);
                return $result;
            }

            // 切换机器状态
            $used_id = AddressModel::where([
                ['use_status', 'in', '1,2'],
                ['id', 'in', $data['id']]
            ])->column('id');

            $un_use = AddressModel::where([
                ['use_status', 'in', '0,2'],
                ['id', 'in', $data['id']]
            ])->column('id');

            if (!empty($used_id)) {
                AddressModel::update(['use_status' => 0], ['id' => $used_id]);
            }

            if (!empty($un_use)) {
                AddressModel::update(['use_status' => 1], ['id' => $un_use]);
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
            /*'切换' => [
                'auth' => 'address/switch_status',
                'href' => "javascript:switch_status(" . $id . ")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-check-circle',
            ],*/
            '停止' => [
                'auth' => 'address/switch_status',
                'href' => "javascript:switch_status(" . $id . ",2)",
                'btnStyle' => 'warning',
                'icon' => 'fa fa-close',
            ],
            '编辑' => [
                'auth' => 'address/edit',
                'href' => url('address/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste',
            ],
            '删除' => [
                'auth' => 'address/delete',
                'href' => "javascript:AddressDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o',
            ],
        ];
    }
}
