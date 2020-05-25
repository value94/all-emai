<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\admin\validate\UserValidate;
use think\Model;

class UserModel extends Model {
    // 确定链接表名
    protected $name = 's_user';

    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUsersByWhere($where, $offset, $limit) {
        return $this->alias('user')->field('user.*,role_name')
            ->join('role rol', 'user.role_id = ' . 'rol.id')
            ->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     * @return float|string
     */
    public function getAllUsers($where) {
        return $this->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     * @return array
     */
    public function insertUser($param) {
        try {
            $UserValidate = new UserValidate();

            if (false === $UserValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $UserValidate->getError());
            }

            $this->save($param);
            return msg(1, url('user/index'), '添加用户成功');

        } catch (\Exception $e) {

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     * @return array
     */
    public function editUser($param) {
        try {
            $UserValidate = new UserValidate();

            if (false === $UserValidate->check($param)) {
                // 验证失败 输出错误信息
                return msg(-1, '', $UserValidate->getError());
            }

            $this->save($param, ['id' => $param['id']]);
            return msg(1, url('user/index'), '编辑用户成功');

        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOneUser($id) {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除管理员
     * @param $id
     * @return array
     */
    public function delUser($id) {
        try {

            $this->where('id', $id)->delete();
            return msg(1, '', '删除管理员成功');

        } catch (\Exception $e) {
            return msg(-1, '', $e->getMessage());
        }
    }

    /**
     * 根据用户名获取管理员信息
     * @param $name
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findUserByName($name) {
        return $this->where('user_name', $name)->find();
    }

    /**
     * 更新管理员状态
     * @param       $uid
     * @param array $param
     * @return array
     */
    public function updateStatus($uid, $param = []) {
        try {
            $this->where('id', $uid)->update($param);
            return msg(1, '', 'ok');
        } catch (\Exception $e) {

            return msg(-1, '', $e->getMessage());
        }
    }

    /**
     * 根据用户名检测用户数据
     * @param $userName
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkUser($userName) {
        return $this->alias('u')->field('u.id as user_id,u.*,r.*')
            ->join('role r', 'u.role_id = r.id')
            ->where('u.user_name', $userName)
            ->find();
    }
}
