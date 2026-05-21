<?php
// Lokasi: user/edit_karya.php
require '../includes/functions.php';
require_user();

$id_portfolio = $_GET['id'] ?? 0;
$id_user = $_SESSION['id_user'];

// 1. Ambil data lama dari database
$stmt = $conn->prepare("SELECT * FROM portfolio_data WHERE id_portfolio = ? AND id_user = ?");
$stmt->bind_param('ii', $id_portfolio, $id_user);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) { die("Data tidak ditemukan atau bukan milikmu!"); }

// Ambil daftar kategori untuk dropdown
$kategori = [];
$kategori_result = fetch_categories($conn);
while ($row = $kategori_result->fetch_assoc()) {
    $kategori[] = $row;
}

// 2. Proses Update Data
if (isset($_POST['update'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_kegiatan = trim($_POST['tanggal_kegiatan']);
    $id_kategori = isset($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : $data['id_kategori'];
    $valid_category = false;
    foreach ($kategori as $kat) {
        if ($kat['id_kategori'] === $id_kategori) {
            $valid_category = true;
            break;
        }
    }

    if (!$valid_category) {
        $error = 'Kategori tidak valid.';
    } elseif (empty($tanggal_kegiatan)) {
        $error = 'Tanggal kegiatan tidak boleh kosong.';
    } else {
        // Variabel untuk file baru
        $nama_file = $_FILES['bukti']['name'];
        $tmp_file = $_FILES['bukti']['tmp_name'];
        $ukuran_file = $_FILES['bukti']['size'];

    // JIKA ADA FILE BARU YANG DI-UPLOAD
    if (!empty($nama_file)) {
        // Lapisan Keamanan 1: Whitelist Ekstensi
        $ekstensi_diizinkan = ['pdf', 'zip', 'jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_diizinkan)) {
            // Lapisan Keamanan 2: Batas Ukuran Maksimal 5MB (5000000 bytes)
            if ($ukuran_file <= 5000000) {
                // Lapisan Keamanan 3: Ganti nama file agar unik dan aman
                $file_baru = uniqid() . "-" . preg_replace("/[^a-zA-Z0-9.-]/", "_", $nama_file);
                $path = "../uploads/bukti_karya/" . $file_baru;

                if (move_uploaded_file($tmp_file, $path)) {
                    // Hapus file lama dari folder server agar tidak menumpuk
                    $file_lama = "../uploads/bukti_karya/" . $data['file_bukti'];
                    if (file_exists($file_lama) && !empty($data['file_bukti'])) {
                        unlink($file_lama);
                    }

                    // Update database beserta nama file barunya
                    $stmt = $conn->prepare("UPDATE portfolio_data SET judul_karya = ?, deskripsi = ?, id_kategori = ?, tanggal_kegiatan = ?, file_bukti = ? WHERE id_portfolio = ? AND id_user = ?");
                    $stmt->bind_param('ssissii', $judul, $deskripsi, $id_kategori, $tanggal_kegiatan, $file_baru, $id_portfolio, $id_user);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = 'Karya berhasil diperbarui.';
                        $_SESSION['message_type'] = 'success';
                        redirect('dashboard.php');
                    } else {
                        $error = "Gagal memperbarui karya: " . $stmt->error;
                    }

                } else {
                    $error = "Sistem gagal menyimpan file ke folder server.";
                }
            } else {
                $error = "Gagal: Ukuran file terlalu besar! Maksimal 5 MB.";
            }
        } else {
            $error = "Ditolak: Terdeteksi file tidak aman! Hanya boleh PDF, ZIP, JPG, dan PNG.";
        }
    } 
    // JIKA TIDAK ADA FILE BARU (Hanya ubah teks)
    else {
        $stmt = $conn->prepare("UPDATE portfolio_data SET judul_karya = ?, deskripsi = ?, id_kategori = ?, tanggal_kegiatan = ? WHERE id_portfolio = ? AND id_user = ?");
        $stmt->bind_param('ssissi', $judul, $deskripsi, $id_kategori, $tanggal_kegiatan, $id_portfolio, $id_user);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Karya berhasil diperbarui.';
            $_SESSION['message_type'] = 'success';
            redirect('dashboard.php');
        } else {
            $error = "Gagal memperbarui karya: " . $stmt->error;
        }
    }
    }
}

$page_css = ['user.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card p-4 shadow-sm border-0 rounded-3 auth-card">
            <h4 class="mb-4">Edit Karya</h4>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Judul Karya</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul_karya']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" class="form-control" value="<?= htmlspecialchars($data['tanggal_kegiatan'] ?? date('Y-m-d')); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <?php foreach ($kategori as $kat): ?>
                            <option value="<?= $kat['id_kategori']; ?>" <?= $kat['id_kategori'] === (int)$data['id_kategori'] ? 'selected' : ''; ?>><?= htmlspecialchars($kat['nama_kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label>Ganti File Bukti (Opsional)</label>
                    <input type="file" name="bukti" class="form-control" accept=".pdf,.zip,.jpg,.jpeg,.png">
                    <small class="text-muted d-block mt-1">
                        File saat ini: <a href="../uploads/bukti_karya/<?= $data['file_bukti']; ?>" target="_blank"><?= $data['file_bukti']; ?></a>
                        <br>*Abaikan jika tidak ingin mengubah file. Max 5MB (PDF/ZIP/IMG).
                    </small>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="update" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>