<?php

namespace app\admin\model;

use app\admin\validate\MachineValidate;
use think\Model;
use think\model\concern\SoftDelete;

class MachineModel extends Model
{
    use SoftDelete;

    protected $table = 's_machine';

    public function email()
    {
        return $this->hasOne('EmailModel', 'id', 'email_id');
    }

    public function getUseStatusAttr($value)
    {
        $status = [0 => '未使用', 1 => '<p style="color: blue">已使用</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    /*public function getDeviceCert($value)
    {
        empty($value) ? $value = 1 : $value = 0;
        $status = [0 => '未上传', 1 => '<p style="color: blue">已上传</p>', 2 => '<p style="color: red">停止使用</p>'];

        return $status[$value];
    }*/

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
        return $this->where($where)->with('email')->limit($offset, $limit)->order('id desc')->select();
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

    /**
     * 添加一个机器
     * @param $data
     * @return mixed
     */
    public function insertMachine($param)
    {
        try {
            $MachineValidate = new MachineValidate();
            if (false === $MachineValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $MachineValidate->getError());
            }

            $this->save($param);
            return msg(1, url('Machine/index'), '添加机器成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editMachine($param)
    {
        try {
            $MachineValidate = new MachineValidate();
            if (false === $MachineValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $MachineValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('Machine/index'), '修改机器成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
