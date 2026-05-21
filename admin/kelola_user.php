<?php
// Lokasi: admin/kelola_user.php
require '../includes/functions.php';
require_admin();

// Proses Logika Ubah Role
if (isset($_POST['ubah_role'])) {
    $id_target = $_POST['id_user'];
    $role_baru = $_POST['role'];
    
    // Cegah admin mengubah akunnya sendiri secara tidak sengaja
    if ($id_target != $_SESSION['id_user']) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id_user = ?");
        $stmt->bind_param('si', $role_baru, $id_target);
        $stmt->execute();
    }
}

// Proses Logika Hapus User
$pesan_sukses = "";
$pesan_error = "";
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    if ($id_hapus == $_SESSION['id_user']) {
        $pesan_error = "Tidak dapat menghapus akun Anda sendiri.";
    } else {
        $stmt = $conn->prepare("SELECT file_bukti FROM portfolio_data WHERE id_user = ?");
        $stmt->bind_param('i', $id_hapus);
        $stmt->execute();
        $files = $stmt->get_result();

        while ($file = $files->fetch_assoc()) {
            $file_path = '../uploads/bukti_karya/' . $file['file_bukti'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $stmt = $conn->prepare("DELETE FROM portfolio_data WHERE id_user = ?");
        $stmt->bind_param('i', $id_hapus);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->bind_param('i', $id_hapus);
        if ($stmt->execute()) {
            $pesan_sukses = "Pengguna dan semua portofolionya berhasil dihapus.";
        } else {
            $pesan_error = "Gagal menghapus pengguna: " . $stmt->error;
        }
    }
}

// Ambil semua data user dari tabel, beserta jumlah portfolio yang dimiliki
$users = $conn->query("SELECT u.*, COUNT(p.id_portfolio) AS karya_count FROM users u LEFT JOIN portfolio_data p ON u.id_user = p.id_user GROUP BY u.id_user ORDER BY u.id_user DESC");

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Manajemen Otoritas Pengguna</h4>
            <a href="dashboard.php" class="btn btn-sm btn-secondary">Kembali ke Dashboard</a>
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
                        <th class="px-3">Nama</th>
                        <th>Username</th>
                        <th>Role Saat Ini</th>
                        <th>Jumlah Karya</th>
                        <th class="text-center">Aksi Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td class="px-3 fw-semibold"><?= esc($row['nama']); ?></td>
                        <td><code><?= esc($row['username']); ?></code></td>
                        <td>
                            <?php if ($row['role'] == 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-success">User</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info text-dark"><?= intval($row['karya_count']); ?> karya</span></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <form method="POST" class="d-flex gap-2 m-0">
                                    <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                                    <select name="role" class="form-select form-select-sm admin-action-select" <?= $row['id_user'] == $_SESSION['id_user'] ? 'disabled' : ''; ?> >
                                        <option value="user" <?= $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="ubah_role" class="btn btn-sm btn-dark" <?= $row['id_user'] == $_SESSION['id_user'] ? 'disabled' : ''; ?>>Update</button>
                                </form>
                                <a href="kelola_user.php?hapus=<?= $row['id_user']; ?>" class="btn btn-sm btn-danger confirm-link" data-confirm="Yakin ingin menghapus pengguna ini beserta semua portofolionya?" <?= $row['id_user'] == $_SESSION['id_user'] ? 'disabled' : ''; ?>>Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>