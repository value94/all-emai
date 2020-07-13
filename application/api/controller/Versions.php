<?php
/**
 * Created by PhpStorm.
 * User: x_x94
 * Date: 2018/8/9
 * Time: 17:40
 */

namespace app\api\controller;


use app\api\validate\VersionsValidate;
use think\Cache;
use think\Controller;

class Versions extends Controller
{
    /**
     * 验证版本号
     * @return array
     * @throws \app\lib\exception\MerchantException
     * @throws \app\lib\exception\ParameterException
     */
    public function CheckVersions()
    {
        // 解密及验证参数
        $params = (new VersionsValidate())->goCheck();
        // 从redis缓存中判断版本号是否正确
        $nowVersion = Cache::get($params['BankName']);
        if ($nowVersion) {
            // 返回结果
            return json_decode($nowVersion,true);
        }else{
            // 返回结果
            return [
                'Msg' => '没有该版本号信息,请先添加',
                'Status' => 0,
                'ErrorCode' => 3702
            ];
        }
    }

    /**
     * 更新版号
     * @return array
     * @throws \app\lib\exception\MerchantException
     * @throws \app\lib\exception\ParameterException
     */
    public function SendVersions()
    {
        // 解密及验证参数
        $params = (new VersionsValidate())->goCheck();
        // 更新版本号到数据库
        $versionsModel = new \app\api\model\Versions();

        $ver = Cache::get($params['BankName']);
        //判断是更新还是新增
        if ($ver){
            $result = $versionsModel->where([
                'BankName'=> $params['BankName']
            ])->update($params);
            //判断是不是版本号相同
            if (!$result){
                // 返回结果
                return [
                    'Msg' => '该版本号已存在',
                    'Status' => 0,
                    'ErrorCode' => 3701
                ];
            }
        }else{
            // 执行修改
            $result = $versionsModel->isUpdate(false)->save($params);
        }

        // 生成缓存
        if ($result){
            $versions = [
                'ServerVersions' => $params['ServerVersions'],
                'NewloginVersions' => $params['NewloginVersions'],
                'BankVersions' => $params['BankVersions'],
            ];
            //添加缓存数据
            Cache::set($params['BankName'],json_encode($versions));
            // 返回结果
            return [
                'Msg' => '更新版本号成功',
                'Status' => 1,
                'ErrorCode' => 0
            ];
        }else{
            // 返回结果
            return [
                'Msg' => '更新版本号失败请重试',
                'Status' => 0,
                'ErrorCode' => 3700
            ];
        }
    }
}