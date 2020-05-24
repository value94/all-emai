<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Role extends Migrator
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
        $table = $this->table('role', ['engine' => 'Innodb', 'signed' => false]);

        $table->addColumn('role_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '角色名称'])
            ->addColumn('rule', 'string', ['limit' => 255, 'null' => false, 'comment' => '权限节点数据'])
            ->create();
    }
}
