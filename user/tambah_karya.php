<?php
// Lokasi: user/tambah_karya.php
require '../includes/functions.php';
require_user();




if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id_user'];
    $id_kat = $_POST['id_kategori'];
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_kegiatan = trim($_POST['tanggal_kegiatan']);

    if (empty($tanggal_kegiatan)) {
        $error = 'Tanggal kegiatan diperlukan.';
    }

    if (!isset($error)) {
        // Logika Upload File dengan Validasi Ketat
        $nama_file = $_FILES['bukti']['name'];
        $tmp_file = $_FILES['bukti']['tmp_name'];
        $ukuran_file = $_FILES['bukti']['size'];

    // 1. Whitelist Ekstensi (Hanya file relevan yang diizinkan)
    $ekstensi_diizinkan = ['pdf', 'zip', 'jpg', 'jpeg', 'png'];
    $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if (in_array($ekstensi, $ekstensi_diizinkan)) {
        // 2. Batas Ukuran Maksimal 5MB (5000000 bytes)
        if ($ukuran_file <= 5000000) {
            // 3. Sanitasi Nama File agar aman dari karakter aneh
            $file_baru = uniqid() . "-" . preg_replace("/[^a-zA-Z0-9.-]/", "_", $nama_file);
            $path = "../uploads/bukti_karya/" . $file_baru;

            if (move_uploaded_file($tmp_file, $path)) {
                $stmt = $conn->prepare("INSERT INTO portfolio_data (id_user, id_kategori, judul_karya, deskripsi, file_bukti, tanggal_kegiatan) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('iissss', $id_user, $id_kat, $judul, $deskripsi, $file_baru, $tanggal_kegiatan);
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Karya berhasil ditambahkan.';
                    $_SESSION['message_type'] = 'success';
                    redirect('dashboard.php');
                } else {
                    $error = "Gagal menyimpan karya: " . $stmt->error;
                }
            } else {
                $error = "Sistem gagal menyimpan file ke folder server.";
            }
        } else {
            $error = "Ditolak: Ukuran file terlalu besar! Maksimal 5 MB.";
        }
    } else {
        $error = "Ditolak: Terdeteksi file tidak aman! Hanya boleh PDF, ZIP, JPG, dan PNG.";
    }
    }
}

// Ambil kategori untuk Dropdown
$kategori = [];
$kategori_result = fetch_categories($conn);
while ($row = $kategori_result->fetch_assoc()) {
    $kategori[] = $row;
}
$has_categories = has_categories($conn);

$page_css = ['user.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card p-4 shadow-sm border-0 rounded-3 auth-card">
            <h4 class="fw-bold mb-3">Tambah Karya Baru</h4>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <?php if (!$has_categories): ?>
                <div class="alert alert-warning">
                    Belum ada jenis portfolio. Silakan minta Admin untuk menambahkan kategori terlebih dahulu.
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Judul Karya</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-select" required <?= $has_categories ? '' : 'disabled'; ?> >
                        <?php foreach($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>"><?= htmlspecialchars($k['nama_kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label>Upload File Bukti</label>
                    <input type="file" name="bukti" class="form-control" accept=".pdf,.zip,.jpg,.jpeg,.png" required>
                    <small class="text-muted">Max 5MB (PDF/ZIP/IMG).</small>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary fw-bold">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary fw-bold" <?= $has_categories ? '' : 'disabled'; ?>>Simpan Karya</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>