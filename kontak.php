<?php
$page_title = "Hubungi Kami";
require 'includes/header.php';

$q_kontak = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
$kontak = mysqli_fetch_assoc($q_kontak);
?>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Kontak & Lokasi</h1>
            <p class="text-gray-500">Kunjungi kantor desa atau hubungi kami melalui saluran resmi.</p>
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
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 h-full">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Informasi Kantor</h3>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h5 class="font-bold text-gray-700">Alamat</h5>
                            <p class="text-gray-500 text-sm leading-relaxed"><?= $kontak['alamat']; ?></p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <h5 class="font-bold text-gray-700">Telepon / WhatsApp</h5>
                            <p class="text-gray-500 text-sm"><?= $kontak['telepon']; ?> / <?= $kontak['whatsapp']; ?></p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0"><i class="bi bi-envelope-fill"></i></div>
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