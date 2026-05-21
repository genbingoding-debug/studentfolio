<?php
// Lokasi file: register.php
require 'includes/functions.php';

$page_css = ['auth.css'];

// Jika sudah login, tendang ke halaman utama
if (isset($_SESSION['id_user'])) { redirect('index.php'); }

$error = ""; $sukses = "";
$in_nama = ""; $in_user = "";

if (isset($_POST['register'])) {
    $in_nama = trim($_POST['nama']);
    $in_user = trim($_POST['username']);
    $password = $_POST['password'];
    
    // 1. Tangkap data file foto profil
    $nama_foto = $_FILES['foto_profil']['name'];
    $tmp_foto  = $_FILES['foto_profil']['tmp_name'];
    $error_upload = $_FILES['foto_profil']['error'];

    // 2. Validasi Password
    if (strlen($password) <= 8) {
        $error = "Password harus lebih dari 8 karakter!";
    } elseif (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $error = "Password harus kombinasi huruf dan angka!";
    } else {
        // 3. Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $stmt->bind_param('s', $in_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah dipakai, silakan cari yang lain!";
        } else {
            // 4. Validasi Ekstensi Foto
            $ekstensi_diperbolehkan = ['png', 'jpg', 'jpeg'];
            $x = explode('.', $nama_foto);
            $ekstensi = strtolower(end($x));

            if ($error_upload === UPLOAD_ERR_OK && in_array($ekstensi, $ekstensi_diperbolehkan)) {
                // Buat nama foto menjadi unik agar tidak bentrok
                $foto_unik = uniqid() . "-" . str_replace(" ", "_", $nama_foto);
                $lokasi_simpan = "uploads/profil/" . $foto_unik;

                if (move_uploaded_file($tmp_foto, $lokasi_simpan)) {
                    // Enkripsi password
                    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $conn->prepare("INSERT INTO users (nama, username, password, foto_profil, role) VALUES (?, ?, ?, ?, 'user')");
                    $stmt->bind_param('ssss', $in_nama, $in_user, $pass_hash, $foto_unik);

                    if ($stmt->execute()) {
                        $sukses = "Akun berhasil dibuat! Silakan login menggunakan akun baru Anda.";
                        $in_nama = ""; $in_user = ""; // Kosongkan form setelah sukses
                    } else {
                        $error = "Gagal mendaftar ke database: " . $stmt->error;
                    }
                } else {
                    $error = "Gagal mengunggah foto profil. Pastikan folder uploads/profil/ tersedia.";
                }
            } else {
                $error = "Format foto tidak valid. Hanya diperbolehkan file PNG, JPG, atau JPEG.";
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-5">
        <div class="card shadow-sm p-4 border-0 rounded-3 auth-card">
            <h3 class="text-center fw-bold mb-4">Daftar Akun Baru</h3>
            
            <?php if($error): ?>
                <div class='alert alert-danger shadow-sm'><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error; ?></div>
            <?php endif; ?>
            
            <?php if($sukses): ?>
                <div class='alert alert-success shadow-sm'><i class="bi bi-check-circle-fill me-2"></i><?= $sukses; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($in_nama); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($in_user); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    <div class="form-text">Min. 9 karakter, harus mengandung kombinasi huruf & angka.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Foto Profil</label>
                    <input type="file" name="foto_profil" class="form-control" accept=".jpg,.jpeg,.png" required>
                    <div class="form-text">Format didukung: JPG, JPEG, PNG.</div>
                </div>
                
                <button type="submit" name="register" class="btn btn-primary w-100 fw-bold py-2">Daftar Sekarang</button>
            </form>
            
            <div class="text-center mt-4">
                <span class="text-muted">Sudah punya akun? </span>
                <a href="login.php" class="text-decoration-none fw-bold">Login di sini</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>