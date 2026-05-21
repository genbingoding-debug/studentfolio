<?php
// Lokasi: user/hapus_karya.php
require '../includes/functions.php';
require_user();




$id_portfolio = $_GET['id'] ?? 0;
$id_user = $_SESSION['id_user'];

// $conn sudah di-load oleh includes/functions.php (melalui config/koneksi.php)

// 1) Ambil nama file bukti
$stmt = $conn->prepare(
    "SELECT file_bukti FROM portfolio_data WHERE id_portfolio = ? AND id_user = ?"
);
$stmt->bind_param('ii', $id_portfolio, $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $file_bukti = $data['file_bukti'] ?? '';

    $file_path = "../uploads/bukti_karya/" . $file_bukti;

    // 2) Hapus file fisik jika ada
    if (!empty($file_bukti) && file_exists($file_path)) {
        unlink($file_path);
    }

    // 3) Hapus baris data dari database
    $stmt = $conn->prepare(
        "DELETE FROM portfolio_data WHERE id_portfolio = ? AND id_user = ?"
    );
    $stmt->bind_param('ii', $id_portfolio, $id_user);
    $stmt->execute();
}

// Notifikasi informatif
$_SESSION['message'] = 'Karya berhasil dihapus.';
$_SESSION['message_type'] = 'success';

redirect('dashboard.php');
?>

