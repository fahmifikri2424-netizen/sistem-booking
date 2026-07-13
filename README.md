# Sistem Booking Barbershop

Sistem Booking Barbershop adalah aplikasi berbasis web yang dibangun menggunakan **CodeIgniter 4**. Aplikasi ini dirancang untuk memudahkan pengelolaan reservasi (booking) pelanggan, manajemen jadwal staff/barber, integrasi pembayaran digital, hingga sinkronisasi jadwal otomatis ke Google Calendar.

---

## 🚀 Fitur Utama

- **Multi-Role System:** Terdapat 3 role utama (Admin, Staff, Customer).
- **Manajemen Booking & Jadwal:** Pelanggan dapat memilih layanan, barber/staff, dan ketersediaan jadwal secara real-time.
- **Integrasi Google Calendar:** Sinkronisasi jadwal otomatis (event creation) ke Google Calendar pusat menggunakan Service Account.
- **Integrasi Pembayaran (Payment Gateway):** Terintegrasi dengan Midtrans untuk memfasilitasi berbagai metode pembayaran otomatis.
- **Manajemen Staff & Layanan:** Admin dapat mengatur data staff, spesialisasi, serta daftar layanan (services) beserta harga dan durasi.
- **Sistem Ulasan (Review):** Pelanggan dapat memberikan rating dan komentar setelah layanan selesai.
- **Dashboard Interaktif:** Menggunakan template *NiceAdmin* yang responsif untuk kemudahan navigasi dan monitoring jadwal harian.

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8+, CodeIgniter 4
- **Frontend:** HTML, CSS, JavaScript (Bootstrap 5 via NiceAdmin template)
- **Database:** MySQL
- **Integrasi Pihak Ketiga:** 
  - Google Calendar API (via Service Account JSON)
  - Midtrans API (Payment Gateway)

---

## 📋 Prasyarat

Pastikan sistem Anda sudah terinstal:
- PHP >= 8.1 dengan ekstensi `intl`, `mbstring`, `curl`, `json`
- Composer
- MySQL Server

---

## ⚙️ Cara Instalasi & Menjalankan Project

1. **Clone atau Ekstrak Repository**
   Pastikan kode sumber berada di direktori server lokal Anda (misal: `c:\sistem-booking` atau di dalam folder `htdocs`/`www`).

2. **Instalasi Dependencies (Opsional jika vendor belum ada)**
   Buka terminal di root direktori project, lalu jalankan:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (.env)**
   Copy file `env` menjadi `.env` dan sesuaikan pengaturan berikut:
   
   ```ini
   # Ubah environment menjadi development
   CI_ENVIRONMENT = development

   # Konfigurasi URL utama
   app.baseURL = 'http://localhost:8080/'

   # Konfigurasi Database
   database.default.hostname = localhost
   database.default.database = nama_database_anda
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi

   # Konfigurasi Google Calendar
   GOOGLE_CALENDAR_ID = "email-kalender-pusat@gmail.com"
   ```

4. **Konfigurasi Google Calendar Service Account**
   - Letakkan file JSON dari Google Service Account Anda di:
     `app/ThirdParty/google/service-account.json`
   - Pastikan Google Calendar Anda sudah di-*share* ke email Service Account dengan izin *"Make changes to events"*.

5. **Migrasi Database**
   Jalankan perintah migrasi untuk membuat tabel-tabel di database secara otomatis:
   ```bash
   php spark migrate
   ```
   *(Catatan: Anda juga dapat menambahkan data awal jika ada file `DatabaseSeeder`)*

6. **Jalankan Aplikasi**
   Mulai local development server menggunakan perintah:
   ```bash
   php spark serve
   ```
   Aplikasi dapat diakses melalui browser pada alamat: `http://localhost:8080`

---

## 🧑‍💻 Hak Akses (Role)

- **Admin**: Memiliki akses penuh ke seluruh manajemen data (User, Staff, Layanan, Booking, Laporan Pembayaran).
- **Staff (Barber)**: Mengelola jadwal harian pribadi, dan memperbarui status pengerjaan pesanan.
- **Customer**: Melakukan pemesanan jadwal, memilih staff, membayar layanan, dan memberikan ulasan.

---

## 📝 Lisensi

Proyek ini dibuat untuk keperluan manajemen barbershop internal. Silakan sesuaikan kebijakan dan lisensi penggunaan sesuai dengan aturan perusahaan atau instansi Anda.
