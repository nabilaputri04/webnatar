<?php
/**
 * Component: Widget Statistik
 * Variable required: $conn (koneksi database)
 */

// Ambil data profil dari database
$profil = ['populasi' => '0', 'luas_wilayah' => '0'];
if ($conn) {
    $q_profil = @mysqli_query($conn, "SELECT populasi, luas_wilayah FROM profil LIMIT 1");
    if ($q_profil && mysqli_num_rows($q_profil) > 0) {
        $profil = mysqli_fetch_assoc($q_profil);
    }
}
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Penduduk -->
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="bi bi-people-fill"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?= number_format($profil['populasi'] ?? 0, 0, ',', '.') ?></h3>
        <p class="text-gray-500 font-medium">Jumlah Penduduk</p>
    </div>

    <!-- Luas Wilayah -->
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="bi bi-map-fill"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1">± <?= htmlspecialchars($profil['luas_wilayah'] ?? '0') ?> Ha</h3>
        <p class="text-gray-500 font-medium">Luas Wilayah</p>
    </div>

    <!-- Layanan -->
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
        <div class="w-16 h-16 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="bi bi-building-fill"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1">24/7</h3>
        <p class="text-gray-500 font-medium">Layanan Online</p>
    </div>
</div>