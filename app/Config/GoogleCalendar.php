<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi Google Calendar Integration.
 *
 * Cara penggunaan:
 *   1. Letakkan file JSON Service Account di: app/ThirdParty/google/service-account.json
 *   2. Pastikan kalender sudah di-share ke email service account (dengan permission "Make changes to events")
 *   3. Isi GOOGLE_CALENDAR_ID di .env dengan email / ID kalender tujuan
 */
class GoogleCalendar extends BaseConfig
{
    /**
     * Path ke file JSON Service Account yang didownload dari Google Cloud Console.
     * Letakkan file JSON tersebut di dalam folder: app/ThirdParty/google/
     */
    public string $credentialsPath = '';

    /**
     * ID Kalender Google yang akan diisi event.
     *
     * Untuk kalender utama (primary), gunakan 'primary'.
     * Untuk kalender lain, salin ID-nya dari Google Calendar Settings.
     * Contoh: 'namaanda@gmail.com' atau 'abc123xyz@group.calendar.google.com'
     *
     * Wajib diisi via .env:
     *   GOOGLE_CALENDAR_ID = rf595584@gmail.com
     */
    public string $calendarId = '';

    /**
     * Email pemilik kalender (akun Google yang memiliki kalender).
     * Diperlukan agar Service Account bisa menulis ke kalender milik user biasa.
     * Isi dengan email Google yang sama dengan calendarId.
     *
     * Wajib diisi via .env:
     *   GOOGLE_CALENDAR_SUBJECT = rf595584@gmail.com
     */
    public string $calendarSubject = '';

    /**
     * Timezone yang digunakan untuk event Google Calendar.
     * Lihat daftar: https://www.php.net/manual/en/timezones.asia.php
     */
    public string $timezone = 'Asia/Jakarta';

    /**
     * Nama aplikasi (ditampilkan di Google API Console logs).
     */
    public string $applicationName = 'Sistem Booking Barber';

    public function __construct()
    {
        parent::__construct();

        // Default path menggunakan APPPATH (harus di constructor, bukan di property)
        $this->credentialsPath = APPPATH . 'ThirdParty/google/service-account.json';

        // Override dari .env menggunakan CI4 env() helper (lebih reliable dari getenv())
        $calendarId = env('GOOGLE_CALENDAR_ID', '');
        if (!empty($calendarId)) {
            $this->calendarId = $calendarId;
        } else {
            // Fallback default (ganti sesuai kalender kamu)
            $this->calendarId = 'rf595584@gmail.com';
        }

        $calendarSubject = env('GOOGLE_CALENDAR_SUBJECT', '');
        if (!empty($calendarSubject)) {
            $this->calendarSubject = $calendarSubject;
        } else {
            // Biasanya sama dengan calendarId
            $this->calendarSubject = $this->calendarId;
        }

        $credentialsPath = env('GOOGLE_CREDENTIALS_PATH', '');
        if (!empty($credentialsPath)) {
            $this->credentialsPath = $credentialsPath;
        }
    }
}
