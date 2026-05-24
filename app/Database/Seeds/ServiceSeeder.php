<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'nama'       => 'Haircut',
                'deskripsi' => 'Potong rambut standar',
                'harga'      => 30000,
                'durasi'     => 30,
                'foto'       => 'haircut.jpg',
                'status'     => 'aktif'
            ],

            [
                'nama'       => 'Hair Coloring',
                'deskripsi' => 'Pewarnaan rambut',
                'harga'      => 120000,
                'durasi'     => 90,
                'foto'       => 'coloring.jpg',
                'status'     => 'aktif'
            ],

            [
                'nama'       => 'Shaving',
                'deskripsi' => 'Cukur jenggot dan kumis',
                'harga'      => 20000,
                'durasi'     => 20,
                'foto'       => 'shaving.jpg',
                'status'     => 'aktif'
            ]

        ];

        $this->db->table('services')->insertBatch($data);
    }
}