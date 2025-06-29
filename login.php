<?php
session_start();
include "koneksi.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $db->prepare("SELECT * FROM form_login WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 350px;
            margin: 100px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            color: #222;
        }
        input[type="text"], input[type="password"] {
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
        <h2>Login</h2>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($_GET['daftar']) && $_GET['daftar'] == 'success') echo "<div class='success'>Pendaftaran berhasil! Silakan login.</div>"; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" id="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="link">
            Belum punya akun? <a href="formdaftar.php">Daftar</a>
        </div>
    </div>
</body>
</html>