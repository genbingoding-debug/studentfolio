<?php
// Lokasi: user/view_bukti.php
require '../includes/functions.php';
require_user();

$id_portfolio = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT p.*, k.nama_kategori FROM portfolio_data p INNER JOIN kategori_portfolio k ON p.id_kategori = k.id_kategori WHERE p.id_portfolio = ? AND p.id_user = ?");
$stmt->bind_param('ii', $id_portfolio, $_SESSION['id_user']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$page_css = ['user.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-column flex-md-row">
            <div>
                <h3 class="fw-bold">Lihat Bukti Karya</h3>
                <p class="text-muted mb-0">Tampilan bukti lengkap dengan kontrol kembali ke Portfolio Saya.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Portfolio Saya</a>
                <a href="../index.php" class="btn btn-outline-primary">Ke StudentFolio</a>
            </div>
        </div>
    </div>
</div>

<?php if (!$data): ?>
    <div class="alert alert-danger">Bukti tidak ditemukan atau Anda tidak memiliki akses.</div>
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
                    <h5 class="fw-bold mb-3"><?= htmlspecialchars($data['judul_karya']); ?></h5>
                    <p class="text-muted mb-2"><strong>Kategori:</strong> <?= htmlspecialchars($data['nama_kategori']); ?></p>
                    <p class="text-muted mb-2"><strong>Tanggal Kegiatan:</strong> <?= $data['tanggal_kegiatan'] ? date('d M Y', strtotime($data['tanggal_kegiatan'])) : '-'; ?></p>
                    <p class="text-muted mb-3"><?= nl2br(htmlspecialchars($data['deskripsi'])); ?></p>
                    <a href="dashboard.php" class="btn btn-primary w-100 mb-2">Kembali ke Portfolio</a>
                    <a href="../uploads/bukti_karya/<?= $fileName; ?>" target="_blank" class="btn btn-outline-secondary w-100">Buka Raw File</a>
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
