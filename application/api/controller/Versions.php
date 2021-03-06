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
use app\api\model\Versions as VersionsModel;

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

        // 查询文件版本是否正确
        $check_versions = VersionsModel::where('file_name', '=', $params['app_name'])->field('file_name,file_versions,file_url,file_md5')->find();
        if ($check_versions) {
            // 返回结果
            $check_versions['msg'] = '需要更新';
            $check_versions['status'] = 1;
            $check_versions['error_code'] = 0;
            $check_versions['file_url'] = env('pro_url') . $check_versions['file_url'];
            if (isset($params['version_num']) &&
                isset($params['file_md5']) &&
                $check_versions['file_versions'] != $params['version_num'] &&
                $check_versions['file_md5'] != $params['file_md5']
            ) {
                return $check_versions;
            } elseif (isset($params['version_num']) && $check_versions['file_versions'] != $params['version_num']) {
                return $check_versions;
            } elseif (isset($params['file_md5']) && $check_versions['file_md5'] != $params['file_md5']) {
                return $check_versions;
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