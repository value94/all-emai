<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Db;

class Data extends Base {
    // 备份首页列表
    /**
     * @return mixed
     */
    public function index() {
        $dbname = Db::getConfig('database');

        $tables = Db::query('show tables');
        foreach ($tables as $key => &$vo) {
            $table = $vo['Tables_in_' . $dbname];

            $count = Db::table($table)->count();

            $tables[$key]['alls']    = $count;
            $tables[$key]['operate'] = showOperate($this->makeButton($table));

            if (file_exists(config('back_path') . $table . ".sql")) {
                $tables[$key]['ctime'] = date('Y-m-d H:i:s', filemtime(config('back_path') . $table . ".sql"));
            } else {
                $tables[$key]['ctime'] = '无';
            }
        }

        $this->assign(['tables' => $tables]);
        $this->assign('database_name', 'Tables_in_' . $dbname);

        return $this->fetch();
    }

    // 备份数据

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function importData() {
        set_time_limit(0);
        $table = addslashes(input('param.table'));

        $sqlStr = "SET FOREIGN_KEY_CHECKS=0;\r\n";
        $sqlStr .= "DROP TABLE IF EXISTS `$table`;\r\n";
        $create = db()->query('show create table ' . $table);
        $sqlStr .= $create['0']['Create Table'] . ";\r\n";
        $sqlStr .= "\r\n";

        $result = db()->query('select * from ' . $table);
        foreach ($result as $key => $vo) {
            $keys   = array_keys($vo);
            $keys   = array_map('addslashes', $keys);
            $keys   = join('`,`', $keys);
            $keys   = "`" . $keys . "`";
            $vals   = array_values($vo);
            $vals   = array_map('addslashes', $vals);
            $vals   = join("','", $vals);
            $vals   = "'" . $vals . "'";
            $sqlStr .= "insert into `$table`($keys) values($vals);\r\n";
        }

        $filename = config('back_path') . $table . ".sql";
        $fp       = fopen($filename, 'w');
        fputs($fp, $sqlStr);
        fclose($fp);

        return json(['code' => 1, 'data' => '', 'msg' => 'success']);
    }

    // 还原数据

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function backData() {
        set_time_limit(0);
        $table = addslashes(input('param.table'));

        if (stripos($table, "..")) {
            return json(['code' => 999, 'data' => '', 'msg' => 'error']);
        }

        if (!file_exists(config('back_path') . $table . ".sql")) {
            return json(['code' => -1, 'data' => '', 'msg' => '备份数据不存在!']);
        }

        $sqls = analysisSql(config('back_path') . $table . ".sql");
        foreach ($sqls as $key => $sql) {
            db()->query($sql);
        }
        return json(['code' => 1, 'data' => '', 'msg' => 'success']);
    }

    /**
     * 拼装操作按钮
     * @param $table
     * @return array
     */
    private function makeButton($table) {
        return [
            '备份' => [
                'auth'     => 'data/importdata',
                'href'     => "javascript:importData('" . $table . "')",
                'btnStyle' => 'primary',
                'icon'     => 'fa fa-tasks',
            ],
            '还原' => [
                'auth'     => 'data/backdata',
                'href'     => "javascript:backData('" . $table . "')",
                'btnStyle' => 'info',
                'icon'     => 'fa fa-retweet',
            ],
        ];
    }

}
