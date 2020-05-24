<?php

use think\migration\Seeder;

class User extends Seeder
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
                'id'            => 1,
                'user_name'     => 'admin',
                'password'      => md5("admin" . config("salt")),
                "head"          => "/static/admin/images/profile_small.jpg",
                "real_name"     => "admin",
                "last_login_ip" => "127.0.0.1",
                "status"        => "1",
                "role_id"       => "1",
            ],
        ];

        $posts = $this->table('user');
        $posts->insert($data)
            ->save();
    }
}