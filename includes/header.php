<?php
require_once __DIR__ . '/../config/db.php';

// Ambil Pengaturan Situs (Default jika kosong atau tabel belum ada)
$settings = ['judul_website' => 'Desa Natar', 'tagline' => 'Website Resmi Pemerintah Desa'];
if ($conn) {
    $q_set = @mysqli_query($conn, "SELECT * FROM site_settings LIMIT 1");
    if ($q_set && mysqli_num_rows($q_set) > 0) {
        $settings = mysqli_fetch_assoc($q_set);
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= isset($page_title) ? $page_title : 'Beranda'; ?> - <?= $settings['judul_website']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose img { border-radius: 0.75rem; margin-top: 1.5rem; margin-bottom: 1.5rem; }
        .text-justify { text-align: justify; }
        
        /* Dark Mode Styles - Comprehensive */
        .dark body { background-color: #0f172a; color: #e5e7eb; }
        
        /* Background Colors */
        .dark .bg-gray-50 { background-color: #1e293b; }
        .dark .bg-white { background-color: #1e293b; }
        .dark .bg-gray-100 { background-color: #334155; }
        .dark .bg-gray-800 { background-color: #0f172a; }
        .dark .bg-gray-900 { background-color: #020617; }
        .dark .bg-blue-50 { background-color: #1e3a5f; }
        .dark .bg-emerald-50 { background-color: #064e3b; }
        
        /* Text Colors */
        .dark .text-gray-800, .dark .text-gray-900 { color: #f1f5f9 !important; }
        .dark .text-gray-700 { color: #e2e8f0 !important; }
        .dark .text-gray-600 { color: #cbd5e1 !important; }
        .dark .text-gray-500 { color: #94a3b8 !important; }
        .dark .text-gray-400 { color: #64748b !important; }
        .dark .text-gray-300 { color: #cbd5e1 !important; }
        .dark .text-white { color: #f8fafc !important; }
        
        /* Border Colors */
        .dark .border-gray-100 { border-color: #334155; }
        .dark .border-gray-200 { border-color: #475569; }
        .dark .border-gray-300 { border-color: #64748b; }
        .dark .border-t { border-color: #334155; }
        .dark .border-b { border-color: #334155; }
        .dark .border-l { border-color: #334155; }
        .dark .border-r { border-color: #334155; }
        
        /* Navigation */
        .dark nav { 
            background-color: rgba(15, 23, 42, 0.95) !important; 
            border-color: #1e293b;
        }
        
        /* Footer */
        .dark footer { background-color: #020617; border-color: #1e293b; }
        
        /* Shadows */
        .dark .shadow-sm { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.5); }
        .dark .shadow { box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.5); }
        .dark .shadow-lg { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6); }
        .dark .shadow-xl { box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.7); }
        
        /* Hover States */
        .dark .hover\:bg-gray-50:hover { background-color: #334155 !important; }
        .dark .hover\:bg-gray-100:hover { background-color: #334155 !important; }
        .dark .hover\:bg-emerald-50:hover { background-color: #064e3b !important; }
        .dark .hover\:text-emerald-600:hover { color: #10b981 !important; }
        .dark .hover\:text-emerald-400:hover { color: #34d399 !important; }
        
        /* Prose/Content */
        .dark .prose { color: #cbd5e1 !important; }
        .dark .prose h1, .dark .prose h2, .dark .prose h3, 
        .dark .prose h4, .dark .prose h5, .dark .prose h6 { color: #f1f5f9 !important; }
        .dark .prose strong { color: #e2e8f0 !important; }
        .dark .prose a { color: #60a5fa !important; }
        .dark .prose code { background-color: #334155; color: #f1f5f9; }
        
        /* Cards & Articles */
        .dark article, .dark .card { 
            background-color: #1e293b !important; 
            border-color: #334155 !important;
        }
        
        /* Forms & Inputs */
        .dark input, .dark textarea, .dark select {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #f1f5f9 !important;
        }
        .dark input::placeholder, .dark textarea::placeholder {
            color: #64748b !important;
        }
        
        /* Tables */
        .dark table { border-color: #334155; }
        .dark thead { background-color: #1e293b; }
        .dark tbody tr { border-color: #334155; }
        .dark tbody tr:hover { background-color: #334155; }
        
        /* Gradients */
        .dark .bg-gradient-to-br { 
            background: linear-gradient(to bottom right, #064e3b, #0f172a, #020617) !important;
        }
        .dark .bg-gradient-to-r {
            background: linear-gradient(to right, #10b981, #14b8a6) !important;
        }
        .dark .text-transparent { opacity: 1 !important; }
        .dark .bg-clip-text {
            -webkit-background-clip: text !important;
            background-clip: text !important;
            color: transparent !important;
        }
        
        /* Badges & Pills */
        .dark .bg-emerald-600 { background-color: #059669 !important; }
        .dark .text-emerald-600 { color: #10b981 !important; }
        .dark .text-emerald-700 { color: #10b981 !important; }
        .dark .text-emerald-300 { color: #6ee7b7 !important; }
        .dark .text-emerald-400 { color: #34d399 !important; }
        .dark .text-blue-600 { color: #3b82f6 !important; }
        .dark .text-teal-300 { color: #5eead4 !important; }
        
        /* Mobile Menu */
        .dark #mobile-menu { 
            background-color: #0f172a !important; 
            border-color: #1e293b !important;
        }
        
        /* Hero Section - Make text more visible */
        .dark .hero-section { background-color: #020617; }
        .dark section.relative h1,
        .dark section.relative span,
        .dark section.relative p { color: #f8fafc !important; }
        
        /* Ensure all headings are visible */
        .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
            color: #f1f5f9 !important;
        }
        
        /* Line Clamp */
        .dark .line-clamp-3 { color: #94a3b8 !important; }
        
        /* Stats Cards & Info Boxes */
        .dark .bg-blue-600, .dark .bg-indigo-600, .dark .bg-purple-600,
        .dark .bg-pink-600, .dark .bg-red-600, .dark .bg-orange-600,
        .dark .bg-yellow-600, .dark .bg-green-600, .dark .bg-teal-600,
        .dark .bg-cyan-600 {
            filter: brightness(0.9);
        }
        
        /* Ensure white text stays white */
        .dark .text-white { color: #ffffff !important; }
        
        /* Quote blocks */
        .dark blockquote {
            background-color: #1e293b !important;
            border-color: #10b981 !important;
            color: #cbd5e1 !important;
        }
        
        .dark .dark-quote-box {
            background-color: #064e3b !important;
            border-color: #10b981 !important;
        }
        
        .dark .dark-quote-text {
            color: #d1fae5 !important;
        }
        
        /* Lists */
        .dark ul li, .dark ol li {
            color: #cbd5e1 !important;
        }
        
        /* Transparent backgrounds */
        .dark .bg-white\/90 { background-color: rgba(30, 41, 59, 0.9) !important; }
        .dark .bg-emerald-500\/20 { background-color: rgba(16, 185, 129, 0.2) !important; }
        
        /* Structure Organization Chart */
        .dark svg line, .dark svg path, .dark svg rect {
            stroke: #475569 !important;
        }
        
        /* All gray-800 lines in org chart */
        .dark .bg-gray-800 {
            background-color: #64748b !important;
        }
        
        /* Specific connector lines */
        .dark [style*="bg-gray-800"],
        .dark .absolute.bg-gray-800 {
            background: #64748b !important;
        }
        
        /* Green boxes - make text visible */
        .dark .bg-green-900, .dark .bg-green-800, .dark .bg-green-700,
        .dark .bg-emerald-900, .dark .bg-emerald-800, .dark .bg-emerald-700 {
            background-color: #064e3b !important;
        }
        
        /* Ensure text in colored boxes is visible */
        .dark .bg-green-900 *, .dark .bg-green-800 *, .dark .bg-green-700 *,
        .dark .bg-emerald-900 *, .dark .bg-emerald-800 *, .dark .bg-emerald-700 * {
            color: #f0fdf4 !important;
        }
        
        /* Text colors for specific elements */
        .dark .text-emerald-500 { color: #34d399 !important; }
        .dark .text-green-500 { color: #34d399 !important; }
        .dark .text-green-600 { color: #10b981 !important; }
        
        /* Buttons and links */
        .dark button, .dark a[class*="text-emerald"] {
            color: #34d399 !important;
        }
        
        /* Border colors for org chart */
        .dark .border, .dark [class*="border-"] {
            border-color: #475569 !important;
        }
        .dark .border-emerald-500, .dark .border-emerald-600 {
            border-color: #10b981 !important;
        }
        .dark .border-blue-500, .dark .border-blue-600 {
            border-color: #3b82f6 !important;
        }
        .dark .border-orange-500, .dark .border-orange-600 {
            border-color: #f97316 !important;
        }
        .dark .border-purple-500, .dark .border-purple-600 {
            border-color: #a855f7 !important;
        }
        .dark .border-pink-500, .dark .border-pink-600 {
            border-color: #ec4899 !important;
        }
        
        /* Panels and sections with green background */
        .dark [style*="background"] {
            filter: brightness(0.7);
        }
        
        /* Dark mode toggle button */
        #darkModeToggle { transition: all 0.3s ease; }
        
        /* Smooth transitions */
        * { transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease; }
        
        /* Mobile Responsive Improvements */
        @media (max-width: 768px) {
            /* Smaller text on mobile */
            h1 { font-size: 1.875rem !important; }
            h2 { font-size: 1.5rem !important; }
            h3 { font-size: 1.25rem !important; }
            
            /* Better spacing on mobile */
            .container { padding-left: 1rem; padding-right: 1rem; }
            
            /* Navbar adjustments */
            nav { position: sticky; }
            
            /* Cards smaller padding */
            .card, article { padding: 1rem !important; }
            
            /* Images responsive */
            img { max-width: 100%; height: auto; }
            
            /* Overflow handling */
            .org-structure { overflow-x: auto; }
            
            /* Font size adjustments */
            .text-xs { font-size: 0.65rem; }
            .text-sm { font-size: 0.8rem; }
            
            /* Better touch targets */
            button, a { min-height: 44px; min-width: 44px; }
        }
        
        /* Prevent horizontal scroll */
        body { overflow-x: hidden; }
        * { box-sizing: border-box; }
        
        /* Animation for blobs */
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -50px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(50px, 50px) scale(1.05); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
    <script>
        // Dark mode initialization
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }
        
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'disabled');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'enabled');
            }
            
            // Update icon
            updateDarkModeIcon();
        }
        
        function updateDarkModeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const icons = document.querySelectorAll('#darkModeToggle i');
            icons.forEach(icon => {
                if (isDark) {
                    icon.className = 'bi bi-sun-fill text-lg';
                } else {
                    icon.className = 'bi bi-moon-fill text-lg';
                }
            });
        }
        
        // Update icon on page load
        document.addEventListener('DOMContentLoaded', updateDarkModeIcon);
    </script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen transition-colors duration-200">
    <!-- Navbar -->
    <nav class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-sm/50 backdrop-blur-md bg-white/90">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20 group-hover:scale-105 transition duration-300">
                        <i class="bi bi-geo-alt-fill text-xl"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-gray-800 leading-none tracking-tight"><?= $settings['judul_website']; ?></h1>
                        <p class="text-xs text-gray-500 mt-1 font-medium">Kabupaten Lampung Selatan</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-1">
                    <?php
                    $menu = [
                        'index.php' => 'Beranda',
                        'profil.php' => 'Profil',
                        'berita.php' => 'Berita',
                        'potensi.php' => 'Potensi',
                        'sarana-prasarana.php' => 'Sarana & Prasarana',
                        'apbdesa.php' => 'APBDes'
                    ];
                    $current_page = basename($_SERVER['PHP_SELF']);
                    foreach ($menu as $link => $label) {
                        $active = ($current_page == $link) ? 'text-emerald-700 bg-emerald-50 font-bold' : 'text-gray-600 hover:text-emerald-600 hover:bg-emerald-50';
                        echo "<a href=\"$link\" class=\"px-4 py-2 text-sm font-semibold rounded-lg transition $active\">$label</a>";
                    }
                    ?>
                    <button onclick="toggleDarkMode()" id="darkModeToggle" class="ml-2 p-2.5 text-gray-600 hover:text-emerald-600 hover:bg-gray-100 rounded-full transition" title="Toggle Dark Mode">
                        <i class="bi bi-moon-fill text-lg"></i>
                    </button>
                    <a href="cek-pengaduan.php" class="ml-2 px-4 py-2 text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition border border-emerald-200 rounded-full hover:bg-emerald-50 flex items-center gap-1.5">
                        <i class="bi bi-search"></i>
                        Cek Pengaduan
                    </a>
                    <a href="admin/login.php" class="ml-2 px-4 py-2 text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition border border-emerald-200 rounded-full hover:bg-emerald-50">Login Admin</a>
                    <a href="kontak.php" class="ml-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">Kontak</a>
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
            <a href="sarana-prasarana.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Sarana & Prasarana</a>
            <a href="apbdesa.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Transparansi APBDes</a>
            <a href="cek-pengaduan.php" class="block px-4 py-3 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center gap-2">
                <i class="bi bi-search"></i> Cek Status Pengaduan
            </a>
            <a href="admin/login.php" class="block px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700">Login Admin</a>
            <button onclick="toggleDarkMode()" class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 font-semibold text-gray-700 flex items-center gap-2">
                <i class="bi bi-moon-fill"></i> Mode Gelap
            </button>
            <a href="kontak.php" class="block px-4 py-3 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-center mt-4">Hubungi Kami</a>
        </div>
    </nav>