<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambahkan kolom google_calendar_event_id ke tabel bookings.
 * Kolom ini digunakan untuk menyimpan ID event Google Calendar
 * yang dibuat secara otomatis saat booking dikonfirmasi.
 */
class AddGoogleCalendarEventIdToBookings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'google_calendar_event_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'status_pembayaran',
                'comment'    => 'ID Event Google Calendar yang terhubung dengan booking ini',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'google_calendar_event_id');
    }
}
