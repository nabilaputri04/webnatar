<?php
require 'auth_check.php';
require '../config/db.php';

// Hitung Data untuk Statistik Dashboard
$jml_berita = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM berita"));
$jml_layanan = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM layanan"));
// Hitung total perangkat dari semua tabel (perangkat_desa + bpd + lpmd + rt)
$jml_perangkat_desa = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM perangkat_desa"));
$jml_bpd = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bpd"));
$jml_lpmd = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM lpmd"));
$jml_rt = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM rt"));
$jml_perangkat = $jml_perangkat_desa + $jml_bpd + $jml_lpmd + $jml_rt;
$jml_potensi = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM potensi_desa"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Desa Natar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-active { transform: translateX(0) !important; }
    </style>
</head>
<body class="bg-gray-50">

<!-- Mobile Header -->
<div class="md:hidden flex justify-between items-center bg-white p-4 border-b sticky top-0 z-50">
    <span class="font-bold text-emerald-600 text-lg">Admin Desa Natar</span>
    <button id="sidebarToggle" class="text-gray-600 focus:outline-none">
        <i class="bi bi-list text-2xl"></i>
    </button>
</div>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <nav id="sidebar" class="w-64 bg-gray-900 text-gray-400 h-screen fixed left-0 top-0 flex flex-col p-4 transition-transform transform -translate-x-full md:translate-x-0 z-50 overflow-y-auto">
        <div class="text-emerald-500 font-bold text-xl mb-8 px-2 flex items-center gap-2">
            <i class="bi bi-geo-alt-fill"></i> Desa Natar
        </div>
        
        <ul class="flex-1 space-y-1">
            <li><a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-900/50"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-kontak.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="mt-auto pt-6 border-t border-gray-800">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-900/20 hover:text-red-300 transition"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-6 md:p-10 transition-all">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Dashboard Overview</h3>
                <p class="text-gray-500">Selamat datang kembali, <strong class="text-emerald-600"><?= $_SESSION['nama_admin'] ?? 'Admin'; ?></strong>!</p>
            </div>
            <a href="../index.php" target="_blank" class="flex items-center gap-2 px-5 py-2.5 rounded-full border border-emerald-600 text-emerald-600 font-medium hover:bg-emerald-50 transition"><i class="bi bi-box-arrow-up-right"></i> Lihat Website</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Berita</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?= $jml_berita; ?></h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="bi bi-newspaper"></i></div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Perangkat Desa</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?= $jml_perangkat; ?></h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="bi bi-people"></i></div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Layanan Publik</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?= $jml_layanan; ?></h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl"><i class="bi bi-file-earmark-text"></i></div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Potensi Desa</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?= $jml_potensi; ?></h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl"><i class="bi bi-gem"></i></div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-active');
        });
        
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('sidebar-active');
            }
        });
    }
</script>
</body>
</html>