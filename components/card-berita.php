<?php
/**
 * Component: Card Berita
 * Variable required: $row (array data berita dari database)
 */
?>
<article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full group">
    <div class="relative h-56 overflow-hidden">
        <img src="assets/img/berita/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.jpg'; ?>" 
             alt="<?= htmlspecialchars($row['judul']); ?>" 
             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-emerald-600 shadow-sm">
            <?= $row['nama_kategori'] ?? 'Umum'; ?>
        </div>
    </div>
    <div class="p-6 flex-1 flex flex-col">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($row['tgl_posting'])); ?>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3 leading-snug group-hover:text-emerald-600 transition">
            <a href="berita.php?id=<?= $row['id']; ?>"><?= htmlspecialchars($row['judul']); ?></a>
        </h3>
        <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">
            <?= strip_tags(substr($row['isi_berita'], 0, 150)); ?>...
        </p>
        <a href="berita.php?id=<?= $row['id']; ?>" class="inline-flex items-center text-emerald-600 font-semibold text-sm hover:underline mt-auto">
            Baca Selengkapnya <i class="bi bi-arrow-right ml-2"></i>
        </a>
    </div>
</article>