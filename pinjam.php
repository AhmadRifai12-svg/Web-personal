<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT id, profile_picture, role FROM form_login WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id, $profile_picture_db, $role);
$stmt->fetch();
$stmt->close();
$profile_picture = (!empty($profile_picture_db) && file_exists($profile_picture_db)) ? $profile_picture_db : "default-profile.png";

// Batasi akses hanya untuk staf
if ($role !== 'staf') {
    echo "<script>alert('Halaman ini hanya bisa diakses oleh staf!');window.location='home.php';</script>";
    exit();
}

// Proses pengembalian
if (isset($_GET['kembali'])) {
    $id_peminjaman = intval($_GET['kembali']);
    $stmt = $db->prepare("UPDATE peminjaman SET status='Dikembalikan' WHERE id_peminjaman=? AND id_user=?");
    $stmt->bind_param("ii", $id_peminjaman, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: pinjam.php"); // Ganti sesuai nama file kamu
    exit();
}

// Ambil data peminjaman user
$peminjaman = [];
$q = $db->prepare("SELECT p.id_peminjaman, b.judul, b.Penulis, p.tanggal_pinjam, p.tanggal_kembali, p.status
                   FROM peminjaman p JOIN buku b ON p.id_buku = b.id_buku
                   WHERE p.id_user=? ORDER BY p.id_peminjaman DESC");
$q->bind_param("i", $user_id);
$q->execute();
$q->bind_result($id_peminjaman, $judul, $Penulis, $tgl_pinjam, $tgl_kembali, $status);
while ($q->fetch()) {
    $peminjaman[] = [
        'id_peminjaman' => $id_peminjaman,
        'judul' => $judul,
        'penulis' => $Penulis, // gunakan $Penulis sesuai bind_result
        'tanggal_pinjam' => $tgl_pinjam,
        'tanggal_kembali' => $tgl_kembali,
        'status' => $status
    ];
}
$q->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Buku</title>
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
        .status-dipinjam { color: #0d6efd; font-weight: bold; }
        .status-dikembalikan { color: #28a745; font-weight: bold; }
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
                    <li class="nav-item"><a class="nav-link active" href="pinjam.php">Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang Kami</a></li>
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

    <!-- Daftar Peminjaman -->
    <section class="py-5">
        <div class="container">
            <h2 class="fw-bold mb-4 text-center">Daftar Peminjaman Buku</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($peminjaman) > 0): ?>
                            <?php foreach ($peminjaman as $i => $p): ?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo htmlspecialchars($p['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($p['penulis']); ?></td>
                                    <td><?php echo htmlspecialchars($p['tanggal_pinjam']); ?></td>
                                    <td><?php echo htmlspecialchars($p['tanggal_kembali']); ?></td>
                                    <td>
                                        <?php if ($p['status'] == "Dipinjam"): ?>
                                            <span class="status-dipinjam"><?php echo $p['status']; ?></span>
                                        <?php else: ?>
                                            <span class="status-dikembalikan"><?php echo $p['status']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] == "Dipinjam"): ?>
                                            <a href="?kembali=<?php echo $p['id_peminjaman']; ?>" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Kembalikan</a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada peminjaman buku.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>