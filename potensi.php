<?php
$page_title = "Potensi Desa";
require 'includes/header.php';

$q_potensi = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM potensi_desa p LEFT JOIN kategori_potensi k ON p.id_kategori = k.id ORDER BY p.tgl_input DESC");
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Potensi Desa</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Jelajahi kekayaan alam, UMKM, dan destinasi menarik di Desa Natar.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($row = mysqli_fetch_assoc($q_potensi)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="assets/img/potensi/<?= $row['gambar']; ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <span class="bg-emerald-600 text-xs font-bold px-2 py-1 rounded mb-2 inline-block"><?= $row['nama_kategori']; ?></span>
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($row['nama_potensi']); ?></h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm text-gray-600 mb-6 line-clamp-4">
                        <?= $row['deskripsi']; ?>
                    </div>
                    
                    <?php if(!empty($row['lokasi_maps'])): ?>
                    <a href="<?= $row['lokasi_maps']; ?>" target="_blank" class="w-full block text-center bg-gray-50 hover:bg-emerald-50 text-gray-700 hover:text-emerald-600 font-semibold py-3 rounded-xl border border-gray-200 transition flex items-center justify-center gap-2">
                        <i class="bi bi-geo-alt-fill"></i> Lihat di Google Maps
                    </a>
                    <?php else: ?>
                    <button disabled class="w-full block text-center bg-gray-50 text-gray-400 font-semibold py-3 rounded-xl border border-gray-200 cursor-not-allowed">
                        <i class="bi bi-geo-alt-fill"></i> Lokasi Belum Tersedia
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>