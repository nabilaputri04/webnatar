<?php
// Hapus session lama yang corrupt
@session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
// Mulai session baru
session_start();

require '../config/db.php';

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit;
}

$error = false;
if (isset($_POST['login'])) {
    // Cek koneksi database
    if (!$conn) {
        $error = "Database tidak terhubung. Pastikan MySQL sudah berjalan.";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['status'] = "login";
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['nama_admin'] = $row['nama_lengkap'];
                header("Location: index.php");
                exit;
            }
        }
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Natar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-gray-100">
    <div class="text-center mb-8">
        <div class="bg-emerald-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600">
            <i class="bi bi-shield-lock-fill text-3xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800">Admin Login</h3>
        <p class="text-gray-500 text-sm mt-1">Sistem Informasi Desa Natar</p>
    </div>

    <?php if ($error === true) : ?>
        <div class="bg-red-50 text-red-600 text-sm py-3 px-4 rounded-lg mb-6 text-center flex items-center justify-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i> Username atau password salah!
        </div>
    <?php elseif ($error) : ?>
        <div class="bg-yellow-50 text-yellow-700 text-sm py-3 px-4 rounded-lg mb-6 text-center flex items-center justify-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
            <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-gray-50 focus:bg-white" placeholder="Masukkan username" required autocomplete="off">
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-gray-50 focus:bg-white" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            Masuk Dashboard
        </button>
    </form>

    <div class="text-center mt-8 text-xs text-gray-400">
        &copy; 2026 Desa Natar &bull; KKN Regular Universitas Lampung
    </div>
</div>

</body>
</html>