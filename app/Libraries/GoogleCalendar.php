<?php

namespace App\Libraries;

use Config\GoogleCalendar as GoogleCalendarConfig;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

/**
 * Google Calendar Library
 *
 * Library untuk mengintegrasikan Google Calendar API ke dalam
 * Sistem Booking Barber Shop berbasis CodeIgniter 4.
 *
 * Cara penggunaan:
 *   $calendar = new \App\Libraries\GoogleCalendar();
 *   $eventId  = $calendar->createBookingEvent($bookingData);
 *   $calendar->deleteEvent($eventId);
 */
class GoogleCalendar
{
    protected Client $client;
    protected Calendar $service;
    protected GoogleCalendarConfig $config;

    public function __construct()
    {
        $this->config = new GoogleCalendarConfig();
        $this->initClient();
    }

    /**
     * Inisialisasi Google API Client menggunakan Service Account.
     */
    private function initClient(): void
    {
        if (!file_exists($this->config->credentialsPath)) {
            throw new \RuntimeException(
                'File credentials Google tidak ditemukan di: ' . $this->config->credentialsPath .
                '. Silakan download file JSON Service Account dari Google Cloud Console.'
            );
        }

        $this->client = new Client();
        $this->client->setApplicationName($this->config->applicationName);
        $this->client->setAuthConfig($this->config->credentialsPath);
        $this->client->addScope(Calendar::CALENDAR);

        $this->service = new Calendar($this->client);
    }

    /**
     * Membuat event di Google Calendar berdasarkan data booking.
     *
     * @param array $bookingData Data booking yang berisi informasi pelanggan, layanan, dll.
     *              Contoh struktur:
     *              [
     *                  'kode_booking'   => 'BOOK-ABC123',
     *                  'nama_pelanggan' => 'John Doe',
     *                  'nama_staff'     => 'Ali Barber',
     *                  'nama_service'   => 'Haircut Premium',
     *                  'tanggal'        => '2025-08-01',
     *                  'jam_mulai'      => '10:00:00',
     *                  'jam_selesai'    => '11:00:00',
     *                  'catatan'        => 'Tolong potong tipis',
     *              ]
     * @return string|null ID event Google Calendar yang berhasil dibuat, atau null jika gagal.
     */
    public function createBookingEvent(array $bookingData): ?string
    {
        try {
            $tanggal    = $bookingData['tanggal'];
            $jamMulai   = $bookingData['jam_mulai'];
            $jamSelesai = $bookingData['jam_selesai'];

            // Format waktu sesuai standar Google Calendar (RFC3339)
            $startDateTime = $tanggal . 'T' . $jamMulai;
            $endDateTime   = $tanggal . 'T' . $jamSelesai;

            // Deskripsi event yang informatif
            $description = implode("\n", [
                '🪒 DETAIL BOOKING',
                '─────────────────────────',
                '📋 Kode Booking : ' . ($bookingData['kode_booking'] ?? '-'),
                '👤 Pelanggan    : ' . ($bookingData['nama_pelanggan'] ?? '-'),
                '✂️ Barber        : ' . ($bookingData['nama_staff'] ?? '-'),
                '💈 Layanan      : ' . ($bookingData['nama_service'] ?? '-'),
                '─────────────────────────',
                '📝 Catatan: ' . ($bookingData['catatan'] ?? 'Tidak ada catatan'),
            ]);

            $event = new Event([
                'summary'     => '✂️ Booking: ' . ($bookingData['nama_pelanggan'] ?? 'Pelanggan') . ' - ' . ($bookingData['nama_service'] ?? 'Layanan'),
                'description' => $description,
                'start'       => new EventDateTime([
                    'dateTime' => $startDateTime,
                    'timeZone' => $this->config->timezone,
                ]),
                'end' => new EventDateTime([
                    'dateTime' => $endDateTime,
                    'timeZone' => $this->config->timezone,
                ]),
                'colorId' => '2', // Warna hijau — menandai booking terkonfirmasi
                'reminders' => [
                    'useDefault' => false,
                    'overrides'  => [
                        ['method' => 'popup', 'minutes' => 30],
                        ['method' => 'popup', 'minutes' => 10],
                    ],
                ],
            ]);

            $createdEvent = $this->service->events->insert(
                $this->config->calendarId,
                $event
            );

            log_message('info', '[GoogleCalendar] Event berhasil dibuat: ' . $createdEvent->getId() . ' untuk booking ' . ($bookingData['kode_booking'] ?? 'N/A'));

            return $createdEvent->getId();

        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal membuat event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menghapus event dari Google Calendar berdasarkan ID event.
     *
     * @param string $eventId ID event yang disimpan dari saat event dibuat.
     * @return bool True jika berhasil dihapus, false jika gagal.
     */
    public function deleteEvent(string $eventId): bool
    {
        try {
            $this->service->events->delete($this->config->calendarId, $eventId);

            log_message('info', '[GoogleCalendar] Event berhasil dihapus: ' . $eventId);
            return true;

        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal menghapus event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil detail event dari Google Calendar berdasarkan ID event.
     *
     * @param string $eventId ID event Google Calendar.
     * @return Event|null Object event, atau null jika tidak ditemukan.
     */
    public function getEvent(string $eventId): ?Event
    {
        try {
            return $this->service->events->get($this->config->calendarId, $eventId);
        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal mengambil event: ' . $e->getMessage());
            return null;
        }
    }
}
