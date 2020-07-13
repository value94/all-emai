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
        $nowVersion = Cache::get($params['app_name']);
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
}