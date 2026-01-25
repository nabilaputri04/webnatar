<?php
session_start();

// 1. Menghapus semua data session yang tersimpan
$_SESSION = [];

// 2. Menghentikan session secara total di server
session_unset();
session_destroy();

// 3. Mengarahkan kembali admin ke halaman login
header("Location: login.php");
exit;
?>