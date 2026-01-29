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

<div class="bg-gradient-to-br from-gray-50 via-yellow-50 to-orange-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 max-w-6xl relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-yellow-500 to-orange-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-cash-stack"></i> Keuangan Desa
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 via-orange-600 to-red-600 mb-6">Transparansi APBDes</h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-yellow-500 rounded-full"></div>
                <div class="h-2 w-2 bg-yellow-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 rounded-full"></div>
                <div class="h-2 w-2 bg-red-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-red-500 rounded-full"></div>
            </div>
        </div>

        <!-- Ringkasan Cards - 4 Kolom -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs opacity-90 font-semibold uppercase tracking-wide">Pendapatan Cair</div>
                    <i class="bi bi-arrow-down-circle-fill text-2xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold">Rp <?= number_format($total['total_pendapatan'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs opacity-90 font-semibold uppercase tracking-wide">Belanja Terpakai</div>
                    <i class="bi bi-arrow-up-circle-fill text-2xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold">Rp <?= number_format($total['total_belanja'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs opacity-90 font-semibold uppercase tracking-wide">Pembiayaan Cair</div>
                    <i class="bi bi-wallet2 text-2xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold">Rp <?= number_format($total['total_pembiayaan'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs opacity-90 font-semibold uppercase tracking-wide">Sisa Anggaran</div>
                    <i class="bi bi-piggy-bank-fill text-2xl opacity-80"></i>
                </div>
                <div class="text-2xl font-bold">Rp <?= number_format($sisa_anggaran, 0, ',', '.'); ?></div>
            </div>
        </div>

        <!-- Detail Tabel -->
        <div class="bg-white rounded-3xl shadow-2xl border-2 border-yellow-100 overflow-hidden hover:shadow-yellow-200/50 transition-all duration-500">
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