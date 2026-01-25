<?php
$page_title = "Profil Desa";
require 'includes/header.php';

$q_profil = mysqli_query($conn, "SELECT * FROM profil LIMIT 1");
$profil = mysqli_fetch_assoc($q_profil);

$q_perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Profil Desa Natar 2</h1>
            <div class="w-20 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <!-- Sejarah & Visi Misi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-20">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center"><i class="bi bi-clock-history text-xl"></i></div>
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

        <!-- Struktur Organisasi -->
        <div class="mb-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Perangkat Desa</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Struktur organisasi pemerintahan Desa Natar 2 yang siap melayani masyarakat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php while($p = mysqli_fetch_assoc($q_perangkat)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition text-center group">
                    <div class="aspect-square overflow-hidden bg-gray-100">
                        <?php if($p['foto']): ?>
                            <img src="assets/img/perangkat/<?= $p['foto']; ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="bi bi-person-fill text-6xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-1"><?= htmlspecialchars($p['nama']); ?></h3>
                        <p class="text-blue-600 text-sm font-medium"><?= htmlspecialchars($p['jabatan']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<?php require 'includes/footer.php'; ?>