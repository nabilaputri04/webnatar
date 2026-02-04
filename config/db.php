<?php
// Pengaturan Database
$host = "localhost";
$user = "u855675680_useruntuknatar";      // Username default XAMPP
$pass = "Desanatarmaju123";          // Password default XAMPP (kosong)
$db   = "u855675680_db_natar2";

// Nonaktifkan error reporting untuk mysqli
mysqli_report(MYSQLI_REPORT_OFF);

// Membuat koneksi
$conn = @mysqli_connect($host, $user, $pass, $db, $port);

// Cek Koneksi - jangan die, biarkan jalan dengan warning saja
if (!$conn) {
    $conn = null;
    error_log("WARNING: Database tidak terkoneksi - " . mysqli_connect_error());
} else {
    // Set charset ke utf8mb4 agar karakter khusus tampil benar dan mendukung emoji
    mysqli_set_charset($conn, "utf8mb4");
}
?>