<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'username'   => 'yoga',
                'nama'       => 'Yoga Admin',
                'email'      => 'admin@gmail.com',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'telepon'    => '081111111111',
                'role'       => 'admin',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'username'   => 'ichlas',
                'nama'       => 'Ichlas Barber',
                'email'      => 'staff@gmail.com',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'telepon'    => '082222222222',
                'role'       => 'staff',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'username'   => 'rizal',
                'nama'       => 'Rizal Customer',
                'email'      => 'customer@gmail.com',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'telepon'    => '083333333333',
                'role'       => 'customer',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]

        ];

        $this->db->table('users')->insertBatch($data);
    }
}