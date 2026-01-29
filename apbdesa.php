<?php
$page_title = "Transparansi APBDes";
require 'includes/header.php';

// Ambil semua data tanpa filter tahun
$q_apb = mysqli_query($conn, "SELECT * FROM apb_desa ORDER BY tahun DESC, jenis ASC");

// Hitung Total dari semua data
$q_total = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis = 'Pendapatan' THEN realisasi ELSE 0 END) as total_pendapatan,
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as total_belanja,
    SUM(CASE WHEN jenis = 'Pembiayaan Desa' THEN realisasi ELSE 0 END) as total_pembiayaan
    FROM apb_desa");
$total = mysqli_fetch_assoc($q_total);
$sisa_anggaran = $total['total_pendapatan'] - $total['total_belanja'];
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Manajemen Transparansi APB Desa</h1>
        </div>

        <!-- Ringkasan Cards - 4 Kolom -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="bg-green-500 text-white p-5 rounded-xl shadow-sm">
                <div class="text-xs opacity-80 mb-1">Total Pendapatan Cair</div>
                <div class="text-xl font-bold">Rp <?= number_format($total['total_pendapatan'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-yellow-500 text-white p-5 rounded-xl shadow-sm">
                <div class="text-xs opacity-80 mb-1">Total Belanja Terpakai</div>
                <div class="text-xl font-bold">Rp <?= number_format($total['total_belanja'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-cyan-500 text-white p-5 rounded-xl shadow-sm">
                <div class="text-xs opacity-80 mb-1">Total Pembiayaan Cair</div>
                <div class="text-xl font-bold">Rp <?= number_format($total['total_pembiayaan'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-emerald-500 text-white p-5 rounded-xl shadow-sm">
                <div class="text-xs opacity-80 mb-1">Sisa Anggaran Desa</div>
                <div class="text-xl font-bold">Rp <?= number_format($sisa_anggaran, 0, ',', '.'); ?></div>
            </div>
        </div>

        <!-- Detail Tabel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Rincian & Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Anggaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while($row = mysqli_fetch_assoc($q_apb)): 
                            if($row['jenis'] == 'Pendapatan') {
                                $badge_class = 'bg-green-100 text-green-700 border-green-300';
                            } elseif($row['jenis'] == 'Belanja') {
                                $badge_class = 'bg-yellow-100 text-yellow-700 border-yellow-300';
                            } else {
                                $badge_class = 'bg-cyan-100 text-cyan-700 border-cyan-300';
                            }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($row['rincian']); ?></div>
                                <div class="text-xs text-gray-500 mt-1">Tahun <?= $row['tahun']; ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border <?= $badge_class; ?>"><?= $row['jenis']; ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-gray-800" style="font-family: 'Courier New', monospace; font-size: 0.95rem;">Rp <?= number_format($row['realisasi'], 2, ',', '.'); ?></div>
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