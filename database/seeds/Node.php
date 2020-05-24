<?php

use think\migration\Seeder;

class Node extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'id'           => 1,
                "node_name"    => '用户管理',
                "control_name" => '#',
                "action_name"  => '#',
                "is_menu"      => '2',
                "type_id"      => '0',
                "style"        => 'fa fa-users',
            ], [
                'id'           => 2,
                "node_name"    => '管理员管理',
                "control_name" => 'user',
                "action_name"  => 'index',
                "is_menu"      => '2',
                "type_id"      => '1',
                "style"        => '',
            ], [
                'id'           => 3,
                "node_name"    => '添加管理员',
                "control_name" => 'user',
                "action_name"  => 'useradd',
                "is_menu"      => '1',
                "type_id"      => '2',
                "style"        => '',
            ], [
                'id'           => 4,
                "node_name"    => '编辑管理员',
                "control_name" => 'user',
                "action_name"  => 'useredit',
                "is_menu"      => '1',
                "type_id"      => '2',
                "style"        => '',
            ], [
                'id'           => 5,
                "node_name"    => '删除管理员',
                "control_name" => 'user',
                "action_name"  => 'userdel',
                "is_menu"      => '1',
                "type_id"      => '2',
                "style"        => '',
            ], [
                'id'           => 6,
                "node_name"    => '角色管理',
                "control_name" => 'role',
                "action_name"  => 'index',
                "is_menu"      => '2',
                "type_id"      => '1',
                "style"        => '',
            ], [
                'id'           => 7,
                "node_name"    => '添加角色',
                "control_name" => 'role',
                "action_name"  => 'roleadd',
                "is_menu"      => '1',
                "type_id"      => '6',
                "style"        => '',
            ], [
                'id'           => 8,
                "node_name"    => '编辑角色',
                "control_name" => 'role',
                "action_name"  => 'roleedit',
                "is_menu"      => '1',
                "type_id"      => '6',
                "style"        => '',
            ], [
                'id'           => 9,
                "node_name"    => '删除角色',
                "control_name" => 'role',
                "action_name"  => 'roledel',
                "is_menu"      => '1',
                "type_id"      => '6',
                "style"        => '',
            ], [
                'id'           => 10,
                "node_name"    => '分配权限',
                "control_name" => 'role',
                "action_name"  => 'giveaccess',
                "is_menu"      => '1',
                "type_id"      => '6',
                "style"        => '',
            ], [
                'id'           => 11,
                "node_name"    => '系统管理',
                "control_name" => '#',
                "action_name"  => '#',
                "is_menu"      => '2',
                "type_id"      => '0',
                "style"        => 'fa fa-desktop',
            ], [
                'id'           => 12,
                "node_name"    => '数据备份/还原',
                "control_name" => 'data',
                "action_name"  => 'index',
                "is_menu"      => '2',
                "type_id"      => '11',
                "style"        => '',
            ], [
                'id'           => 13,
                "node_name"    => '备份数据',
                "control_name" => 'data',
                "action_name"  => 'importdata',
                "is_menu"      => '1',
                "type_id"      => '12',
                "style"        => '',
            ], [
                'id'           => 14,
                "node_name"    => '还原数据',
                "control_name" => 'data',
                "action_name"  => 'backdata',
                "is_menu"      => '1',
                "type_id"      => '12',
                "style"        => '',
            ], [
                'id'           => 15,
                "node_name"    => '节点管理',
                "control_name" => 'node',
                "action_name"  => 'index',
                "is_menu"      => '2',
                "type_id"      => '1',
                "style"        => '',
            ], [
                'id'           => 16,
                "node_name"    => '添加节点',
                "control_name" => 'node',
                "action_name"  => 'nodeadd',
                "is_menu"      => '1',
                "type_id"      => '15',
                "style"        => '',
            ], [
                'id'           => 17,
                "node_name"    => '编辑节点',
                "control_name" => 'node',
                "action_name"  => 'nodeedit',
                "is_menu"      => '1',
                "type_id"      => '15',
                "style"        => '',
            ], [
                'id'           => 18,
                "node_name"    => '删除节点',
                "control_name" => 'node',
                "action_name"  => 'nodedel',
                "is_menu"      => '1',
                "type_id"      => '15',
                "style"        => '',
            ], [
                'id'           => 19,
                "node_name"    => '个人中心',
                "control_name" => '#',
                "action_name"  => '#',
                "is_menu"      => '1',
                "type_id"      => '0',
                "style"        => '',
            ], [
                'id'           => 20,
                "node_name"    => '编辑信息',
                "control_name" => 'profile',
                "action_name"  => 'index',
                "is_menu"      => '1',
                "type_id"      => '19',
                "style"        => '',
            ], [
                'id'           => 21,
                "node_name"    => '编辑头像',
                "control_name" => 'profile',
                "action_name"  => 'headedit',
                "is_menu"      => '1',
                "type_id"      => '19',
                "style"        => '',
            ], [
                'id'           => 22,
                "node_name"    => '上传头像',
                "control_name" => 'profile',
                "action_name"  => 'uploadheade',
                "is_menu"      => '1',
                "type_id"      => '19',
                "style"        => '',
            ],
        ];

        $node = $this->table('node');
        $node->insert($data)
            ->save();
    }
}