<?php
/**
 * Created by PhpStorm.
 * User: x_x94
 * Date: 2018/7/14
 * Time: 21:14
 */

namespace app\api\controller;

use app\api\model\AlipayAccount as AlipayAccountModel;
use app\api\model\AlipayTransfer as AlipayTransferModel;
use app\api\validate\GetPendingAlipayTransfers;
use app\api\validate\SubmitAlipayCardValidate;
use app\api\validate\UpdateAlipayTransferStatus;
use app\lib\exception\AlipayTransferException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\WebRemitException;
use think\Cache;
use think\Controller;

class AlipayTransfer extends Controller
{
    /**
     * 接收转账订单
     * @throws AlipayTransferException
     * @throws SuccessMessage
     * @throws \app\lib\exception\MerchantException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    /*public function SubmitAlipayTransfer()
    {
        $insertCount = config('setting.alipay_count');// 每分钟插入信息数据多少条
        //每分钟限制次数判断
        if (Cache::get('a_date') && Cache::get('a_count') && Cache::get('a_count') > $insertCount) {
            throw new AlipayTransferException([
                'msg' => '系统繁忙,请稍后再试',
                'errorCode' => 42900,
                'code' => 429
            ]);
        }
        //解密及参数校验
        $params = (new SubmitAlipayCardValidate())->goCheck();

        // 验证打款机器是否存在
        $chek_remit = \app\api\model\WebRemit::checkWebRemitIdExist($params['opt_card']);
        if (!$chek_remit) {
            throw new AlipayTransferException([
                'msg' => '卡号不存在,请先访问后台添加',
            ]);
        }

        // 1.存储新的收账账户
        $alipay = new AlipayAccountModel();
        $card = $alipay->where('account_no', '=', $params['account_no'])->find();
        if (!$card) {
            $alipay->save($params);
            // 3.获取账户id
            $params['account_id'] = $alipay->account_id;
        } else {
            // 3.获取账户id
            $params['account_id'] = $card['account_id'];
        }

        // 插入订单数据到待处理队列
        $redis = redisInit();
        $queue_key = 'web-queue-' . $params['opt_card'];
        // 处理转账数据
        $order_data['task'] = [
            'order_no' => $params['order_no'],
            'account_name' => $params['account_name'],
            'account_no' => $params['account_no'],
            'amount' => $params['amount'],
            'bank_name' => $params['bank_name'],
            'type' => 2
        ];
        $queue_order = json_encode($order_data);
        $redis_result = $redis->lPush($queue_key, $queue_order);
        if ($redis_result != false) {
            $params['status'] = 4;
        }

        // 4.存储转账信息
        $AlipayTransfer = new AlipayTransferModel();
        $result = $AlipayTransfer->save($params);
        if (!$result) {
            throw new AlipayTransferException();
        } else {
            //如果是同一分钟的话自加一次,不是同一分钟重新设置限制
            if (Cache::get('a_count') && Cache::get('a_date')) {
                Cache::inc('a_count');
            } else {
                Cache::set('a_count', 1, 60);
                Cache::set('a_date', 1, 60);
            }
            throw new SuccessMessage();
        }
    }*/

    /**
     * 获取待处理订单
     * @return mixed
     * @throws AlipayTransferException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function GetPendingAlipayTransfer()
    {
        //派单开关
        if (config('setting.alipay_send_switch')) {
            throw new AlipayTransferException([
                'msg' => '系统已停止派单,请稍后再试!',
                'errorCode' => 50003,
                'code' => 503
            ]);
        }

        // redis初始化
        $redis = redisInit();

        //数据解密及验证
        $params = (new GetPendingAlipayTransfers())->goCheck();

        // 验证机器状态
        $chek_remit = \app\api\model\WebRemit::checkWebRemitIdExist($params['RemitCard']);
        if (!$chek_remit) {
            throw new WebRemitException([
                'msg' => '机器不存在/状态异常',
                'errorCode' => 30300,
                'code' => 303
            ]);
        }
        // 判断机器有查账任务
        $getInfo_job = $redis->hGet('remit-job-2019-getInfo', $params['RemitCard']);
        if (false != $getInfo_job) {
            //设置返回信息
            $job_data['task'] = [
                'opt_card' => $params['RemitCard'],
                'type' => 4
            ];
            // 删除任务
            $redis->hDel('remit-job-2019-getInfo', $params['RemitCard']);
            return $job_data;
        }
        // 判断机器有查余额任务
        $getBalance_job = $redis->hGet('remit-job-2019-getBalance', $params['RemitCard']);
        if (false != $getBalance_job){
            //设置返回信息
            $job_data['task'] = [
                'opt_card' => $params['RemitCard'],
                'type' => 3
            ];
            // 删除任务
            $redis->hDel('remit-job-2019-getBalance', $params['RemitCard']);
            return $job_data;
        }

        // 系统频率处理
        $now_count = $redis->get('web_bank_get_count');
        // 每分钟插入信息数据多少条
        $getCount = config('setting.get_alipay_count');

        //每分钟限制次数判断
        if ($now_count > $getCount) {
            throw new AlipayTransferException([
                'msg' => '系统繁忙,请稍后再试',
                'errorCode' => 42900,
                'code' => 429
            ]);
        } elseif ($now_count == false) {
            // 设置处理次数定时器
            $redis->set('web_bank_get_count', 1, 60);
        } else {
            //处理次数加1
            $redis->incr('web_bank_get_count');
        }

        // 获取一条该转账机器订单
        $pending_order = AlipayTransferModel::getPendingAlipayOrders($params);

        //处理订单数据
        $pendingTransferData['task'] = [
            'opt_card' => $params['RemitCard'],
            'order_no' => $pending_order['order_no'],
            'account_name' => $pending_order['alipay_account'][0]['account_name'],
            'account_no' => $pending_order['alipay_account'][0]['account_no'],
            'amount' => $pending_order['amount'],
            'bank_name' => $pending_order['alipay_account'][0]['bank_name'],
            'type' => 2
        ];
        $pendingTransferData = json_encode($pendingTransferData);
        //返回加密数据
        ob_clean();
        echo $pendingTransferData;
        /*$openssl = new Openssl();
        echo $openssl->private_encrypt(json_encode($pendingTransferData));*/

    }

    /**
     * 更新订单状态
     * @throws AlipayTransferException
     * @throws SuccessMessage
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function UpdateAlipayTransferStatus()
    {
        $params = (new UpdateAlipayTransferStatus())->goCheck();
        //处理次数验证
        $order = AlipayTransferModel::checkAlipayOrderIsReal($params['order_no']);
        //判断订单是不是为待处理/成功订单
        if ($order['status'] == 3 || $order['status'] == 1) {
            throw new SuccessMessage([
                'msg' => '该订单不是处理中或失败的订单',
                'errorCode' => 40017,
                'code' => 417
            ]);
        }
        // 更新机器余额
        if (!empty($params['balance']) && $params['balance'] != -1) {
            $remit_balance = $params['balance'];
            $card_no = $order['opt_card'];

            $update_result = \app\api\model\WebRemit::updateBalance($remit_balance, $card_no);
            if (!$update_result) {
                throw new SuccessMessage([
                    'msg' => '更新机器余额失败,请重试',
                    'status' => 0,
                    'errorCode' => 34000
                ]);
            }
        }
        // 是否需要保持截图
        if (!empty($params['img'])) {
            //保存截图
            $TransferImg = $this->saveBase64Image('data:image/jpg;base64,' . $params['img'], $params['order_no']);
            //保存base64图片
            if ($TransferImg['code'] == 0) {
                throw new AlipayTransferException([
                    'msg' => $TransferImg['msg'],
                    'errorCode' => 40006,
                    'code' => 406
                ]);
            }
            //将图片路径存储到数据库中
            unset($params['img']);
            $params['img_url'] = $TransferImg['url'];
        }
        //更新订单状态
        $result = AlipayTransferModel::updateAlipayOrderStatus($params);
        if ($result) {
            //判断是否需要回调
            if ($params['status'] == 1 || $params['status'] == 0 && $order['notify_url'] != null) {
                //回调接口
                $notify_result = $this->notify($params, $order);
                if ($notify_result != null) {
                    //保存回调数据
                    $notify_result = json_decode($notify_result, true);
                    AlipayTransferModel::where('order_no', '=', $params['order_no'])->update([
                        'notify_status' => isset($notify_result['status']) ? $notify_result['status'] : 2,
                        'notify_time' => time()
                    ]);
                }
            }
            //输出调试日志
//            mylog('test', "updateData:" . json_encode($params), 'updateBalance' . date('Ymd'));
            //返回状态
            throw new SuccessMessage([
                'msg' => '更新订单状态成功!'
            ]);
        } else {
            throw new SuccessMessage([
                'msg' => '更新订单状态失败!',
                'status' => 0
            ]);
        }
    }

    /**
     * 回调商户,返回订单状态
     * @param $params
     * @param $order
     * @return \type
     */
    public function notify($params, $order)
    {
        //添加金额数据
        $params['amount'] = $order['amount'];
        //添加加密验证
        $params['Sign'] = md5('sdYUGUG..68,>231>>');
        //移除不必要的数据
        if (isset($params['Pub'])) unset($params['Pub']);
        if (isset($params['type'])) unset($params['type']);
        //调用curl发送请求
        $result = cUrlGetData($order['notify_url'], json_encode($params));

        return $result;
    }

    /**
     * 保存base64 图片
     * @param $base64_image_content
     * @param $image_name
     * @return mixed
     */
    function saveBase64Image($base64_image_content, $image_name)
    {

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {

            //图片后缀
            $type = $result[2];

            $image_name = $image_name . '.' . $type;
            //保存位置--图片名
            $image_url = '/uploads/image/' . date('Ymd') . '/' . $image_name;
            if (!is_dir(dirname('.' . $image_url))) {
                mkdir(dirname('.' . $image_url), 0777, true);
                chmod(dirname('.' . $image_url), 0777);
                umask();
            }
            /* $transferImg = \think\Image::open('./uploads/image/20180702/02533358214.jpg');
             // uploads/image/20180702/00222291221.jpeg
             var_dump($transferImg);
             die();*/
            //解码
            $decode = base64_decode(str_replace($result[1], '', $base64_image_content));
            if (file_put_contents('.' . $image_url, $decode)) {
                // 处理成略缩图
                // 1.获取刚保存的图片
                /*$transferImg = \think\Image::open('.'.$image_url);
    //            $transferImg = \think\Image::open('./static/admin/images/a1.jpg'.$image_url);
                var_dump($transferImg);
                die();*/
                // 2.处理成略缩图
                $data['code'] = 1;
                $data['imageName'] = $image_name;
                $data['url'] = $image_url;
                $data['msg'] = '保存成功！';
            } else {
                $data['code'] = 0;
                $data['imgageName'] = '';
                $data['url'] = '';
                $data['msg'] = '图片保存失败！';
            }
        } else {
            $data['code'] = 0;
            $data['imgageName'] = '';
            $data['url'] = '';
            $data['msg'] = 'base64图片格式有误！';
        }
        return $data;
    }

    /**
     * 从队列中获取待处理订单
     * @return mixed
     * @throws AlipayTransferException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function GetPendingAlipayTransferByQueue()
    {
        //派单开关
        if (config('setting.alipay_send_switch')) {
            throw new AlipayTransferException([
                'msg' => '系统已停止派单,请稍后再试!',
                'errorCode' => 50003,
                'code' => 503
            ]);
        }

        // 每分钟插入信息数据多少条
        $getCount = config('setting.get_alipay_count');
        //每分钟限制次数判断
        $redis = redisInit();
        $now_count = $redis->get('web_bank_get_count');
        if ($now_count > $getCount) {
            throw new AlipayTransferException([
                'msg' => '系统繁忙,请稍后再试',
                'errorCode' => 42900,
                'code' => 429
            ]);
        } elseif ($now_count == false) {
            // 设置处理次数定时器
            $redis->set('web_bank_get_count', 1, 60);
        } else {
            //处理次数加1
            $redis->incr('web_bank_get_count');
        }

        //数据解密及验证
        $params = (new GetPendingAlipayTransfers())->goCheck();

        // 从待处理队列中提取订单
        $queue_key = 'web-queue-' . $params['RemitCard'];

        $pending_orders = $redis->rPop($queue_key);
        if (empty($pending_orders)) {
            throw new AlipayTransferException([
                'msg' => '该机器没有待处理订单,请稍后再试!',
                'errorCode' => 42200,
                'code' => 422
            ]);
        }

        // 验证订单数据
        $orders_array = json_decode($pending_orders, true);

        $order_no = isset($orders_array['task']['order_no']) ? $orders_array['task']['order_no'] : '';
        if (empty($order_no)) {
            throw new AlipayTransferException([
                'msg' => '订单数据错误,请稍后再试',
                'errorCode' => 43100,
                'code' => 431
            ]);
        }
        // 存储记录数据
        $params['requested_time'] = time();
        $params['order_no'] = $order_no;

        $update_result = AlipayTransferModel::updateAlipayOrderStatus($params);

        if ($update_result) {
            // 改变机器余额
            /*if (Cache::get($AlipayID)){
                Cache::dec($AlipayID,$pending_orders['TransferAmount']);
                if (Cache::get($AlipayID) < 100){
                    //更新机器余额到数据库
                    Remit::updateBalance(Cache::get($AlipayID),$AlipayID);
                }
            }*/
            //返回加密数据
            ob_clean();
            echo $pending_orders;
            /*$openssl = new Openssl();
            echo $openssl->private_encrypt(json_encode($pendingTransferData));*/
        } else {
            throw new AlipayTransferException([
                'msg' => '没有待处理订单,请稍后再试',
                'errorCode' => 43000,
                'code' => 430
            ]);
        }
    }
}