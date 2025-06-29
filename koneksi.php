<?php
$host = "localhost";
$user = "root";
$pass = ""; // kosongkan jika tidak ada password
$dbname = "db_login";

// Perbaikan: variabel $db harus menggunakan $host, $user, $pass, $dbname
$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Koneksi database gagal: " . $db->connect_error);
}
?>


