<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'tanggal'     => '2026-05-25',
                'jam_mulai'   => '09:00:00',
                'jam_selesai' => '10:00:00',
                'kapasitas'   => 1,
                'status'      => 'available'
            ],

            [
                'tanggal'     => '2026-05-25',
                'jam_mulai'   => '10:00:00',
                'jam_selesai' => '11:00:00',
                'kapasitas'   => 1,
                'status'      => 'available'
            ]

        ];

        $this->db->table('schedules')->insertBatch($data);
    }
}