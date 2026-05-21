<?php
// Lokasi: includes/navbar.php
$base_url = defined('BASE_URL') ? BASE_URL : '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
<a class="navbar-brand fw-bold bg-white px-1 py-1 rounded-4 d-inline-flex align-items-center" href="<?= $base_url ?>index.php" style="height: 40px;">
    <img src="<?= $base_url ?>assets/img/StudentFolio-removebg-preview.png" alt="Logo" class="logo-navbar" style="height: 100px; width: auto;">
</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>index.php">Beranda</a>
                </li>

                <?php if (isset($_SESSION['id_user']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>admin/dashboard.php">Dashboard Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>admin/kelola_user.php">Kelola Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>admin/kelola_kategori.php">Kelola Kategori</a></li>
                <?php elseif (isset($_SESSION['id_user']) && $_SESSION['role'] === 'user'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>user/dashboard.php">Portfolio Saya</a></li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['id_user'])):
                    $foto = !empty($_SESSION['foto_profil']) ? $_SESSION['foto_profil'] : 'default.png';
                ?>
                    <li class="nav-item d-flex align-items-center me-2">
                        <img src="<?= $base_url ?>uploads/profil/<?= htmlspecialchars($foto); ?>" class="avatar-sm rounded-circle">
                    </li>
                    <li class="nav-item me-3 text-white d-none d-lg-inline">
                        Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-danger" href="<?= $base_url ?>logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="<?= $base_url ?>login.php">Login</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-sm btn-light text-primary fw-bold" href="<?= $base_url ?>register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>