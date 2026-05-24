<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'id_user'      => 2,
                'spesialisasi' => 'Fade Cut'
            ]

        ];

        $this->db->table('staffs')->insertBatch($data);
    }
}