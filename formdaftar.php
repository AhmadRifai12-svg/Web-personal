<?php
session_start();
include "koneksi.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $profile_picture = "default.jpg";

    // Jika staf, cek kode staf
    if ($role === "staf") {
        $kode_staf = $_POST["kode_staf"];
        // Ganti 'KODESTAF123' dengan kode staf yang kamu tentukan
        if ($kode_staf !== "KODESTAF123") {
            $error = "Kode staf salah!";
        }
    }

    // Jika tidak ada error, proses daftar
    if (!$error) {
        // Cek apakah username sudah ada
        $stmt = $db->prepare("SELECT * FROM form_login WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            $stmt = $db->prepare("INSERT INTO form_login (Username, Password, profile_picture, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $profile_picture, $role);
            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Silakan login.";
                header("Location: login.php?daftar=success");
                exit();
            } else {
                $error = "Pendaftaran gagal: " . $db->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Form Daftar</title>
  <style>
    body {
      background: #f4f4f4;
      font-family: Arial, sans-serif;
    }
    .container {
      width: 350px;
      margin: 80px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 30px 30px 20px 30px;
      text-align: center;
    }
    h2 {
      margin-bottom: 25px;
      color: #222;
    }
    input[type="text"], input[type="password"], select {
      width: 92%;
      padding: 10px;
      margin: 8px 0 18px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 15px;
      background: #fafafa;
    }
    button[type="submit"] {
      width: 100%;
      padding: 10px;
      background: #03a9f4;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-bottom: 10px;
      transition: background 0.2s;
    }
    button[type="submit"]:hover {
      background: #0288d1;
    }
    .link {
      margin-top: 10px;
      font-size: 14px;
    }
    .link a {
      color: #03a9f4;
      text-decoration: none;
    }
    .link a:hover {
      text-decoration: underline;
    }
    .error {
      color: #dc3545;
      margin-bottom: 10px;
    }
    .success {
      color: #28a745;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Daftar Akun</h2>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <form action="" method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <select name="role" id="role" onchange="toggleKodeStaf()" required>
        <option value="anggota">Murid</option>
        <option value="staf">Staf</option>
      </select><br>
      <div id="kodeStafDiv" style="display:none;">
        <input type="text" name="kode_staf" id="kode_staf" placeholder="Kode Staf">
        <div class="form-text" style="font-size:12px;color:#888;">Minta kode staf ke admin.</div>
      </div>
      <button type="submit">Daftar</button>
    </form>
    <div class="link">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
  </div>
  <script>
    function toggleKodeStaf() {
      var role = document.getElementById('role').value;
      document.getElementById('kodeStafDiv').style.display = (role === 'staf') ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleKodeStaf);
  </script>
</body>
</html>