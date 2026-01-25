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
    </div>
</div>

<?php require 'includes/footer.php'; ?>