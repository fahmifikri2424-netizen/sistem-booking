<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\GoogleCalendar;

/**
 * Command: php spark calendar:test
 *
 * Menguji koneksi dan fungsionalitas Google Calendar API secara langsung dari terminal.
 * Akan membuat event dummy, lalu langsung menghapusnya.
 */
class TestGoogleCalendar extends BaseCommand
{
    protected $group       = 'Booking';
    protected $name        = 'calendar:test';
    protected $description = 'Tes koneksi Google Calendar API — membuat event dummy lalu menghapusnya.';

    public function run(array $params): void
    {
        CLI::write('');
        CLI::write('╔══════════════════════════════════════════╗', 'cyan');
        CLI::write('║     TES GOOGLE CALENDAR INTEGRATION      ║', 'cyan');
        CLI::write('╚══════════════════════════════════════════╝', 'cyan');
        CLI::write('');

        // ----------------------------------------------------------------
        // STEP 1: Cek file credentials
        // ----------------------------------------------------------------
        CLI::write('[1/4] Mengecek file credentials...', 'yellow');

        $credPath = APPPATH . 'ThirdParty/google/service-account.json';

        if (!file_exists($credPath)) {
            CLI::error('  ✗ File tidak ditemukan: ' . $credPath);
            CLI::write('  → Download dari Google Cloud Console dan letakkan di folder tersebut.', 'red');
            return;
        }

        $json = json_decode(file_get_contents($credPath), true);
        if (!$json || ($json['type'] ?? '') !== 'service_account') {
            CLI::error('  ✗ File JSON tidak valid atau bukan Service Account.');
            return;
        }

        CLI::write('  ✓ File credentials ditemukan.', 'green');
        CLI::write('    Project  : ' . ($json['project_id']    ?? '-'));
        CLI::write('    SA Email : ' . ($json['client_email']  ?? '-'));
        CLI::write('');

        // ----------------------------------------------------------------
        // STEP 2: Cek konfigurasi
        // ----------------------------------------------------------------
        CLI::write('[2/4] Mengecek konfigurasi...', 'yellow');

        $config = new \Config\GoogleCalendar();

        CLI::write('  Calendar ID      : ' . $config->calendarId);
        CLI::write('  Calendar Subject : ' . $config->calendarSubject);
        CLI::write('  Timezone         : ' . $config->timezone);
        CLI::write('');

        if (empty($config->calendarId)) {
            CLI::error('  ✗ GOOGLE_CALENDAR_ID belum diset di .env!');
            return;
        }

        // ----------------------------------------------------------------
        // STEP 3: Buat event dummy
        // ----------------------------------------------------------------
        CLI::write('[3/4] Membuat event dummy di Google Calendar...', 'yellow');

        // Gunakan tanggal besok supaya tidak bentrok
        $tomorrow   = date('Y-m-d', strtotime('+1 day'));
        $jamMulai   = '09:00:00';
        $jamSelesai = '09:30:00';

        $dummyData = [
            'kode_booking'   => 'TEST-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'nama_pelanggan' => '[TES SISTEM] Dummy Pelanggan',
            'nama_staff'     => 'Barber Test',
            'nama_service'   => 'Tes Koneksi Calendar',
            'tanggal'        => $tomorrow,
            'jam_mulai'      => $jamMulai,
            'jam_selesai'    => $jamSelesai,
            'catatan'        => 'Event ini dibuat otomatis oleh php spark calendar:test dan akan segera dihapus.',
        ];

        try {
            $calendar = new GoogleCalendar();
            $eventId  = $calendar->createBookingEvent($dummyData);

            if (!$eventId) {
                CLI::error('  ✗ Gagal membuat event. Cek log di writable/logs/ untuk detail.');
                return;
            }

            CLI::write('  ✓ Event berhasil dibuat!', 'green');
            CLI::write('    Event ID  : ' . $eventId);
            CLI::write('    Tanggal   : ' . $tomorrow . ' ' . $jamMulai . ' — ' . $jamSelesai);
            CLI::write('');

            // ----------------------------------------------------------------
            // STEP 4: Hapus event dummy
            // ----------------------------------------------------------------
            CLI::write('[4/4] Menghapus event dummy...', 'yellow');

            $deleted = $calendar->deleteEvent($eventId);

            if ($deleted) {
                CLI::write('  ✓ Event dummy berhasil dihapus.', 'green');
            } else {
                CLI::write('  ⚠ Event dibuat tapi gagal dihapus. Hapus manual di Google Calendar.', 'yellow');
                CLI::write('    Event ID: ' . $eventId, 'yellow');
            }

        } catch (\RuntimeException $e) {
            CLI::error('  ✗ ' . $e->getMessage());
            return;
        } catch (\Exception $e) {
            CLI::error('  ✗ Error tidak terduga: ' . $e->getMessage());
            return;
        }

        CLI::write('');
        CLI::write('══════════════════════════════════════════', 'cyan');
        CLI::write('  ✅  Google Calendar berfungsi dengan baik!', 'green');
        CLI::write('══════════════════════════════════════════', 'cyan');
        CLI::write('');
    }
}
