<?php
// Lokasi: includes/header.php

// Logika cerdas mendeteksi posisi folder
$current_folder = basename(dirname($_SERVER['PHP_SELF']));
$base_url = ($current_folder == 'admin' || $current_folder == 'user') ? "../" : "";
define('BASE_URL', $base_url);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentFolio - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/common.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/navbar.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/forms.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/cards.css" rel="stylesheet">
    <?php if (!empty($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
            <link href="<?= BASE_URL ?>assets/css/<?= htmlspecialchars($css); ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-light">

    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="container pb-5">