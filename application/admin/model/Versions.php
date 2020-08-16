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
namespace app\admin\model;

use app\admin\validate\VersionsValidate;
use think\facade\Cache;
use think\Model;

class Versions extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 根据搜索条件获取版本列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getVersionsByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的版本数量
     * @param $where
     */
    public function getAllVersions($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入版本信息
     * @param $param
     */
    public function insertVersions($param)
    {
        try {
            $VersionsValidate = new VersionsValidate();
            if (false === $VersionsValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $VersionsValidate->getError());
            } else {
                $this->save($param);

                return msg(1, url('versions/index'), '添加程序版本成功');
            }
        } catch (PDOException $e) {

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑版本信息
     * @param $param
     */
    public function editVersions($param)
    {
        try {
            $VersionsValidate = new VersionsValidate();

            if (false === $VersionsValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            } else {

                $this->save($param, ['id' => $param['id']]);
                return msg(1, url('versions/index'), '编辑版本成功');
            }
        } catch (PDOException $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据版本id获取角色信息
     * @param $id
     */
    public function getOneVersions($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除版本
     * @param $id
     */
    public function delversions($id)
    {
        try {
            $this->where('id', $id)->delete();

            return msg(1, '', '删除版本成功');
        } catch (PDOException $e) {
            return msg(-1, '', $e->getMessage());
        }
    }


}
