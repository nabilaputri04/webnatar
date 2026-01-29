<?php
require 'auth_check.php';
require '../config/db.php';

// Hitung Data untuk Statistik Dashboard
$jml_berita = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM berita"));
// Hitung total perangkat dari semua tabel (perangkat_desa + bpd + lpmd + rt)
$jml_perangkat_desa = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM perangkat_desa"));
$jml_bpd = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bpd"));
$jml_lpmd = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM lpmd"));
$jml_rt = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM rt"));
$jml_perangkat = $jml_perangkat_desa + $jml_bpd + $jml_lpmd + $jml_rt;
$jml_potensi = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM potensi_desa"));

// Hitung statistik pengaduan
$q_pengaduan = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaduan");
$jml_pengaduan = $q_pengaduan ? mysqli_fetch_assoc($q_pengaduan)['count'] : 0;

$q_pengaduan_baru = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaduan WHERE status='Baru'");
$jml_pengaduan_baru = $q_pengaduan_baru ? mysqli_fetch_assoc($q_pengaduan_baru)['count'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }
        
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.25rem;
            padding: 0 20px 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid #2d3238;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-menu { 
            list-style: none; 
            padding: 0 15px; 
            padding-bottom: 80px;
            margin: 0; 
            flex-grow: 1;
            overflow-y: auto;
        }
        .sidebar-menu li { margin-bottom: 6px; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-menu a i { font-size: 1.2rem; }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: var(--active-blue);
            color: white;
        }
        .logout-section {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 280px;
            padding: 20px 15px;
            border-top: 1px solid #2d3238;
            background: var(--sidebar-bg);
            flex-shrink: 0;
        }
        .logout-section a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #ef4444;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .logout-section a i { font-size: 1.2rem; }
        .logout-section a:hover {
            background: rgba(239, 68, 68, 0.1);
        }
        .main-content { margin-left: 280px; padding: 40px; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="active"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
        <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
        <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>
        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Overview</h3>
            <p class="text-muted mb-0">Selamat datang kembali, <strong style="color: #10b981;"><?= $_SESSION['nama_admin'] ?? 'Admin'; ?></strong>!</p>
        </div>
        <a href="../index.php" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">Lihat Website <i class="bi bi-box-arrow-up-right ms-1"></i></a>
    </div>

    <div class="row g-0">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2">Total Berita</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_berita; ?></h1>
                        </div>
                        <div class="bg-success bg-opacity-10 p-4 rounded">
                            <i class="bi bi-newspaper text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2">Perangkat Desa</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_perangkat; ?></h1>
                        </div>
                        <div class="bg-success bg-opacity-10 p-4 rounded">
                            <i class="bi bi-people text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2">Potensi Desa</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_potensi; ?></h1>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-4 rounded">
                            <i class="bi bi-gem text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan Statistics -->
    <div class="row g-0 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2">Total Pengaduan</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_pengaduan; ?></h1>
                        </div>
                        <div class="bg-info bg-opacity-10 p-4 rounded">
                            <i class="bi bi-megaphone-fill text-info fs-2"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="manage-pengaduan.php" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-arrow-right-circle me-1"></i>Kelola Pengaduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2">Pengaduan Baru</p>
                            <h1 class="fw-bold mb-0 display-4 text-warning"><?= $jml_pengaduan_baru; ?></h1>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-4 rounded">
                            <i class="bi bi-exclamation-circle-fill text-warning fs-2"></i>
                        </div>
                    </div>
                    <?php if($jml_pengaduan_baru > 0): ?>
                        <div class="mt-3">
                            <a href="manage-pengaduan.php" class="btn btn-sm btn-warning text-dark">
                                <i class="bi bi-bell-fill me-1"></i>Lihat Sekarang
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-3">
                            <span class="badge bg-success">Tidak ada pengaduan baru</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>