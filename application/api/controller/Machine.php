<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:05
 */

namespace app\api\controller;


use app\api\model\MachineModel;
use app\api\validate\GetFullDevice;
use app\api\validate\GetMachineValidate;
use app\api\validate\SendDeviceCert;
use app\api\validate\SendUsedCount;
use app\lib\exception\MachineException;
use think\Controller;

class Machine extends Controller
{
    public function getUnUsedMachine()
    {
        // 数据验证
        $params = (new GetMachineValidate())->goCheck();

        // 获取一个未使用机器
        try {
            $machine_data = MachineModel::getOneNotUseMachine($params['force']);
        } catch (MachineException $e) {
            throw new MachineException();
        }

        return ['status' => 1, 'msg' => '成功获取机器', 'machine_data' => $machine_data];
    }

    public function GetNewDevice()
    {
        // 获取一个未使用机器
        try {
            $machine_data = MachineModel::GetNewDevice();
        } catch (MachineException $e) {
            throw new MachineException();
        }

        $returnData = [
            "Serial" => $machine_data['sn'],
            "Imei" => $machine_data['imei'],
            "Bt" => $machine_data['bt'],
            "Wifi" => $machine_data['wifi'],
            "Udid" => $machine_data['udid'],
            "Ecid" => $machine_data['ecid'],
            "ProductType" => $machine_data['ProductType'],
        ];

        return ['status' => 1, 'msg' => '成功获取新设备', 'machine_data' => $returnData];
    }

    public function SendDeviceCert()
    {
        // 数据验证
        $params = (new SendDeviceCert())->goCheck();

        // 验证设备是否存在
        $check = MachineModel::checkMachineBySn($params['Serial']);
        if (!$check) {
            throw new MachineException(['msg' => '不存在该序列号的机器信息', 'error_code' => 40004]);
        }

        // 更新机器证书数据
        $result = MachineModel::update(['device_cert' => $params['DeviceCert']], ['sn' => $params['Serial']]);

        if ($result) {
            return ['status' => 1, 'msg' => '成功'];
        } else {
            return ['status' => 0, 'msg' => '失败'];
        }

    }

    public function SendUsedCount()
    {
        // 数据验证
        $params = (new SendUsedCount())->goCheck();

        // 验证设备是否存在
        $check = MachineModel::checkMachineBySn($params['Serial']);
        if (!$check) {
            throw new MachineException(['msg' => '不存在该序列号的机器信息', 'error_code' => 40004]);
        }

        // 更新使用次数
        $result = MachineModel::update(['use_count' => $params['UsedCount']], ['sn' => $params['Serial']]);

        if ($result) {
            return ['status' => 1, 'msg' => '成功'];
        } else {
            return ['status' => 0, 'msg' => '失败'];
        }
    }

    public function GetFullDevice()
    {
        // 数据验证
        $params = (new GetFullDevice())->goCheck();

        // 验证设备是否存在
        isset($params['Serial']) ? $sn = $params['Serial'] : $sn = '';
        if ($sn) {
            $check = MachineModel::checkMachineBySn($sn);
            if (!$check) {
                throw new MachineException(['msg' => '不存在该序列号的机器信息', 'error_code' => 40004]);
            }
        }


        $machine_data = MachineModel::getDeviceByUsedCount($params['MaxUsedCount'], $sn);

        $returnData = [
            "Serial" => $machine_data['sn'],
            "Imei" => $machine_data['imei'],
            "Bt" => $machine_data['bt'],
            "Wifi" => $machine_data['wifi'],
            "Udid" => $machine_data['udid'],
            "Ecid" => $machine_data['ecid'],
            "ProductType" => $machine_data['ProductType'],
            'UsedCount' => $params['MaxUsedCount'],
            'DeviceCert' => $machine_data['device_cert'],
        ];

        return ['status' => 1, 'msg' => '成功获取新设备', 'machine_data' => $returnData];
    }

}