<?php
/**
 * Component: Card Perangkat Desa
 * Variable required: $row (array data perangkat desa)
 */
?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition text-center group h-full">
    <div class="aspect-square overflow-hidden bg-gray-100 relative">
        <?php if(!empty($row['foto'])): ?>
            <?php
            $foto_path = 'assets/img/perangkat/' . $row['foto'];
            $foto_src = img_cache_buster($foto_path);
            ?>
            <img src="<?= $foto_src ?>" 
                 alt="<?= htmlspecialchars($row['nama']); ?>"
                 class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                <i class="bi bi-person-fill text-6xl"></i>
            </div>
        <?php endif; ?>
    </div>
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1"><?= htmlspecialchars($row['nama']); ?></h3>
        <p class="text-emerald-600 text-sm font-medium"><?= htmlspecialchars($row['jabatan']); ?></p>
    </div>
</div>