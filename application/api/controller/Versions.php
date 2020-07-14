<?php
/**
 * Created by PhpStorm.
 * User: x_x94
 * Date: 2018/8/9
 * Time: 17:40
 */

namespace app\api\controller;


use app\api\validate\VersionsValidate;
use think\Controller;
use think\facade\Cache;

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
        $nowVersion = Cache::tag('versions')->get($params['app_name']);
        if ($nowVersion) {
            $nowVersion = json_decode($nowVersion, true);
            if ($nowVersion['file_versions'] != $params['version_num']) {
                // 返回结果
                $nowVersion['msg'] = '需要更新';
                $nowVersion['status'] = 1;
                $nowVersion['error_code'] = 0;

                return $nowVersion;
            }
            // 返回结果
            return [
                'msg' => '该程序不需要更新',
                'status' => 0,
                'error_code' => 3703
            ];
        } else {
            // 返回结果
            return [
                'msg' => '没有该版本号信息,请先添加',
                'status' => 0,
                'error_code' => 3702
            ];
        }
    }
}