<?php
// Admin Layout Helper - Include this after <body> tag
?>
<!-- Mobile Menu Toggle Button -->
<button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

<!-- Sidebar Navigation -->
<nav class="sidebar" id="adminSidebar" role="navigation">
    <div class="sidebar-brand">
        <i class="bi bi-geo-alt-fill"></i> Desa Natar
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
        </a></li>
        <li><a href="manage-profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-profil.php' ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> <span>Profil Desa</span>
        </a></li>
        <li><a href="manage-struktur.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-struktur.php' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> <span>Perangkat Desa</span>
        </a></li>
        <li><a href="manage-bpd.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-bpd.php' ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> <span>BPD</span>
        </a></li>
        <li><a href="manage-lpmd.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-lpmd.php' ? 'active' : '' ?>">
            <i class="bi bi-diagram-3"></i> <span>LPMD</span>
        </a></li>
        <li><a href="manage-rt.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-rt.php' ? 'active' : '' ?>">
            <i class="bi bi-house"></i> <span>RT</span>
        </a></li>
        <li><a href="manage-berita.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-berita.php' ? 'active' : '' ?>">
            <i class="bi bi-journal-text"></i> <span>Kelola Berita</span>
        </a></li>
        <li><a href="manage-apbdesa.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-apbdesa.php' ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i> <span>APB Desa</span>
        </a></li>
        <li><a href="manage-potensi.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-potensi.php' ? 'active' : '' ?>">
            <i class="bi bi-map"></i> <span>Potensi Desa</span>
        </a></li>
        <li><a href="manage-sarana.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-sarana.php' ? 'active' : '' ?>">
            <i class="bi bi-building"></i> <span>Sarana & Prasarana</span>
        </a></li>
        <li><a href="manage-pengaduan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-pengaduan.php' ? 'active' : '' ?>">
            <i class="bi bi-megaphone-fill"></i> <span>Pengaduan</span>
        </a></li>
        <li><a href="manage-prosedur.php" class="<?= strpos(basename($_SERVER['PHP_SELF']), 'manage-prosedur') !== false ? 'active' : '' ?>">
            <i class="bi bi-card-checklist"></i> <span>Prosedur</span>
        </a></li>
        <li><a href="manage-kontak.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-kontak.php' ? 'active' : '' ?>">
            <i class="bi bi-telephone"></i> <span>Kontak</span>
        </a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Keluar</span></a>
    </div>
</nav>

<script>
// Mobile Menu Toggle Script - Optimized Version
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebar = document.getElementById('adminSidebar');
        
        if (!toggleBtn || !overlay || !sidebar) {
            console.warn('Sidebar elements not found');
            return;
        }
        
        let isAnimating = false;
        let isOpen = false;
        
        // Toggle sidebar function
        function toggleSidebar(e) {
            if (isAnimating) return;
            
            isAnimating = true;
            if (e) e.stopPropagation();
            
            isOpen = !isOpen;
            
            sidebar.classList.toggle('active', isOpen);
            overlay.classList.toggle('active', isOpen);
            overlay.setAttribute('aria-hidden', !isOpen);
            
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
            }
            
            // Prevent body scroll when sidebar open on mobile
            if (window.innerWidth <= 992) {
                document.body.style.overflow = isOpen ? 'hidden' : '';
            }
            
            setTimeout(() => {
                isAnimating = false;
            }, 350);
        }
        
        // Close sidebar function
        function closeSidebar() {
            if (isOpen && window.innerWidth <= 992) {
                toggleSidebar();
            }
        }
        
        // Event listeners
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            toggleSidebar(e);
        });
        
        overlay.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isOpen) {
                toggleSidebar(e);
            }
        });
        
        // Prevent sidebar clicks from closing
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Close sidebar when clicking a menu item on mobile
        const menuLinks = sidebar.querySelectorAll('.sidebar-menu a, .logout-section a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992 && isOpen) {
                    setTimeout(closeSidebar, 200);
                }
            });
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                    isOpen = false;
                    const icon = toggleBtn.querySelector('i');
                    if (icon) icon.className = 'bi bi-list';
                }
            }, 250);
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeSidebar();
            }
        });
    });
})();
</script>
