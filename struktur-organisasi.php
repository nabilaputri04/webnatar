<?php
$page_title = "Struktur Organisasi";
require 'includes/header.php';
require 'config/db.php';

// Ambil data BPD
$query_bpd = "SELECT * FROM bpd ORDER BY urutan ASC";
$result_bpd = mysqli_query($conn, $query_bpd);
$data_bpd = [];
if ($result_bpd) {
    while ($row = mysqli_fetch_assoc($result_bpd)) {
        $data_bpd[] = $row;
    }
}

// Ambil data LPMD
$query_lpmd = "SELECT * FROM lpmd ORDER BY urutan ASC";
$result_lpmd = mysqli_query($conn, $query_lpmd);
$data_lpmd = [];
if ($result_lpmd) {
    while ($row = mysqli_fetch_assoc($result_lpmd)) {
        $data_lpmd[] = $row;
    }
}

// Ambil data RT per dusun
$query_rt = "SELECT * FROM rt ORDER BY dusun ASC, nomor_rt ASC";
$result_rt = mysqli_query($conn, $query_rt);
$data_rt = [];
if ($result_rt) {
    while ($row = mysqli_fetch_assoc($result_rt)) {
        $data_rt[$row['dusun']][] = $row;
    }
}

// Ambil data Perangkat Desa
$query_perangkat = "SELECT * FROM perangkat_desa ORDER BY urutan ASC";
$result_perangkat = mysqli_query($conn, $query_perangkat);
$data_perangkat = [];
$data_kadus = []; // Array khusus untuk Kepala Dusun
if ($result_perangkat) {
    while ($row = mysqli_fetch_assoc($result_perangkat)) {
        $jabatan_key = strtolower(str_replace([' ', '/'], ['_', '_'], $row['jabatan']));
        $data_perangkat[$jabatan_key] = $row;
        
        // Jika jabatan adalah Kepala Dusun, simpan ke array kadus
        if (stripos($row['jabatan'], 'Kepala Dusun') !== false) {
            // Ambil nomor dusun dari jabatan (contoh: "Kepala Dusun VI" -> "VI")
            preg_match('/Kepala Dusun\s+([IVX]+)/i', $row['jabatan'], $matches);
            if (isset($matches[1])) {
                $nomor_dusun = strtoupper($matches[1]);
                $data_kadus[$nomor_dusun] = $row;
            }
        }
    }
}
?>

<style>
.org-structure {
    position: relative;
}
.connector-line {
    position: absolute;
    background: #1f2937;
}
.dark .connector-line {
    background: #64748b !important;
}
.vertical-line {
    width: 3px;
    left: 50%;
    transform: translateX(-50%);
}
.horizontal-line {
    height: 3px;
    top: 50%;
    transform: translateY(-50%);
}

/* Dark mode fixes for org chart */
.dark #line-bpd,
.dark #line-lpmd,
.dark .bg-gray-800[style*="height: 3px"] {
    background: #64748b !important;
}

.dark .modal-content {
    background-color: #1e293b !important;
    color: #e5e7eb !important;
}

.dark .close {
    color: #94a3b8 !important;
}
.dark .close:hover {
    color: #f1f5f9 !important;
}

/* Clickable cards */
.clickable-card {
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
}
.clickable-card:hover {
    transform: translateY(-5px);
}
.clickable-card::after {
    content: '▼';
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 14px;
    opacity: 0.5;
    transition: transform 0.3s ease;
}
.clickable-card.active::after {
    transform: rotate(180deg);
}

/* Detail list */
.detail-list {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.3s ease, margin 0.3s ease;
    opacity: 0;
}
.detail-list.show {
    max-height: 1000px;
    opacity: 1;
    margin-top: 16px;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.6);
}
.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background-color: #fefefe;
    padding: 30px;
    border-radius: 20px;
    max-width: 600px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
.close {
    color: #aaa;
    float: right;
    font-size: 32px;
    font-weight: bold;
    line-height: 20px;
    cursor: pointer;
    transition: color 0.3s;
}
.close:hover,
.close:focus {
    color: #000;
}
</style>

<div class="bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50 py-8 md:py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-2 md:px-4 max-w-7xl relative z-10">
        <div class="text-center mb-8 md:mb-16">
            <div class="inline-block bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-diagram-3-fill"></i> Pemerintahan Desa
                </span>
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 mb-6">Struktur Organisasi</h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-indigo-500 rounded-full"></div>
                <div class="h-2 w-2 bg-indigo-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full"></div>
                <div class="h-2 w-2 bg-pink-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-pink-500 rounded-full"></div>
            </div>
            <p class="text-sm md:text-lg text-gray-600 max-w-3xl mx-auto px-4">Pemerintahan Desa Natar yang siap melayani masyarakat dengan dedikasi dan profesionalitas</p>
        </div>

        <div class="bg-white rounded-xl md:rounded-3xl shadow-2xl hover:shadow-indigo-200/50 transition-all duration-500 border-2 border-indigo-100 p-3 md:p-8 org-structure mb-8 md:mb-12 relative overflow-x-auto">

            <!-- Garis penghubung atas ke BPD dan LPMD -->
            <div id="top-connector" class="hidden lg:block" style="position: absolute; inset: 0; height: 0; pointer-events: none;">
                <div id="line-bpd" class="absolute connector-line" style="height: 3px;"></div>
                <div id="line-lpmd" class="absolute connector-line" style="height: 3px;"></div>
            </div>

            <!-- Grid 3 Kolom: BPD (Kiri) | Struktur Pemerintah Desa (Tengah) | LPMD (Kanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- KOLOM KIRI: BPD -->
                <div class="lg:col-span-2 mt-12 lg:mt-24">
                    <div id="bpd-panel" class="border-4 border-indigo-400 rounded-xl p-3 bg-gradient-to-br from-white to-indigo-50 shadow-2xl hover:shadow-indigo-300/50 transition-all duration-500">
                        <div class="text-center mb-3">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-2 py-1 rounded-lg text-xs font-bold shadow-lg">
                                KETUA BPD
                            </div>
                        </div>
                        <?php
                        $wakil_bpd = null;
                        $wakil_bpd_index = null;
                        for ($i = 1; $i < count($data_bpd); $i++) {
                            if (stripos($data_bpd[$i]['jabatan'], 'Wakil') !== false) {
                                $wakil_bpd = $data_bpd[$i];
                                $wakil_bpd_index = $i;
                                break;
                            }
                        }
                        ?>
                        <?php if (isset($data_bpd[0])): ?>
                        <div class="bg-indigo-50 border-2 border-indigo-300 p-2 rounded-lg mb-2">
                            <div class="w-16 h-16 bg-white rounded-full overflow-hidden border-2 border-indigo-500 mx-auto mb-1">
                                <?php if (!empty($data_bpd[0]['foto'])): ?>
                                    <img src="assets/img/perangkat/<?php echo $data_bpd[0]['foto']; ?>?v=<?php echo filemtime('assets/img/perangkat/'.$data_bpd[0]['foto']); ?>" alt="<?php echo $data_bpd[0]['nama']; ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($data_bpd[0]['nama']); ?>&size=200&background=6366f1&color=fff&bold=true'">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data_bpd[0]['nama']); ?>&size=200&background=6366f1&color=fff&bold=true" alt="<?php echo $data_bpd[0]['nama']; ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <p class="font-bold text-[10px] text-indigo-900 text-center leading-tight"><?php echo strtoupper($data_bpd[0]['nama']); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($wakil_bpd): ?>
                        <div class="bg-indigo-50 border-2 border-indigo-300 p-2 rounded-lg mb-2">
                            <div class="w-16 h-16 bg-white rounded-full overflow-hidden border-2 border-indigo-500 mx-auto mb-1">
                                <?php if (!empty($wakil_bpd['foto'])): ?>
                                    <img src="assets/img/perangkat/<?php echo $wakil_bpd['foto']; ?>" alt="<?php echo $wakil_bpd['nama']; ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($wakil_bpd['nama']); ?>&size=200&background=6366f1&color=fff&bold=true'">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($wakil_bpd['nama']); ?>&size=200&background=6366f1&color=fff&bold=true" alt="<?php echo $wakil_bpd['nama']; ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <p class="font-bold text-[10px] text-indigo-900 text-center leading-tight"><?php echo strtoupper($wakil_bpd['nama']); ?></p>
                            <p class="text-[9px] text-indigo-700 font-semibold text-center">WAKIL KETUA</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="bg-indigo-50 border-2 border-indigo-300 rounded-lg p-2">
                            <h5 class="font-bold text-center text-indigo-900 text-[10px] mb-1 border-b border-indigo-300 pb-1">PENGURUS BPD</h5>
                            <div class="space-y-1 text-[9px]">
                                <?php for ($i = 1; $i < count($data_bpd); $i++): ?>
                                <?php if ($i === $wakil_bpd_index) { continue; } ?>
                                <div class="bg-white p-1 rounded">
                                    <p class="font-semibold text-indigo-700"><?php echo ($data_bpd[$i]['jabatan'] == 'Sekretaris') ? 'SEKRETARIS' : 'ANGGOTA'; ?></p>
                                    <p class="text-indigo-900"><?php echo $data_bpd[$i]['nama']; ?></p>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM TENGAH: STRUKTUR PEMERINTAH DESA -->
                <div class="lg:col-span-8">
            
            <!-- Level 1: Kepala Desa -->
            <div class="flex justify-center relative" style="margin-bottom: 0;">
                <div id="kepala-desa-card" class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white px-6 md:px-12 py-6 md:py-8 rounded-xl md:rounded-2xl shadow-xl md:shadow-2xl border-2 md:border-4 border-emerald-400 text-center transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 md:w-32 md:h-32 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-emerald-300 shadow-lg">
                        <?php 
                        $kepala_desa = $data_perangkat['kepala_desa'] ?? null;
                        
                        // Cari foto dengan berbagai ekstensi
                        if ($kepala_desa && !empty($kepala_desa['foto'])) {
                            $foto_kades = 'assets/img/perangkat/'.$kepala_desa['foto'];
                            // Jika file tidak ada, coba ganti ekstensi
                            if (!file_exists($foto_kades)) {
                                $nama_base = pathinfo($kepala_desa['foto'], PATHINFO_FILENAME);
                                $ekstensi = ['jpeg', 'jpg', 'png', 'JPG', 'JPEG', 'PNG'];
                                foreach ($ekstensi as $ext) {
                                    $coba_path = "assets/img/perangkat/$nama_base.$ext";
                                    if (file_exists($coba_path)) {
                                        $foto_kades = $coba_path;
                                        break;
                                    }
                                }
                            }
                        } else {
                            // Coba foto default dengan berbagai ekstensi
                            $ekstensi_default = ['jpeg', 'jpg', 'png'];
                            $foto_kades = '';
                            foreach ($ekstensi_default as $ext) {
                                if (file_exists("assets/img/perangkat/kepala-desa.$ext")) {
                                    $foto_kades = "assets/img/perangkat/kepala-desa.$ext";
                                    break;
                                }
                            }
                            // Jika tidak ada, set path default
                            if (empty($foto_kades)) {
                                $foto_kades = 'assets/img/perangkat/kepala-desa.jpeg';
                            }
                        }
                        
                        $nama_kades = $kepala_desa ? $kepala_desa['nama'] : 'M. ARIF, S.Pd.';
                        $nama_avatar = urlencode($nama_kades);
                        ?>
                        <img src="<?php echo $foto_kades; ?>" alt="Kepala Desa" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $nama_avatar; ?>&size=200&background=10b981&color=fff&bold=true';">
                    </div>
                    <h3 class="font-bold text-lg md:text-2xl mb-1"><?php echo strtoupper($nama_kades); ?></h3>
                    <p class="text-emerald-100 text-sm md:text-base font-bold tracking-wide">KEPALA DESA</p>
                </div>
            </div>

            <!-- Garis dari Kepala Desa -->
            <div class="relative hidden md:block" style="height: 80px;">
                <!-- Garis vertikal turun dari kepala desa -->
                <div class="absolute bg-gray-800" style="width: 3px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                
                <!-- Garis horizontal -->
                <div class="absolute bg-gray-800" style="height: 3px; width: 50%; left: 25%; top: 40px;"></div>
                
                <!-- Garis vertikal kiri ke 3 Kepala Seksi -->
                <div class="absolute bg-gray-800" style="width: 3px; height: 65px; left: 25%; top: 40px;"></div>
                
                <!-- Garis vertikal kanan ke Sekretariat -->
                <div class="absolute bg-gray-800" style="width: 3px; height: 50px; left: 75%; top: 40px;"></div>
            </div>

            <!-- Layout 2 kolom: Kepala Seksi (kiri) dan Sekretariat (kanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-16 max-w-6xl mx-auto relative">
                
                <!-- KOLOM KIRI: 3 Kepala Seksi -->
                <div class="relative">
                    <div class="space-y-6 relative">
                        <!-- Garis vertikal utama untuk 3 Kasi di sebelah KANAN -->
                        <div class="absolute bg-gray-800" style="width: 3px; height: 128%; right: 0; top: 0;"></div>
                        
                        <!-- Kepala Seksi Pemerintahan -->
                        <div class="relative">
                            <?php 
                            $kasi_pem = $data_perangkat['kepala_seksi_pemerintahan'] ?? null;
                            $nama_kasi_pem = $kasi_pem ? $kasi_pem['nama'] : 'VIKI REZA PURNAMA';
                            $foto_kasi_pem = $kasi_pem && $kasi_pem['foto'] ? 'assets/img/perangkat/' . $kasi_pem['foto'] : 'assets/img/perangkat/kasi-pemerintahan.jpeg';
                            $nama_url = urlencode(str_replace(' ', '+', $nama_kasi_pem));
                            ?>
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 3px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-blue-100 to-blue-200 border-2 md:border-4 border-blue-400 p-4 md:p-9 rounded-xl md:rounded-2xl shadow-2xl hover:shadow-blue-300/60 transition-all duration-300 transform hover:-translate-y-1" style="margin-right: 50px;">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-blue-500 shadow-md">
                            <img src="<?= htmlspecialchars($foto_kasi_pem) ?>" alt="Kasi Pemerintahan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=3b82f6&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kasi_pem) ?></h4>
                                <p class="text-blue-700 text-xs md:text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Pemerintahan</p>
                            </div>
                        </div>

                        <!-- Kepala Seksi Kesejahteraan -->
                        <div class="relative">
                            <?php 
                            $kasi_kes = $data_perangkat['kepala_seksi_kesejahteraan'] ?? null;
                            $nama_kasi_kes = $kasi_kes ? $kasi_kes['nama'] : 'HERTATI';
                            $foto_kasi_kes = $kasi_kes && $kasi_kes['foto'] ? 'assets/img/perangkat/' . $kasi_kes['foto'] : 'assets/img/perangkat/kasi-kesejahteraan.jpeg';
                            $nama_url = urlencode(str_replace(' ', '+', $nama_kasi_kes));
                            ?>
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 3px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 border-2 md:border-4 border-purple-400 p-4 md:p-9 rounded-xl md:rounded-2xl shadow-2xl hover:shadow-purple-300/60 transition-all duration-300 transform hover:-translate-y-1" style="margin-right: 50px;">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-purple-500 shadow-md">
                            <img src="<?= htmlspecialchars($foto_kasi_kes) ?>" alt="Kasi Kesejahteraan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=a855f7&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kasi_kes) ?></h4>
                                <p class="text-purple-700 text-xs md:text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Kesejahteraan</p>
                            </div>
                        </div>

                        <!-- Kepala Seksi Pelayanan -->
                        <div class="relative">
                            <?php 
                            $kasi_pel = $data_perangkat['kepala_seksi_pelayanan'] ?? null;
                            $nama_kasi_pel = $kasi_pel ? $kasi_pel['nama'] : 'SUHARTATI';
                            $foto_kasi_pel = $kasi_pel && $kasi_pel['foto'] ? 'assets/img/perangkat/' . $kasi_pel['foto'] : 'assets/img/perangkat/kasi-pelayanan.jpeg';
                            $nama_url = urlencode(str_replace(' ', '+', $nama_kasi_pel));
                            ?>
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 3px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-pink-100 to-pink-200 border-2 md:border-4 border-pink-400 p-4 md:p-9 rounded-xl md:rounded-2xl shadow-2xl hover:shadow-pink-300/60 transition-all duration-300 transform hover:-translate-y-1" style="margin-right: 50px;">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-pink-500 shadow-md">
                            <img src="<?= htmlspecialchars($foto_kasi_pel) ?>" alt="Kasi Pelayanan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=ec4899&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kasi_pel) ?></h4>
                                <p class="text-pink-700 text-xs md:text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Pelayanan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Sekretariat + 3 Kaur -->
                <div class="relative">
                    <!-- Sekretariat Desa -->
                    <div class="mb-8">
                        <?php 
                        $sekretaris = $data_perangkat['sekretaris_desa'] ?? null;
                        $nama_sekretaris = $sekretaris ? $sekretaris['nama'] : 'EGI MEIRAWAN';
                        $foto_sekretaris = $sekretaris && $sekretaris['foto'] ? 'assets/img/perangkat/' . $sekretaris['foto'] : 'assets/img/perangkat/sekretaris.jpeg';
                        $nama_url = urlencode(str_replace(' ', '+', $nama_sekretaris));
                        ?>
                        <div class="bg-gradient-to-br from-orange-100 to-orange-200 border-2 md:border-4 border-orange-400 p-4 md:p-9 rounded-xl md:rounded-2xl shadow-2xl hover:shadow-orange-300/60 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-orange-500 shadow-md">
                                <img src="<?= htmlspecialchars($foto_sekretaris) ?>" alt="Sekretaris Desa" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=f97316&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_sekretaris) ?></h4>
                            <p class="text-orange-700 text-xs md:text-sm font-bold uppercase tracking-wide">Sekretariat Desa</p>
                        </div>
                    </div>

                    <!-- Garis vertikal dari Sekretariat ke bawah dan horizontal ke garis vertikal Kaur -->
                    <div class="relative hidden lg:block" style="height: 40px;">
                        <!-- Garis vertikal dari Sekretariat -->
                        <div class="absolute bg-gray-800" style="width: 3px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                        
                        <!-- Garis horizontal ke kiri menuju garis vertikal Kaur -->
                        <div class="absolute bg-gray-800" style="height: 3px; width: 50%; left: 0; top: 20px;"></div>
                        
                        <!-- Garis vertikal turun ke garis vertikal Kaur -->
                        <div class="absolute bg-gray-800" style="width: 3px; height: 50px; left: 0; top: 20px;"></div>
                    </div>

                    <!-- 3 Kaur dengan garis vertikal -->
                    <div class="relative">
                        <!-- Garis vertikal utama untuk 3 kaur di sebelah KIRI -->
                        <div class="absolute bg-gray-800" style="width: 3px; height: 100%; left: 0; top: 0;"></div>
                        
                        <div class="space-y-6 relative">
                            <!-- Kaur TU -->
                            <div class="relative">
                                <?php 
                                $kaur_tu = $data_perangkat['kepala_urusan_umum_dan_tata_usaha'] ?? null;
                                $nama_kaur_tu = $kaur_tu ? $kaur_tu['nama'] : 'YEKO BAGUS CAHYANI';
                                $foto_kaur_tu = $kaur_tu && $kaur_tu['foto'] ? 'assets/img/perangkat/' . $kaur_tu['foto'] : 'assets/img/perangkat/kaur-tu.jpeg';
                                $nama_url = urlencode(str_replace(' ', '+', $nama_kaur_tu));
                                ?>
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 3px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-2 md:border-4 border-orange-300 p-4 md:p-9 rounded-lg md:rounded-xl shadow-md md:shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-16 h-16 md:w-24 md:h-24 bg-orange-50 rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-3 border-orange-400">
                                        <img src="<?= htmlspecialchars($foto_kaur_tu) ?>" alt="Kaur TU" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kaur_tu) ?></h5>
                                    <p class="text-xs md:text-sm text-orange-600 font-semibold uppercase">Kaur Umum & Tata Usaha</p>
                                </div>
                            </div>

                            <!-- Kaur Perencanaan -->
                            <div class="relative">
                                <?php 
                                $kaur_ren = $data_perangkat['kepala_urusan_perencanaan'] ?? null;
                                $nama_kaur_ren = $kaur_ren ? $kaur_ren['nama'] : 'EKKI REYNALDI';
                                $foto_kaur_ren = $kaur_ren && $kaur_ren['foto'] ? 'assets/img/perangkat/' . $kaur_ren['foto'] : 'assets/img/perangkat/kaur-perencanaan.jpeg';
                                $nama_url = urlencode(str_replace(' ', '+', $nama_kaur_ren));
                                ?>
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 3px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-2 md:border-4 border-orange-300 p-4 md:p-9 rounded-lg md:rounded-xl shadow-md md:shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-16 h-16 md:w-24 md:h-24 bg-orange-50 rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-3 border-orange-400">
                                        <img src="<?= htmlspecialchars($foto_kaur_ren) ?>" alt="Kaur Perencanaan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kaur_ren) ?></h5>
                                    <p class="text-xs md:text-sm text-orange-600 font-semibold uppercase">Kaur Perencanaan</p>
                                </div>
                            </div>

                            <!-- Kaur Keuangan -->
                            <div class="relative">
                                <?php 
                                $kaur_keu = $data_perangkat['kepala_urusan_keuangan'] ?? null;
                                $nama_kaur_keu = $kaur_keu ? $kaur_keu['nama'] : 'SUCI RAHAYU';
                                $foto_kaur_keu = $kaur_keu && $kaur_keu['foto'] ? 'assets/img/perangkat/' . $kaur_keu['foto'] : 'assets/img/perangkat/kaur-keuangan.jpeg';
                                $nama_url = urlencode(str_replace(' ', '+', $nama_kaur_keu));
                                ?>
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 3px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-2 md:border-4 border-orange-300 p-4 md:p-9 rounded-lg md:rounded-xl shadow-md md:shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-16 h-16 md:w-24 md:h-24 bg-orange-50 rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-3 border-orange-400">
                                        <img src="<?= htmlspecialchars($foto_kaur_keu) ?>" alt="Kaur Keuangan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= $nama_url ?>&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-base md:text-lg text-gray-800 mb-1"><?= htmlspecialchars($nama_kaur_keu) ?></h5>
                                    <p class="text-xs md:text-sm text-orange-600 font-semibold uppercase">Kaur Keuangan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Garis penghubung dari kedua kolom ke Operator & Bendahara -->
            <div class="relative hidden lg:block" style="height: 80px;">
                <div class="max-w-6xl mx-auto relative" style="height: 100%;">
                    <!-- Garis horizontal menghubungkan kedua kolom -->
                    <div class="absolute bg-gray-800" style="height: 3px; width: calc(5% + 3px); left: calc(48% - 3px); top: 0;"></div>
                    
                    <!-- Garis vertikal dari tengah turun -->
                    <div class="absolute bg-gray-800" style="width: 3px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                    
                    <!-- Garis horizontal untuk cabang 2 kotak -->
                    <div class="absolute bg-gray-800" style="height: 3px; width: 180px; left: 50%; transform: translateX(-50%); top: 40px;"></div>
                    
                    <!-- Garis vertikal ke Operator (kiri) -->
                    <div class="absolute bg-gray-800" style="width: 3px; height: 40px; left: calc(50% - 90px); top: 40px;"></div>
                    
                    <!-- Garis vertikal ke Bendahara (kanan) -->
                    <div class="absolute bg-gray-800" style="width: 3px; height: 40px; left: calc(50% + 90px); top: 40px;"></div>
                </div>
            </div>

            <!-- Operator & Bendahara -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 max-w-5xl mx-auto">
                <!-- Operator -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 md:border-4 border-gray-400 px-4 md:px-8 py-4 md:py-6 rounded-xl md:rounded-2xl shadow-lg md:shadow-xl text-center hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-gray-500 shadow-md">
                        <img src="assets/img/perangkat/operator.jpeg" alt="Operator" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=DESMIATI&size=200&background=6b7280&color=fff&bold=true'">
                    </div>
                    <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1">DESMIATI ARIANTINI</h4>
                    <p class="text-gray-600 text-xs md:text-sm font-bold uppercase tracking-wide">Operator</p>
                </div>

                <!-- Bendahara -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 md:border-4 border-gray-400 px-4 md:px-8 py-4 md:py-6 rounded-xl md:rounded-2xl shadow-lg md:shadow-xl text-center hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-16 h-16 md:w-24 md:h-24 bg-white rounded-full mx-auto mb-3 md:mb-4 overflow-hidden border-2 md:border-4 border-gray-500 shadow-md">
                        <img src="assets/img/perangkat/bendahara.jpeg" alt="Bendahara" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=EKA+NOVIYANTI&size=200&background=6b7280&color=fff&bold=true'">
                    </div>
                    <h4 class="font-bold text-base md:text-lg text-gray-800 mb-1">ERA NOVIYANTI</h4>
                    <p class="text-gray-600 text-xs md:text-sm font-bold uppercase tracking-wide">Bendahara Barang</p>
                </div>
            </div>
            
            </div>
            <!-- End of KOLOM TENGAH -->

            <!-- KOLOM KANAN: LPMD -->
            <div class="lg:col-span-2 mt-12 lg:mt-24">
                <div id="lpmd-panel" class="border-4 border-teal-400 rounded-xl p-3 bg-gradient-to-br from-white to-teal-50 shadow-2xl hover:shadow-teal-300/50 transition-all duration-500">
                    <div class="text-center mb-3">
                        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white px-2 py-1 rounded-lg text-xs font-bold shadow-lg">
                            KETUA LPMD
                        </div>
                    </div>
                    <?php
                    $wakil_lpmd = null;
                    $wakil_lpmd_index = null;
                    for ($i = 1; $i < count($data_lpmd); $i++) {
                        if (stripos($data_lpmd[$i]['jabatan'], 'Wakil') !== false) {
                            $wakil_lpmd = $data_lpmd[$i];
                            $wakil_lpmd_index = $i;
                            break;
                        }
                    }
                    ?>
                    <?php if (isset($data_lpmd[0])): ?>
                    <div class="bg-teal-50 border-2 border-teal-300 p-2 rounded-lg mb-2">
                        <div class="w-16 h-16 bg-white rounded-full overflow-hidden border-2 border-teal-500 mx-auto mb-1">
                            <?php if (!empty($data_lpmd[0]['foto'])): ?>
                                <img src="assets/img/perangkat/<?php echo $data_lpmd[0]['foto']; ?>" alt="<?php echo $data_lpmd[0]['nama']; ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($data_lpmd[0]['nama']); ?>&size=200&background=14b8a6&color=fff&bold=true'">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data_lpmd[0]['nama']); ?>&size=200&background=14b8a6&color=fff&bold=true" alt="<?php echo $data_lpmd[0]['nama']; ?>" class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        <p class="font-bold text-[10px] text-teal-900 text-center leading-tight"><?php echo strtoupper($data_lpmd[0]['nama']); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($wakil_lpmd): ?>
                    <div class="bg-teal-50 border-2 border-teal-300 p-2 rounded-lg mb-2">
                        <div class="w-16 h-16 bg-white rounded-full overflow-hidden border-2 border-teal-500 mx-auto mb-1">
                            <?php if (!empty($wakil_lpmd['foto'])): ?>
                                <img src="assets/img/perangkat/<?php echo $wakil_lpmd['foto']; ?>" alt="<?php echo $wakil_lpmd['nama']; ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($wakil_lpmd['nama']); ?>&size=200&background=14b8a6&color=fff&bold=true'">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($wakil_lpmd['nama']); ?>&size=200&background=14b8a6&color=fff&bold=true" alt="<?php echo $wakil_lpmd['nama']; ?>" class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        <p class="font-bold text-[10px] text-teal-900 text-center leading-tight"><?php echo strtoupper($wakil_lpmd['nama']); ?></p>
                        <p class="text-[9px] text-teal-700 font-semibold text-center">WAKIL KETUA</p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="bg-teal-50 border-2 border-teal-300 rounded-lg p-2">
                        <h5 class="font-bold text-center text-teal-900 text-[10px] mb-1 border-b border-teal-300 pb-1">PENGURUS LPMD</h5>
                        <div class="space-y-1 text-[9px]">
                            <?php for ($i = 1; $i < count($data_lpmd); $i++): ?>
                            <?php if ($i === $wakil_lpmd_index) { continue; } ?>
                            <div class="bg-white p-1 rounded">
                                <p class="font-semibold text-teal-700"><?php echo ($data_lpmd[$i]['jabatan'] == 'Sekretaris') ? 'SEKRETARIS' : 'ANGGOTA'; ?></p>
                                <p class="text-teal-900"><?php echo $data_lpmd[$i]['nama']; ?></p>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>
            <!-- End of Grid 3 Kolom -->

            <!-- Section Kepala Dusun -->
            <div class="mt-20 pt-12 border-t-4 border-emerald-500">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">KEPALA DUSUN</h2>
                    <div class="w-32 h-1 bg-emerald-500 mx-auto"></div>
                </div>
                
                <!-- Dusun I, II, III -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN I - III
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php 
                        $dusun_group_1 = ['I', 'II', 'III'];
                        foreach ($dusun_group_1 as $idx => $dusun):
                            $kadus = $data_kadus[$dusun] ?? null;
                            $nama_kadus = $kadus ? $kadus['nama'] : 'TIDAK ADA DATA';
                            $foto_kadus = $kadus && $kadus['foto'] ? 'assets/img/perangkat/'.$kadus['foto'] : 'assets/img/perangkat/kadus-'.($idx+1).'.jpeg';
                            $nama_avatar = urlencode($nama_kadus);
                        ?>
                        <div class="clickable-card bg-gradient-to-br from-emerald-50 to-emerald-100 border-4 border-emerald-500 p-6 rounded-2xl shadow-2xl hover:shadow-emerald-300/60 transition-all duration-300 transform hover:-translate-y-2" onclick="toggleDetails('rt-kadus-<?php echo $idx+1; ?>', '<?php echo $dusun; ?>', this, 'emerald')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-emerald-600 shadow-md">
                                <img src="<?php echo $foto_kadus; ?>" alt="Kepala Dusun <?php echo $dusun; ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $nama_avatar; ?>&size=200&background=10b981&color=fff&bold=true';">
                            </div>
                            <h4 class="font-bold text-lg text-emerald-900 mb-1"><?php echo strtoupper($nama_kadus); ?></h4>
                            <p class="text-emerald-700 font-bold text-sm mb-2">KEPALA DUSUN <?php echo $dusun; ?></p>
                            <p class="text-xs text-emerald-600 bg-emerald-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dusun IV, V, VI -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN IV - VI
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php 
                        $dusun_group_2 = ['IV', 'V', 'VI'];
                        foreach ($dusun_group_2 as $idx => $dusun):
                            $kadus = $data_kadus[$dusun] ?? null;
                            $nama_kadus = $kadus ? $kadus['nama'] : 'TIDAK ADA DATA';
                            $foto_kadus = $kadus && $kadus['foto'] ? 'assets/img/perangkat/'.$kadus['foto'] : 'assets/img/perangkat/kadus-'.($idx+4).'.jpeg';
                            $nama_avatar = urlencode($nama_kadus);
                        ?>
                        <div class="clickable-card bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-500 p-6 rounded-2xl shadow-2xl hover:shadow-blue-300/60 transition-all duration-300 transform hover:-translate-y-2" onclick="toggleDetails('rt-kadus-<?php echo $idx+4; ?>', '<?php echo $dusun; ?>', this, 'blue')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-blue-600 shadow-md">
                                <img src="<?php echo $foto_kadus; ?>" alt="Kepala Dusun <?php echo $dusun; ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $nama_avatar; ?>&size=200&background=3b82f6&color=fff&bold=true';">
                            </div>
                            <h4 class="font-bold text-lg text-blue-900 mb-1"><?php echo strtoupper($nama_kadus); ?></h4>
                            <p class="text-blue-700 font-bold text-sm mb-2">KEPALA DUSUN <?php echo $dusun; ?></p>
                            <p class="text-xs text-blue-600 bg-blue-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dusun VII, VIII, IX -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN VII - IX
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php 
                        $dusun_group_3 = ['VII', 'VIII', 'IX'];
                        foreach ($dusun_group_3 as $idx => $dusun):
                            $kadus = $data_kadus[$dusun] ?? null;
                            $nama_kadus = $kadus ? $kadus['nama'] : 'TIDAK ADA DATA';
                            $foto_kadus = $kadus && $kadus['foto'] ? 'assets/img/perangkat/'.$kadus['foto'] : 'assets/img/perangkat/kadus-'.($idx+7).'.jpeg';
                            $nama_avatar = urlencode($nama_kadus);
                        ?>
                        <div class="clickable-card bg-gradient-to-br from-purple-50 to-purple-100 border-4 border-purple-500 p-6 rounded-2xl shadow-2xl hover:shadow-purple-300/60 transition-all duration-300 transform hover:-translate-y-2" onclick="toggleDetails('rt-kadus-<?php echo $idx+7; ?>', '<?php echo $dusun; ?>', this, 'purple')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-purple-600 shadow-md">
                                <img src="<?php echo $foto_kadus; ?>" alt="Kepala Dusun <?php echo $dusun; ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $nama_avatar; ?>&size=200&background=a855f7&color=fff&bold=true';">
                            </div>
                            <h4 class="font-bold text-lg text-purple-900 mb-1"><?php echo strtoupper($nama_kadus); ?></h4>
                            <p class="text-purple-700 font-bold text-sm mb-2">KEPALA DUSUN <?php echo $dusun; ?></p>
                            <p class="text-xs text-purple-600 bg-purple-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dusun X, XI -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN X - XI
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                        <?php 
                        $dusun_group_4 = ['X', 'XI'];
                        foreach ($dusun_group_4 as $idx => $dusun):
                            $kadus = $data_kadus[$dusun] ?? null;
                            $nama_kadus = $kadus ? $kadus['nama'] : 'TIDAK ADA DATA';
                            $foto_kadus = $kadus && $kadus['foto'] ? 'assets/img/perangkat/'.$kadus['foto'] : 'assets/img/perangkat/kadus-'.($idx+10).'.jpeg';
                            $nama_avatar = urlencode($nama_kadus);
                        ?>
                        <div class="clickable-card bg-gradient-to-br from-orange-50 to-orange-100 border-4 border-orange-500 p-6 rounded-2xl shadow-2xl hover:shadow-orange-300/60 transition-all duration-300 transform hover:-translate-y-2" onclick="toggleDetails('rt-kadus-<?php echo $idx+10; ?>', '<?php echo $dusun; ?>', this, 'orange')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-orange-600 shadow-md">
                                <img src="<?php echo $foto_kadus; ?>" alt="Kepala Dusun <?php echo $dusun; ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $nama_avatar; ?>&size=200&background=f97316&color=fff&bold=true';">
                            </div>
                            <h4 class="font-bold text-lg text-orange-900 mb-1"><?php echo strtoupper($nama_kadus); ?></h4>
                            <p class="text-orange-700 font-bold text-sm mb-2">KEPALA DUSUN <?php echo $dusun; ?></p>
                            <p class="text-xs text-orange-600 bg-orange-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="profil.php" class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:underline"><i class="bi bi-arrow-left"></i> Kembali ke Profil Desa</a>
        </div>
    </div>
</div>

<!-- Modal untuk detail RT -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalBody"></div>
    </div>
</div>

<script>
// Data RT dari PHP
const dataRT = <?php echo json_encode($data_rt); ?>;

// Data BPD dan LPMD
const dataBPD = <?php echo json_encode(array_slice($data_bpd, 3)); ?>;
const dataLPMD = <?php echo json_encode(array_slice($data_lpmd, 3)); ?>;

// Mapping nama dusun
const dusunNames = {
    'I': 'DUSUN I', 'II': 'DUSUN II', 'III': 'DUSUN III',
    'IV': 'DUSUN IV', 'V': 'DUSUN V', 'VI': 'DUSUN VI',
    'VII': 'DUSUN VII', 'VIII': 'DUSUN VIII', 'IX': 'DUSUN IX',
    'X': 'DUSUN X', 'XI': 'DUSUN XI'
};

// Color schemes
const colors = {
    'emerald': { bg: 'from-emerald-500 to-emerald-600', border: 'border-emerald-300', bgLight: 'from-emerald-50 to-emerald-100' },
    'blue': { bg: 'from-blue-500 to-blue-600', border: 'border-blue-300', bgLight: 'from-blue-50 to-blue-100' },
    'purple': { bg: 'from-purple-500 to-purple-600', border: 'border-purple-300', bgLight: 'from-purple-50 to-purple-100' },
    'orange': { bg: 'from-orange-500 to-orange-600', border: 'border-orange-300', bgLight: 'from-orange-50 to-orange-100' },
    'indigo': { bg: 'from-indigo-500 to-indigo-600', border: 'border-indigo-300', bgLight: 'from-indigo-50 to-indigo-100' },
    'teal': { bg: 'from-teal-500 to-teal-600', border: 'border-teal-300', bgLight: 'from-teal-50 to-teal-100' }
};

function positionTopConnector() {
    const container = document.getElementById('top-connector');
    const lineBpd = document.getElementById('line-bpd');
    const lineLpmd = document.getElementById('line-lpmd');
    if (!container || !lineBpd || !lineLpmd) return;

    const containerRect = container.getBoundingClientRect();
    const bpdPanel = document.getElementById('bpd-panel');
    const lpmdPanel = document.getElementById('lpmd-panel');
    const kepalaCard = document.getElementById('kepala-desa-card');

    const lineY = (() => {
        if (!kepalaCard) return 0;
        const rect = kepalaCard.getBoundingClientRect();
        return rect.top + rect.height * 0.5 - containerRect.top;
    })();

    const centerX = (el, fallbackPercent) => {
        if (!el) return (fallbackPercent / 100) * containerRect.width;
        const rect = el.getBoundingClientRect();
        return rect.left + rect.width / 2 - containerRect.left;
    };

    const kCenter = centerX(kepalaCard, 50);
    const bCenter = centerX(bpdPanel, 13);
    const lCenter = centerX(lpmdPanel, 87);

    const shortenSpan = (a, b, factor = 0.6) => {
        const mid = (a + b) / 2;
        const half = Math.abs(a - b) * factor / 2;
        return [mid - half, mid + half];
    };

    const setLine = (lineEl, start, end) => {
        const [shortStart, shortEnd] = shortenSpan(start, end);
        const left = Math.min(shortStart, shortEnd);
        const width = Math.abs(shortEnd - shortStart);
        lineEl.style.left = `${left}px`;
        lineEl.style.width = `${width}px`;
        lineEl.style.top = `${lineY}px`;
    };

    setLine(lineBpd, kCenter, bCenter);
    setLine(lineLpmd, kCenter, lCenter);
}

window.addEventListener('resize', positionTopConnector);
window.addEventListener('load', () => {
    positionTopConnector();
    setTimeout(positionTopConnector, 200);
});

function toggleDetails(id, dusun, element, colorScheme) {
    const gridContainer = element.closest('.grid, .max-w-6xl');
    const detailList = document.getElementById(id);
    
    // Toggle if already exists
    if (detailList) {
        if (detailList.classList.contains('show')) {
            detailList.classList.remove('show');
            element.classList.remove('active');
            setTimeout(() => detailList.remove(), 500);
        } else {
            detailList.classList.add('show');
            element.classList.add('active');
        }
        return;
    }
    
    // Create new detail list
    let content = '';
    
    // Handle RT list
    if (dusun && dataRT[dusun]) {
        const color = colors[colorScheme] || colors['emerald'];
        const rtList = dataRT[dusun];
        
        content = `
            <div class="text-center mb-4">
                <h6 class="font-bold text-gray-800 text-lg">${dusunNames[dusun]}</h6>
                <p class="text-gray-600 text-sm">Daftar Ketua RT (${rtList.length} RT)</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        `;
        
        rtList.forEach((item) => {
            content += `
                <div class="bg-white border-2 ${color.border} rounded-lg p-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br ${color.bg} text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                            ${item.nomor_rt}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-semibold">Ketua RT ${item.nomor_rt}</p>
                            <p class="text-sm font-bold text-gray-800">${item.nama_ketua}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        content += '</div>';
    }
    
    // Handle Anggota BPD
    if (id === 'anggota-bpd') {
        const color = colors[colorScheme] || colors['indigo'];
        content = `
            <div class="text-center mb-6">
                <h6 class="font-bold text-gray-800 text-xl">Anggota BPD</h6>
                <p class="text-gray-600 text-sm">Daftar lengkap anggota Badan Permusyawaratan Desa</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        `;
        
        dataBPD.forEach((item) => {
            content += `
                <div class="bg-white border-2 ${color.border} rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-gradient-to-br ${color.bg} text-white rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-lg shadow">
                        ${item.nama.charAt(0)}
                    </div>
                    <p class="text-sm font-bold text-gray-800">${item.nama}</p>
                    <p class="text-xs text-gray-500">${item.jabatan}</p>
                </div>
            `;
        });
        content += '</div>';
    }
    
    // Handle Anggota LPMD
    if (id === 'anggota-lpmd') {
        const color = colors[colorScheme] || colors['teal'];
        content = `
            <div class="text-center mb-6">
                <h6 class="font-bold text-gray-800 text-xl">Anggota LPMD</h6>
                <p class="text-gray-600 text-sm">Daftar lengkap anggota Lembaga Pemberdayaan Masyarakat Desa</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        `;
        
        dataLPMD.forEach((item) => {
            content += `
                <div class="bg-white border-2 ${color.border} rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-gradient-to-br ${color.bg} text-white rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-lg shadow">
                        ${item.nama.charAt(0)}
                    </div>
                    <p class="text-sm font-bold text-gray-800">${item.nama}</p>
                    <p class="text-xs text-gray-500">${item.jabatan}</p>
                </div>
            `;
        });
        content += '</div>';
    }
    
    if (!content) return;
    
    const color = colors[colorScheme] || colors['emerald'];
    const detailContainer = document.createElement('div');
    detailContainer.id = id;
    detailContainer.className = `detail-list bg-gradient-to-br ${color.bgLight} p-6 rounded-xl shadow-lg border-2 ${color.border}`;
    detailContainer.style.gridColumn = '1 / -1';
    detailContainer.style.marginTop = '16px';
    detailContainer.innerHTML = content;
    
    gridContainer.appendChild(detailContainer);
    
    setTimeout(() => {
        detailContainer.classList.add('show');
        element.classList.add('active');
    }, 10);
}

function closeModal() {
    document.getElementById('detailModal').classList.remove('show');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target == modal) {
        modal.classList.remove('show');
    }
}

// Cache busting untuk semua gambar perangkat - memastikan foto terbaru selalu dimuat
document.addEventListener('DOMContentLoaded', function() {
    const timestamp = new Date().getTime();
    const perangkatImages = document.querySelectorAll('img[src*="assets/img/perangkat/"]');
    perangkatImages.forEach(img => {
        const currentSrc = img.src;
        // Hanya tambahkan timestamp jika belum ada
        if (!currentSrc.includes('?v=') && !currentSrc.includes('ui-avatars.com')) {
            img.src = currentSrc + '?v=' + timestamp;
        }
    });
});
</script>

<?php require 'includes/footer.php'; ?>
