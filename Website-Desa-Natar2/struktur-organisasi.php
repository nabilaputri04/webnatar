<?php
$page_title = "Struktur Organisasi";
require 'includes/header.php';

$q_perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Struktur Organisasi</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Pemerintahan Desa Natar yang siap melayani masyarakat.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php while($row = mysqli_fetch_assoc($q_perangkat)): ?>
                <!-- Menggunakan Component Card Perangkat -->
                <?php include 'components/card-perangkat.php'; ?>
            <?php endwhile; ?>
        </div>

        <div class="mt-12 text-center">
            <a href="profil.php" class="text-emerald-600 font-semibold hover:underline"><i class="bi bi-arrow-left"></i> Kembali ke Profil Desa</a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>