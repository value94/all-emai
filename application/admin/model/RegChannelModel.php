<?php

namespace app\admin\model;

use app\admin\validate\RegChannelValidate;
use think\Model;
use think\model\concern\SoftDelete;

class RegChannelModel extends Model
{
    use SoftDelete;
    protected $table = 's_channel';

    /**
     * 查询注册通道
     * @param $where
     * @param $offset
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRegChannelByWhere($where, $offset = '', $limit = '')
    {
        return $this->where($where)
            ->limit($offset, $limit)
            ->order('rank asc')
            ->select();
    }

    /**
     * 根据注册通道id获取注册通道信息
     * @param $id
     */
    public function getOneRegChannel($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 查询所有注册通道数量
     * @param $where
     * @return int|string
     */
    public function getAllRegChannel($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加一个注册通道
     * @param $data
     * @return mixed
     */
    public function insertRegChannel($param)
    {
        try {
            $RegChannelValidate = new RegChannelValidate();
            if (false === $RegChannelValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegChannelValidate->getError());
            }

            $this->save($param);
            return msg(1, url('regChannel/index'), '添加注册通道成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑商户
     * @param $param
     * @return array
     */
    public function editRegChannel($param)
    {
        try {
            $RegChannelValidate = new RegChannelValidate();
            if (false === $RegChannelValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $RegChannelValidate->getError());
            }

            $this->update($param, ['id' => $param['id']]);

            return msg(1, url('regChannel/index'), '修改注册通道成功');
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}
