<?php

namespace app\api\model;

use app\admin\validate\MachineValidate;
use app\lib\exception\MachineException;
use think\Db;
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
     * 获取一个没有使用过的设备
     * @param $force
     * @return array|\PDOStatement|string|Model
     * @throws MachineException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOneNotUseMachine($force)
    {
        Db::startTrans();
        $machine_data = self::where('use_status', '=', 0)->order('id asc')->find();
        if ($machine_data) {
            if ($force) {
                self::update(['use_status' => 1], ['id' => $machine_data['id']]);
            }
            Db::commit();
            return $machine_data;
        } else {
            Db::rollback();
            throw new MachineException(['msg' => '没有可用机器']);
        }
    }

    public static function checkMachineBySn($sn)
    {
        return self::where('sn', '=', $sn)->find();
    }

    /**
     * 获取一个没有上传过证书的设备
     * @return array|\PDOStatement|string|Model
     * @throws MachineException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function GetNewDevice()
    {
        Db::startTrans();
        $machine_data = self::where('device_cert', '=', null)->order('id asc')->find();
        if ($machine_data) {
            self::update(['use_status' => 1], ['id' => $machine_data['id']]);
            Db::commit();
            return $machine_data;
        } else {
            Db::rollback();
            throw new MachineException(['msg' => '没有可用机器', 'error_code' => 40003]);
        }
    }

    public static function getDeviceByUsedCount($used_count, $sn = '', $check_cert = '')
    {
        $where = [
            ['use_count', '<=', $used_count]
        ];
        if ($sn) $where[] = ['sn', '=', $sn];
        if ($check_cert) $where[] = ['device_cert', '=', 'not null'];


        Db::startTrans();
        $machine_data = self::where($where)->order('id asc')->find();
        if ($machine_data) {
            self::update(['use_count' => $used_count], ['id' => $machine_data['id']]);
            Db::commit();
            return $machine_data;
        } else {
            Db::rollback();
            throw new MachineException(['msg' => '没有可用机器', 'error_code' => 40003]);
        }
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
