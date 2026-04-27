<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'yogi',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role' => 'admin'
            ],
            [
                'username' => 'user',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role' => 'user'
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
