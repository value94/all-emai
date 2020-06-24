<?php

namespace app\admin\model;

use app\admin\validate\AddressValidate;
use think\Model;

class AddressModel extends Model
{
    protected $table = 's_address';

    public function getUseStatusAttr($value)
    {
        $status = [0 => '可以使用', 1 => '<p style="color: blue">已使用</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    /**
     * 查询地址
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAddressByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据地址id获取地址信息
     * @param $id
     */
    public function getOneAddress($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有地址数量
     * @param $where
     * @return int|string
     */
    public function getAllAddress($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个地址
     * @param $data
     * @return mixed
     */
    public function insertAddress($param)
    {
        try {
            $AddressValidate = new AddressValidate();
            if (false === $AddressValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $AddressValidate->getError());
            }

            $this->save($param);
            return msg(1, url('Address/index'), '添加地址成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editAddress($param)
    {
        try {
            $AddressValidate = new AddressValidate();
            if (false === $AddressValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $AddressValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('Address/index'), '修改地址成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
