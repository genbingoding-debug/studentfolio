<?php
// Lokasi: includes/functions.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

function redirect($target) {
    header("Location: $target");
    exit;
}

function require_role($role, $redirectTarget = 'index.php') {
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== $role) {
        redirect($redirectTarget);
    }
}

function require_admin($redirectTarget = '../index.php') {
    require_role('admin', $redirectTarget);
}

function require_user($redirectTarget = '../index.php') {
    require_role('user', $redirectTarget);
}

function esc($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_user() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function current_user_name() {
    return isset($_SESSION['nama']) ? esc($_SESSION['nama']) : '';
}

function current_user_photo() {
    return isset($_SESSION['foto_profil']) && !empty($_SESSION['foto_profil']) ? esc($_SESSION['foto_profil']) : 'default.png';
}

function fetch_categories($conn) {
    $stmt = $conn->prepare("SELECT id_kategori, nama_kategori FROM kategori_portfolio ORDER BY nama_kategori ASC");
    $stmt->execute();
    return $stmt->get_result();
}

function has_categories($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM kategori_portfolio");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return isset($row['total']) && intval($row['total']) > 0;
}
