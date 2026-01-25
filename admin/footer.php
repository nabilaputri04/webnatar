<?php
$q_contact = mysqli_query($conn, "SELECT * FROM kontak WHERE id=1");
$contact = mysqli_fetch_assoc($q_contact);
?>
    <footer class="bg-gray-900 text-gray-300 mt-auto border-t border-gray-800">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                            <i class="bi bi-geo-alt-fill text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-white">Desa Natar 2</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6 text-sm">
                        Mewujudkan tata kelola pemerintahan desa yang jujur, transparan, dan akuntabel demi kesejahteraan masyarakat.
                    </p>
                    <div class="flex gap-3">
                        <?php if(!empty($contact['facebook'])): ?><a href="<?= $contact['facebook'] ?>" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><i class="bi bi-facebook"></i></a><?php endif; ?>
                        <?php if(!empty($contact['instagram'])): ?><a href="<?= $contact['instagram'] ?>" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition"><i class="bi bi-instagram"></i></a><?php endif; ?>
                        <?php if(!empty($contact['whatsapp'])): ?><a href="https://wa.me/<?= $contact['whatsapp'] ?>" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="bi bi-whatsapp"></i></a><?php endif; ?>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Jelajahi</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="profil.php" class="hover:text-blue-400 transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Profil Desa</a></li>
                        <li><a href="berita.php" class="hover:text-blue-400 transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Kabar Berita</a></li>
                        <li><a href="potensi.php" class="hover:text-blue-400 transition flex items-center gap-2"><i class="bi bi-chevron-right text-xs"></i> Potensi Lokal</a></li>
                        <li><a href="admin/login.php" class="hover:text-blue-400 transition flex items-center gap-2"><i class="bi bi-lock text-xs"></i> Login Admin</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="lg:col-span-2">
                    <h4 class="text-white font-bold text-lg mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt text-blue-500 mt-1 text-lg"></i>
                            <span class="leading-relaxed"><?= $contact['alamat'] ?? 'Alamat belum diatur'; ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-telephone text-blue-500 text-lg"></i>
                            <span><?= $contact['telepon'] ?? '-'; ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-envelope text-blue-500 text-lg"></i>
                            <span><?= $contact['email'] ?? '-'; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
                &copy; <?= date('Y'); ?> Pemerintah Desa Natar. KKN Reguler Universitas Lampung.
            </div>
        </div>
    </footer>
</body>
</html>