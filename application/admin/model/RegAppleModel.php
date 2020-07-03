<?php

namespace app\admin\model;

use app\admin\validate\RegAppleValidate;
use think\Model;

class RegAppleModel extends Model
{
    protected $table = 's_reg_apple';

    public function getUseStatusAttr($value)
    {
        $status = [0 => '可以使用', 1 => '<p style="color: blue">已使用</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    public function getRegStatusAttr($value)
    {
        $status = [0 => '<p style="color: red">失败</p>', 1 => '<p style="color: green">成功</p>', 2 => '待激活'];
        return $status[$value];
    }

    /**
     * 查询apple待激活账号
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRegAppleByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据apple待激活账号id获取apple待激活账号信息
     * @param $id
     */
    public function getOneRegApple($id)
    {
        return $this->where('id', $id)->find()->getData();
    }

    /**
     * 查询所有apple待激活账号数量
     * @param $where
     * @return int|string
     */
    public function getAllRegApple($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个apple待激活账号
     * @param $param
     * @return mixed
     */
    public function insertRegApple($param)
    {
        try {
            $RegAppleValidate = new RegAppleValidate();
            if (false === $RegAppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegAppleValidate->getError());
            }

            $this->save($param);
            return msg(1, url('RegApple/index'), '添加apple待激活账号成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑
     * @param $param
     * @return array
     */
    public function editRegApple($param)
    {
        try {
            $RegAppleValidate = new RegAppleValidate();
            if (false === $RegAppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegAppleValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('RegApple/index'), '修改apple待激活账号成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
