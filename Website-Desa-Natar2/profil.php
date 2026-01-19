<?php
$page_title = "Profil Desa";
require 'includes/header.php';

$q_profil = mysqli_query($conn, "SELECT * FROM profil LIMIT 1");
$profil = mysqli_fetch_assoc($q_profil);
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Profil Desa Natar</h1>
            <div class="w-20 h-1 bg-emerald-600 mx-auto rounded-full"></div>
        </div>

        <!-- Sejarah & Visi Misi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center"><i class="bi bi-clock-history text-xl"></i></div>
                    <h2 class="text-2xl font-bold text-gray-800">Sejarah Desa</h2>
                </div>
                <div class="prose text-gray-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($profil['sejarah'])); ?>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center"><i class="bi bi-bullseye text-xl"></i></div>
                        <h2 class="text-2xl font-bold text-gray-800">Visi</h2>
                    </div>
                    <p class="text-gray-600 italic text-lg border-l-4 border-emerald-500 pl-4">
                        "<?= htmlspecialchars($profil['visi']); ?>"
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center"><i class="bi bi-list-check text-xl"></i></div>
                        <h2 class="text-2xl font-bold text-gray-800">Misi</h2>
                    </div>
                    <div class="text-gray-600 leading-relaxed">
                        <?= nl2br(htmlspecialchars($profil['misi'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link ke Struktur Organisasi -->
        <div class="text-center">
            <a href="struktur-organisasi.php" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/30">
                <i class="bi bi-people-fill"></i> Lihat Struktur Organisasi
            </a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>