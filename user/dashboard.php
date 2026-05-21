<?php
// Lokasi: user/dashboard.php
require '../includes/functions.php';
require_user();

$id_user = $_SESSION['id_user'];

// LOGIKA PENCARIAN (SEARCH)
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = trim($_GET['cari']);
    $search = "%{$keyword}%";
    
    $stmt = $conn->prepare("SELECT p.*, k.nama_kategori FROM portfolio_data p INNER JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori WHERE p.id_user = ? AND (p.judul_karya LIKE ? OR p.deskripsi LIKE ? OR k.nama_kategori LIKE ?) ORDER BY p.id_portfolio DESC");
    $stmt->bind_param('isss', $id_user, $search, $search, $search);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    // Query standar jika tidak mencari apa-apa
    $stmt = $conn->prepare("SELECT p.*, k.nama_kategori FROM portfolio_data p INNER JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori WHERE p.id_user = ? ORDER BY p.id_portfolio DESC");
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $query = $stmt->get_result();
}

$nama_user = $_SESSION['nama'] ?? 'Teman';
$page_css = ['user.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-4">
    <div class="col-lg-7 mb-3">
        <div class="card shadow-sm dashboard-hero-card">
            <div class="card-body d-flex flex-column justify-content-center h-100">
                <div class="dashboard-hero-text">
                    <h3 class="fw-bold mb-3">Halo, <?= htmlspecialchars($nama_user); ?>!</h3>
                    <p class="text-muted mb-4">Tambah portofolio kamu sekarang dengan mudah. Klik tombol di bawah untuk unggah karya lengkap dengan bukti dan tanggal kegiatan.</p>
                    <a href="tambah_karya.php" class="btn btn-primary btn-lg fw-bold dashboard-cta-btn">+ Tambah Karya</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-3">
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm border-0 dashboard-info-card">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-3">Ringkasan Portfolio</h6>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <h2 class="mb-1"><?= mysqli_num_rows($query); ?></h2>
                                <p class="mb-0 te   xt-muted">Total karya saat ini</p>
                            </div>
                            <div class="dashboard-info-icon">📁</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card shadow-sm border-0 dashboard-info-card">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-3">Tips cepat</h6>
                        <ul class="dashboard-tip-list mb-0">
                            <li>Klik + Tambah Karya untuk mengunggah bukti.</li>
                            <li>Gunakan kata kunci di pencarian untuk menemukan karya.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-md-8 mx-auto">
        <form action="" method="GET" class="input-group search-input-group">
            <input type="text" name="cari" class="form-control" placeholder="Cari judul atau kategori..." value="<?= htmlspecialchars($keyword); ?>">
            <button type="submit" class="btn btn-outline-primary search-icon-btn" aria-label="Cari">🔍</button>
            <?php if($keyword): ?>
                <a href="dashboard.php" class="btn btn-outline-secondary search-clear-btn" aria-label="Reset pencarian">✕</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
    <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 portfolio-card">
                <div class="card-body">
                    <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($row['nama_kategori']); ?></span>
                    <h5 class="fw-bold text-truncate" title="<?= htmlspecialchars($row['judul_karya']); ?>">
                        <?= htmlspecialchars($row['judul_karya']); ?>
                    </h5>
                    <p class="text-muted small mb-2">
                        <strong>Tanggal Kegiatan:</strong>
                        <?= $row['tanggal_kegiatan'] ? htmlspecialchars(date('d M Y', strtotime($row['tanggal_kegiatan']))) : '-'; ?>
                    </p>
                    <p class="text-muted small text-clamp-3">
                        <?= htmlspecialchars($row['deskripsi']); ?>
                    </p>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-top-0">
                    <a href="view_bukti.php?id=<?= $row['id_portfolio']; ?>" class="btn btn-sm btn-outline-primary fw-bold">Lihat Bukti</a>
                    <div class="d-flex gap-2">
                        <a href="edit_karya.php?id=<?= $row['id_portfolio']; ?>" class="btn btn-sm btn-warning fw-bold px-3">Edit</a>
                        <a href="hapus_karya.php?id=<?= $row['id_portfolio']; ?>" class="btn btn-sm btn-danger fw-bold px-2" onclick="return confirm('Yakin hapus karya ini?');">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    
    <?php if(mysqli_num_rows($query) == 0): ?>
        <div class="col-12">
            <div class="card shadow-sm border-0 empty-state-card">
                <div class="card-body text-center">
                    <div class="empty-state-icon">🎯</div>
                    <h4 class="fw-bold mb-3">Belum ada portfolio</h4>
                    <p class="text-muted mb-4">Mulai dengan menambahkan karya pertama kamu. Prosesnya sederhana dan cepat.</p>
                    <a href="tambah_karya.php" class="btn btn-primary btn-lg fw-bold">+ Tambah Karya Sekarang</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>