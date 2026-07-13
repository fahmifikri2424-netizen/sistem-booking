<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\GoogleCalendar;

class TestGcal extends BaseCommand
{
    protected $group       = 'Test';
    protected $name        = 'test:gcal';
    protected $description = 'Test Google Calendar integration';

    public function run(array $params)
    {
        try {
            $gcal = new GoogleCalendar();
            $eventId = $gcal->createBookingEvent([
                'kode_booking'   => 'TEST-001',
                'nama_pelanggan' => 'Test Customer',
                'nama_staff'     => 'Test Staff',
                'nama_service'   => 'Test Service',
                'tanggal'        => date('Y-m-d', strtotime('+1 day')),
                'jam_mulai'      => '10:00:00',
                'jam_selesai'    => '11:00:00',
                'catatan'        => 'Test Note',
            ]);
            CLI::write('Event ID: ' . $eventId);
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
        }
    }
}
