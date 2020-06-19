<?php

namespace app\admin\model;

use app\admin\validate\PhoneValidate;
use think\Model;
use think\model\concern\SoftDelete;

class PhoneModel extends Model
{
    use SoftDelete;
    protected $table = 's_phone';

    public function email()
    {
        return $this->hasOne('EmailModel', 'id', 'email_id');
    }

    public function getStatusAttr($value)
    {
        $status = [0 => '未运行', 1 => '<p style="color: blue">正在运行</p>', 2 => '<p style="color: red">停止使用</p>'];
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
    public function getPhoneByWhere($where, $offset, $limit)
    {
        return $this->where($where)->with('email')->limit($offset, $limit)->order('update_time desc')->select();
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
    public function getOnePhone($id)
    {
        return $this->get($id)->getData();
    }

    /**
     * 查询所有任务设备数量
     * @param $where
     * @return int|string
     */
    public function getAllPhone($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个任务设备
     * @param $data
     * @return mixed
     */
    public function insertPhone($param)
    {
        try {
            $PhoneValidate = new PhoneValidate();
            if (false === $PhoneValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $PhoneValidate->getError());
            }

            $this->save($param);

            return msg(1, url('Phone/index'), '添加任务设备成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editPhone($param)
    {
        try {
            $PhoneValidate = new PhoneValidate();
            if (false === $PhoneValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $PhoneValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('Phone/index'), '修改任务设备成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
