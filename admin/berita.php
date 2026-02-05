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
                    <?php
                    $img_path = 'assets/img/berita/' . $berita['gambar'];
                    $img_src = img_cache_buster($img_path);
                    ?>
                    <img src="<?= $img_src ?>" class="w-full h-64 md:h-96 object-cover">
                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-bold text-xs uppercase tracking-wide"><?= $berita['nama_kategori']; ?></span>
                            <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($berita['tgl_posting'])); ?></span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 leading-tight"><?= htmlspecialchars($berita['judul']); ?></h1>
                        
                        <div class="prose prose-lg prose-blue max-w-none text-gray-600">
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
        echo "<div class='container mx-auto px-4 py-20 text-center'><h2 class='text-2xl font-bold text-gray-800'>Berita tidak ditemukan.</h2><a href='berita.php' class='text-blue-600 mt-4 inline-block'>Kembali</a></div>";
    }
} else {
    // LIST BERITA
    $q_berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
    ?>
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Kabar Desa</h1>
                <p class="text-gray-500 max-w-2xl mx-auto">Ikuti perkembangan terbaru dan informasi penting dari Desa Natar 2.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($q_berita)): ?>
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full group">
                    <div class="relative h-56 overflow-hidden">
                        <?php
                        $img_path = 'assets/img/berita/' . $row['gambar'];
                        $img_src = img_cache_buster($img_path);
                        ?>
                        <img src="<?= $img_src ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-blue-600 shadow-sm">
                            <?= $row['nama_kategori'] ?? 'Umum'; ?>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                            <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($row['tgl_posting'])); ?>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3 leading-snug group-hover:text-blue-600 transition">
                            <a href="?id=<?= $row['id']; ?>"><?= htmlspecialchars($row['judul']); ?></a>
                        </h3>
                        <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">
                            <?= strip_tags(substr($row['isi_berita'], 0, 150)); ?>...
                        </p>
                        <a href="?id=<?= $row['id']; ?>" class="inline-flex items-center text-blue-600 font-semibold text-sm hover:underline mt-auto">
                            Baca Selengkapnya <i class="bi bi-arrow-right ml-2"></i>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'includes/footer.php';
?>