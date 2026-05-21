# StudentFolio — Dokumentasi Kode dan Pengembangan

Versi lengkap dokumentasi kode untuk proyek StudentFolio. Dokumen ini menjelaskan arsitektur, struktur folder, dependensi, pengaturan lingkungan pengembangan, alur kerja kunci, dan panduan kontribusi.

## Ringkasan Proyek
StudentFolio adalah aplikasi berbasis PHP + MySQL yang berfungsi sebagai platform katalog karya siswa dengan fitur upload bukti, manajemen kategori, dan otentikasi user/admin.

## Struktur Proyek
- `index.php` — Halaman beranda / landing page.
- `login.php`, `register.php`, `logout.php` — Otentikasi dasar.
- `admin/` — Panel admin (dashboard, kelola_kategori.php, kelola_user.php).
- `user/` — Area user (dashboard, tambah_karya.php, edit_karya.php, hapus_karya.php, view_bukti.php).
- `assets/` — CSS, JS, gambar statis.
- `config/koneksi.php` — Konfigurasi koneksi database.
- `includes/` — Header, navbar, footer, utilities (`functions.php`).
- `uploads/` — Folder penyimpanan file yang di-upload (`profil/`, `bukti_karya/`).

## Teknologi & Dependensi
- PHP 7.4+ (direkomendasikan PHP 8)
- MySQL / MariaDB
- XAMPP / LAMP (untuk lingkungan lokal)
- Browser modern (Chrome, Firefox)

## Pengaturan Lingkungan Pengembangan (Local)
1. Pastikan XAMPP atau stack serupa terpasang.
2. Copy folder proyek ke direktori web server (mis. `C:\xampp\htdocs\studentfolio`).
3. Buat database MySQL mis. `studentfolio`.
4. Edit `config/koneksi.php` dengan kredensial database Anda.

Contoh `config/koneksi.php` minimal:

```php
<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'studentfolio_db';
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Gagal koneksi DB: ' . mysqli_connect_error());
}
?>
```

## Skema Database (Contoh)
Contoh berikut memberi tabel dasar yang sesuai dengan fitur yang terlihat di kode.

```sql
CREATE DATABASE studentfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studentfolio_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') DEFAULT 'user',
  profil VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT DEFAULT NULL
);

CREATE TABLE karya (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  kategori_id INT DEFAULT NULL,
  judul VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  file_bukti VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
);
```

Tambahkan akun admin awal (ubah password sesuai kebutuhan):

```sql
INSERT INTO users (nama, email, password, role) VALUES
('Admin', 'admin@example.com', MD5('password123'), 'admin');
```

Catatan: Gantilah hashing `MD5` dengan `password_hash()` dalam implementasi baru. Jika kode saat ini memakai MD5, pertimbangkan migrasi.

## Alur Kode Utama dan Komponen
- Otentikasi: `login.php` dan `register.php` menggunakan `config/koneksi.php` dan `functions.php` untuk validasi. Pastikan `session_start()` dipanggil di header yang sesuai.
- Middleware sederhana: Gunakan pengecekan session untuk membatasi akses ke `admin/` dan `user/`.
- Upload file: File diunggah ke `uploads/bukti_karya/` atau `uploads/profil/`. Pastikan direktori writable (`chmod 775`/di Windows proper permissions).
- CRUD Karya: `user/tambah_karya.php`, `user/edit_karya.php`, `user/hapus_karya.php` melakukan operasi INSERT/UPDATE/DELETE pada tabel `karya`.
- Manajemen Kategori/User: `admin/kelola_kategori.php`, `admin/kelola_user.php` menyediakan UI untuk manajemen.

## Keamanan dan Hardening
- Gunakan `prepared statements` (mysqli_stmt atau PDO) untuk mencegah SQL injection.
- Sanitasi input (trim, strip_tags, htmlspecialchars) sebelum menampilkan.
- Simpan password menggunakan `password_hash()` dan verifikasi dengan `password_verify()`.
- Validasi tipe & ukuran file sebelum menyimpan upload; batasi ekstensi (jpg, png, pdf, dll).
- Jangan letakkan `uploads/` pada path yang dapat mengeksekusi skrip PHP.

## Gaya Kode & Konvensi
- Struktur MVC sederhana tidak wajib, tetapi pisahkan logika (PHP) dari tampilan (HTML/CSS) bila memungkinkan.
- Gunakan fungsi di `includes/functions.php` untuk menghindari duplikasi.

## Debugging & Logging
- Aktifkan `display_errors` hanya pada development.
- Gunakan logging (file) untuk mencatat kesalahan kritikal.

## Cara Berkontribusi
1. Fork repo.
2. Buat branch fitur dari `main` (mis. `feature/upload-validation`).
3. Buat commit kecil dan deskriptif.
4. Ajukan Pull Request dengan deskripsi perubahan dan testing yang dilakukan.

## Checklist Sebelum Deploy
- Periksa input validation.
- Hapus kredensial dev dan gunakan variabel lingkungan atau file konfigurasi terpisah.
- Pastikan folder `uploads/` aman.

## Kontak dan Referensi
Jika Anda butuh bantuan lebih lanjut, tambahkan issue di GitHub repository Anda atau hubungi pembuat proyek.
