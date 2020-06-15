<?php
/**
 * Created by V.
 * Date: 2018/7/9
 * Time: 2:06
 */

namespace app\admin\controller;


use think\facade\Config;
use think\facade\Env;

class General extends Base
{
    /**
     * 读取配置
     * @return mixed
     */
    public function index()
    {
        //查询及返回数据
        if (request()->isAjax()) {
            $param = input('param.');
            if ($this->setconfig($param)) {
                return json(msg('1', 'index', '修改成功'));
            } else {
                return json(msg('0', 'index', '修改失败,请重试'));
            }
        }

        $config_data = Config::pull('setting');
        $this->assign('config_data', $config_data);

        return $this->fetch();
    }

    /**
     * 修改config的函数
     * @param $config array 配置数组
     * @return bool 返回状态
     */
    protected function setconfig($config)
    {
        /**
         * 原理就是 打开config配置文件 然后使用正则查找替换 然后在保存文件.
         * 传递的参数为2个数组 前面的为配置 后面的为数值.  正则的匹配为单引号  如果你的是分号 请自行修改为分号
         * $pat[0] = 参数前缀;      例:   default_return_type
         * $rep[0] = 要替换的内容;    例:  json
         */
        if (is_array($config)) {
            // 删除init文件
            @unlink(Env::get('ROOT_PATH') . 'runtime' . Env::get('DS') . 'init.php');
            $setting = var_export($config, true);
            //配置路径
            $fileurl = Env::get('CONFIG_PATH') . "setting.php";
            file_put_contents($fileurl, '<?php return ' . $setting . ';'); // 写入配置文件
            return true;
        } else {
            return false;
        }
    }
}