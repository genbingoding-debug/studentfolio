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

$page_css = ['user.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="fw-bold">Portfolio Saya</h3>
        <p class="text-muted mb-0">Kelola rekam jejak karyamu di sini.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2 flex-wrap">
        <form action="" method="GET" class="d-flex search-form">
            <input type="text" name="cari" class="form-control me-2 search-input" placeholder="Cari judul/kategori..." value="<?= htmlspecialchars($keyword); ?>">
            <button type="submit" class="btn btn-outline-primary">Cari</button>
            <?php if($keyword): ?>
                <a href="dashboard.php" class="btn btn-outline-danger ms-2">Reset</a>
            <?php endif; ?>
        </form>
        
        <a href="tambah_karya.php" class="btn btn-primary fw-bold shadow-sm btn-add-item">+ Tambah</a>
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
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">
                <?= $keyword ? "Tidak ada karya yang cocok dengan pencarian '$keyword'." : "Belum ada karya. Yuk, tambah sekarang!"; ?>
            </h5>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>