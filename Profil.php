<?php
session_start();
include 'koneksi.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$profile_picture = "";

// Ambil data user
$stmt = $db->prepare("SELECT profile_picture FROM form_login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Proses upload foto profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $file = $_FILES["profile_picture"];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file['error'] == 0 && in_array($ext, $allowed)) {
        // Pastikan folder uploads/ ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $newname = "uploads/" . $username . "_" . time() . "." . $ext;
        if (move_uploaded_file($file['tmp_name'], $newname)) {
            // Update database
            $stmt = $db->prepare("UPDATE form_login SET profile_picture=? WHERE username=?");
            $stmt->bind_param("ss", $newname, $username);
            $stmt->execute();
            $stmt->close();
            $profile_picture = $newname;
            $success = "Foto profil berhasil diubah!";
        } else {
            $error = "Gagal upload file.";
        }
    } else {
        $error = "Format file tidak didukung!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna</title>
    <style>
        body { background: #f4f4f4; font-family: Arial, sans-serif; }
        .container {
            width: 350px; margin: 80px auto; background: #fff; border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px 30px 20px 30px; text-align: center;
        }
        h2 { color: #222; margin-bottom: 20px; }
        .profile-img {
            width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 2px solid #03a9f4; cursor:pointer;
            transition: opacity 0.2s;
        }
        .profile-img:hover { opacity: 0.7; }
        .btn {
            background: #03a9f4; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer;
        }
        .btn:hover { background: #0288d1; }
        .msg { margin: 10px 0; color: #28a745; }
        .error { margin: 10px 0; color: #dc3545; }
        .logout-link { margin-top: 20px; display: block; color: #03a9f4; text-decoration: none; }
        .logout-link:hover { text-decoration: underline; }
        #profileInput { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profil Pengguna</h2>
        <form method="POST" enctype="multipart/form-data" id="profileForm">
            <label for="profileInput">
                <?php if (!empty($profile_picture) && file_exists($profile_picture)): ?>
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-img" alt="Foto Profil" id="profilePic">
                <?php else: ?>
                <img src="default-profile.jpg" class="profile-img" alt="Default Foto Profil" id="profilePic">
                    <?php endif; ?>
            </label>
            <input type="file" name="profile_picture" id="profileInput" accept="image/*" onchange="document.getElementById('profileForm').submit();">
        </form>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <?php if (isset($success)) echo "<div class='msg'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <a href="home.php" class="logout-link">Kembali ke Home</a>
        <a href="login.php" class="logout-link" style="color:#dc3545;" onclick="return confirm('Apakah Anda yakin ingin logout?');">Logout</a>
    </div>
</body>
</html>