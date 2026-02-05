<?php
$page_title = "Profil Desa";
require 'includes/header.php';

$q_profil = mysqli_query($conn, "SELECT * FROM profil LIMIT 1");
$profil = mysqli_fetch_assoc($q_profil);
?>

<div class="bg-gradient-to-br from-gray-50 via-emerald-50 to-teal-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i> Profil Desa
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 mb-6">Profil Desa Natar</h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-emerald-500 rounded-full"></div>
                <div class="h-2 w-2 bg-emerald-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full"></div>
                <div class="h-2 w-2 bg-teal-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-teal-500 rounded-full"></div>
            </div>
        </div>

        <!-- Sejarah & Visi Misi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
            <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-emerald-100 hover:shadow-emerald-200/50 transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl blur-lg opacity-50 animate-pulse"></div>
                            <div class="relative w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-xl transform hover:rotate-12 transition-all">
                                <i class="bi bi-clock-history text-2xl text-white"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sejarah Desa</h2>
                    </div>
                </div>
                <div class="text-gray-600 leading-relaxed">
                    <div id="sejarah-preview" class="space-y-4">
                        <p class="text-justify">
                            Pada awalnya Desa Natar adalah merupakan hutan belantara yang dibuka pada tahun 1803 dipimpin dua orang bersaudara yaitu: <strong>Tuan Raja Lama</strong> dan <strong>Tuan Dulu Kuning</strong>, keduanya salah satu termasuk keturunan Ratu Balau.
                        </p>
                        <p class="text-justify">
                            Pada masa Ratu Balau sedang jaya, wilayahnya berada di Bukit Singgalang yaitu suatu bukit dekat Way Lunik antara Teluk Betung – Panjang...
                        </p>
                    </div>
                    
                    <div id="sejarah-full" class="hidden space-y-4">
                        <p class="text-justify">
                            Pada awalnya Desa Natar adalah merupakan hutan belantara yang dibuka pada tahun 1803 dipimpin dua orang bersaudara yaitu: <strong>Tuan Raja Lama</strong> dan <strong>Tuan Dulu Kuning</strong>, keduanya salah satu termasuk keturunan Ratu Balau.
                        </p>
                        <p class="text-justify">
                            Pada masa Ratu Balau sedang jaya, wilayahnya berada di Bukit Singgalang yaitu suatu bukit dekat Way Lunik antara Teluk Betung – Panjang. Pada mulanya kurang lebih tahun 1801 masuklah pemerintah penjajah Belanda ke daerah Lampung. Tujuan Belanda antara lain ingin menguasai Keratuan Balau.
                        </p>
                        <p class="text-justify">
                            Tetapi semua keturunan dan ahli waris Ratu Balau tidak mau dijajah oleh Belanda pada masa itu, namun karena merasa tidak mungkin untuk melawan penjajahan Belanda dengan kekuatan pada saat itu maka keturunan dan ahli waris Keratuan Balau terpaksa mengungsi ke tempat lain, sebagian pindah dan menetap di Desa Kedamaian Bandar Lampung dan sebagian lagi pindah dan menetap di Desa Natar sekarang.
                        </p>
                        <p class="text-justify">
                            Adapun nama <strong>Natar</strong> diberi atas kesepakatan dan persetujuan dari dua orang bersaudara tersebut di atas, yaitu Tuan Raja Lama dan Tuan Dulu Kuning, karena pada waktu itu setelah dicari kesana kesini lokasi yang tepat dan cocok untuk tempat tinggal, akhirnya ditemukan daerah yang rata yaitu Stasiun PJKA Pasar Lama sampai Sungai Way Rumbay sekarang.
                        </p>
                        <p class="text-justify font-semibold text-gray-700 mt-6">
                            Setelah itu lebih jelas diketahui bahwa yang turut meresmikan Desa Natar atau Tiyuh Natar itu adalah terdiri dari suku-suku sebagai berikut:
                        </p>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li class="text-gray-700">BUAY KUNING BALAU</li>
                            <li class="text-gray-700">BUAY KUNING GEDUNG</li>
                            <li class="text-gray-700">RULUNG TENOH BIH</li>
                            <li class="text-gray-700">RULUNG BUJUNG</li>
                            <li class="text-gray-700">BUAY PEMUKA PATI</li>
                        </ol>
                        <p class="text-justify mt-4">
                            Kelimanya membuat suatu kesepakatan dan sekaligus menyimpulkan Pantun Tiyuh Adat yaitu:
                        </p>
                        <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-lg mt-3 dark-quote-box">
                            <p class="text-emerald-900 font-semibold italic text-center dark-quote-text">
                                "DELOM BANGSA KUMALA, LAIN SAI TALI NANGGAI<br>
                                JEJAMA BINTANG LIMA SEPAKAI DI JAKNI PESAI"
                            </p>
                        </div>
                    </div>

                    <button onclick="toggleSejarah()" id="btn-sejarah" class="mt-6 text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-2 transition">
                        <span id="btn-text">Baca Selengkapnya</span>
                        <i id="btn-icon" class="bi bi-chevron-down"></i>
                    </button>
                </div>
                
                <!-- Peta Desa -->
                <div class="mt-6">
                    <div class="bg-white p-4 rounded-2xl shadow-xl border-2 border-emerald-100 overflow-hidden hover:shadow-emerald-200/50 transition-all duration-500">
                        <?php
                        $peta_path = 'assets/img/peta.jpeg';
                        $peta_src = img_cache_buster($peta_path);
                        ?>
                        <img src="<?= $peta_src ?>" alt="Peta Desa Natar" class="w-full h-auto rounded-xl shadow-lg" onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600/10b981/ffffff?text=Peta+Desa+Natar';">
                        <p class="text-center text-gray-600 font-semibold mt-4 text-sm">Peta Wilayah Desa Natar</p>
                    </div>
                </div>
            </div>

            <script>
            function toggleSejarah() {
                const preview = document.getElementById('sejarah-preview');
                const full = document.getElementById('sejarah-full');
                const btnText = document.getElementById('btn-text');
                const btnIcon = document.getElementById('btn-icon');
                
                if (full.classList.contains('hidden')) {
                    preview.classList.add('hidden');
                    full.classList.remove('hidden');
                    btnText.textContent = 'Sembunyikan';
                    btnIcon.classList.remove('bi-chevron-down');
                    btnIcon.classList.add('bi-chevron-up');
                } else {
                    preview.classList.remove('hidden');
                    full.classList.add('hidden');
                    btnText.textContent = 'Baca Selengkapnya';
                    btnIcon.classList.remove('bi-chevron-up');
                    btnIcon.classList.add('bi-chevron-down');
                }
            }
            </script>

            <div class="space-y-8">
                <!-- VISI -->
                <div class="relative bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700 p-8 md:p-12 rounded-3xl shadow-2xl overflow-hidden group hover:shadow-emerald-500/50 transition-all duration-500">
                    <!-- Animated Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-yellow-200 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <!-- Decorative Corner Elements -->
                    <div class="absolute top-0 right-0 w-40 h-40 border-t-4 border-r-4 border-white/30 rounded-tr-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 border-b-4 border-l-4 border-white/30 rounded-bl-3xl"></div>
                    
                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-8">
                            <div class="w-20 h-20 bg-white/25 backdrop-blur-lg rounded-3xl flex items-center justify-center shadow-2xl transform group-hover:rotate-12 transition-transform duration-500">
                                <i class="bi bi-bullseye text-4xl text-white drop-shadow-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-2 tracking-tight drop-shadow-lg">Visi Desa Natar</h2>
                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-semibold border border-white/30">
                                        <i class="bi bi-calendar-event mr-1"></i> 2020 - 2025
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visi Content Card -->
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 border-2 border-white/30 shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500">
                            <!-- Quote Icon -->
                            <div class="absolute -top-6 -left-6 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-xl transform rotate-12">
                                <i class="bi bi-quote text-3xl text-white"></i>
                            </div>
                            
                            <!-- Visi Text -->
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-yellow-400 via-white to-yellow-400 rounded-full"></div>
                                <p class="text-white text-lg md:text-2xl font-bold leading-relaxed text-justify" style="font-family: 'Georgia', 'Times New Roman', serif;">
                                    <?= htmlspecialchars($profil['visi']); ?>
                                </p>
                            </div>
                            
                            <!-- Decorative Bottom Quote -->
                            <div class="absolute -bottom-4 -right-4 w-14 h-14 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-xl transform -rotate-12">
                                <i class="bi bi-quote text-2xl text-white transform rotate-180"></i>
                            </div>
                        </div>

                        <!-- Info Footer -->
                        <div class="mt-8 flex flex-col md:flex-row items-start md:items-center gap-4 text-white/90">
                            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 rounded-xl border border-white/20">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-info-circle text-lg"></i>
                                </div>
                                <p class="text-sm font-medium">Visi ini disepakati antara Pemerintah Desa Natar dengan BPD Desa Natar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MISI -->
                <div class="bg-white p-10 rounded-3xl shadow-xl border-2 border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-list-check text-3xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-800">Misi Desa Natar</h2>
                            <p class="text-gray-500 text-sm font-medium mt-1">5 Pernyataan yang harus dilaksanakan untuk mencapai visi</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php 
                        $misi_list = explode("\n", $profil['misi']);
                        $nomor = 1;
                        foreach($misi_list as $misi_item):
                            $misi_clean = trim($misi_item);
                            if(!empty($misi_clean)):
                        ?>
                        <div class="group flex gap-5 p-5 rounded-2xl bg-gradient-to-r from-orange-50 to-white border-2 border-orange-100 hover:border-orange-300 hover:shadow-lg transition-all duration-300">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:scale-110 transition-transform">
                                    <?= $nomor; ?>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-gray-700 text-lg leading-relaxed font-medium">
                                    <?= htmlspecialchars($misi_clean); ?>
                                </p>
                            </div>
                        </div>
                        <?php 
                            $nomor++;
                            endif;
                        endforeach; 
                        ?>
                    </div>

                    <!-- Footer info -->
                    <div class="mt-8 pt-6 border-t-2 border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-gray-500 text-sm">
                                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                                <span>Misi ini akan membantu mewujudkan visi Desa Natar</span>
                            </div>
                        </div>
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