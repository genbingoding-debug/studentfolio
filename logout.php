<?php
// Lokasi: logout.php
session_start();

// Simpan pesan ke variabel sebelum menghancurkan sesi
$flash_msg = 'Anda berhasil keluar dari akun.';

session_unset();
session_destroy();

// Mulai sesi baru untuk menyimpan flash message
session_start();
$_SESSION['message'] = $flash_msg;
$_SESSION['message_type'] = 'success';

header("Location: login.php");
exit;
?>