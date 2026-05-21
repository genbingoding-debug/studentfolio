<?php
// Lokasi: admin/dashboard.php
require '../includes/functions.php';
require_admin();

// Mengambil total pengguna dan total karya untuk statistik
$users_count = $conn->query("SELECT COUNT(id_user) AS total FROM users")->fetch_assoc()['total'];
$portfolio_count = $conn->query("SELECT COUNT(id_portfolio) AS total FROM portfolio_data")->fetch_assoc()['total'];

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4">
    <div class="col-12">
        <div class="admin-banner shadow-sm mb-4">
            <h2 class="fw-bold"><i class="bi bi-shield-lock-fill me-2"></i> Ruang Kendali Admin</h2>
            <p class="mb-0 opacity-75">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>!</p>
        </div>
        
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0 text-center h-100">
                    <h5 class="text-muted fw-bold">Pengguna Terdaftar</h5>
                    <h1 class="text-primary fw-bolder display-4"><?= $users_count; ?></h1>
                    <p class="text-muted mt-3 mb-0">Jumlah akun aktif yang menggunakan sistem StudentFolio.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0 text-center h-100">
                    <h5 class="text-muted fw-bold">Portfolio Tersimpan</h5>
                    <h1 class="text-success fw-bolder display-4"><?= $portfolio_count; ?></h1>
                    <p class="text-muted mt-3 mb-0">Jumlah karya yang telah diunggah dan dikelola di platform.</p>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 action-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Kelola Pengguna</h5>
                        <p class="card-text text-muted">Tambah, ubah, atau cek otorisasi akun pengguna secara cepat.</p>
                        <a href="kelola_user.php" class="btn btn-outline-primary mt-auto">Buka Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 action-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Kelola Kategori</h5>
                        <p class="card-text text-muted">Atur kategori portfolio agar pengguna bisa memilih jenis karya dengan jelas.</p>
                        <a href="kelola_kategori.php" class="btn btn-outline-secondary mt-auto">Buka Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 action-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Kelola Karya</h5>
                        <p class="card-text text-muted">Lihat dan kelola semua karya yang diunggah oleh pengguna.</p>
                        <a href="kelola_portfolio.php" class="btn btn-outline-success mt-auto">Buka Sekarang</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>