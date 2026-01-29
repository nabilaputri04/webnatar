<?php
/**
 * File untuk membersihkan session yang corrupt
 * Jalankan file ini sekali untuk menghapus semua session yang bermasalah
 * Akses: http://localhost/Website-Desa-Natar2/admin/clear-session.php
 */

// Hapus semua session
@session_start();
$_SESSION = array();

// Hapus cookie session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Hancurkan session
session_destroy();

// Hapus file session di server
$session_path = session_save_path();
if (!empty($session_path)) {
    $files = glob($session_path . '/sess_*');
    if ($files) {
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Cleared</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md text-center">
        <div class="bg-green-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-check-circle-fill text-green-600 text-4xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Session Berhasil Dibersihkan!</h2>
        <p class="text-gray-600 mb-6">Semua session yang corrupt telah dihapus. Silakan login kembali.</p>
        <a href="login.php" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-8 py-3 rounded-lg transition">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
        </a>
    </div>
</body>
</html>
<?php exit; ?>
