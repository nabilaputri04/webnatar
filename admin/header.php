<?php
require_once __DIR__ . '/../config/db.php';

// Ambil Pengaturan Situs (Default jika kosong)
$q_set = mysqli_query($conn, "SELECT * FROM site_settings LIMIT 1");
$settings = mysqli_fetch_assoc($q_set) ?? ['judul_website' => 'Desa Natar 2', 'tagline' => 'Website Resmi Pemerintah Desa'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= $page_title ?? 'Beranda'; ?> - <?= $settings['judul_website']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose img { border-radius: 0.75rem; margin-top: 1.5rem; margin-bottom: 1.5rem; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-sm/50 backdrop-blur-md bg-white/90">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 group-hover:scale-105 transition duration-300">
                        <i class="bi bi-geo-alt-fill text-xl"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-gray-800 leading-none tracking-tight"><?= $settings['judul_website']; ?></h1>
                        <p class="text-xs text-gray-500 mt-1 font-medium">Kabupaten Lampung Selatan</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-1">
                    <a href="index.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Beranda</a>
                    <a href="profil.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Profil</a>
                    <a href="berita.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Berita</a>
                    <a href="potensi.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Potensi</a>
                    <a href="layanan.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Layanan</a>
                    <a href="apbdesa.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">APBDes</a>
                    <a href="unduhan.php" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition">Unduhan</a>
                    <a href="kontak.php" class="ml-3 px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5">Kontak</a>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="lg:hidden text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 p-4 space-y-2 shadow-xl absolute w-full left-0 top-20">
            <a href="index.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Beranda</a>
            <a href="profil.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Profil Desa</a>
            <a href="berita.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Berita & Artikel</a>
            <a href="potensi.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Potensi Desa</a>
            <a href="layanan.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Layanan Publik</a>
            <a href="apbdesa.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Transparansi APBDes</a>
            <a href="unduhan.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Pusat Unduhan</a>
            <a href="kontak.php" class="block px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-bold text-center mt-4">Hubungi Kami</a>
        </div>
    </nav>