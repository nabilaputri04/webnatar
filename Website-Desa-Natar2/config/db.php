<?php
// Pengaturan Database
$host = "localhost";
$user = "root";      // Username default XAMPP
$pass = "";          // Password default XAMPP (kosong)
$db   = "db_natar2";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8 agar karakter khusus tampil benar
mysqli_set_charset($conn, "utf8");
?>