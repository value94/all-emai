<?php

namespace app\admin\model;

use app\admin\validate\CheckAppleValidate;
use think\Model;

class CheckAppleModel extends Model
{
    protected $table = 's_check_apple';

    public function getUseStatusAttr($value)
    {
        $status = [
            0 => '可以使用',
            1 => '<p style="color: blue">已使用</p>',
            2 => '<p style="color: red">停止使用</p>'
        ];

        return $status[$value];
    }

    public function getCheckStatusAttr($value)
    {
        $status = [
            0 => '<p style="color: red">失败</p>',
            1 => '<p style="color: green">成功</p>',
            2 => '待过检'
        ];
        return $status[$value];
    }

    /**
     * 查询apple待过检账号
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCheckAppleByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据apple待过检账号id获取apple待过检账号信息
     * @param $id
     */
    public function getOneCheckApple($id)
    {
        return $this->where('id', $id)->find()->getData();
    }

    /**
     * 查询所有apple待过检账号数量
     * @param $where
     * @return int|string
     */
    public function getAllCheckApple($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个apple待过检账号
     * @param $param
     * @return mixed
     */
    public function insertCheckApple($param)
    {
        try {
            $CheckAppleValidate = new CheckAppleValidate();
            if (false === $CheckAppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $CheckAppleValidate->getError());
            }

            $this->save($param);
            return msg(1, url('CheckApple/index'), '添加apple待过检账号成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑
     * @param $param
     * @return array
     */
    public function editCheckApple($param)
    {
        try {
            $CheckAppleValidate = new CheckAppleValidate();
            if (false === $CheckAppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $CheckAppleValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('CheckApple/index'), '修改apple待过检账号成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
