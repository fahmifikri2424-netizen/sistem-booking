# 📘 Sistem Booking

## 📌 Deskripsi
Sistem Booking adalah aplikasi berbasis web menggunakan CodeIgniter 4 yang memiliki fitur login multi-role (admin, staff, user). Setiap role memiliki akses dashboard yang berbeda sesuai dengan hak aksesnya.

---

## ⚙️ Cara Instalasi

1. Clone repository
```bash
git clone https://github.com/fahmifikri2424-netizen/sistem-booking.git
```

2. Masuk ke folder project kemudian jalankan :
php spark serve

3. konfigurasi .env
cp .env.example .env
sesuaikan pada bagian dibawah ini :
database.default.hostname = localhost
database.default.database = db_booking
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

4. Migration & Seeder
buat database : db_booking 
php spark migrate
php spark db:seed UserSeeder

5. Akun Demo 
| Role  | Username | Password |
| ----- | -------- | -------- |
| Admin | yogi     | 123      |
| Staff | ichlas   | 123      |
| User  | rizal    | 123      |

