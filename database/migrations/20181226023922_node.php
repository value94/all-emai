<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Node extends Migrator
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
        $table = $this->table('node', ['engine' => 'Innodb', 'signed' => false]);

        $table->addColumn('node_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '节点名称'])
            ->addColumn('control_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '控制器名'])
            ->addColumn('action_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '方法名'])
            ->addColumn('is_menu', 'integer', ['limit' => 1, 'null' => false, "default" => "1", 'comment' => '是否是菜单项 1不是 2是'])
            ->addColumn('type_id', 'integer', ['null' => false, 'comment' => '父级节点id'])
            ->addColumn('style', 'string', ['null' => false, 'comment' => '菜单样式'])
            ->create();
    }
}
 