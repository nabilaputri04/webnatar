<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// --- LOGIKA KATEGORI ---
if (isset($_POST['tambah_kategori'])) {
    $nama_kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori_berita (nama_kategori) VALUES ('$nama_kat')");
    $sukses = "Kategori berhasil ditambah!";
}

if (isset($_GET['hapus_kategori'])) {
    $id_kat = (int)$_GET['hapus_kategori'];
    mysqli_query($conn, "DELETE FROM kategori_berita WHERE id = $id_kat");
    header("Location: manage-berita.php"); exit;
}

// --- LOGIKA BERITA (Tambah & Edit) ---
if (isset($_POST['tambah_berita']) || isset($_POST['edit_berita'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_kategori = (int)$_POST['id_kategori'];
    $isi = mysqli_real_escape_string($conn, $_POST['isi_berita']);
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/berita/";
        $new_filename = uniqid() . '.' . strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_filename);
        $foto = $new_filename;
    }

    if (isset($_POST['tambah_berita'])) {
        mysqli_query($conn, "INSERT INTO berita (judul, id_kategori, gambar, isi_berita, tgl_posting) VALUES ('$judul', $id_kategori, '$foto', '$isi', NOW())");
        $sukses = "Berita berhasil terbit!";
    } else {
        $id = (int)$_POST['id_berita'];
        if ($foto != "") mysqli_query($conn, "UPDATE berita SET gambar = '$foto' WHERE id = $id");
        mysqli_query($conn, "UPDATE berita SET judul='$judul', id_kategori=$id_kategori, isi_berita='$isi' WHERE id=$id");
        $sukses = "Berita diperbarui!";
    }
}

// Hapus Berita
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM berita WHERE id = $id");
    header("Location: manage-berita.php"); exit;
}

$data_berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
$categories = mysqli_query($conn, "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Desa Natar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-active { transform: translateX(0) !important; }
        /* FIX LINK & IMAGE POPUP DI MODAL */
        :root { --ck-z-modal: 1060 !important; }
        .ck-editor__editable { min-height: 400px; }
    </style>
</head>
<body class="bg-gray-50">

<!-- Mobile Header -->
<div class="md:hidden flex justify-between items-center bg-white p-4 border-b sticky top-0 z-50">
    <span class="font-bold text-emerald-600 text-lg">Admin Desa Natar</span>
    <button id="sidebarToggle" class="text-gray-600 focus:outline-none">
        <i class="bi bi-list text-2xl"></i>
    </button>
</div>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <nav id="sidebar" class="w-64 bg-gray-900 text-gray-400 h-screen fixed left-0 top-0 flex flex-col p-4 transition-transform transform -translate-x-full md:translate-x-0 z-50 overflow-y-auto">
        <div class="text-blue-500 font-bold text-xl mb-8 px-2 flex items-center gap-2">
            <i class="bi bi-geo-alt-fill"></i> Desa Natar
        </div>
        
        <ul class="flex-1 space-y-1">
            <li><a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-900/50"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 hover:text-white transition"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="mt-auto pt-6 border-t border-gray-800">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-900/20 hover:text-red-300 transition"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-6 md:p-10 transition-all">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h3 class="text-2xl font-bold text-gray-800">Manajemen Konten</h3>
            <div class="flex gap-3">
                <button onclick="openModal('modalKategori')" class="px-5 py-2.5 rounded-full border border-emerald-600 text-emerald-600 font-medium hover:bg-emerald-50 transition">
                    Kategori
                </button>
                <button onclick="openModal('modalBerita')" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white font-medium shadow-lg hover:bg-emerald-700 hover:shadow-xl transition">
                    Tulis Berita
                </button>
            </div>
        </div>

        <?php if($sukses): ?> 
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert"><?php echo $sukses; ?></div> 
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while($row = mysqli_fetch_assoc($data_berita)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <img src="../assets/img/berita/<?php echo $row['gambar']; ?>" class="w-20 h-14 object-cover rounded-lg shadow-sm">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 mb-1"><?php echo $row['judul']; ?></div>
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-emerald-600 bg-emerald-50 rounded-md border border-emerald-100">
                                    <?php echo $row['nama_kategori']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button onclick='editBerita(<?php echo json_encode($row); ?>)' class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-full transition">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus?')" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Kategori -->
<div id="modalKategori" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h5 class="font-bold text-lg text-gray-800">Kelola Kategori</h5>
            <button onclick="closeModal('modalKategori')" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="p-6">
                <form action="" method="POST" class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tambah Baru</label>
                    <div class="flex gap-2">
                        <input type="text" name="nama_kategori" class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Nama kategori..." required>
                        <button type="submit" name="tambah_kategori" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
                    </div>
                </form>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Daftar Kategori</label>
                <ul class="divide-y divide-gray-100">
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <li class="py-3 flex justify-between items-center">
                            <?php echo $cat['nama_kategori']; ?>
                            <a href="?hapus_kategori=<?php echo $cat['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-x-circle-fill"></i></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
        </div>
    </div>
</div>

<!-- Modal Berita -->
<div id="modalBerita" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl w-full max-w-6xl shadow-2xl my-8">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_berita" id="id_berita">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white rounded-t-2xl z-10">
                <h5 class="font-bold text-lg text-gray-800" id="modalTitle">Tulis Berita Baru</h5>
                <button type="button" onclick="closeModal('modalBerita')" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3">
                    <input type="text" name="judul" id="judul" class="w-full text-2xl font-bold px-4 py-3 rounded-lg bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none mb-4 placeholder-gray-400" placeholder="Judul Berita" required>
                    <textarea name="isi_berita" id="editor"></textarea>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 sticky top-24">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 outline-none mb-4 bg-white" required>
                            <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nama_kategori']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto Sampul</label>
                        <input type="file" name="foto" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 mb-6">
                        
                        <button type="submit" name="tambah_berita" id="btnSubmit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 shadow-lg transition transform hover:-translate-y-0.5">
                            Publikasikan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-active');
        });
        
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('sidebar-active');
            }
        });
    }

    // Modal Logic
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    let editorInstance;
    ClassicEditor.create(document.querySelector('#editor'), {
        ckfinder: { uploadUrl: 'upload_image.php' }, // Mesin pengolah gambar
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'imageUpload', 'undo', 'redo' ]
    }).then(editor => { editorInstance = editor; });

    function editBerita(data) {
        document.getElementById('modalTitle').innerText = "Edit Berita";
        document.getElementById('btnSubmit').innerText = "Simpan Perubahan";
        document.getElementById('btnSubmit').name = "edit_berita";
        document.getElementById('id_berita').value = data.id;
        document.getElementById('judul').value = data.judul;
        document.getElementById('id_kategori').value = data.id_kategori;
        editorInstance.setData(data.isi_berita);
        openModal('modalBerita');
    }
</script>
</body>
</html>