<?php
require 'config/db.php';

$pengaduan = null;
$error = "";

if (isset($_GET['cek']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, trim($_GET['id']));
    
    $query = "SELECT * FROM pengaduan WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $pengaduan = mysqli_fetch_assoc($result);
    } else {
        $error = "Pengaduan dengan ID tersebut tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pengaduan - Desa Natar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <main class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-4">
                    <i class="bi bi-search text-4xl text-emerald-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Cek Status Pengaduan</h1>
                <p class="text-gray-600">Masukkan nomor ID pengaduan untuk melihat status dan tanggapan</p>
            </div>

            <!-- Form Pencarian -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bi bi-hash me-1"></i>Nomor ID Pengaduan
                        </label>
                        <input type="text" 
                               name="id" 
                               value="<?= htmlspecialchars($_GET['id'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" 
                               placeholder="Contoh: 1, 2, 3..." 
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="bi bi-info-circle me-1"></i>Nomor ID diterima setelah pengaduan berhasil dikirim
                        </p>
                    </div>
                    <div class="pt-7">
                        <button type="submit" 
                                name="cek"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 font-semibold">
                            <i class="bi bi-search"></i>
                            Cek Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-8">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl mr-3 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-red-800">Pengaduan Tidak Ditemukan</h3>
                            <p class="text-red-700 mt-1"><?= $error ?></p>
                            <p class="text-sm text-red-600 mt-2">Pastikan nomor ID yang Anda masukkan benar.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Hasil Pencarian -->
            <?php if ($pengaduan): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Header dengan Status -->
                    <div class="p-6 <?php
                        $bg_colors = [
                            'Baru' => 'bg-emerald-500',
                            'Diproses' => 'bg-blue-500',
                            'Selesai' => 'bg-green-500',
                            'Ditolak' => 'bg-red-500'
                        ];
                        echo $bg_colors[$pengaduan['status']] ?? 'bg-gray-500';
                    ?>">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h2 class="text-2xl font-bold mb-1">
                                    <i class="bi bi-file-text-fill me-2"></i>Pengaduan #<?= $pengaduan['id'] ?>
                                </h2>
                                <p class="opacity-90">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Dikirim pada: <?= date('d F Y, H:i', strtotime($pengaduan['tanggal_dibuat'])) ?> WIB
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm opacity-90 mb-1">Status Pengaduan:</div>
                                <div class="text-3xl font-bold">
                                    <?php
                                    $icons = [
                                        'Baru' => 'bi-hourglass-split',
                                        'Diproses' => 'bi-gear-fill',
                                        'Selesai' => 'bi-check-circle-fill',
                                        'Ditolak' => 'bi-x-circle-fill'
                                    ];
                                    echo '<i class="bi ' . ($icons[$pengaduan['status']] ?? 'bi-question-circle-fill') . ' me-2"></i>';
                                    echo $pengaduan['status'];
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pengaduan -->
                    <div class="p-6 space-y-6">
                        <!-- Informasi Pelapor -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                <i class="bi bi-person-circle me-2 text-emerald-600"></i>Informasi Pelapor
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Nama</div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($pengaduan['nama']) ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Email</div>
                                    <div class="font-semibold text-gray-800"><?= $pengaduan['email'] ?: '-' ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Telepon</div>
                                    <div class="font-semibold text-gray-800"><?= $pengaduan['telepon'] ?: '-' ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pengaduan -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                <i class="bi bi-file-earmark-text me-2 text-emerald-600"></i>Detail Pengaduan
                            </h3>
                            <div class="space-y-3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-gray-600">Kategori:</span>
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium">
                                            <?= htmlspecialchars($pengaduan['kategori']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-600 mb-2">Judul Pengaduan:</div>
                                    <div class="text-gray-800 font-medium"><?= htmlspecialchars($pengaduan['judul']) ?></div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-600 mb-2">Isi Pengaduan:</div>
                                    <div class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">
                                        <?= nl2br(htmlspecialchars($pengaduan['isi_pengaduan'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggapan/Status dari Admin -->
                        <?php if (!empty($pengaduan['tanggapan'])): ?>
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                    <i class="bi bi-chat-left-text-fill me-2 text-emerald-600"></i>Tanggapan dari Petugas
                                </h3>
                                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg">
                                    <div class="text-gray-700 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($pengaduan['tanggapan'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="border-t pt-6">
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <i class="bi bi-clock-history text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-600">Belum ada tanggapan dari petugas.</p>
                                    <p class="text-sm text-gray-500 mt-1">Silakan cek kembali nanti.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Status Timeline -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <i class="bi bi-clock-history me-2 text-emerald-600"></i>Status Pengaduan
                            </h3>
                            <div class="space-y-3">
                                <?php
                                $statuses = [
                                    'Baru' => ['icon' => 'bi-hourglass-split', 'color' => 'yellow', 'text' => 'Pengaduan telah diterima dan menunggu ditindaklanjuti'],
                                    'Diproses' => ['icon' => 'bi-gear-fill', 'color' => 'blue', 'text' => 'Pengaduan sedang dalam proses penanganan'],
                                    'Selesai' => ['icon' => 'bi-check-circle-fill', 'color' => 'green', 'text' => 'Pengaduan telah selesai ditangani'],
                                    'Ditolak' => ['icon' => 'bi-x-circle-fill', 'color' => 'red', 'text' => 'Pengaduan ditolak. Lihat tanggapan untuk alasan penolakan.']
                                ];
                                
                                $current_status = $pengaduan['status'];
                                foreach ($statuses as $status_name => $status_info):
                                    $is_current = ($status_name === $current_status);
                                    $opacity = $is_current ? '' : 'opacity-40';
                                ?>
                                    <div class="flex items-start gap-3 <?= $opacity ?>">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-<?= $status_info['color'] ?>-100 flex items-center justify-center">
                                            <i class="bi <?= $status_info['icon'] ?> text-<?= $status_info['color'] ?>-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-800"><?= $status_name ?></div>
                                            <div class="text-sm text-gray-600"><?= $status_info['text'] ?></div>
                                        </div>
                                        <?php if ($is_current): ?>
                                            <span class="px-3 py-1 bg-<?= $status_info['color'] ?>-100 text-<?= $status_info['color'] ?>-800 rounded-full text-xs font-semibold">
                                                Status Saat Ini
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="border-t pt-6 flex gap-3">
                            <a href="cek-pengaduan.php" class="flex-1 text-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                                <i class="bi bi-arrow-left me-2"></i>Cek Pengaduan Lain
                            </a>
                            <a href="kontak.php" class="flex-1 text-center px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-semibold">
                                <i class="bi bi-plus-circle me-2"></i>Buat Pengaduan Baru
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Info Card -->
            <?php if (!isset($_GET['cek'])): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-6 rounded-lg">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle-fill text-emerald-500 text-2xl mr-3 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-emerald-900 mb-2">Informasi</h3>
                            <ul class="text-emerald-800 space-y-1 text-sm">
                                <li><i class="bi bi-check2 me-2"></i>Nomor ID pengaduan diterima setelah Anda mengirimkan pengaduan melalui form kontak</li>
                                <li><i class="bi bi-check2 me-2"></i>Simpan nomor ID untuk melacak status pengaduan Anda</li>
                                <li><i class="bi bi-check2 me-2"></i>Status akan diperbarui oleh petugas desa setelah pengaduan ditinjau</li>
                                <li><i class="bi bi-check2 me-2"></i>Cek secara berkala untuk melihat perkembangan pengaduan Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
