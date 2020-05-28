<?php
/**
 * Created by PhpStorm.
 * User: voke
 * Date: 2020/5/25
 * Time: 11:05
 */

namespace app\api\controller;


use app\api\model\MachineModel;
use app\api\validate\GetMachineValidate;
use think\Controller;

class Machine extends Controller
{
    public function getUnUsedMachine()
    {
        // 数据验证
        $params = (new GetMachineValidate())->goCheck();

        // 获取一个未使用机器
        $machine_data = MachineModel::getOneNotUseMachine($params['force']);

        return ['status' => 1, 'msg' => '成功获取机器', 'machine_data' => $machine_data];
    }

}