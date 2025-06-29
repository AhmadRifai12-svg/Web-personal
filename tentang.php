<?php
session_start();
include "koneksi.php";

// Ambil foto profil user (untuk navbar)
$profile_picture = "default-profile.png";
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $db->prepare("SELECT profile_picture FROM form_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($profile_picture_db);
    $stmt->fetch();
    $stmt->close();
    if (!empty($profile_picture_db) && file_exists($profile_picture_db)) {
        $profile_picture = $profile_picture_db;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tentang Kami - Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
        }
        .profile-navbar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-left: 18px;
            transition: box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
        }
        .profile-navbar:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        }
        .social-icons a {
            font-size: 2rem;
            margin: 0 10px;
            color: #0d6efd;
            transition: color 0.2s;
        }
        .social-icons a:hover {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="home.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="katalog.php">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="pinjam.php">Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link active" href="tentang.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Logout</a></li>
                    <li class="nav-item">
                        <a href="Profil.php" title="Profil">
                            <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-navbar" alt="Profil">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tentang Kami -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Tentang Kami</h2>
                <p class="lead text-muted">
                    Perpustakaan Digital adalah platform perpustakaan online yang menyediakan ribuan koleksi buku dari berbagai genre untuk seluruh masyarakat Indonesia. 
                    Kami berkomitmen untuk memudahkan akses literasi dan meningkatkan minat baca melalui layanan digital yang mudah, cepat, dan gratis.
                </p>
            </div>
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm p-4">
                        <h4 class="mb-3">Visi & Misi</h4>
                        <ul>
                            <li>Meningkatkan literasi dan minat baca masyarakat Indonesia.</li>
                            <li>Menyediakan akses buku berkualitas tanpa batas ruang dan waktu.</li>
                            <li>Mendukung pembelajaran dan pengembangan diri melalui koleksi digital.</li>
                        </ul>
                        <h4 class="mt-4 mb-3">Tim Kami</h4>
                        <ul>
                            <li>Faii - Founder & Developer</li>
                            <li>Tim Perpustakaan Digital</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <h5>Ikuti Kami di Sosial Media</h5>
                <div class="social-icons mt-2">
                    <a href="https://facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://instagram.com/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://youtube.com/" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>