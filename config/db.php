<?php
// Pengaturan Database
$host = "localhost";
$user = "root";      // Username default XAMPP
$pass = "";          // Password default XAMPP (kosong)
$db   = "db_natar2";
$port = 3307;        // Port MySQL XAMPP (sesuai XAMPP Control Panel)

// Nonaktifkan error reporting untuk mysqli
mysqli_report(MYSQLI_REPORT_OFF);

// Membuat koneksi
$conn = @mysqli_connect($host, $user, $pass, $db, $port);

// Cek Koneksi - jangan die, biarkan jalan dengan warning saja
if (!$conn) {
    $conn = null;
    error_log("WARNING: Database tidak terkoneksi - " . mysqli_connect_error());
} else {
    // Set charset ke utf8 agar karakter khusus tampil benar
    mysqli_set_charset($conn, "utf8");
}
?>