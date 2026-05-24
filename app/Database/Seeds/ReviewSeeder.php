<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'id_booking' => 1,
                'rating'     => 5,
                'komentar'   => 'Pelayanan mantap dan barber ramah'
            ]

        ];

        $this->db->table('reviews')->insertBatch($data);
    }
}