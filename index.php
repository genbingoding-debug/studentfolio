<?php
// Lokasi: index.php
require 'includes/functions.php';

// Jika sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}

$page_css = ['home.css'];
include 'includes/header.php';
?>

<div class="row mb-5 mt-3">
    <div class="col-12 text-center">
        <div class="hero-section shadow-sm">
            <h1 class="display-4 fw-bolder mb-3">Rekam Jejak Karyamu,<br>Dalam Satu Portal.</h1>
            <p class="lead mb-4 opacity-75">Platform dokumentasi portofolio terpadu untuk pamerkan pencapaian akademismu.</p>
            <a href="register.php" class="btn btn-light btn-lg px-4 text-primary fw-bold shadow-sm me-2">Mulai Sekarang</a>
            <a href="login.php" class="btn btn-outline-light btn-lg px-4">Login Akun</a>
        </div>
    </div>
</div>

<div class="row text-center g-4 mb-5">
    <div class="col-md-4">
        <div class="card h-100 feature-card shadow-sm p-4">
            <i class="bi bi-collection display-4 text-primary mb-3"></i>
            <h4 class="fw-bold">Kategori Terstruktur</h4>
            <p class="text-muted">Klasifikasikan karyamu berdasarkan jenisnya agar mudah ditemukan.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 feature-card shadow-sm p-4">
            <i class="bi bi-shield-lock display-4 text-primary mb-3"></i>
            <h4 class="fw-bold">Keamanan Data</h4>
            <p class="text-muted">Dilindungi enkripsi kata sandi yang kokoh untuk setiap pengguna.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 feature-card shadow-sm p-4">
            <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3"></i>
            <h4 class="fw-bold">Penyimpanan Terpusat</h4>
            <p class="text-muted">Unggah dokumen teknis atau bukti proyekmu ke server dengan aman.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>