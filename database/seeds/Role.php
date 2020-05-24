<?php

use think\migration\Seeder;

class Role extends Seeder
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
                'id'        => 1,
                'role_name' => '超级管理员',
                'rule'      => '*',
            ],
            [
                'id'        => 2,
                'role_name' => '系统维护员',
                'rule'      => '1,2,3,4,5,6,7,8,9,10',
            ],
        ];

        $posts = $this->table('role');
        $posts->insert($data)
            ->save();
    }
}