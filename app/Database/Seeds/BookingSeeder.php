<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'id_user'      => 3,
                'id_staff'     => 1,
                'id_schedule'  => 1,
                'id_service'   => 1,
                'status'       => 'pending',
                'catatan'      => 'Potong tipis samping',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]

        ];

        $this->db->table('bookings')->insertBatch($data);
    }
}