<?php
// Lokasi: config/koneksi.php

$host = "localhost";
$user = "root"; 
$pass = "";     // Sesuaikan jika ada password di XAMPP/MariaDB kamu
$db   = "StudentFolio";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>