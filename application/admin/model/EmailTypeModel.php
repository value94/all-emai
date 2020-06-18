<?php

namespace app\admin\model;

use app\admin\validate\EmailTypeValidate;
use think\Model;
use think\model\concern\SoftDelete;

class EmailTypeModel extends Model
{
    protected $table = 's_email_type';
    use SoftDelete;

    public function getConnectionMethodAttr($value)
    {
        $status = [1 => '<p style="color: green">imap</p>', 2 => '<p style="color: blue">pop3</p>'];
        return $status[$value];
    }

    /**
     * 查询邮箱类型
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmailTypeByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据邮箱类型id获取邮箱类型信息
     * @param $id
     */
    public function getOneEmailType($id)
    {
        return $this->where('id', $id)->find()->getData();
    }

    /**
     * 查询所有邮箱类型数量
     * @param $where
     * @return int|string
     */
    public function getAllEmailType($where)
    {
        return $this->where($where)->count();
    }

    public static function getEmailType()
    {
        return self::select();
    }

    public static function getEmailTypeById($id)
    {
        return self::where('id', '=', $id)->find();
    }

    /**
     * 添加一个邮箱类型
     * @param $data
     * @return mixed
     */
    public function insertEmailType($param)
    {
        try {
            $EmailTypeValidate = new EmailTypeValidate();
            if (false === $EmailTypeValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $EmailTypeValidate->getError());
            }

            $this->save($param);
            return msg(1, url('email_type/index'), '添加邮箱类型成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editEmailType($param)
    {
        try {
            $EmailTypeValidate = new EmailTypeValidate();
            if (false === $EmailTypeValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $EmailTypeValidate->getError());
            }
            $this->save($param, ['id' => $param['id']]);

            return msg(1, url('email_type/index'), '修改邮箱类型成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
