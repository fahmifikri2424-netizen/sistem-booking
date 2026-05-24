<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'id_booking'    => 1,
                'metode'        => 'qris',
                'jumlah'        => 30000,
                'status'        => 'sukses',
                'snap_token'    => 'TOKEN123',
                'transaction_id'=> 'TRX123456',
                'payment_time'  => date('Y-m-d H:i:s'),
            ]

        ];

        $this->db->table('payments')->insertBatch($data);
    }
}