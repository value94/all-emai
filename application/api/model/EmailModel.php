<?php

namespace app\api\model;

use app\lib\exception\EmailException;
use think\Model;
use think\model\concern\SoftDelete;

class EmailModel extends Model
{
    use SoftDelete;
    protected $table = 's_email';

    // 邮箱类型关联
    public function EmailType()
    {
        return $this->belongsTo('EmailTypeModel', 'email_type_id', 'id');
    }


    public static function getOneNotUseEmail()
    {
        $email_data = self::with('EmailType')->where('use_status', '=', 0)->order('id asc')->find();
        if ($email_data) {
            return $email_data;
        } else {
            throw new EmailException(['msg' => '没有可用邮箱']);
        }
    }

    public static function checkEmail($email_name)
    {
        return self::where('email_name', '=', $email_name)->find();
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
    public static function getEmailByWhere($where)
    {
        return self::with('EmailType')->where($where)->find();
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
}
