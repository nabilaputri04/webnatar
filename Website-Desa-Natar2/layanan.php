<?php
$page_title = "Layanan Publik";
require 'includes/header.php';

$q_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan ASC");
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Layanan Administrasi</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Panduan pengurusan surat dan dokumen kependudukan di Kantor Desa Natar.</p>
        </div>

        <div class="space-y-6">
            <?php while($row = mysqli_fetch_assoc($q_layanan)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <details class="group">
                    <summary class="flex justify-between items-center p-6 cursor-pointer list-none hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                                <i class="bi bi-file-earmark-text text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-600 transition"><?= htmlspecialchars($row['nama_layanan']); ?></h3>
                                <div class="flex gap-4 text-sm text-gray-500 mt-1">
                                    <span class="flex items-center gap-1"><i class="bi bi-clock"></i> <?= $row['estimasi_waktu']; ?></span>
                                    <span class="flex items-center gap-1"><i class="bi bi-cash"></i> <?= $row['biaya']; ?></span>
                                </div>
                            </div>
                        </div>
                        <span class="transition group-open:rotate-180">
                            <i class="bi bi-chevron-down text-gray-400"></i>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 border-t border-gray-50">
                        <div class="grid md:grid-cols-2 gap-8 mt-4">
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="bi bi-check-circle text-green-500"></i> Persyaratan</h4>
                                <div class="prose prose-sm text-gray-600"><?= $row['persyaratan']; ?></div>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="bi bi-list-ol text-emerald-500"></i> Prosedur</h4>
                                <div class="prose prose-sm text-gray-600"><?= $row['prosedur']; ?></div>
                            </div>
                        </div>
                    </div>
                </details>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>