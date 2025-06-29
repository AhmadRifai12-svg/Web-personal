<?php
session_start();
include "koneksi.php";

// Cek login dan ambil foto profil user
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
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontak Kami - Perpustakaan Digital</title>
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
                    <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link active" href="kontak.php">Kontak</a></li>
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

    <!-- Kontak Kami -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kontak Kami</h2>
                <p class="lead text-muted">
                    Jika Anda memiliki pertanyaan, kritik, atau saran, silakan hubungi kami melalui form di bawah ini atau melalui sosial media kami.
                </p>
            </div>
            <div class="row justify-content-center mb-4">
                <div class="col-md-7">
                    <div class="card shadow-sm p-4">
                        <form>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" placeholder="Nama Anda" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="email@contoh.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="pesan" class="form-label">Pesan</label>
                                <textarea class="form-control" id="pesan" rows="4" placeholder="Tulis pesan Anda..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <h5>Atau hubungi kami di sosial media:</h5>
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