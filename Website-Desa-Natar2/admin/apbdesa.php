<?php
$page_title = "Transparansi APBDes";
require 'includes/header.php';

$tahun = date('Y');
$q_apb = mysqli_query($conn, "SELECT * FROM apb_desa WHERE tahun = '$tahun' ORDER BY jenis ASC");

// Hitung Total
$q_total = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis = 'Pendapatan' THEN anggaran ELSE 0 END) as target_pendapatan,
    SUM(CASE WHEN jenis = 'Pendapatan' THEN realisasi ELSE 0 END) as real_pendapatan,
    SUM(CASE WHEN jenis = 'Belanja' THEN anggaran ELSE 0 END) as target_belanja,
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as real_belanja
    FROM apb_desa WHERE tahun = '$tahun'");
$total = mysqli_fetch_assoc($q_total);
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Transparansi Anggaran <?= $tahun; ?></h1>
            <p class="text-gray-500">Laporan realisasi Anggaran Pendapatan dan Belanja Desa (APBDes).</p>
        </div>

        <!-- Ringkasan Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                <h3 class="text-gray-500 font-bold text-sm uppercase mb-2">Total Pendapatan</h3>
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-xs text-gray-400">Realisasi</span>
                        <div class="text-2xl font-bold text-gray-800">Rp <?= number_format($total['real_pendapatan'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400">Target</span>
                        <div class="text-sm font-semibold text-gray-600">Rp <?= number_format($total['target_pendapatan'], 0, ',', '.'); ?></div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-500">
                <h3 class="text-gray-500 font-bold text-sm uppercase mb-2">Total Belanja</h3>
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-xs text-gray-400">Terpakai</span>
                        <div class="text-2xl font-bold text-gray-800">Rp <?= number_format($total['real_belanja'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400">Anggaran</span>
                        <div class="text-sm font-semibold text-gray-600">Rp <?= number_format($total['target_belanja'], 0, ',', '.'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Tabel -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-6 border-b pb-4">Rincian Anggaran</h3>
            <div class="space-y-6">
                <?php while($row = mysqli_fetch_assoc($q_apb)): 
                    $persen = ($row['anggaran'] > 0) ? round(($row['realisasi'] / $row['anggaran']) * 100) : 0;
                    $color = ($row['jenis'] == 'Pendapatan') ? 'bg-green-500' : 'bg-orange-500';
                ?>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold text-gray-700"><?= htmlspecialchars($row['rincian']); ?></span>
                        <span class="text-sm font-bold <?= ($row['jenis'] == 'Pendapatan') ? 'text-green-600' : 'text-orange-600'; ?>"><?= $persen; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="<?= $color; ?> h-3 rounded-full transition-all duration-1000" style="width: <?= $persen; ?>%"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                        <span>Target: Rp <?= number_format($row['anggaran'], 0, ',', '.'); ?></span>
                        <span>Real: Rp <?= number_format($row['realisasi'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>