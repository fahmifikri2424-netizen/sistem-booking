<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;


//Untuk Mengisi Table
//buat seeder = php spark make:seeder UserSeeder
//jalankan = php spark db:seed UserSeeder

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
                'username' => 'ichlas',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role' => 'staff'
            ],
            [
                'username' => 'rizal',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role' => 'pelanggan'
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
