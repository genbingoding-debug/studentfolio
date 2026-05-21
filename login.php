<?php
// Lokasi: login.php
require 'includes/functions.php';

if (isset($_SESSION['id_user'])) { redirect('index.php'); }

$page_css = ['auth.css'];

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, nama, password, role, foto_profil FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Simpan data ke sesi
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['foto_profil'] = $row['foto_profil']; // Mengingat foto

            // Notifikasi toast setelah login
            $_SESSION['message'] = 'Selamat datang, ' . esc($row['nama']) . '! Anda berhasil masuk.';
            $_SESSION['message_type'] = 'success';

            if ($row['role'] == 'admin') header("Location: admin/dashboard.php");
            else header("Location: user/dashboard.php");
            exit;
        } else {
            // Gagal login -> tampilkan toast
            $_SESSION['message'] = 'Password salah!';
            $_SESSION['message_type'] = 'danger';
            redirect('login.php');
        }
    } else {
        $_SESSION['message'] = 'Username tidak ditemukan!';
        $_SESSION['message_type'] = 'danger';
        redirect('login.php');
    }
}

include 'includes/header.php';
?>

<div class="row mt-5">
    <div class="col-md-4 mx-auto">
        <div class="card p-4 shadow-sm auth-card">
            <h3 class="text-center mb-4">Login</h3>
            


            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
            </form>
            <div class="text-center mt-3">
                <span class="text-muted">Belum punya akun? </span>
                <a href="register.php" class="text-decoration-none fw-bold">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>