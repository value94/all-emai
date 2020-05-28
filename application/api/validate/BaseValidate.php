<?php
/**
 * Created by V
 * Date: 2018/1/24
 * Time: 10:46
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Env;
use think\Validate;

class BaseValidate extends Validate
{
    //进行参数校验
    public function goCheck()
    {
        //参数获取
        $params = @file_get_contents('php://input');
        mylog('接口 ' . Env::get('log_path') . '接收参数:', $params, 'param.log');
        $params = json_decode($params, true);

        //根据token去数据库获取秘钥
        /*$token = Request::instance()->header('token');
        $merchant = Merchant::getPkByToken($token);*/

        //解密数据
        /*if ($merchant['type'] === 2){
            $Openssl = new Openssl();
            $params = $Openssl->public_encrypt($params);
            echo $params;die();
            if (!is_array($params)){
                throw new OpensslException([
                    'msg' => '解密失败'
                ]);
            }
        }else{
            echo base64_encode(sodium_crypto_box_seal(json_encode($params), base64_decode($merchant['Pub'])));die();

            $params = json_decode(sodium_crypto_box_seal_open($params,base64_decode($merchant['Pkk'])),true);
            var_dump($params);
            die();
        }*/
        //判断商户信息
        /*if ($merchant){
            $params['MerchantId'] = $merchant['MerchantId'];
            $params['Pub'] = $merchant['Pub'];
            $params['type'] = $merchant['type'];
        }else{
            throw new MerchantException([
                'msg' => '没有相应商户'
            ]);
        }*/
        //参数验证
        $result = $this->batch()->check($params);
        if (!$result) {
            throw new ParameterException([
                'msg' => $this->error,
            ]);
        } else {
            return $params;
        }
    }

    /*
     * 判断是否符合手机号验证规则
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 判断是否是正整数
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function checkOrderNoIsRel($value)
    {
        $result = BankTransfer::where('OrderNo', '=', $value)->find();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}