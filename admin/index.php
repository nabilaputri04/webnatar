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

// Hitung sarana & prasarana
$q_sarana = mysqli_query($conn, "SELECT COUNT(*) as count FROM sarana_prasarana");
$jml_sarana = $q_sarana ? mysqli_fetch_assoc($q_sarana)['count'] : 0;

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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dashboard Admin - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); --active-blue: #10b981; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%); margin: 0; }
        
        /* Animated Background Blobs */
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -20px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(20px, 20px) scale(1.05); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.25rem;
            padding: 0 20px 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        .sidebar-menu::-webkit-scrollbar { width: 6px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-menu a i { font-size: 1.2rem; }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .logout-section {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 280px;
            padding: 20px 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.2) 100%);
            flex-shrink: 0;
            z-index: 9999;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .logout-section a i { font-size: 1.2rem; }
        .logout-section a:hover {
            background: rgba(239, 68, 68, 0.15);
            transform: translateX(5px);
        }
        .main-content { 
            margin-left: 280px; 
            padding: 40px;
            position: relative;
            min-height: 100vh;
            z-index: 1;
            width: calc(100% - 280px);
        }
        /* Animated Background Decorations */
        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            animation: blob 10s infinite;
            z-index: 0;
        }
        .main-content::after {
            content: '';
            position: fixed;
            bottom: 0;
            left: 280px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            animation: blob 12s infinite;
            animation-delay: 2s;
            z-index: 0;
        }
        .card {
            position: relative;
            z-index: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
        }
        .display-4 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }
        
        /* ========== RESPONSIVE DESIGN FOR MOBILE ========== */
        /* Mobile Menu Toggle Button */
        .mobile-menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 10000;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s;
        }
        .mobile-menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        .mobile-menu-toggle i {
            font-size: 1.5rem;
        }
        
        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
            -webkit-backdrop-filter: blur(2px);
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active {
            opacity: 1;
        }
        
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        
        @media (max-width: 992px) {
            /* Hide sidebar by default on mobile */
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
                box-shadow: 6px 0 30px rgba(0, 0, 0, 0.3);
            }
            
            /* Show mobile menu toggle */
            .mobile-menu-toggle {
                display: flex;
            }
            .sidebar-overlay.active {
                display: block;
            }
            
            /* Adjust main content */
            .main-content {
                margin-left: 0 !important;
                padding: 85px 20px 20px 20px !important;
                width: 100% !important;
            }
            
            /* Adjust logout section */
            .logout-section {
                width: 280px;
            }
            
            /* Hide "Lihat Website" button text on mobile */
            .btn-outline-primary .d-none-mobile {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            /* Further adjustments for smaller phones */
            .main-content {
                padding: 75px 16px 16px 16px !important;
            }
            
            h2.fw-bold, h1 {
                font-size: 1.35rem !important;
            }
            
            .display-4 {
                font-size: 1.75rem !important;
            }
            
            .btn {
                min-height: 44px;
                padding: 0.625rem 1rem !important;
            }
            
            /* Stack action buttons */
            .d-flex.justify-content-between {
                flex-direction: column !important;
                gap: 1rem;
            }
            
            .btn-outline-primary {
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            /* Extra small devices */
            .mobile-menu-toggle {
                width: 44px;
                height: 44px;
                top: 12px;
                left: 12px;
            }
            
            .main-content {
                padding: 68px 12px 12px 12px !important;
            }
            
            h2.fw-bold, h1 {
                font-size: 1.25rem !important;
            }
            
            .display-4 {
                font-size: 1.5rem !important;
            }
            
            .card-body {
                padding: 1rem !important;
            }
            
            .small {
                font-size: 0.75rem !important;
            }
        }
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
        <li><a href="manage-sarana.php"><i class="bi bi-building"></i> Sarana & Prasarana</a></li>
        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <div class="d-inline-block mb-3">
                    <span class="badge rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); font-size: 0.85rem; font-weight: 600;">
                        <i class="bi bi-speedometer2 me-2"></i>Panel Administrator
                    </span>
                </div>
                <h2 class="fw-bold mb-2" style="font-size: 2.5rem; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Dashboard Overview</h2>
                <p class="text-muted mb-0 fs-5">Selamat datang kembali, <strong style="color: #10b981;"><?= $_SESSION['nama_admin'] ?? 'Administrator Desa!'; ?></strong></p>
            </div>
            <a href="../index.php" target="_blank" class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm" style="font-weight: 600; transition: all 0.3s;">
                <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Website
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card 1 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Total Berita</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_berita; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-newspaper text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <div class="progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Perangkat Desa</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_perangkat; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-people text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <div class="progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Potensi Desa</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_potensi; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <i class="bi bi-gem text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <div class="progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 4 - Sarana & Prasarana -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Sarana & Prasarana</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_sarana; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="bi bi-building text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <div class="progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan Statistics -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Total Pengaduan</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_pengaduan; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                            <i class="bi bi-megaphone text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <a href="manage-pengaduan.php" class="btn btn-sm w-100 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; font-weight: 600;">
                        <i class="bi bi-arrow-right-circle me-2"></i>Kelola Pengaduan
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase mb-2 fw-semibold" style="letter-spacing: 1px;">Pengaduan Baru</p>
                            <h1 class="fw-bold mb-0 display-4"><?= $jml_pengaduan_baru; ?></h1>
                        </div>
                        <div class="p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="bi bi-bell text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <?php if($jml_pengaduan_baru > 0): ?>
                    <div class="alert alert-warning mb-0 rounded-pill py-2 px-3 d-flex align-items-center" style="border: none; background: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <small class="fw-semibold">Tidak ada pengaduan baru</small>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-success mb-0 rounded-pill py-2 px-3 d-flex align-items-center" style="border: none; background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <small class="fw-semibold">Semua pengaduan sudah ditangani</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile Menu Toggle Script
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    
    // Check if sidebar exists
    if (!sidebar) return;
    
    // Create overlay element
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    // Create mobile menu toggle button
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'mobile-menu-toggle';
    toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
    document.body.appendChild(toggleBtn);
    
    let isAnimating = false;
    
    // Toggle sidebar
    function toggleSidebar(e) {
        if (isAnimating) return;
        isAnimating = true;
        
        if (e) e.stopPropagation();
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        const icon = toggleBtn.querySelector('i');
        if (sidebar.classList.contains('active')) {
            icon.className = 'bi bi-x-lg';
        } else {
            icon.className = 'bi bi-list';
        }
        
        setTimeout(() => {
            isAnimating = false;
        }, 300);
    }
    
    // Event listeners
    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleSidebar(e);
    });
    
    overlay.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('active')) {
            toggleSidebar(e);
        }
    });
    
    // Prevent sidebar clicks from closing
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close sidebar when clicking a menu item on mobile
    const menuLinks = document.querySelectorAll('.sidebar-menu a, .logout-section a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                setTimeout(() => toggleSidebar(), 150);
            }
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            toggleBtn.querySelector('i').className = 'bi bi-list';
        }
    });
});
</script>
</body>
</html>