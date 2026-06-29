<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleCalendar extends BaseConfig
{
    /**
     * Path ke file JSON Service Account yang didownload dari Google Cloud Console.
     * Letakkan file JSON tersebut di dalam folder: app/ThirdParty/google/
     * Contoh: app/ThirdParty/google/service-account.json
     */
    public string $credentialsPath = APPPATH . 'ThirdParty/google/service-account.json';

    /**
     * ID Kalender Google yang akan diisi event.
     * Untuk kalender utama (primary), gunakan 'primary'.
     * Untuk kalender lain, salin ID-nya dari Google Calendar Settings.
     * Contoh: 'namaanda@gmail.com' atau 'abc123xyz@group.calendar.google.com'
     *
     * Anda bisa override ini dari .env dengan variabel:
     * GOOGLE_CALENDAR_ID = 'your_calendar_id_here'
     */
    public string $calendarId = 'rf595584@gmail.com';

    /**
     * Timezone yang digunakan untuk event Google Calendar.
     */
    public string $timezone = 'Asia/Jakarta';

    /**
     * Nama aplikasi Anda (ditampilkan di Google API Console logs).
     */
    public string $applicationName = 'Sistem Booking Barber';

    public function __construct()
    {
        parent::__construct();

        // Override dari .env jika ada
        if (getenv('GOOGLE_CALENDAR_ID')) {
            $this->calendarId = getenv('GOOGLE_CALENDAR_ID');
        }

        if (getenv('GOOGLE_CREDENTIALS_PATH')) {
            $this->credentialsPath = getenv('GOOGLE_CREDENTIALS_PATH');
        }
    }
}
