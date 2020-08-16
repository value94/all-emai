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

use app\admin\model\Versions as VersionsModel;
use think\facade\Env;

class Versions extends Base
{
    //用户列表
    public function index()
    {
        $data = array(
            'module_name' => '版本',
            'module_url' => 'versions/index',
            'module_slug' => 'Versions',
        );
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['file_name'])) {
                $where['file_name'] = ['like', '%' . $param['file_name'] . '%'];
            }

            $versions = new VersionsModel();
            $selectResult = $versions->getVersionsByWhere($where, $offset, $limit);

            // 拼装参数
            foreach ($selectResult as $key => $vo) {
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $versions->getAllVersions($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        $this->assign('data', $data);
        return $this->fetch();
    }

    // 添加版本
    public function versionsAdd()
    {
        $data = array(
            'module_name' => '版本',
        );
        if (request()->isPost()) {

            $param = input('post.');

            // 保存上传文件
            $file = request()->file('file_url');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move('upload/versions_file', '');
            if ($info) {
                // 上传后
                $param['file_url'] = '/upload/versions_file/' . $info->getSaveName();
                $param['file_md5'] = md5_file(Env::get('root_path') . 'public/' . $param['file_url']);

            } else {
                // 上传失败获取错误信息
                return json(msg(0, 0, $file->getError()));
            }

            $versions = new VersionsModel();

            $flag = $versions->insertVersions($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
        $this->assign('data', $data);
        return $this->fetch();
    }

    // 编辑版本
    public function versionsEdit()
    {
        $data = array(
            'module_name' => '版本',
        );
        $versions = new VersionsModel();

        if (request()->isPost()) {
            $param = input('post.');

            // 更新上传文件
            $file = request()->file('file_url');

            if ($file) {
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move('upload/versions_file', '');
                if ($info) {
                    // 上传后
                    $param['file_url'] = '/upload/versions_file/' . $info->getSaveName();
                    $param['file_md5'] = md5_file(Env::get('root_path') . 'public/' . $param['file_url']);
                } else {
                    // 上传失败获取错误信息
                    return json(msg(0, 0, $file->getError()));
                }
            } else {
                unset($param['file_url']);
            }

            $flag = $versions->editVersions($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'versions' => $versions->getOneVersions($id),
            'data' => $data
        ]);
        return $this->fetch();
    }

    // 删除版本
    public function versionsDel()
    {
        $id = input('param.id');

        $versions = new VersionsModel();

        $flag = $versions->delversions($id);

        return json(msg($flag['code'], $flag['data'], $flag['msg']));
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
                'auth' => 'versions/versionsedit',
                'href' => url('versions/versionsedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'versions/versionsDel',
                'href' => "javascript:versionsDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
