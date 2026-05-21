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

// Ambil semua data user dari tabel
$users = $conn->query("SELECT * FROM users ORDER BY id_user DESC");

$page_css = ['admin.css'];
include '../includes/header.php';
?>

<div class="row mt-4 mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Manajemen Otoritas Pengguna</h4>
            <a href="dashboard.php" class="btn btn-sm btn-secondary">Kembali ke Dashboard</a>
        </div>
        
        <div class="table-responsive bg-white shadow-sm rounded-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="px-3">Nama</th>
                        <th>Username</th>
                        <th>Role Saat Ini</th>
                        <th class="text-center">Aksi Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td class="px-3 fw-semibold"><?= htmlspecialchars($row['nama']); ?></td>
                        <td><code><?= htmlspecialchars($row['username']); ?></code></td>
                        <td>
                            <?php if ($row['role'] == 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-success">User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center gap-2 m-0">
                                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                                <select name="role" class="form-select form-select-sm admin-action-select">
                                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" name="ubah_role" class="btn btn-sm btn-dark">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>