<?php
// dit is het scherm dat je ziet als de kiosk niet gebruikt wordt
// als je ergens op klikt ga je naar het startscherm
require_once 'includes/init.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore Kiosk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-pagina="idle">

<div class="idle-scherm" id="idle-scherm">
    <video class="idle-video" autoplay loop muted playsinline preload="auto">
        <source src="assets/video/idle.mp4" type="video/mp4">
    </video>
    <div class="idle-overlay"></div>
    <div class="idle-content">
        <img src="assets/images/logo_complete.png" alt="Happy Herbivore" class="idle-logo">
        <h1 class="idle-welkom"><?= htmlspecialchars($t['welkom']) ?></h1>
        <p class="idle-slogan"><?= htmlspecialchars($t['slogan']) ?></p>
        <div class="idle-start-btn">
            <svg class="idle-tap-icon" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M20 4 L20 18"/>
                <path d="M26 9 C29 12 30 15 30 18 L30 27 C30 32 26 36 20 36 C14 36 10 32 10 27 L10 18 C10 15 11 12 14 9"/>
                <circle cx="20" cy="18" r="3.5" fill="currentColor"/>
            </svg>
            <span><?= htmlspecialchars($t['tik_start']) ?></span>
        </div>
    </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
