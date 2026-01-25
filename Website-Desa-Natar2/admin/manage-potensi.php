<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// --- LOGIKA KATEGORI ---
if (isset($_POST['tambah_kategori'])) {
    $nama_kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori_potensi (nama_kategori) VALUES ('$nama_kat')");
    $sukses = "Kategori potensi berhasil ditambah!";
}

if (isset($_GET['hapus_kategori'])) {
    $id_kat = (int)$_GET['hapus_kategori'];
    mysqli_query($conn, "DELETE FROM kategori_potensi WHERE id = $id_kat");
    header("Location: manage-potensi.php"); exit;
}

// --- LOGIKA POTENSI (Tambah & Edit) ---
if (isset($_POST['tambah_potensi']) || isset($_POST['edit_potensi'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_potensi']);
    $id_kat = (int)$_POST['id_kategori'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $maps = mysqli_real_escape_string($conn, $_POST['lokasi_maps']);
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/potensi/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $new_filename = uniqid() . '.' . strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_filename);
        $foto = $new_filename;
    }

    if (isset($_POST['tambah_potensi'])) {
        mysqli_query($conn, "INSERT INTO potensi_desa (nama_potensi, id_kategori, deskripsi, gambar, lokasi_maps) VALUES ('$nama', $id_kat, '$deskripsi', '$foto', '$maps')");
        $sukses = "Potensi desa berhasil didaftarkan!";
    } else {
        $id = (int)$_POST['id_potensi'];
        if ($foto != "") mysqli_query($conn, "UPDATE potensi_desa SET gambar = '$foto' WHERE id = $id");
        mysqli_query($conn, "UPDATE potensi_desa SET nama_potensi='$nama', id_kategori=$id_kat, deskripsi='$deskripsi', lokasi_maps='$maps' WHERE id=$id");
        $sukses = "Data potensi diperbarui!";
    }
}

// Hapus Potensi
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $res = mysqli_query($conn, "SELECT gambar FROM potensi_desa WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row['gambar'] && file_exists("../assets/img/potensi/" . $row['gambar'])) unlink("../assets/img/potensi/" . $row['gambar']);
    mysqli_query($conn, "DELETE FROM potensi_desa WHERE id = $id");
    header("Location: manage-potensi.php"); exit;
}

$data_potensi = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM potensi_desa p LEFT JOIN kategori_potensi k ON p.id_kategori = k.id ORDER BY p.tgl_input DESC");
$categories = mysqli_query($conn, "SELECT * FROM kategori_potensi ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Potensi - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; --bs-primary: #10b981; --bs-primary-rgb: 16, 185, 129; }
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; margin: 0; }

        /* SIDEBAR SAMA SEPERTI INDEX.PHP */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 25px 15px;
            transition: all 0.3s;
            z-index: 1000;
            overflow: hidden;
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.4rem; 
            padding-bottom: 30px; 
            border-bottom: 1px solid #2d3238; 
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0; 
            margin: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }
        
        .sidebar-menu::-webkit-scrollbar { width: 6px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { 
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover { 
            background-color: rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            color: #adb5bd;
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            font-weight: 500;
            margin-bottom: 4px;
            white-space: nowrap;
            text-decoration: none;
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0;
            background: var(--sidebar-bg);
        }
        .logout-link { color: #ea868f !important; }
        .logout-link:hover { background: rgba(234, 134, 143, 0.1); }

        .main-content { margin-left: 280px; padding: 40px; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; }
        }

        .card-premium { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #fff; }

        /* Fix Editor Z-Index untuk Link & Image */
        :root { --ck-z-modal: 1060 !important; }
        .ck-editor__editable { min-height: 350px; border-radius: 0 0 12px 12px !important; }
        .potensi-img { width: 70px; height: 70px; object-fit: cover; border-radius: 12px; }
    </style>
</head>
<body>

<div class="mobile-header">
    <span class="fw-bold text-primary">Admin Desa Natar</span>
    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
</div>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
        
        <ul class="sidebar-menu">
            <li><a href="index.php" class="nav-link"><i class="bi bi-house-door"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link active"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Peta Potensi Desa</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalKategori">Kategori</button>
                <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalPotensi">Tambah Potensi</button>
            </div>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="card card-premium p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-secondary fw-bold"><th>FOTO</th><th>POTENSI & KATEGORI</th><th>AKSI</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_potensi)): ?>
                        <tr>
                            <td><img src="../assets/img/potensi/<?php echo $row['gambar']; ?>" class="potensi-img"></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_potensi']); ?></div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill"><?php echo $row['nama_kategori']; ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning border-0" onclick='editPotensi(<?php echo json_encode($row); ?>)'><i class="bi bi-pencil-square"></i></button>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus potensi ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-0"><h5 class="fw-bold mb-0">Kelola Kategori Potensi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4">
                <form action="" method="POST" class="mb-4">
                    <label class="form-label small fw-bold">TAMBAH KATEGORI</label>
                    <div class="input-group">
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Wisata / UMKM" required>
                        <button type="submit" name="tambah_kategori" class="btn btn-primary px-3">Tambah</button>
                    </div>
                </form>
                <label class="form-label small fw-bold text-secondary">DAFTAR SAAT INI</label>
                <ul class="list-group list-group-flush border-top">
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <?php echo $cat['nama_kategori']; ?>
                            <a href="?hapus_kategori=<?php echo $cat['id']; ?>" class="text-danger" onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-x-circle-fill"></i></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPotensi" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form action="" method="POST" enctype="multipart/form-data" id="formPotensi">
                <input type="hidden" name="id_potensi" id="id_potensi">
                <div class="modal-header border-0"><h5 class="fw-bold" id="modalTitle">Tambah Potensi Desa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-lg-8 border-end">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">NAMA POTENSI</label>
                                <input type="text" name="nama_potensi" id="nama_potensi" class="form-control form-control-lg bg-light border-0" placeholder="Misal: Kerajinan Tapis Natar 2" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">DESKRIPSI LENGKAP (Ceritakan Keunggulannya)</label>
                                <textarea name="deskripsi" id="editor"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-3 bg-light rounded-4 mb-3">
                                <label class="form-label small fw-bold">KATEGORI</label>
                                <select name="id_kategori" id="id_kategori" class="form-select border-0 mb-3" required>
                                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nama_kategori']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <label class="form-label small fw-bold">FOTO UTAMA</label>
                                <input type="file" name="foto" class="form-control border-0 shadow-sm mb-2">
                                <div id="foto_current"></div>
                            </div>
                            <div class="p-3 bg-light rounded-4">
                                <label class="form-label small fw-bold"><i class="bi bi-geo-alt-fill text-primary"></i> LINK GOOGLE MAPS</label>
                                <input type="text" name="lokasi_maps" id="lokasi_maps" class="form-control border-0" placeholder="Tempel link share maps di sini">
                                <small class="text-muted mt-2 d-block">Membantu warga/wisatawan menemukan lokasi ini.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="tambah_potensi" id="btnSubmit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">Simpan Potensi Desa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    let editorInstance;
    ClassicEditor.create(document.querySelector('#editor'), {
        ckfinder: { uploadUrl: 'upload_image.php' }, // Re-use file pengolah gambar berita
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'imageUpload', 'undo', 'redo' ]
    }).then(editor => { editorInstance = editor; });

    function editPotensi(data) {
        document.getElementById('modalTitle').innerText = "Edit Potensi Desa";
        document.getElementById('btnSubmit').innerText = "Simpan Perubahan";
        document.getElementById('btnSubmit').name = "edit_potensi";
        document.getElementById('id_potensi').value = data.id;
        document.getElementById('nama_potensi').value = data.nama_potensi;
        document.getElementById('id_kategori').value = data.id_kategori;
        document.getElementById('lokasi_maps').value = data.lokasi_maps;
        editorInstance.setData(data.deskripsi);
        document.getElementById('foto_current').innerHTML = `<img src="../assets/img/potensi/${data.gambar}" class="img-fluid rounded-3 mt-2 shadow-sm">`;
        new bootstrap.Modal(document.getElementById('modalPotensi')).show();
    }

    document.getElementById('modalPotensi').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerText = "Tambah Potensi Desa";
        document.getElementById('btnSubmit').innerText = "Simpan Potensi Desa";
        document.getElementById('btnSubmit').name = "tambah_potensi";
        document.getElementById('formPotensi').reset();
        editorInstance.setData("");
        document.getElementById('foto_current').innerHTML = "";
    });
</script>
</body>
</html>