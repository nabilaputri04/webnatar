<?php
$page_title = "Potensi Desa";
require 'includes/header.php';

$q_potensi = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM potensi_desa p LEFT JOIN kategori_potensi k ON p.id_kategori = k.id ORDER BY p.tgl_input DESC");
?>

<div class="bg-gradient-to-br from-gray-50 via-green-50 to-emerald-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-stars"></i> Potensi Lokal
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 mb-6">Potensi Desa</h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-green-500 rounded-full"></div>
                <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-full"></div>
                <div class="h-2 w-2 bg-teal-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-teal-500 rounded-full"></div>
            </div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">Jelajahi kekayaan alam, UMKM, dan destinasi menarik di Desa Natar.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($row = mysqli_fetch_assoc($q_potensi)): ?>
            <div class="bg-white rounded-3xl shadow-xl border-2 border-emerald-100 overflow-hidden hover:shadow-2xl hover:shadow-emerald-200/50 transition-all duration-500 group transform hover:-translate-y-2">
                <div class="relative h-64 overflow-hidden">
                    <?php
                    $img_path = 'assets/img/potensi/' . $row['gambar'];
                    $img_src = img_cache_buster($img_path);
                    ?>
                    <img src="<?= $img_src ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
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
                    <button onclick="bukaGoogleMaps('<?= addslashes($row['lokasi_maps']); ?>')" class="w-full block text-center bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 border-0 cursor-pointer shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="bi bi-geo-alt-fill"></i> Lihat di Google Maps
                    </button>
                    <?php else: ?>
                    <button disabled class="w-full block text-center bg-gray-100 text-gray-400 font-semibold py-3 rounded-xl border border-gray-200 cursor-not-allowed">
                        <i class="bi bi-geo-alt-fill"></i> Lokasi Belum Tersedia
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
function bukaGoogleMaps(lokasi) {
    if (lokasi.startsWith('http://') || lokasi.startsWith('https://')) {
        window.open(lokasi, '_blank');
    } else {
        const searchUrl = 'https://www.google.com/maps/search/' + encodeURIComponent(lokasi);
        window.open(searchUrl, '_blank');
    }
}
</script>

<?php require 'includes/footer.php'; ?>