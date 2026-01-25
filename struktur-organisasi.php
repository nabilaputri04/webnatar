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
?>

<style>
.org-structure {
    position: relative;
}
.connector-line {
    position: absolute;
    background: #1f2937;
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

<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Struktur Organisasi</h1>
            <div class="w-24 h-1 bg-emerald-500 mx-auto mb-4"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">Pemerintahan Desa Natar yang siap melayani masyarakat dengan dedikasi dan profesionalitas</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-8 org-structure mb-12 relative">

            <!-- Garis penghubung atas ke BPD dan LPMD -->
            <div id="top-connector" class="hidden lg:block" style="position: absolute; inset: 0; height: 0; pointer-events: none;">
                <div id="line-bpd" class="absolute bg-gray-800" style="height: 1px;"></div>
                <div id="line-lpmd" class="absolute bg-gray-800" style="height: 1px;"></div>
            </div>

            <!-- Grid 3 Kolom: BPD (Kiri) | Struktur Pemerintah Desa (Tengah) | LPMD (Kanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- KOLOM KIRI: BPD -->
                <div class="lg:col-span-2 mt-12 lg:mt-24">
                    <div id="bpd-panel" class="border-4 border-indigo-400 rounded-xl p-3 bg-white shadow-lg">
                        <div class="text-center mb-3">
                            <div class="bg-indigo-600 text-white px-2 py-1 rounded text-xs font-bold">
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
                                    <img src="assets/img/perangkat/<?php echo $data_bpd[0]['foto']; ?>" alt="<?php echo $data_bpd[0]['nama']; ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($data_bpd[0]['nama']); ?>&size=200&background=6366f1&color=fff&bold=true'">
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
                <div id="kepala-desa-card" class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white px-12 py-8 rounded-2xl shadow-2xl border-4 border-emerald-400 text-center transform hover:scale-105 transition-all duration-300">
                    <div class="w-32 h-32 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-emerald-300 shadow-lg">
                        <img src="assets/img/perangkat/kepala-desa.jpeg" alt="Kepala Desa" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=M+ARIF&size=200&background=10b981&color=fff&bold=true'">
                    </div>
                    <h3 class="font-bold text-2xl mb-1">M. ARIF, S.Pd.</h3>
                    <p class="text-emerald-100 text-base font-bold tracking-wide">KEPALA DESA</p>
                </div>
            </div>

            <!-- Garis dari Kepala Desa -->
            <div class="relative" style="height: 80px;">
                <!-- Garis vertikal turun dari kepala desa -->
                <div class="absolute bg-gray-800" style="width: 2px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                
                <!-- Garis horizontal -->
                <div class="absolute bg-gray-800" style="height: 2px; width: 50%; left: 25%; top: 40px;"></div>
                
                <!-- Garis vertikal kiri ke 3 Kepala Seksi -->
                <div class="absolute bg-gray-800" style="width: 2px; height: 65px; left: 25%; top: 40px;"></div>
                
                <!-- Garis vertikal kanan ke Sekretariat -->
                <div class="absolute bg-gray-800" style="width: 2px; height: 50px; left: 75%; top: 40px;"></div>
            </div>

            <!-- Layout 2 kolom: Kepala Seksi (kiri) dan Sekretariat (kanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 max-w-6xl mx-auto relative">
                
                <!-- KOLOM KIRI: 3 Kepala Seksi -->
                <div class="relative">
                    <div class="space-y-6 relative">
                        <!-- Garis vertikal utama untuk 3 Kasi di sebelah KANAN -->
                        <div class="absolute bg-gray-800" style="width: 2px; height: 128%; right: 0; top: 0;"></div>
                        
                        <!-- Kepala Seksi Pemerintahan -->
                        <div class="relative">
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 2px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-blue-100 to-blue-200 border-4 border-blue-400 p-9 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" style="margin-right: 50px;">
                        <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-blue-500 shadow-md">
                            <img src="assets/img/perangkat/kasi-pemerintahan.jpeg" alt="Kasi Pemerintahan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=YIKI+REZA&size=200&background=3b82f6&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-lg text-gray-800 mb-1">VIKI REZA PURNAMA</h4>
                                <p class="text-blue-700 text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Pemerintahan</p>
                            </div>
                        </div>

                        <!-- Kepala Seksi Kesejahteraan -->
                        <div class="relative">
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 2px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 border-4 border-purple-400 p-9 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" style="margin-right: 50px;">
                        <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-purple-500 shadow-md">
                            <img src="assets/img/perangkat/kasi-kesejahteraan.jpeg" alt="Kasi Kesejahteraan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=HERTATI&size=200&background=a855f7&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-lg text-gray-800 mb-1">HERTATI</h4>
                                <p class="text-purple-700 text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Kesejahteraan</p>
                            </div>
                        </div>

                        <!-- Kepala Seksi Pelayanan -->
                        <div class="relative">
                            <!-- Garis horizontal dari kotak ke garis vertikal kanan -->
                            <div class="absolute bg-gray-800" style="height: 2px; width: 50px; right: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="bg-gradient-to-br from-pink-100 to-pink-200 border-4 border-pink-400 p-9 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" style="margin-right: 50px;">
                        <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-pink-500 shadow-md">
                            <img src="assets/img/perangkat/kasi-pelayanan.jpeg" alt="Kasi Pelayanan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=SUHARTATI&size=200&background=ec4899&color=fff&bold=true'">
                        </div>
                                <h4 class="font-bold text-lg text-gray-800 mb-1">SUHARTATI</h4>
                                <p class="text-pink-700 text-sm font-bold uppercase tracking-wide">Kepala Seksi<br>Pelayanan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Sekretariat + 3 Kaur -->
                <div class="relative">
                    <!-- Sekretariat Desa -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-br from-orange-100 to-orange-200 border-4 border-orange-400 p-9 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-orange-500 shadow-md">
                                <img src="assets/img/perangkat/sekretaris.jpeg" alt="Sekretaris Desa" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=ECI+MEIRAWAN&size=200&background=f97316&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-gray-800 mb-1">EGI MEIRAWAN</h4>
                            <p class="text-orange-700 text-sm font-bold uppercase tracking-wide">Sekretariat Desa</p>
                        </div>
                    </div>

                    <!-- Garis vertikal dari Sekretariat ke bawah dan horizontal ke garis vertikal Kaur -->
                    <div class="relative" style="height: 40px;">
                        <!-- Garis vertikal dari Sekretariat -->
                        <div class="absolute bg-gray-800" style="width: 2px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                        
                        <!-- Garis horizontal ke kiri menuju garis vertikal Kaur -->
                        <div class="absolute bg-gray-800" style="height: 2px; width: 50%; left: 0; top: 20px;"></div>
                        
                        <!-- Garis vertikal turun ke garis vertikal Kaur -->
                        <div class="absolute bg-gray-800" style="width: 2px; height: 50px; left: 0; top: 20px;"></div>
                    </div>

                    <!-- 3 Kaur dengan garis vertikal -->
                    <div class="relative">
                        <!-- Garis vertikal utama untuk 3 kaur di sebelah KIRI -->
                        <div class="absolute bg-gray-800" style="width: 2px; height: 100%; left: 0; top: 0;"></div>
                        
                        <div class="space-y-6 relative">
                            <!-- Kaur TU -->
                            <div class="relative">
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 2px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-4 border-orange-300 p-9 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-24 h-24 bg-orange-50 rounded-full mx-auto mb-4 overflow-hidden border-3 border-orange-400">
                                        <img src="assets/img/perangkat/kaur-tu.jpeg" alt="Kaur TU" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=YEKO&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-lg text-gray-800 mb-1">YEKO BAGUS CAHYANI</h5>
                                    <p class="text-sm text-orange-600 font-semibold uppercase">Kaur Umum & Tata Usaha</p>
                                </div>
                            </div>

                            <!-- Kaur Perencanaan -->
                            <div class="relative">
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 2px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-4 border-orange-300 p-9 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-24 h-24 bg-orange-50 rounded-full mx-auto mb-4 overflow-hidden border-3 border-orange-400">
                                        <img src="assets/img/perangkat/kaur-perencanaan.jpeg" alt="Kaur Perencanaan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=EKKI&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-lg text-gray-800 mb-1">EKKI REYNALDI</h5>
                                    <p class="text-sm text-orange-600 font-semibold uppercase">Kaur Perencanaan</p>
                                </div>
                            </div>

                            <!-- Kaur Keuangan -->
                            <div class="relative">
                                <!-- Garis horizontal dari garis vertikal kiri ke kotak -->
                                <div class="absolute bg-gray-800" style="height: 2px; width: 50px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                                <div class="bg-white border-4 border-orange-300 p-9 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300" style="margin-left: 50px;">
                                    <div class="w-24 h-24 bg-orange-50 rounded-full mx-auto mb-4 overflow-hidden border-3 border-orange-400">
                                        <img src="assets/img/perangkat/kaur-keuangan.jpeg" alt="Kaur Keuangan" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=SUCI&size=200&background=fed7aa&color=000'">
                                    </div>
                                    <h5 class="font-bold text-lg text-gray-800 mb-1">SUCI RAHAYU</h5>
                                    <p class="text-sm text-orange-600 font-semibold uppercase">Kaur Keuangan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Garis penghubung dari kedua kolom ke Operator & Bendahara -->
            <div class="relative" style="height: 80px;">
                <div class="max-w-6xl mx-auto relative" style="height: 100%;">
                    <!-- Garis horizontal menghubungkan kedua kolom -->
                    <div class="absolute bg-gray-800" style="height: 2px; width: calc(5% + 3px); left: calc(48% - 3px); top: 0;"></div>
                    
                    <!-- Garis vertikal dari tengah turun -->
                    <div class="absolute bg-gray-800" style="width: 2px; height: 40px; left: 50%; transform: translateX(-50%); top: 0;"></div>
                    
                    <!-- Garis horizontal untuk cabang 2 kotak -->
                    <div class="absolute bg-gray-800" style="height: 2px; width: 180px; left: 50%; transform: translateX(-50%); top: 40px;"></div>
                    
                    <!-- Garis vertikal ke Operator (kiri) -->
                    <div class="absolute bg-gray-800" style="width: 2px; height: 40px; left: calc(50% - 90px); top: 40px;"></div>
                    
                    <!-- Garis vertikal ke Bendahara (kanan) -->
                    <div class="absolute bg-gray-800" style="width: 2px; height: 40px; left: calc(50% + 90px); top: 40px;"></div>
                </div>
            </div>

            <!-- Operator & Bendahara -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 max-w-5xl mx-auto">
                <!-- Operator -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-4 border-gray-400 px-8 py-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-gray-500 shadow-md">
                        <img src="assets/img/perangkat/operator.jpeg" alt="Operator" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=DESMIATI&size=200&background=6b7280&color=fff&bold=true'">
                    </div>
                    <h4 class="font-bold text-lg text-gray-800 mb-1">DESMIATI ARIANTINI</h4>
                    <p class="text-gray-600 text-sm font-bold uppercase tracking-wide">Operator</p>
                </div>

                <!-- Bendahara -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-4 border-gray-400 px-8 py-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-gray-500 shadow-md">
                        <img src="assets/img/perangkat/bendahara.jpeg" alt="Bendahara" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=EKA+NOVIYANTI&size=200&background=6b7280&color=fff&bold=true'">
                    </div>
                    <h4 class="font-bold text-lg text-gray-800 mb-1">ERA NOVIYANTI</h4>
                    <p class="text-gray-600 text-sm font-bold uppercase tracking-wide">Bendahara Barang</p>
                </div>
            </div>
            
            </div>
            <!-- End of KOLOM TENGAH -->

            <!-- KOLOM KANAN: LPMD -->
            <div class="lg:col-span-2 mt-12 lg:mt-24">
                <div id="lpmd-panel" class="border-4 border-teal-400 rounded-xl p-3 bg-white shadow-lg">
                    <div class="text-center mb-3">
                        <div class="bg-teal-600 text-white px-2 py-1 rounded text-xs font-bold">
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
                        <div class="clickable-card bg-gradient-to-br from-emerald-50 to-emerald-100 border-4 border-emerald-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-1', 'I', this, 'emerald')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-emerald-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-1.jpeg" alt="Kepala Dusun I" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=KODRI&size=200&background=10b981&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-emerald-900 mb-1">KODRI</h4>
                            <p class="text-emerald-700 font-bold text-sm mb-2">KEPALA DUSUN I</p>
                            <p class="text-xs text-emerald-600 bg-emerald-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-emerald-50 to-emerald-100 border-4 border-emerald-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-2', 'II', this, 'emerald')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-emerald-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-2.jpeg" alt="Kepala Dusun II" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=NURMANSYAH&size=200&background=10b981&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-emerald-900 mb-1">NURMANSYAH</h4>
                            <p class="text-emerald-700 font-bold text-sm mb-2">KEPALA DUSUN II</p>
                            <p class="text-xs text-emerald-600 bg-emerald-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-emerald-50 to-emerald-100 border-4 border-emerald-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-3', 'III', this, 'emerald')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-emerald-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-3.jpeg" alt="Kepala Dusun III" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=SUHARDI&size=200&background=10b981&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-emerald-900 mb-1">SUHARDI</h4>
                            <p class="text-emerald-700 font-bold text-sm mb-2">KEPALA DUSUN III</p>
                            <p class="text-xs text-emerald-600 bg-emerald-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                    </div>
                </div>

                <!-- Dusun IV, V, VI -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN IV - VI
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="clickable-card bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-4', 'IV', this, 'blue')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-blue-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-4.jpeg" alt="Kepala Dusun IV" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=TUKIMIN&size=200&background=3b82f6&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-blue-900 mb-1">TUKIMIN</h4>
                            <p class="text-blue-700 font-bold text-sm mb-2">KEPALA DUSUN IV</p>
                            <p class="text-xs text-blue-600 bg-blue-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-5', 'V', this, 'blue')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-blue-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-5.jpeg" alt="Kepala Dusun V" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=NURYADI&size=200&background=3b82f6&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-blue-900 mb-1">NURYADI</h4>
                            <p class="text-blue-700 font-bold text-sm mb-2">KEPALA DUSUN V</p>
                            <p class="text-xs text-blue-600 bg-blue-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-6', 'VI', this, 'blue')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-blue-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-6.jpeg" alt="Kepala Dusun VI" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=DEDEN&size=200&background=3b82f6&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-blue-900 mb-1">DEDEN HANDOKO</h4>
                            <p class="text-blue-700 font-bold text-sm mb-2">KEPALA DUSUN VI</p>
                            <p class="text-xs text-blue-600 bg-blue-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                    </div>
                </div>

                <!-- Dusun VII, VIII, IX -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN VII - IX
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="clickable-card bg-gradient-to-br from-purple-50 to-purple-100 border-4 border-purple-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-7', 'VII', this, 'purple')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-purple-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-7.jpeg" alt="Kepala Dusun VII" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=EKO&size=200&background=a855f7&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-purple-900 mb-1">EKO SISWANTO</h4>
                            <p class="text-purple-700 font-bold text-sm mb-2">KEPALA DUSUN VII</p>
                            <p class="text-xs text-purple-600 bg-purple-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-purple-50 to-purple-100 border-4 border-purple-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-8', 'VIII', this, 'purple')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-purple-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-8.jpeg" alt="Kepala Dusun VIII" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=KOKO&size=200&background=a855f7&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-purple-900 mb-1">KOKO WAHONO</h4>
                            <p class="text-purple-700 font-bold text-sm mb-2">KEPALA DUSUN VIII</p>
                            <p class="text-xs text-purple-600 bg-purple-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-purple-50 to-purple-100 border-4 border-purple-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-9', 'IX', this, 'purple')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-purple-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-9.jpeg" alt="Kepala Dusun IX" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=SUMARSONO&size=200&background=a855f7&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-purple-900 mb-1">SUMARSONO</h4>
                            <p class="text-purple-700 font-bold text-sm mb-2">KEPALA DUSUN IX</p>
                            <p class="text-xs text-purple-600 bg-purple-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
                    </div>
                </div>

                <!-- Dusun X, XI -->
                <div class="mb-10">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white text-center py-3 rounded-t-xl font-bold text-lg mb-6 shadow-lg">
                        DUSUN X - XI
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                        <div class="clickable-card bg-gradient-to-br from-orange-50 to-orange-100 border-4 border-orange-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-10', 'X', this, 'orange')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-orange-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-10.jpeg" alt="Kepala Dusun X" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=EDI&size=200&background=f97316&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-orange-900 mb-1">EDI SUHENDRA</h4>
                            <p class="text-orange-700 font-bold text-sm mb-2">KEPALA DUSUN X</p>
                            <p class="text-xs text-orange-600 bg-orange-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>

                        <div class="clickable-card bg-gradient-to-br from-orange-50 to-orange-100 border-4 border-orange-500 p-6 rounded-2xl shadow-xl text-center hover:shadow-2xl transition-shadow duration-300" onclick="toggleDetails('rt-kadus-11', 'XI', this, 'orange')">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 overflow-hidden border-4 border-orange-600 shadow-md">
                                <img src="assets/img/perangkat/kadus-11.jpeg" alt="Kepala Dusun XI" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=SUGITO&size=200&background=f97316&color=fff&bold=true'">
                            </div>
                            <h4 class="font-bold text-lg text-orange-900 mb-1">SUGITO</h4>
                            <p class="text-orange-700 font-bold text-sm mb-2">KEPALA DUSUN XI</p>
                            <p class="text-xs text-orange-600 bg-orange-50 py-1 px-2 rounded">Klik untuk lihat RT</p>
                        </div>
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
</script>

<?php require 'includes/footer.php'; ?>
