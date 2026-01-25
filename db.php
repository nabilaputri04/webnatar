<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_natar2";
$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>