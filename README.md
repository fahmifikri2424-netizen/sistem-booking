# 📘 Sistem Booking

## 📌 Deskripsi
Sistem Booking adalah aplikasi berbasis web menggunakan CodeIgniter 4 yang memiliki fitur login multi-role (admin, staff, user). Setiap role memiliki akses dashboard yang berbeda sesuai dengan hak aksesnya.

---

## ⚙️ Cara Instalasi

1. Clone repository
```bash
git clone https://github.com/fahmifikri2424-netizen/sistem-booking.git
```
2. install composer : composer install

3. Masuk ke folder project kemudian jalankan :
php spark serve

4. konfigurasi .env
cp .env.example .env
sesuaikan pada bagian dibawah ini :
database.default.hostname = localhost
database.default.database = db_booking
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

5. Migration & Seeder
buat database : db_booking 
php spark migrate
php spark db:seed UserSeeder

6. Akun Demo 
role = admin, username = yogi , password = 123 , 
role = staff, username = ichlas , password = 123 ,
role = user, username = rizal  , password = 123
