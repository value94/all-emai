<?php

namespace app\admin\model;

use app\admin\validate\SmsPhoneValidate;
use think\Model;
use think\model\concern\SoftDelete;

class SmsPhoneModel extends Model
{
    use SoftDelete;
    protected $table = 's_sms_phone';

    // 运行状态
    public function getStatusAttr($value)
    {
        $status = [0 => '空闲', 1 => '<p style="color: blue">接码中</p>', 2 => '<p style="color: red">停止使用</p>'];
        return $status[$value];
    }

    /**
     * 查询任务设备
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSmsPhoneByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    // 根据条件,获取数量
    public function getSumByWhere($where, $field)
    {
        return $this->where($where)->sum($field);
    }

    /**
     * 根据任务设备id获取任务设备信息
     * @param $id
     */
    public function getOneSmsPhone($id)
    {
        return $this->get($id)->getData();
    }

    /**
     * 查询所有任务设备数量
     * @param $where
     * @return int|string
     */
    public function getAllSmsPhone($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个任务设备
     * @param $data
     * @return mixed
     */
    public function insertSmsPhone($param)
    {
        try {
            $SmsPhoneValidate = new SmsPhoneValidate();
            if (false === $SmsPhoneValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $SmsPhoneValidate->getError());
            }

            $this->save($param);

            return msg(1, url('SmsPhone/index'), '添加SMS设备成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editSmsPhone($param)
    {
        try {
            $SmsPhoneValidate = new SmsPhoneValidate();
            if (false === $SmsPhoneValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $SmsPhoneValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('SmsPhone/index'), '修改SMS设备成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
