<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt = $db->prepare("INSERT INTO form_login (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; // Set session
        header("Location: login.php");      // Redirect ke home
        exit();
    } else {
        echo "Data gagal: " . $db->error;
    }
    $stmt->close();
} else {
    header("Location: Home.php");
    exit();
}
?>