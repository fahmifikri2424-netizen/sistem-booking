<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookingColumns extends Migration
{
    public function up()
    {
        // Tambah kolom baru yang belum ada di tabel bookings
        $this->forge->addColumn('bookings', [
            'kode_booking' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'id_service',
            ],
            'tanggal_booking' => [
                'type'  => 'DATE',
                'null'  => true,
                'after' => 'kode_booking',
            ],
            'status_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['belum_bayar', 'sudah_bayar'],
                'default'    => 'belum_bayar',
                'after'      => 'status',
            ],
        ]);

        // Rename kolom 'status' menjadi 'status_booking' agar konsisten
        $this->forge->modifyColumn('bookings', [
            'status' => [
                'name'       => 'status_booking',
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dikonfirmasi', 'selesai', 'batal'],
                'default'    => 'pending',
            ],
        ]);
    }

    public function down()
    {
        // Kembalikan nama kolom 'status_booking' ke 'status'
        $this->forge->modifyColumn('bookings', [
            'status_booking' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dikonfirmasi', 'selesai', 'batal'],
                'default'    => 'pending',
            ],
        ]);

        $this->forge->dropColumn('bookings', ['kode_booking', 'tanggal_booking', 'status_pembayaran']);
    }
}
