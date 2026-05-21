<?php
// Lokasi file: admin/kelola_kategori.php
require '../includes/functions.php';
require_admin();

$pesan_sukses = "";
$pesan_error = "";

// ==========================================
// 1. LOGIKA PROSES TAMBAH KATEGORI (CREATE)
// ==========================================
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    
    // Cek apakah kategori sudah ada
    $stmt = $conn->prepare("SELECT id_kategori FROM kategori_portfolio WHERE nama_kategori = ?");
    $stmt->bind_param('s', $nama_kategori);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pesan_error = "Kategori '" . htmlspecialchars($nama_kategori) . "' sudah ada!";
    } else {
        $stmt = $conn->prepare("INSERT INTO kategori_portfolio (nama_kategori) VALUES (?)");
        $stmt->bind_param('s', $nama_kategori);
        if ($stmt->execute()) {
            $pesan_sukses = "Kategori baru berhasil ditambahkan!";
        } else {
            $pesan_error = "Gagal menambah kategori: " . $stmt->error;
        }
    }
}

// ==========================================
// 2. LOGIKA PROSES EDIT KATEGORI (UPDATE)
// ==========================================
if (isset($_POST['edit_kategori'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_baru = trim($_POST['nama_kategori_baru']);
    
    $stmt = $conn->prepare("UPDATE kategori_portfolio SET nama_kategori = ? WHERE id_kategori = ?");
    $stmt->bind_param('si', $nama_baru, $id_kategori);
    if ($stmt->execute()) {
        $pesan_sukses = "Nama kategori berhasil diperbarui!";
    } else {
        $pesan_error = "Gagal memperbarui kategori: " . $stmt->error;
    }
}

// ==========================================
// 3. LOGIKA PROSES HAPUS KATEGORI (DELETE)
// ==========================================
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // PHP akan mencoba menghapus. Jika kategori sedang digunakan di portfolio mahasiswa,
    // perintah ini akan gagal otomatis karena aturan 'ON DELETE RESTRICT' yang kita buat di database.
    $stmt = $conn->prepare("DELETE FROM kategori_portfolio WHERE id_kategori = ?");
    $stmt->bind_param('i', $id_hapus);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $pesan_sukses = "Kategori berhasil dihapus!";
        } else {
            $pesan_error = "Kategori tidak ditemukan atau tidak bisa dihapus.";
        }
    } else {
        $pesan_error = "Kategori tidak bisa dihapus karena sedang digunakan oleh portfolio mahasiswa.";
    }
}

// ==========================================
// 4. AMBIL DATA UNTUK DITAMPILKAN (READ)
// ==========================================
$result = $conn->query("SELECT k.*, COUNT(p.id_portfolio) AS total_portfolio FROM kategori_portfolio k LEFT JOIN portfolio_data p ON p.id_kategori = k.id_kategori GROUP BY k.id_kategori ORDER BY k.id_kategori DESC");

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-3 align-items-center admin-page-header">
    <div class="col-md-8">
        <h2 class="fw-bold">Kelola Kategori Portfolio</h2>
        <p class="text-muted mb-0">Atur kategori portfolio agar pengguna dapat memilih jenis karya dengan jelas.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="dashboard.php" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
    </div>
</div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <?= $pesan_sukses; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($pesan_error): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <?= $pesan_error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Jenis Karya Baru</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Misal: Animasi 3D, IoT" required>
                    </div>
                    <button type="submit" name="tambah_kategori" class="btn btn-primary w-100">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Kategori Terinput</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3" width="8%">No</th>
                                <th width="35%">Nama Kategori</th>
                                <th width="25%" class="text-center">Jumlah Karya</th>
                                <th width="32%" class="text-center">Ubah atau Hapus Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td class="px-3"><?= $no++; ?></td>
                            <td><strong><?= esc($row['nama_kategori']); ?></strong></td>
                            <td class="text-center"><span class="badge bg-info text-dark"><?= intval($row['total_portfolio']); ?> karya</span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_kategori']; ?>">
                                        Ubah
                                    </button>
                                    
                                    <a href="kelola_kategori.php?hapus=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-danger confirm-link" data-confirm="Yakin ingin menghapus kategori ini?">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['id_kategori']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Nama Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Kategori Baru</label>
                                                <input type="text" name="nama_kategori_baru" class="form-control" value="<?= htmlspecialchars($row['nama_kategori']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="edit_kategori" class="btn btn-warning">Perbarui</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>