<?php
// Lokasi: admin/view_bukti.php
require '../includes/functions.php';
require_admin();

$id_portfolio = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT p.*, k.nama_kategori, u.nama AS pemilik, u.username FROM portfolio_data p LEFT JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori LEFT JOIN users u ON p.id_user = u.id_user WHERE p.id_portfolio = ?");
$stmt->bind_param('i', $id_portfolio);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-column flex-md-row">
            <div>
                <h3 class="fw-bold">Lihat Portfolio</h3>
                <p class="text-muted mb-0">Detail portofolio lengkap beserta bukti karya.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="kelola_portfolio.php" class="btn btn-secondary">Kembali ke Kelola Portfolio</a>
                <a href="dashboard.php" class="btn btn-outline-primary">Dashboard Admin</a>
            </div>
        </div>
    </div>
</div>

<?php if (!$data): ?>
    <div class="alert alert-danger">Portfolio tidak ditemukan atau tidak bisa diakses.</div>
<?php else:
    $fileName = htmlspecialchars($data['file_bukti']);
    $filePath = '../uploads/bukti_karya/' . $fileName;
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png']);
    $isPdf = $fileExt === 'pdf';
?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><?= esc($data['judul_karya']); ?></h5>
                    <p class="text-muted mb-2"><strong>Pemilik:</strong> <?= esc($data['pemilik'] ?? 'Tidak diketahui'); ?></p>
                    <p class="text-muted mb-2"><strong>Username:</strong> <?= esc($data['username'] ?? 'unknown'); ?></p>
                    <p class="text-muted mb-2"><strong>Kategori:</strong> <?= esc($data['nama_kategori'] ?? 'Tanpa kategori'); ?></p>
                    <p class="text-muted mb-3"><?= nl2br(esc($data['deskripsi'])); ?></p>
                    <a href="kelola_portfolio.php" class="btn btn-primary w-100 mb-2">Kembali</a>
                    <a href="../uploads/bukti_karya/<?= $fileName; ?>" target="_blank" class="btn btn-outline-secondary w-100">Buka File Bukti</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body bukti-viewer p-0">
                    <?php if ($isImage): ?>
                        <img src="../uploads/bukti_karya/<?= $fileName; ?>" class="img-fluid rounded-bottom w-100" alt="<?= $fileName; ?>">
                    <?php elseif ($isPdf): ?>
                        <iframe src="../uploads/bukti_karya/<?= $fileName; ?>" class="w-100" style="height:80vh; border:none;"></iframe>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <p class="mb-3">Pratinjau tidak tersedia untuk jenis file ini.</p>
                            <a href="../uploads/bukti_karya/<?= $fileName; ?>" target="_blank" class="btn btn-outline-primary">Download Bukti</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
