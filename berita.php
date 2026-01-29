<?php
$page_title = "Berita & Artikel";
require 'includes/header.php';

// Cek apakah mode detail berita
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $q = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id WHERE b.id = $id");
    
    if (mysqli_num_rows($q) > 0) {
        $berita = mysqli_fetch_assoc($q);
        ?>
        <!-- DETAIL BERITA -->
        <div class="bg-gray-50 py-12">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <img src="assets/img/berita/<?= $berita['gambar']; ?>" class="w-full h-64 md:h-96 object-cover">
                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-bold text-xs uppercase tracking-wide"><?= $berita['nama_kategori']; ?></span>
                            <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($berita['tgl_posting'])); ?></span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 leading-tight"><?= htmlspecialchars($berita['judul']); ?></h1>
                        
                        <div class="prose prose-lg prose-blue max-w-none text-gray-600 text-justify">
                            <?= $berita['isi_berita']; ?>
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <a href="berita.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 font-medium transition">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<div class='container mx-auto px-4 py-20 text-center'><h2 class='text-2xl font-bold text-gray-800'>Berita tidak ditemukan.</h2><a href='berita.php' class='text-emerald-600 mt-4 inline-block'>Kembali</a></div>";
    }
} else {
    // LIST BERITA
    $q_berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
    ?>
    <div class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 py-16 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-block bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                    <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                        <i class="bi bi-newspaper"></i> Berita Terkini
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 mb-6">Kabar Desa</h1>
                <div class="flex justify-center items-center gap-3 mb-6">
                    <div class="h-1 w-20 bg-gradient-to-r from-transparent to-blue-500 rounded-full"></div>
                    <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                    <div class="h-1 w-32 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-full"></div>
                    <div class="h-2 w-2 bg-purple-500 rounded-full"></div>
                    <div class="h-1 w-20 bg-gradient-to-l from-transparent to-purple-500 rounded-full"></div>
                </div>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">Ikuti perkembangan terbaru dan informasi penting dari Desa Natar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($q_berita)): ?>
                    <?php include 'components/card-berita.php'; ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'includes/footer.php';
?>