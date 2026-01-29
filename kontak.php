<?php
$page_title = "Hubungi Kami";
require 'includes/header.php';

$q_kontak = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
$kontak = mysqli_fetch_assoc($q_kontak);
?>

<div class="bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-3 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-transform">
                <span class="text-white font-bold text-sm tracking-wider uppercase flex items-center gap-2">
                    <i class="bi bi-telephone-fill"></i> Hubungi Kami
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 mb-6">Kontak & Lokasi</h1>
            <div class="flex justify-center items-center gap-3 mb-6">
                <div class="h-1 w-20 bg-gradient-to-r from-transparent to-purple-500 rounded-full"></div>
                <div class="h-2 w-2 bg-purple-500 rounded-full"></div>
                <div class="h-1 w-32 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 rounded-full"></div>
                <div class="h-2 w-2 bg-rose-500 rounded-full"></div>
                <div class="h-1 w-20 bg-gradient-to-l from-transparent to-rose-500 rounded-full"></div>
            </div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">Kunjungi kantor desa atau hubungi kami melalui saluran resmi.</p>
        </div>

        <!-- Alert Notifikasi -->
        <?php if(isset($_GET['status'])): ?>
            <?php if($_GET['status'] == 'success'): ?>
                <div class="max-w-6xl mx-auto mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill text-green-500 text-xl mr-3"></i>
                        <p class="text-green-700 font-medium">Pengaduan Anda berhasil dikirim! Kami akan menindaklanjuti segera.</p>
                    </div>
                </div>
            <?php elseif($_GET['status'] == 'error'): ?>
                <div class="max-w-6xl mx-auto mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="bi bi-exclamation-circle-fill text-red-500 text-xl mr-3"></i>
                        <p class="text-red-700 font-medium">Terjadi kesalahan. Silakan coba lagi.</p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Info Kontak -->
            <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-purple-100 h-full hover:shadow-purple-200/50 transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-info-circle-fill text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Informasi Kantor</h3>
                    </div>
                </div>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg transform hover:rotate-12 transition-transform"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h5 class="font-bold text-gray-700">Alamat</h5>
                            <p class="text-gray-500 text-sm leading-relaxed"><?= $kontak['alamat']; ?></p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg transform hover:rotate-12 transition-transform"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <h5 class="font-bold text-gray-700">Telepon / WhatsApp</h5>
                            <p class="text-gray-500 text-sm"><?= $kontak['telepon']; ?> / <?= $kontak['whatsapp']; ?></p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg transform hover:rotate-12 transition-transform"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h5 class="font-bold text-gray-700">Email</h5>
                            <p class="text-gray-500 text-sm"><?= $kontak['email']; ?></p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Peta -->
            <div class="lg:col-span-2">
                <?php include 'components/widget-peta.php'; ?>
            </div>
        </div>

        <!-- Form Pengaduan -->
        <div class="max-w-6xl mx-auto mt-12">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="bi bi-megaphone-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Form Pengaduan Masyarakat</h3>
                        <p class="text-gray-500 text-sm">Sampaikan aspirasi, keluhan, atau saran Anda kepada kami</p>
                    </div>
                </div>

                <form action="submit-pengaduan.php" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="telepon" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pengaduan <span class="text-red-500">*</span></label>
                            <select name="kategori" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                                <option value="">Pilih Kategori</option>
                                <option value="Infrastruktur">Infrastruktur</option>
                                <option value="Pelayanan">Pelayanan</option>
                                <option value="Administrasi">Administrasi</option>
                                <option value="Kebersihan">Kebersihan</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                               placeholder="Ringkasan singkat pengaduan Anda">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="isi_pengaduan" required rows="6" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"
                                  placeholder="Jelaskan secara detail pengaduan Anda..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Sertakan informasi detail agar kami dapat menindaklanjuti dengan tepat</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 flex items-center gap-2 shadow-lg shadow-emerald-500/30">
                            <i class="bi bi-send-fill"></i>
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>