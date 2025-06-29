<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .menu-container {
            max-width: 500px;
            margin: 80px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px 30px 30px 30px;
            text-align: center;
        }
        .menu-title {
            margin-bottom: 30px;
            color: #222;
        }
        .menu-list .btn {
            width: 100%;
            margin-bottom: 15px;
            font-size: 18px;
            padding: 12px;
        }
        .logo-kiri {
            position: absolute;
            top: 20px;
            left: 30px;
            width: 48px;
            height: 48px;
            z-index: 10;
        }
        .logo-kanan {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 48px;
            height: 48px;
            z-index: 10;
        }
    </style>
</head>
<body style="background:#f4f4f4;">
    <img src="logo-kiri.png" alt="Logo Kiri" class="logo-kiri">
    <img src="logo-kanan.png" alt="Logo Kanan" class="logo-kanan">
    <div class="menu-container">
        <h2 class="menu-title">Menu Utama</h2>
        <div class="menu-list">
            <a href="home.php" class="btn btn-primary"><i class="fas fa-home me-2"></i>Beranda</a>
            <a href="katalog.php" class="btn btn-success"><i class="fas fa-book me-2"></i>Katalog Buku</a>
            <a href="pinjam.php" class="btn btn-warning"><i class="fas fa-book-reader me-2"></i>Peminjaman</a>
            <a href="profil.php" class="btn btn-info"><i class="fas fa-user me-2"></i>Profil</a>
            <a href="login.php" class="btn btn-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>