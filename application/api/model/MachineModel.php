<?php

namespace app\api\model;

use app\admin\validate\MachineValidate;
use think\Model;
use think\model\concern\SoftDelete;

class MachineModel extends Model
{
    protected $table = 's_machine';
    use SoftDelete;

    /**
     * 查询机器
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMachineByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据机器id获取机器信息
     * @param $id
     */
    public function getOneMachine($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有机器数量
     * @param $where
     * @return int|string
     */
    public function getAllMachine($where)
    {
        return $this->where($where)->count();
    }
}
