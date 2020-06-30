<?php

namespace app\admin\model;

use app\admin\validate\AppleValidate;
use think\Model;

class AppleModel extends Model
{
    protected $table = 's_apple';

    public function getUseStatusAttr($value)
    {
        $status = [0 => '可以使用', 1 => '<p style="color: blue">已使用</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    /**
     * 查询apple备用账号
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAppleByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据apple备用账号id获取apple备用账号信息
     * @param $id
     */
    public function getOneApple($id)
    {
        return $this->where('id', $id)->find()->getData();
    }

    /**
     * 查询所有apple备用账号数量
     * @param $where
     * @return int|string
     */
    public function getAllApple($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个apple备用账号
     * @param $param
     * @return mixed
     */
    public function insertApple($param)
    {
        try {
            $AppleValidate = new AppleValidate();
            if (false === $AppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $AppleValidate->getError());
            }

            $this->save($param);
            return msg(1, url('Apple/index'), '添加apple备用账号成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑
     * @param $param
     * @return array
     */
    public function editApple($param)
    {
        try {
            $AppleValidate = new AppleValidate();
            if (false === $AppleValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $AppleValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('Apple/index'), '修改apple备用账号成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
