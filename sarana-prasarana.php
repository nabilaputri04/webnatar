<?php
$page_title = "Sarana dan Prasarana";
require 'includes/header.php';
?>

<div class="bg-gradient-to-br from-gray-50 via-emerald-50 to-blue-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-building"></i> Infrastruktur Desa
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-600 to-blue-600 mb-6 leading-tight">
                Sarana dan Prasarana
            </h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-emerald-500 rounded-full"></div>
                <div class="h-2 w-2 bg-emerald-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-emerald-500 via-teal-500 to-blue-500 rounded-full"></div>
                <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-blue-500 rounded-full"></div>
            </div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                Data lengkap fasilitas kesehatan, pendidikan, dan sarana umum lainnya di Desa Natar
            </p>
        </div>

        <!-- Sarana Kesehatan -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-12 border-2 border-red-100 hover:shadow-red-200/50 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-red-100 to-pink-100 rounded-full -mr-20 -mt-20 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-red-50 to-pink-50 rounded-full -ml-16 -mb-16 opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:rotate-12 hover:scale-110 transition-all duration-300">
                            <i class="bi bi-heart-pulse-fill text-4xl text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-1">Sarana dan Prasarana Kesehatan</h2>
                        <p class="text-red-600 text-sm font-semibold flex items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i> Fasilitas pelayanan kesehatan masyarakat
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-red-50 to-pink-50 border-b-2 border-red-200">
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tl-xl">No.</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Sarana / Prasarana</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Jumlah / Volume</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tr-xl">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">1</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Posyandu</td>
                            <td class="px-6 py-4 text-gray-600">3 unit</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Diumah Kader</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">2</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Gedung Posyandu Permanen</td>
                            <td class="px-6 py-4 text-gray-600">8 unit</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Baik</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">3</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Puskesmas</td>
                            <td class="px-6 py-4 text-gray-600">1 unit</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Baik</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">4</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Poliklinik/balai pengobatan</td>
                            <td class="px-6 py-4 text-gray-600">4 unit</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Baik</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">5</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Bidan desa</td>
                            <td class="px-6 py-4 text-gray-600">2 orang</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Aktif</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">6</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Perawat Kesehatan</td>
                            <td class="px-6 py-4 text-gray-600">7 orang</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Aktif</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">7</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Dokter</td>
                            <td class="px-6 py-4 text-gray-600">1 orang</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Aktif</span></td>
                        </tr>
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-800 font-semibold">8</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">Mantri Kesehatan</td>
                            <td class="px-6 py-4 text-gray-600">7 orang</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sarana Pendidikan -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border-2 border-blue-100 hover:shadow-blue-200/50 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full -mr-20 -mt-20 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-blue-50 to-indigo-50 rounded-full -ml-16 -mb-16 opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl blur-lg opacity-50 animate-pulse"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:rotate-12 hover:scale-110 transition-all duration-300">
                            <i class="bi bi-book-fill text-4xl text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-1">Sarana Prasarana Pendidikan</h2>
                        <p class="text-blue-600 text-sm font-semibold flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill"></i> Lembaga pendidikan formal di wilayah desa
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-200">
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tl-xl">No.</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Nama Sekolah</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tr-xl">Tempat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $sekolah = [
                            ['PAUD Bunda Pertiwi', 'Dusun VII (Sukajawa Rejo I)'],
                            ['PAUD Flamboyan', 'Dusun X (Natar I)'],
                            ['PAUD Anggrek', 'Dusun XI (Sukarame Pasar)'],
                            ['TK Al-Munawaroh', 'Dusun VII (Sukarame)'],
                            ['TK Seyang Ibu', 'Dusun IV (Sari Rejo)'],
                            ['TK Bina Asih', 'Dusun IX (Tanjung Rejo II)'],
                            ['TK Tunas Mulya', 'Dusun X (Natar I)'],
                            ['SD N 1 Natar', 'Dusun IV (Sari Rejo)'],
                            ['SD N 2 Natar', 'Dusun VII (Sukarame)'],
                            ['SD N 3 Natar', 'Dusun V (Marga Jaya)'],
                            ['SD N 4 Natar', 'Dusun VIII (Tanjung Rejo I)'],
                            ['SLTP Muhammadiyah Natar', 'Dusun IV (Sari Rejo)'],
                            ['SLTP Budi Karya Natar', 'Dusun VIII (Tanjung Rejo I)'],
                            ['SMA N 1 Natar', 'Dusun I (Natar I)'],
                            ['SMK Budi Karya Natar', 'Dusun VIII (Tanjung Rejo I)']
                        ];
                        $no = 1;
                        foreach ($sekolah as $s) {
                            $bg = $no % 2 == 0 ? 'bg-blue-50/30' : '';
                            echo "<tr class='hover:bg-blue-50 transition-colors duration-200 $bg'>";
                            echo "<td class='px-6 py-4 text-gray-800 font-semibold'>$no</td>";
                            echo "<td class='px-6 py-4 text-gray-800 font-medium'>{$s[0]}</td>";
                            echo "<td class='px-6 py-4 text-gray-600'>{$s[1]}</td>";
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Footer -->
        <div class="mt-12 bg-gradient-to-r from-emerald-100 to-teal-100 rounded-2xl p-8 border border-emerald-200">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="bi bi-info-circle-fill text-3xl text-emerald-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Catatan Penting</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Data sarana dan prasarana ini diperbarui secara berkala oleh pemerintah desa. Jika terdapat informasi yang perlu diperbaharui atau ditambahkan, silakan hubungi kantor desa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
