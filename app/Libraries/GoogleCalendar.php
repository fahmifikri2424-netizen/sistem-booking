<?php

namespace App\Libraries;

use Config\GoogleCalendar as GoogleCalendarConfig;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventReminders;

/**
 * Google Calendar Library
 *
 * Library untuk mengintegrasikan Google Calendar API ke dalam
 * Sistem Booking Barber Shop berbasis CodeIgniter 4.
 *
 * Prasyarat:
 *   1. File service-account.json sudah ada di app/ThirdParty/google/
 *   2. Kalender sudah di-share ke email service account dengan izin "Make changes to events"
 *   3. Domain-wide delegation TIDAK diperlukan selama kalender di-share secara manual
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

    // -------------------------------------------------------------------------
    // PRIVATE: Inisialisasi Google API Client
    // -------------------------------------------------------------------------

    /**
     * Inisialisasi Google API Client menggunakan Service Account credentials.
     *
     * @throws \RuntimeException Jika file credentials tidak ditemukan.
     */
    private function initClient(): void
    {
        // Pastikan file credentials ada
        if (!file_exists($this->config->credentialsPath)) {
            throw new \RuntimeException(
                '[GoogleCalendar] File credentials tidak ditemukan di: ' . $this->config->credentialsPath .
                ' — Silakan download file JSON Service Account dari Google Cloud Console.'
            );
        }

        $this->client = new Client();
        $this->client->setApplicationName($this->config->applicationName);

        // Load Service Account credentials
        $this->client->setAuthConfig($this->config->credentialsPath);

        // Scope yang dibutuhkan: hanya events (lebih aman dari CALENDAR penuh)
        $this->client->setScopes([Calendar::CALENDAR_EVENTS]);

        $this->service = new Calendar($this->client);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Create Event
    // -------------------------------------------------------------------------

    /**
     * Membuat event di Google Calendar berdasarkan data booking.
     *
     * @param  array       $bookingData  Data booking dari database
     *                                   Keys: kode_booking, nama_pelanggan, nama_staff,
     *                                         nama_service, tanggal, jam_mulai, jam_selesai, catatan
     * @return string|null               ID event yang berhasil dibuat, atau null jika gagal.
     */
    public function createBookingEvent(array $bookingData): ?string
    {
        try {
            $tanggal    = $bookingData['tanggal']    ?? '';
            $jamMulai   = $bookingData['jam_mulai']  ?? '00:00:00';
            $jamSelesai = $bookingData['jam_selesai'] ?? '01:00:00';

            if (empty($tanggal)) {
                log_message('error', '[GoogleCalendar] Data tanggal kosong, tidak bisa membuat event.');
                return null;
            }

            // Format RFC3339 yang benar untuk Google Calendar API
            // Pastikan jam dalam format H:i:s agar DateTime bisa parse dengan benar
            $startDt = new \DateTime($tanggal . ' ' . $jamMulai, new \DateTimeZone($this->config->timezone));
            $endDt   = new \DateTime($tanggal . ' ' . $jamSelesai, new \DateTimeZone($this->config->timezone));

            $startDateTime = $startDt->format(\DateTime::RFC3339); // e.g. 2025-08-01T10:00:00+07:00
            $endDateTime   = $endDt->format(\DateTime::RFC3339);   // e.g. 2025-08-01T11:00:00+07:00

            // Deskripsi event yang informatif
            $description = implode("\n", [
                '🪒 DETAIL BOOKING',
                '─────────────────────────',
                '📋 Kode    : ' . ($bookingData['kode_booking']   ?? '-'),
                '👤 Pelanggan: ' . ($bookingData['nama_pelanggan'] ?? '-'),
                '✂️  Barber   : ' . ($bookingData['nama_staff']    ?? '-'),
                '💈 Layanan  : ' . ($bookingData['nama_service']   ?? '-'),
                '─────────────────────────',
                '📝 Catatan  : ' . (trim($bookingData['catatan'] ?? '') ?: 'Tidak ada catatan'),
            ]);

            // Bangun objek Event
            $event = new Event([
                'summary'     => '✂️ ' . ($bookingData['nama_pelanggan'] ?? 'Pelanggan') . ' — ' . ($bookingData['nama_service'] ?? 'Layanan'),
                'description' => $description,
                'start'       => new EventDateTime([
                    'dateTime' => $startDateTime,
                    'timeZone' => $this->config->timezone,
                ]),
                'end' => new EventDateTime([
                    'dateTime' => $endDateTime,
                    'timeZone' => $this->config->timezone,
                ]),
                'colorId'  => '2', // Hijau → booking terkonfirmasi
                'reminders' => new EventReminders([
                    'useDefault' => false,
                    'overrides'  => [
                        ['method' => 'popup', 'minutes' => 30],
                        ['method' => 'popup', 'minutes' => 10],
                    ],
                ]),
            ]);

            // Kirim ke Google Calendar API
            $createdEvent = $this->service->events->insert(
                $this->config->calendarId,
                $event
            );

            $eventId = $createdEvent->getId();

            log_message('info', '[GoogleCalendar] Event berhasil dibuat. ID: ' . $eventId . ' | Booking: ' . ($bookingData['kode_booking'] ?? 'N/A'));

            return $eventId;

        } catch (\Google\Service\Exception $e) {
            // Error spesifik dari Google API (misal: 403 forbidden, 404 calendar not found)
            $errors = $e->getErrors();
            $detail = !empty($errors) ? json_encode($errors) : $e->getMessage();
            log_message('error', '[GoogleCalendar] Google API error saat membuat event: ' . $detail);
            return null;

        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal membuat event: ' . $e->getMessage());
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Delete Event
    // -------------------------------------------------------------------------

    /**
     * Menghapus event dari Google Calendar berdasarkan ID event.
     *
     * @param  string $eventId  ID event yang disimpan saat event dibuat.
     * @return bool             True jika berhasil dihapus, false jika gagal.
     */
    public function deleteEvent(string $eventId): bool
    {
        if (empty($eventId)) {
            log_message('warning', '[GoogleCalendar] deleteEvent dipanggil dengan eventId kosong.');
            return false;
        }

        try {
            $this->service->events->delete($this->config->calendarId, $eventId);

            log_message('info', '[GoogleCalendar] Event berhasil dihapus: ' . $eventId);
            return true;

        } catch (\Google\Service\Exception $e) {
            // Jika event sudah tidak ada di Google (404), anggap sukses
            if ($e->getCode() === 404) {
                log_message('warning', '[GoogleCalendar] Event tidak ditemukan di Google (sudah terhapus?): ' . $eventId);
                return true;
            }

            $errors = $e->getErrors();
            $detail = !empty($errors) ? json_encode($errors) : $e->getMessage();
            log_message('error', '[GoogleCalendar] Google API error saat menghapus event: ' . $detail);
            return false;

        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal menghapus event: ' . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Get Event
    // -------------------------------------------------------------------------

    /**
     * Mengambil detail event dari Google Calendar berdasarkan ID event.
     *
     * @param  string     $eventId  ID event Google Calendar.
     * @return Event|null           Object event, atau null jika tidak ditemukan / error.
     */
    public function getEvent(string $eventId): ?Event
    {
        if (empty($eventId)) {
            return null;
        }

        try {
            return $this->service->events->get($this->config->calendarId, $eventId);

        } catch (\Google\Service\Exception $e) {
            if ($e->getCode() === 404) {
                log_message('warning', '[GoogleCalendar] Event tidak ditemukan: ' . $eventId);
                return null;
            }

            $errors = $e->getErrors();
            $detail = !empty($errors) ? json_encode($errors) : $e->getMessage();
            log_message('error', '[GoogleCalendar] Gagal mengambil event: ' . $detail);
            return null;

        } catch (\Exception $e) {
            log_message('error', '[GoogleCalendar] Gagal mengambil event: ' . $e->getMessage());
            return null;
        }
    }
}
