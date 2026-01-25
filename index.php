<?php
$page_title = "Beranda";
require 'includes/header.php';

// Ambil Data Profil
$profil = [];
if ($conn) {
    $q_profil = @mysqli_query($conn, "SELECT * FROM profil LIMIT 1");
    if ($q_profil) {
        $profil = mysqli_fetch_assoc($q_profil);
    }
}

// Ambil 3 Berita Terbaru
$q_berita = null;
if ($conn) {
    $q_berita = @mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC LIMIT 3");
}
?>

<!-- Hero Section -->
<section class="relative bg-gray-900 text-white py-32 lg:py-40 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-gray-900 to-black opacity-90 z-10"></div>
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')] bg-cover bg-center"></div>
    
    <div class="container mx-auto px-4 relative z-20 text-center">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-sm font-semibold mb-6 backdrop-blur-sm tracking-wide uppercase">Selamat Datang di Website Resmi</span>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight">Desa Natar<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Maju & Sejahtera</span></h1>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="profil.php" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold transition shadow-lg shadow-emerald-600/30">Profil Desa</a>
            <a href="sarana-prasarana.php" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold transition shadow-lg shadow-emerald-600/30">Sarana & Prasarana</a>
        </div>
    </div>
</section>

<!-- Statistik Singkat -->
<section class="py-12 -mt-16 relative z-30">
    <div class="container mx-auto px-4">
        <?php include 'components/widget-statistik.php'; ?>
    </div>
</section>

<!-- Berita Terkini -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2 border-l-4 border-emerald-500 pl-4">Kabar Desa Terkini</h2>
                <p class="text-gray-500">Informasi terbaru seputar kegiatan dan pengumuman desa.</p>
            </div>
            <a href="berita.php" class="hidden md:flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if ($q_berita && mysqli_num_rows($q_berita) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($q_berita)): ?>
                <?php include 'components/card-berita.php'; ?>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="bi bi-newspaper text-5xl mb-4"></i>
                    <p>Belum ada berita tersedia atau database belum terhubung.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="berita.php" class="inline-block w-full py-3 rounded-xl font-bold border border-emerald-600 text-emerald-600 hover:bg-emerald-50 transition">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>