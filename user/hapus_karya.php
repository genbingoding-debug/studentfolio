<?php
// Lokasi: user/hapus_karya.php
require '../includes/functions.php';
require_user();

$id_portfolio = $_GET['id'] ?? 0;
$id_user = $_SESSION['id_user'];

// 1. Cari nama file bukti untuk dihapus dari folder
$stmt = $conn->prepare("SELECT file_bukti FROM portfolio_data WHERE id_portfolio = ? AND id_user = ?");
$stmt->bind_param('ii', $id_portfolio, $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $file_path = "../uploads/bukti_karya/" . $data['file_bukti'];

    // 2. Hapus file fisik jika benar-benar ada
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // 3. Hapus baris data dari database
    $stmt = $conn->prepare("DELETE FROM portfolio_data WHERE id_portfolio = ? AND id_user = ?");
    $stmt->bind_param('ii', $id_portfolio, $id_user);
    $stmt->execute();
}

// Tendang balik ke dashboard
redirect('dashboard.php');
?>