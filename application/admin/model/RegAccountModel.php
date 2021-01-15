<?php

namespace app\admin\model;

use app\admin\validate\RegAccountValidate;
use think\Model;
use think\model\concern\SoftDelete;

class RegAccountModel extends Model
{
    use SoftDelete;

    protected $table = 's_reg_account';
    protected $autoWriteTimestamp = false;

    public function getIsGetAttr($value)
    {
        $status = [0 => '未导出', 1 => '<p style="color: blue">已导出</p>'];
        return $status[$value];
    }

    public function getRegStatusAttr($value)
    {
        $status = [0 => '<p style="color: red">失败</p>', 1 => '成功', 2 => '未使用', 3 => '已获取'];
        return $status[$value];
    }

    public function getCheckStatusAttr($value)
    {
        $status = [0 => '<p style="color: red">失败</p>', 1 => '成功', 2 => '未过检'];
        return $status[$value];
    }


    /**
     * 查询账号
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRegAccountByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据账号id获取账号信息
     * @param $id
     */
    public function getOneRegAccount($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有账号数量
     * @param $where
     * @return int|string
     */
    public function getAllRegAccount($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个账号
     * @param $data
     * @return mixed
     */
    public function insertRegAccount($param)
    {
        try {
            $RegAccountValidate = new RegAccountValidate();
            if (false === $RegAccountValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegAccountValidate->getError());
            }

            $this->save($param);
            return msg(1, url('reg_account/index'), '添加账号成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editRegAccount($param)
    {
        try {
            $RegAccountValidate = new RegAccountValidate();
            if (false === $RegAccountValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegAccountValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('reg_account/index'), '修改账号成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
