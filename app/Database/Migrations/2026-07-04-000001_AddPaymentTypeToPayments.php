<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentTypeToPayments extends Migration
{
    public function up()
    {
        // Tambah kolom payment_type (gopay, bank_transfer, credit_card, dll)
        $this->forge->addColumn('payments', [
            'payment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'snap_token',
            ],
        ]);

        // Ubah ENUM metode agar lebih fleksibel
        // (tidak bisa alter ENUM langsung di semua versi MySQL, gunakan raw query)
        $this->db->query("
            ALTER TABLE `payments`
            MODIFY COLUMN `metode` VARCHAR(50) NULL DEFAULT NULL
        ");
    }

    public function down()
    {
        $this->forge->dropColumn('payments', 'payment_type');

        $this->db->query("
            ALTER TABLE `payments`
            MODIFY COLUMN `metode` ENUM('transfer','qris','gopay') NOT NULL
        ");
    }
}
