# StudentFolio

StudentFolio adalah aplikasi portofolio siswa berbasis PHP dan MySQL. README ini menjelaskan alur pembuatan kode aplikasi, struktur folder, fitur utama, dan cara menjalankan proyek secara lokal.

## 1. Tujuan Aplikasi

Aplikasi ini dibuat untuk memfasilitasi:
- Pendaftaran dan login pengguna
- Upload bukti karya siswa
- Penyimpanan kategori portofolio
- Manajemen portofolio oleh pengguna
- Manajemen kategori dan pengguna oleh admin
- Tampilan bukti karya dengan viewer dan navigasi kembali
- User dapat melihat, edit, delete karya, serta melihat tanggal kegiatan

## 2. Struktur Folder Utama

- `index.php` — Halaman beranda / landing page
- `login.php` — Halaman login
- `register.php` — Halaman pendaftaran
- `logout.php` — Proses logout
- `config/koneksi.php` — Koneksi MySQL
- `includes/` — Header, footer, navbar, dan helper function
- `assets/` — CSS, gambar, JavaScript
- `uploads/` — File hasil upload pengguna (`bukti_karya/` dan `profil/`)
- `user/` — Halaman area user: dashboard, tambah/edit/hapus karya, profile, view bukti
- `admin/` — Halaman area admin: dashboard, kelola kategori, kelola user, kelola portfolio, view bukti

## 3. Alur Pembuatan Kode

### 3.1 Koneksi Database dan Konfigurasi
File utama:
- `config/koneksi.php`

Isi file ini hanya berisi koneksi ke MySQL dengan `mysqli_connect`.

### 3.2 Helper dan Otentikasi
File utama:
- `includes/functions.php`
- `includes/header.php`
- `includes/navbar.php`
- `includes/footer.php`

Fungsi penting di `functions.php` termasuk:
- `require_user()` untuk membatasi halaman user
- `require_admin()` untuk halaman admin
- helper kategori (`fetch_categories`, `has_categories`)
- fungsi flash message untuk menampilkan notifikasi

`header.php` memanggil CSS halaman spesifik dan menampilkan flash message.

### 3.3 Alur Auth

- `register.php`
  - Menerima nama, email, password, dan menyimpan user baru.
  - Password disarankan di-hash menggunakan `password_hash()`.

- `login.php`
  - Mencocokkan email dan password.
  - Menyimpan `$_SESSION` untuk user/role.

- `logout.php`
  - Menghapus session dan mengarahkan ke halaman login.

### 3.4 Area User

- `user/dashboard.php`
  - Menampilkan daftar karya milik user.
  - Terdapat fungsi pencarian inline.
  - Menunjukkan kategori dan tanggal kegiatan setiap karya.

- `user/tambah_karya.php`
  - Form upload portofolio dengan kategori, judul, deskripsi, tanggal kegiatan, dan bukti file.
  - Validasi file berdasarkan ekstensi dan ukuran.
  - Menyimpan data ke `portfolio_data`.

- `user/edit_karya.php`
  - Memuat data lama untuk diedit.
  - Mengizinkan perubahan kategori, judul, deskripsi, tanggal kegiatan, dan bukti file.
  - Hapus file lama jika diupload file baru.

- `user/hapus_karya.php`
  - Menghapus karya milik user.
  - Menghapus file bukti dari `uploads/bukti_karya`.

- `user/view_bukti.php`
  - Menampilkan detail karya beserta preview file bukti.
  - Menampilkan kategori, deskripsi, dan tanggal kegiatan.

- `user/profile.php`
  - Menampilkan dan mengedit profil pengguna.
  - Memperbolehkan foto profil diupload.
  - Nama user bisa dilihat tetapi diberi peringatan jika tidak bisa diubah.

### 3.5 Area Admin

- `admin/dashboard.php`
  - Dasbor ringkas untuk admin dengan metrik dan navigasi.

- `admin/kelola_kategori.php`
  - CRUD kategori portofolio.
  - Menampilkan daftar kategori, jumlah karya, serta modal edit.

- `admin/kelola_user.php`
  - Menampilkan user terdaftar.
  - Menghapus pengguna dan karya terkait bila perlu.

- `admin/kelola_portfolio.php`
  - Menampilkan semua portfolio dari semua user.
  - Fitur pencarian, lihat, dan hapus portfolio.

- `admin/view_bukti.php`
  - Detail portfolio yang bisa diakses admin.
  - Menampilkan data pemilik, kategori, dan tanggal kegiatan.

## 4. Penambahan Tanggal Kegiatan

Aplikasi kini mencatat tanggal kegiatan pada setiap karya:
- kolom database `portfolio_data.tanggal_kegiatan`
- disimpan dari form `tambah_karya` dan `edit_karya`
- ditampilkan di dashboard user dan detail bukti
- ditampilkan juga di panel admin `kelola_portfolio`

## 5. Flow Database

Tabel penting:
- `users`
- `kategori_portfolio`
- `portfolio_data`

Contoh kolom `portfolio_data` seharusnya meliputi:
- `id_portfolio`
- `id_user`
- `id_kategori`
- `judul_karya`
- `deskripsi`
- `file_bukti`
- `tanggal_kegiatan`

## 6. Cara Menjalankan Proyek

1. Pastikan XAMPP terpasang.
2. Copy folder ke `C:\xampp\htdocs\studentfolio`.
3. Buat database MySQL.
4. Edit `config/koneksi.php` dengan detail database.
5. Akses `http://localhost/studentfolio`.

## 7. Catatan Pengembangan

- Pisahkan logika PHP dari markup HTML sebisa mungkin di `includes/`.
- Gunakan `prepared statements` untuk mencegah SQL injection.
- Validasi file upload pada sisi server dengan whitelist ekstensi.
- Tampilkan error/flash message untuk feedback pengguna.

## 8. Rekomendasi Perbaikan Lebih Lanjut

- Ubah password storage ke `password_hash()` jika belum.
- Tambahkan CSRF token pada formulir penting.
- Tambahkan validasi form yang lebih lengkap.
- Pertimbangkan refaktor ke pattern MVC di iterasi selanjutnya.

---

Dokumen ini menguraikan alur pembuatan dan struktur aplikasi StudentFolio agar mudah dipahami dan dikembangkan lebih lanjut.