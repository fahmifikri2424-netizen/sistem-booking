# 📘 Panduan Lengkap Integrasi Midtrans Snap — CodeIgniter 4

**Sistem Booking Barbershop | Mode Sandbox (Development)**

---

## 📑 Daftar Isi

1. [Apa itu Midtrans?](#1-apa-itu-midtrans)
2. [Alur Pembayaran yang Akan Dibangun](#2-alur-pembayaran-yang-akan-dibangun)
3. [Daftar & Aktivasi Akun Midtrans Sandbox](#3-daftar--aktivasi-akun-midtrans-sandbox)
4. [Mendapatkan Server Key & Client Key](#4-mendapatkan-server-key--client-key)
5. [Instalasi Midtrans PHP SDK](#5-instalasi-midtrans-php-sdk)
6. [Konfigurasi .env Proyek](#6-konfigurasi-env-proyek)
7. [Setup Webhook URL (Notifikasi Pembayaran)](#7-setup-webhook-url-notifikasi-pembayaran)
8. [Testing Pembayaran Sandbox](#8-testing-pembayaran-sandbox)
9. [Troubleshooting Umum](#9-troubleshooting-umum)
10. [FAQ](#10-faq)

---

## 1. Apa itu Midtrans?

**Midtrans** adalah payment gateway Indonesia yang digunakan untuk menerima pembayaran online. Midtrans mendukung berbagai metode pembayaran:

| Kategori | Metode |
|---|---|
| Kartu Kredit/Debit | Visa, Mastercard, JCB |
| Transfer Bank | BCA, BNI, BRI, Mandiri, Permata |
| E-Wallet | GoPay, ShopeePay, OVO, Dana |
| QRIS | Semua e-wallet via QR |
| Gerai | Alfamart, Indomaret |

**Midtrans Snap** adalah produk yang menyediakan popup pembayaran siap pakai (tidak perlu bangun UI dari nol).

> **Mode yang digunakan: Sandbox (Gratis)**
> Mode Sandbox = simulasi pembayaran tanpa uang nyata. Cocok untuk development & testing.

---

## 2. Alur Pembayaran yang Akan Dibangun

```
Customer klik "Bayar"
        │
        ▼
[Backend PHP] Buat transaksi → kirim ke Midtrans API
        │
        ▼
Midtrans kembalikan snap_token
        │
        ▼
[Frontend JS] window.snap.pay(snap_token) → muncul popup Midtrans
        │
        ▼
Customer pilih metode & bayar (mode sandbox)
        │
        ▼
Midtrans kirim webhook POST → /api/payment/callback
        │
        ▼
[Backend PHP] Verifikasi signature → update status booking & payment
        │
        ▼
Sistem kirim email konfirmasi ke customer
```

---

## 3. Daftar & Aktivasi Akun Midtrans Sandbox

### Langkah 3.1 — Buka Halaman Registrasi

Buka browser dan pergi ke:
```
https://dashboard.sandbox.midtrans.com
```

### Langkah 3.2 — Isi Form Pendaftaran

Klik tombol **"Sign Up"** dan isi:

| Field | Isi dengan |
|---|---|
| **Full Name** | Nama lengkap Anda |
| **Business/Company Name** | Nama usaha (bisa nama proyek) |
| **Email** | Email aktif yang bisa dicek |
| **Password** | Minimal 8 karakter |
| **Phone Number** | Nomor HP aktif |

Klik **"Create Account"**.

### Langkah 3.3 — Verifikasi Email

1. Buka inbox email yang Anda daftarkan
2. Cari email dari **Midtrans** dengan subject `Verify your Midtrans account`
3. Klik tombol **"Verify Email"** di dalam email tersebut
4. Anda akan diarahkan kembali ke dashboard Midtrans

### Langkah 3.4 — Login ke Dashboard

Setelah verifikasi, login di:
```
https://dashboard.sandbox.midtrans.com
```

---

## 4. Mendapatkan Server Key & Client Key

### Langkah 4.1 — Buka Halaman Access Keys

Setelah login, di sidebar kiri klik:
```
⚙️ Settings  →  Access Keys
```

Atau langsung ke URL:
```
https://dashboard.sandbox.midtrans.com/settings/config_info
```

### Langkah 4.2 — Salin Key Anda

Anda akan melihat dua key:

```
┌─────────────────────────────────────────────────────────┐
│  SANDBOX ENVIRONMENT                                    │
│                                                         │
│  Merchant ID  :  G123456789                             │
│                                                         │
│  Client Key   :  SB-Mid-client-xxxxxxxxxxxxxxxxxxxx     │
│                                    [📋 Copy]            │
│                                                         │
│  Server Key   :  SB-Mid-server-xxxxxxxxxxxxxxxxxxxx     │
│                                    [📋 Copy]            │
└─────────────────────────────────────────────────────────┘
```

Klik ikon **Copy** untuk masing-masing key dan simpan di tempat aman.

> ⚠️ **Server Key = RAHASIA!**
> Jangan pernah taruh Server Key di JavaScript, Git publik, atau share ke orang lain.
> Server Key hanya boleh ada di sisi backend (file `.env` yang masuk `.gitignore`).

> ✅ **Client Key = Boleh publik**
> Client Key aman digunakan di frontend/JavaScript karena hanya dipakai untuk inisiasi Snap popup.

---

## 5. Instalasi Midtrans PHP SDK

Midtrans menyediakan library PHP resmi. Install via Composer:

### Langkah 5.1 — Buka Terminal

Buka terminal/command prompt, arahkan ke folder proyek:
```bash
cd c:\sistem-booking
```

### Langkah 5.2 — Install via Composer

```bash
composer require midtrans/midtrans-php
```

Tunggu hingga proses selesai. Anda akan melihat output seperti:
```
./composer.json has been updated
Running composer update midtrans/midtrans-php
  - Installing midtrans/midtrans-php (v2.x.x)
```

### Langkah 5.3 — Verifikasi Instalasi

Cek apakah library berhasil terinstall:
```bash
composer show midtrans/midtrans-php
```

Output yang diharapkan:
```
name     : midtrans/midtrans-php
descrip. : Official Midtrans PHP Library
keywords : midtrans, payment-gateway
...
```

---

## 6. Konfigurasi .env Proyek

Buka file `c:\sistem-booking\.env` dan tambahkan bagian berikut:

```env
#--------------------------------------------------------------------
# MIDTRANS PAYMENT GATEWAY
#--------------------------------------------------------------------

# Server Key (RAHASIA - jangan expose ke public)
MIDTRANS_SERVER_KEY = SB-Mid-server-xxxxxxxxxxxxxxxxxxxx

# Client Key (aman untuk frontend)
MIDTRANS_CLIENT_KEY = SB-Mid-client-xxxxxxxxxxxxxxxxxxxx

# Mode: false = Sandbox (testing), true = Production (live)
MIDTRANS_IS_PRODUCTION = false

#--------------------------------------------------------------------
# EMAIL (SMTP) - untuk kirim konfirmasi pembayaran
#--------------------------------------------------------------------

# Gunakan Gmail dengan App Password, atau Mailtrap untuk testing
MAIL_SMTP_HOST     = smtp.gmail.com
MAIL_SMTP_USER     = email-anda@gmail.com
MAIL_SMTP_PASS     = xxxx-xxxx-xxxx-xxxx
MAIL_SMTP_PORT     = 587
MAIL_FROM_EMAIL    = email-anda@gmail.com
MAIL_FROM_NAME     = Sistem Booking Barbershop
```

### 📧 Cara Mendapatkan Gmail App Password

Diperlukan karena Gmail tidak izinkan login langsung dari aplikasi.

1. Buka [myaccount.google.com](https://myaccount.google.com)
2. Klik **"Keamanan"** di sidebar kiri
3. Scroll ke bagian **"Cara Anda masuk ke Google"**
4. Aktifkan **"Verifikasi 2 Langkah"** (jika belum aktif)
5. Kembali ke Keamanan → cari **"Sandi Aplikasi"** (App Passwords)
6. Di dropdown:
   - Pilih app: **"Mail"**
   - Pilih perangkat: **"Windows Computer"**
7. Klik **"Buat"** / **"Generate"**
8. Salin 16 karakter password yang muncul (contoh: `abcd efgh ijkl mnop`)
9. Hapus spasi → masukkan ke `.env` sebagai: `MAIL_SMTP_PASS = abcdefghijklmnop`

### 📧 Alternatif: Mailtrap (Lebih Mudah untuk Development)

[Mailtrap.io](https://mailtrap.io) adalah layanan email testing gratis. Email "terkirim" tapi tidak sampai ke inbox nyata — cocok untuk development.

1. Daftar gratis di [mailtrap.io](https://mailtrap.io)
2. Buka **"My Inbox"** → klik nama inbox
3. Di bagian **"SMTP Settings"**, pilih integrasi **"CodeIgniter 4"**
4. Salin konfigurasi yang diberikan ke `.env`

---

## 7. Setup Webhook URL (Notifikasi Pembayaran)

Webhook diperlukan agar Midtrans bisa memberi tahu server Anda ketika pembayaran berhasil/gagal.

### Opsi A — Testing di Localhost dengan ngrok

**Apa itu ngrok?** Tool yang membuat URL publik sementara untuk server lokal Anda.

#### Install ngrok

1. Buka [ngrok.com/download](https://ngrok.com/download)
2. Download versi **Windows (64-bit)**
3. Extract file `.zip` → letakkan `ngrok.exe` di folder yang mudah diakses

#### Daftar Akun ngrok (Gratis)

1. Buka [dashboard.ngrok.com/signup](https://dashboard.ngrok.com/signup)
2. Daftar dengan email atau Google account
3. Setelah login, pergi ke menu **"Your Authtoken"**
4. Salin authtoken dan jalankan di terminal:

```bash
ngrok config add-authtoken YOUR_AUTH_TOKEN_DI_SINI
```

#### Jalankan Server + ngrok

**Terminal 1** — jalankan CodeIgniter:
```bash
cd c:\sistem-booking
php spark serve --port=8080
```

**Terminal 2** — jalankan ngrok:
```bash
ngrok http 8080
```

Output ngrok:
```
Session Status    online
Account           nama-anda@email.com
Forwarding        https://abcd-1234-efgh.ngrok-free.app -> http://localhost:8080
```

Salin URL `https://abcd-1234-efgh.ngrok-free.app`.

#### Daftarkan Webhook URL ke Midtrans

1. Login ke [dashboard.sandbox.midtrans.com](https://dashboard.sandbox.midtrans.com)
2. Klik **Settings → Configuration**
3. Isi field berikut:

| Field | Nilai |
|---|---|
| **Payment Notification URL** | `https://xxxx.ngrok-free.app/api/payment/callback` |
| **Finish Redirect URL** | `https://xxxx.ngrok-free.app/user/riwayat` |
| **Unfinish Redirect URL** | `https://xxxx.ngrok-free.app/user/riwayat` |
| **Error Redirect URL** | `https://xxxx.ngrok-free.app/user/riwayat` |

4. Klik **"Save"**

> ⚠️ **URL ngrok gratis berubah setiap restart.**
> Setiap kali Anda restart ngrok, update juga URL di Midtrans dashboard.

### Opsi B — Server Hosting / VPS

Jika sudah deploy ke server, langsung isi:
```
https://domain-anda.com/api/payment/callback
```

---

## 8. Testing Pembayaran Sandbox

Setelah integrasi selesai, gunakan data test berikut:

### 💳 Kartu Kredit/Debit Test

| Skenario | Nomor Kartu | CVV | Expired |
|---|---|---|---|
| ✅ **Sukses** | `4811 1111 1111 1114` | `123` | `01/39` |
| ❌ **Ditolak (Insufficient Fund)** | `4911 1111 1111 1113` | `123` | `01/39` |
| ⏳ **Challenge / 3DS** | `4411 1111 1111 1118` | `123` | `01/39` |
| ✅ **Sukses (Mastercard)** | `5211 1111 1111 1117` | `123` | `01/39` |

**OTP/3DS:** gunakan kode `112233`

### 📱 E-Wallet Test (GoPay/ShopeePay)

1. Di popup Snap, pilih **GoPay** atau **ShopeePay**
2. Akan muncul QR Code
3. Buka [simulator.sandbox.midtrans.com](https://simulator.sandbox.midtrans.com)
4. Pilih **GoPay Simulator** → masukkan nomor HP → klik **"Pay"**

### 🏦 Bank Transfer / Virtual Account Test

1. Di popup Snap, pilih bank (BCA / BNI / BRI / Mandiri)
2. Catat nomor Virtual Account yang diberikan
3. Buka [simulator.sandbox.midtrans.com](https://simulator.sandbox.midtrans.com)
4. Pilih bank → masukkan nomor VA → klik **"Bayar"**

### 🏪 Gerai (Alfamart / Indomaret)

1. Pilih Alfamart atau Indomaret di popup Snap
2. Catat kode pembayaran
3. Buka simulator → pilih gerai → masukkan kode → konfirmasi

---

## 9. Troubleshooting Umum

### ❌ Error: `401 Unauthorized`

**Penyebab:** Server Key salah atau tidak terbaca dari `.env`.

**Solusi:**
```bash
# Clear cache CodeIgniter
php spark cache:clear

# Pastikan .env dibaca
php spark env
```
Periksa: tidak ada spasi berlebih di sekitar `=` pada `.env`.

---

### ❌ Error: `Could not resolve host: api.sandbox.midtrans.com`

**Penyebab:** Tidak ada koneksi internet atau firewall memblokir.

**Solusi:**
```bash
# Test koneksi
ping api.sandbox.midtrans.com

# Atau via curl
curl -u YOUR_SERVER_KEY: https://api.sandbox.midtrans.com/v2/charge
```
Nonaktifkan VPN sementara jika sedang aktif.

---

### ❌ Webhook tidak diterima (status tidak update)

**Penyebab:** URL callback tidak bisa diakses dari Midtrans.

**Solusi:**
1. Pastikan ngrok masih berjalan
2. Update URL di Midtrans dashboard dengan URL ngrok terbaru
3. Test webhook manual: **Settings → Configuration → Payment Notification** → klik **"Test Notification"**
4. Cek log CodeIgniter: `writable/logs/`

---

### ❌ Popup Snap tidak muncul

**Penyebab:** Snap.js tidak ter-load atau Client Key salah.

**Solusi:**
1. Buka DevTools browser (F12) → tab **Console**
2. Cari error JavaScript merah
3. Pastikan tidak ada typo di Client Key
4. Pastikan script Snap.js sudah ada di HTML:

```html
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-xxxxxx"></script>
```

---

### ❌ Error: `Class "Midtrans\Snap" not found`

**Penyebab:** Library Midtrans belum terinstall atau autoload belum di-refresh.

**Solusi:**
```bash
composer require midtrans/midtrans-php
composer dump-autoload
```

---

### ❌ Error CSRF saat callback webhook

**Penyebab:** CodeIgniter memblokir request POST tanpa CSRF token.

**Solusi:** Tambahkan route callback ke exception CSRF di `app/Config/Filters.php`:
```php
public array $globals = [
    'before' => [
        'csrf' => ['except' => ['api/payment/callback']],
    ],
];
```

---

## 10. FAQ

**Q: Apakah Sandbox gratis selamanya?**
A: Ya! Mode Sandbox Midtrans sepenuhnya gratis tanpa batas waktu.

---

**Q: Apakah bisa langsung ke Production tanpa Sandbox?**
A: Tidak direkomendasikan. Selalu test di Sandbox terlebih dahulu. Untuk Production, perlu verifikasi bisnis di Midtrans.

---

**Q: Berapa lama proses verifikasi ke Production?**
A: Biasanya 1-3 hari kerja setelah semua dokumen bisnis dilengkapi.

---

**Q: Apakah Midtrans bisa untuk proyek mahasiswa/tugas?**
A: Ya, Mode Sandbox bebas digunakan untuk keperluan akademik/belajar.

---

**Q: Apakah perlu domain berbayar untuk Sandbox?**
A: Tidak. Untuk Sandbox, cukup gunakan localhost + ngrok (gratis).

---

**Q: Ngrok URL selalu berubah, ada solusi permanen?**
A: Upgrade ngrok ke plan berbayar (fixed domain), atau deploy ke hosting gratis seperti **Railway** / **Render**, atau gunakan **Cloudflare Tunnel** (gratis & permanen).

---

**Q: Apa bedanya `snap_token` dan `transaction_id`?**
A: `snap_token` dipakai untuk membuka popup Midtrans di frontend (berlaku 24 jam). `transaction_id` adalah ID unik dari Midtrans setelah pembayaran selesai, dipakai untuk rekonsiliasi.

---

**Q: Apakah customer bisa memilih metode pembayaran?**
A: Ya! Dengan Midtrans Snap, customer bebas memilih semua metode yang tersedia dalam satu popup.

---

## 📌 Checklist Final Sebelum Implementasi

- [ ] ✅ Akun Midtrans Sandbox sudah dibuat & email terverifikasi
- [ ] ✅ Client Key sudah disalin dari dashboard
- [ ] ✅ Server Key sudah disalin dari dashboard
- [ ] ✅ Kedua key sudah dimasukkan ke file `.env`
- [ ] ✅ Midtrans PHP SDK sudah diinstall (`composer require midtrans/midtrans-php`)
- [ ] ✅ ngrok sudah dijalankan (untuk testing localhost)
- [ ] ✅ Webhook URL sudah didaftarkan di Midtrans dashboard
- [ ] ✅ SMTP / email sudah dikonfigurasi di `.env`

---

*Setelah semua checklist selesai, beritahu AI assistant Anda dan implementasi kode akan langsung dieksekusi!* 🚀
