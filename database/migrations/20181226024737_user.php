<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('user', ['engine' => 'Innodb', 'signed' => false]);

        $table->addColumn('user_name', 'string', ['limit' => 255, 'null' => false, "default" => '', 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false, "default" => "", 'comment' => '密码'])
            ->addColumn('head', 'string', ['limit' => 255, 'null' => false, "default" => "", 'comment' => '头像'])
            ->addColumn('login_times', 'integer', ['limit' => 1, 'null' => false, "default" => "0", 'comment' => '登陆次数'])
            ->addColumn('last_login_ip', 'string', ['limit' => 255, 'null' => false, "default" => "", 'comment' => '最后登录IP'])
            ->addColumn('last_login_time', 'integer', ['null' => false, "default" => '0', 'comment' => '最后登录时间'])
            ->addColumn('real_name', 'string', ['null' => false, 'comment' => '真实姓名'])
            ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, "default" => "0", 'comment' => '状态'])
            ->addColumn('role_id', 'integer', ['null' => false, "default" => "1", 'comment' => '用户角色id'])
            ->create();
    }
}
