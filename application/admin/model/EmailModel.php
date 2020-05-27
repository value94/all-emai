<?php

namespace app\admin\model;

use app\admin\validate\EmailValidate;
use think\Model;
use think\model\concern\SoftDelete;

class EmailModel extends Model
{
    protected $table = 's_email';
    use SoftDelete;

    public function getUseStatusAttr($value)
    {
        $status = [0 => '未使用', 1 => '<p style="color: blue">已使用</p>'];
        return $status[$value];
    }

    public function getRegStatusAttr($value)
    {
        $status = [0 => '<p style="color: red">失败</p>', 1 => '成功', 2 => '未使用'];
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
    public function getEmailByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据邮箱id获取邮箱信息
     * @param $id
     */
    public function getOneEmail($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有邮箱数量
     * @param $where
     * @return int|string
     */
    public function getAllEmail($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个邮箱
     * @param $data
     * @return mixed
     */
    public function insertEmail($param)
    {
        try {
            $EmailValidate = new EmailValidate();
            if (false === $EmailValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $EmailValidate->getError());
            }

            $this->save($param);
            return msg(1, url('email/index'), '添加邮箱成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editEmail($param)
    {
        try {
            $EmailValidate = new EmailValidate();
            if (false === $EmailValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $EmailValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('email/index'), '修改邮箱成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
