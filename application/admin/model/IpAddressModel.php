<?php

namespace app\admin\model;

use app\admin\validate\IpAddressValidate;
use think\Model;

class IpAddressModel extends Model
{
    protected $table = 's_ip_address';

    public function getUseStatusAttr($value)
    {
        $status = [0 => '未使用', 1 => '<p style="color: blue">已使用</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    /**
     * 查询邮箱
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getIpAddressByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据邮箱id获取邮箱信息
     * @param $id
     */
    public function getOneIpAddress($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有邮箱数量
     * @param $where
     * @return int|string
     */
    public function getAllIpAddress($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个邮箱
     * @param $data
     * @return mixed
     */
    public function insertIpAddress($param)
    {
        try {
            $IpAddressValidate = new IpAddressValidate();
            if (false === $IpAddressValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $IpAddressValidate->getError());
            }

            $this->save($param);
            return msg(1, url('IpAddress/index'), '添加邮箱成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editIpAddress($param)
    {
        try {
            $IpAddressValidate = new IpAddressValidate();
            if (false === $IpAddressValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $IpAddressValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('IpAddress/index'), '修改邮箱成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
