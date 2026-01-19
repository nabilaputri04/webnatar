<?php
$page_title = "Pusat Unduhan";
require 'includes/header.php';

$q_unduhan = mysqli_query($conn, "SELECT u.*, k.nama_kategori FROM unduhan u LEFT JOIN kategori_unduhan k ON u.id_kategori = k.id ORDER BY u.tgl_upload DESC");
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Dokumen Publik</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Unduh formulir, peraturan desa, dan dokumen publik lainnya.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while($row = mysqli_fetch_assoc($q_unduhan)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-file-earmark-text text-blue-500 text-xl"></i>
                                    <?= htmlspecialchars($row['nama_dokumen']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4"><span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold"><?= $row['nama_kategori']; ?></span></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tgl_upload'])); ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="assets/files/unduhan/<?= $row['nama_file']; ?>" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>