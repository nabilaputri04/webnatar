<?php
/**
 * Auto Create Table Pengaduan
 * Jalankan file ini untuk membuat tabel pengaduan secara otomatis
 * Akses: http://localhost/Website-Desa-Natar2/create-table-pengaduan.php
 */

require 'config/db.php';

if (!$conn) {
    die("Koneksi database gagal. Pastikan MySQL sudah running.");
}

// SQL untuk membuat tabel pengaduan
$sql = "CREATE TABLE IF NOT EXISTS pengaduan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(20),
    judul VARCHAR(255) NOT NULL,
    isi_pengaduan TEXT NOT NULL,
    kategori ENUM('Infrastruktur', 'Pelayanan', 'Administrasi', 'Kebersihan', 'Keamanan', 'Lainnya') DEFAULT 'Lainnya',
    status ENUM('Baru', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Baru',
    tanggapan TEXT,
    tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_diupdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$success = false;
$error = null;

if (mysqli_query($conn, $sql)) {
    $success = true;
} else {
    $error = mysqli_error($conn);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Table Pengaduan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl">
        <?php if($success): ?>
            <div class="bg-green-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-check-circle-fill text-green-600 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3 text-center">Tabel Berhasil Dibuat!</h2>
            <p class="text-gray-600 mb-6 text-center">Tabel <code class="bg-gray-100 px-2 py-1 rounded">pengaduan</code> telah berhasil dibuat di database.</p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-800 font-semibold mb-2">✓ Tabel sudah siap digunakan!</p>
                <p class="text-blue-700 text-sm">Sekarang Anda dapat:</p>
                <ul class="text-blue-700 text-sm mt-2 space-y-1 ml-4">
                    <li>• Mengakses form pengaduan di halaman kontak</li>
                    <li>• Mengelola pengaduan di admin panel</li>
                    <li>• Melihat statistik pengaduan di dashboard</li>
                </ul>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="kontak.php" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3 rounded-lg transition text-center">
                    <i class="bi bi-ui-checks me-2"></i>Form Pengaduan
                </a>
                <a href="admin/manage-pengaduan.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition text-center">
                    <i class="bi bi-speedometer2 me-2"></i>Admin Panel
                </a>
            </div>
        <?php else: ?>
            <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-x-circle-fill text-red-600 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3 text-center">Error Membuat Tabel</h2>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <p class="text-red-800 font-semibold mb-2">Error:</p>
                <code class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></code>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <p class="text-yellow-800 font-semibold mb-2">Solusi:</p>
                <ol class="text-yellow-700 text-sm space-y-1 ml-4">
                    <li>1. Pastikan MySQL sudah running (cek XAMPP)</li>
                    <li>2. Pastikan database <code>db_natar2</code> sudah ada</li>
                    <li>3. Cek koneksi database di <code>config/db.php</code></li>
                    <li>4. Pastikan user MySQL memiliki hak akses CREATE TABLE</li>
                </ol>
            </div>

            <a href="create-table-pengaduan.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition w-full text-center">
                <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
            </a>
        <?php endif; ?>
    </div>
</body>
</html>
