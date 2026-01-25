<?php
/**
 * Component: Widget Peta
 * Variable required: $kontak (array data kontak)
 */
?>
<div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 h-full overflow-hidden">
    <?php if(!empty($kontak['maps_embed'])): ?>
        <div class="w-full h-full rounded-xl overflow-hidden relative min-h-[300px]">
            <!-- Wrapper untuk memastikan iframe responsif -->
            <div class="absolute inset-0 [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                <?= $kontak['maps_embed']; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 rounded-xl min-h-[300px]">
            <div class="text-center">
                <i class="bi bi-map text-4xl mb-2 block"></i>
                Peta belum disematkan
            </div>
        </div>
    <?php endif; ?>
</div>