<?php
// Lokasi: admin/kelola_portfolio.php
require '../includes/functions.php';
require_admin();

$pesan_sukses = "";
$pesan_error = "";

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];

    $stmt = $conn->prepare("SELECT file_bukti FROM portfolio_data WHERE id_portfolio = ?");
    $stmt->bind_param('i', $id_hapus);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $file_path = '../uploads/bukti_karya/' . $data['file_bukti'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $stmt = $conn->prepare("DELETE FROM portfolio_data WHERE id_portfolio = ?");
        $stmt->bind_param('i', $id_hapus);
        if ($stmt->execute()) {
            $pesan_sukses = "Portfolio berhasil dihapus.";
        } else {
            $pesan_error = "Gagal menghapus portfolio: " . $stmt->error;
        }
    } else {
        $pesan_error = "Portfolio tidak ditemukan.";
    }
}

// Pencarian/filter sederhana untuk admin
$search = '';
if (isset($_GET['cari'])) {
    $search = trim($_GET['cari']);
    $like = "%{$search}%";
    $stmt = $conn->prepare("SELECT p.*, k.nama_kategori, u.nama AS pemilik, u.username FROM portfolio_data p LEFT JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori LEFT JOIN users u ON p.id_user = u.id_user WHERE p.judul_karya LIKE ? OR p.deskripsi LIKE ? OR k.nama_kategori LIKE ? OR u.nama LIKE ? OR u.username LIKE ? ORDER BY p.id_portfolio DESC");
    $stmt->bind_param('sssss', $like, $like, $like, $like, $like);
    $stmt->execute();
    $portfolios = $stmt->get_result();
} else {
    $portfolios = $conn->query("SELECT p.*, k.nama_kategori, u.nama AS pemilik, u.username FROM portfolio_data p LEFT JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori LEFT JOIN users u ON p.id_user = u.id_user ORDER BY p.id_portfolio DESC");
}

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="fw-bold">Kelola Portfolio</h3>
        <p class="text-muted mb-0">Lihat dan hapus portfolio yang diunggah oleh semua pengguna.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2 flex-wrap">
        <form action="" method="GET" class="d-flex search-form" style="max-width:420px;">
            <input type="text" name="cari" class="form-control me-2 search-input" placeholder="Cari judul/pemilik/kategori..." value="<?= htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-outline-primary">Cari</button>
            <?php if($search): ?>
                <a href="kelola_portfolio.php" class="btn btn-outline-danger ms-2">Reset</a>
            <?php endif; ?>
        </form>
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc($pesan_sukses); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($pesan_error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc($pesan_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-responsive bg-white shadow-sm rounded-3">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th class="px-3">No</th>
                <th>Judul Karya</th>
                <th>Pemilik</th>
                <th>Kategori</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($portfolios)): ?>
            <tr>
                <td class="px-3"><?= $no++; ?></td>
                <td><?= esc($row['judul_karya']); ?></td>
                <td>
                    <?= esc($row['pemilik'] ?? 'Tidak diketahui'); ?><br>
                    <small class="text-muted">@<?= esc($row['username'] ?? 'unknown'); ?></small>
                </td>
                <td><?= esc($row['nama_kategori'] ?? 'Tanpa kategori'); ?></td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="view_bukti.php?id=<?= $row['id_portfolio']; ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="kelola_portfolio.php?hapus=<?= $row['id_portfolio']; ?>" class="btn btn-sm btn-danger confirm-link" data-confirm="Yakin ingin menghapus portofolio ini?">Hapus</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
