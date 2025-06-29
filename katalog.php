<?php
session_start();
include "koneksi.php";

// Cek login dan ambil foto profil user
$profile_picture = "default-profile.png";
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $db->prepare("SELECT id, profile_picture FROM form_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $profile_picture_db);
    $stmt->fetch();
    $stmt->close();
    if (!empty($profile_picture_db) && file_exists($profile_picture_db)) {
        $profile_picture = $profile_picture_db;
    }
} else {
    header("Location: login.php");
    exit();
}

// Proses pinjam buku
if (isset($_GET['pinjam'])) {
    $id_buku = intval($_GET['pinjam']);
    // Cek apakah sudah dipinjam & belum dikembalikan
    $cek = $db->prepare("SELECT COUNT(*) FROM peminjaman WHERE id_user=? AND id_buku=? AND status='Dipinjam'");
    $cek->bind_param("ii", $user_id, $id_buku);
    $cek->execute();
    $cek->bind_result($sudah);
    $cek->fetch();
    $cek->close();
    if ($sudah == 0) {
        $tgl_pinjam = date('Y-m-d');
        $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
        $stmt = $db->prepare("INSERT INTO peminjaman (id_user, id_buku, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, 'Dipinjam')");
        $stmt->bind_param("iiss", $user_id, $id_buku, $tgl_pinjam, $tgl_kembali);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: katalog.php");
    exit();
}

// Ambil data buku dari database
$books = [];
$q = $db->query("SELECT * FROM buku");
while ($row = $q->fetch_assoc()) {
    $books[] = $row;
}

// Proses pencarian
$keyword = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
$filtered_books = [];
if ($keyword != '') {
    foreach ($books as $b) {
        if (strpos(strtolower($b['judul']), $keyword) !== false || strpos(strtolower($b['penulis']), $keyword) !== false) {
            $filtered_books[] = $b;
        }
    }
} else {
    $filtered_books = $books;
}

// Ambil daftar buku yang sedang dipinjam user
$dipinjam = [];
$q = $db->prepare("SELECT id_buku FROM peminjaman WHERE id_user=? AND status='Dipinjam'");
$q->bind_param("i", $user_id);
$q->execute();
$q->bind_result($id_buku_pinjam);
while ($q->fetch()) {
    $dipinjam[] = $id_buku_pinjam;
}
$q->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku</title>
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
        .book-card {
            height: 100%;
            transition: transform 0.3s;
        }
        .book-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .book-cover {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        .rating-star {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                    <li class="nav-item"><a class="nav-link active" href="katalog.php">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="pinjam.php">Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" onclick="return confirmLogout();">Logout</a>
                    </li>
                    <script>
                    function confirmLogout() {
                        return confirm('Apakah Anda yakin ingin logout?');
                    }
                    </script>
                    <li class="nav-item">
                        <a href="Profil.php" title="Profil">
                            <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-navbar" alt="Profil">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Katalog Buku -->
    <section class="py-5">
        <div class="container">
            <h2 class="fw-bold mb-4 text-center">Katalog Buku</h2>
            <div class="search-box mx-auto mb-4" style="max-width: 600px;">
                <form class="input-group" method="get" action="">
                    <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari judul buku atau penulis">
                    <?php if ($keyword): ?>
                        <button class="btn btn-outline-secondary" type="button" onclick="window.location.href='katalog.php'" title="Reset">
                            <i class="fas fa-times"></i>
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
            <div class="row g-4">
                <?php if (count($filtered_books) > 0): ?>
                    <?php foreach ($filtered_books as $b): ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="card book-card shadow-sm">
                                <img src="<?php echo htmlspecialchars($b['cover']); ?>" class="card-img-top book-cover" alt="Sampul buku <?php echo htmlspecialchars($b['judul']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($b['judul']); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($b['Penulis']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php
                                            $full = floor($b['rating']);
                                            $half = ($b['rating'] - $full) >= 0.5 ? 1 : 0;
                                            for ($i=0; $i<$full; $i++) echo '<i class="fas fa-star rating-star"></i>';
                                            if ($half) echo '<i class="fas fa-star-half-alt rating-star"></i>';
                                            for ($i=0; $i<5-$full-$half; $i++) echo '<i class="far fa-star rating-star"></i>';
                                            ?>
                                        </div>
                                        <a href="?pinjam=<?php echo $b['id_buku']; ?>" class="btn btn-sm btn-outline-primary"
                                           <?php echo in_array($b['id_buku'], $dipinjam) ? 'disabled' : ''; ?>>
                                           Pinjam
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">Buku tidak ditemukan.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>