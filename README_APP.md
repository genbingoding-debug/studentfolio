# StudentFolio — Panduan Penggunaan & Cara Menjalankan Aplikasi

Dokumentasi ini menjelaskan fungsi utama aplikasi StudentFolio, alur pengguna, dan langkah-langkah praktis untuk menjalankan aplikasi secara lokal (atau di server). Ditujukan untuk pemilik proyek, pengguna akhir, serta reviewer yang ingin menjalankan dan memeriksa aplikasi.

## Ikhtisar Aplikasi
StudentFolio adalah aplikasi katalog karya siswa yang memungkinkan:
- Pendaftaran dan login pengguna.
- Pengunggahan bukti karya oleh pengguna.
- Melihat, mengedit, dan menghapus karya milik sendiri.
- Panel admin untuk mengelola kategori dan pengguna.

## Peran Pengguna
- Admin: Mengelola kategori, melihat dan mengelola user.
- User: Mendaftar, login, unggah karya, dan melihat bukti karya.

## Alur Kerja Utama
1. Pengguna mendaftar lewat `register.php` → data tersimpan di tabel `users`.
2. Pengguna login lewat `login.php` → session diset untuk menjaga otorisasi.
3. Pada `user/dashboard.php`, pengguna bisa menambah karya (`tambah_karya.php`) dengan mengunggah berkas bukti ke `uploads/bukti_karya/`.
4. Admin mengakses `admin/dashboard.php` setelah login sebagai admin, dapat mengelola kategori (`admin/kelola_kategori.php`) dan pengguna (`admin/kelola_user.php`).

## Menjalankan Aplikasi Secara Lokal (XAMPP pada Windows)
1. Pastikan XAMPP terpasang dan modul Apache + MySQL aktif.
2. Copy folder proyek ke `C:\xampp\htdocs\studentfolio`.
3. Buat database dan tabel (gunakan skema di `README_CODE.md` atau import file SQL bila tersedia).
4. Edit `config/koneksi.php` untuk menyesuaikan `host`, `user`, `pass`, dan `db`.
5. Pastikan folder `uploads/` dan subfoldernya memiliki izin tulis.
6. Buka browser dan akses:

```
http://localhost/studentfolio/
```

Jika Anda meletakkan proyek dalam subfolder lain, sesuaikan URL.

## Contoh Alur Menyiapkan Database Cepat (PHPMyAdmin)
1. Buka `http://localhost/phpmyadmin`.
2. Klik `New` → masukkan nama database `studentfolio` → Create.
3. Gunakan tab SQL untuk menempelkan dan menjalankan skrip yang ada di `README_CODE.md`.

## Konfigurasi Penting
- `config/koneksi.php` — pastikan kredensial database benar.
- `includes/header.php` / `includes/navbar.php` — cek apakah `session_start()` diinisialisasi di file yang dipanggil oleh semua halaman.
- `uploads/` — pastikan lokasi upload tidak mengeksekusi file PHP dan dapat diakses untuk menampilkan gambar/bukti.

## Contoh Masalah dan Solusi Cepat
- Halaman blank / error database: Periksa `config/koneksi.php` dan aktifkan `display_errors` di `php.ini` untuk development.
- File upload gagal: Periksa `upload_max_filesize` dan `post_max_size` di `php.ini`, pastikan `uploads/` writable.
- Login tidak bekerja: Pastikan tabel `users` berisi data dan fungsi verifikasi password cocok dengan hashing yang digunakan.

## Pengujian Manual Singkat
1. Daftar akun baru lewat `register.php`.
2. Login dengan akun tersebut.
3. Tambah karya dengan file kecil (jpg/pdf), lalu cek apakah file tersimpan di `uploads/bukti_karya/` dan tampil di dashboard.
4. Login sebagai admin (akun awal) dan tambahkan kategori baru; pastikan kategori muncul saat menambahkan karya.

## Deployment Ringkas ke Server (catatan umum)
- Pastikan PHP versi yang digunakan cocok.
- Gunakan konfigurasi database yang aman (jangan pakai user root, gunakan password yang kuat).
- Gunakan HTTPS di server produksi.
- Set `display_errors = Off` pada `php.ini` di produksi.

## Peningkatan yang Disarankan
- Migrasi ke `password_hash()` untuk penyimpanan password.
- Gunakan prepared statements atau PDO.
- Implementasikan validasi sisi server yang lebih ketat (size, tipe file, CSRF token).
- Tambahkan unit/integration tests (PHPUnit) jika proyek diperluas.

## FAQ singkat
- Q: Dimana file upload tersimpan?
  - A: `uploads/bukti_karya/` dan `uploads/profil/`.
- Q: Bagaimana membuat akun admin?
  - A: Buat record di tabel `users` dengan `role='admin'` atau daftarkan lalu ubah langsung di DB.

## Kontak
Jika ada pertanyaan atau ingin bantuan memasang di server, buka issue di GitHub repository Anda.
