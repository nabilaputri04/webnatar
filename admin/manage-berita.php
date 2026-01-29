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
    $tgl_posting = mysqli_real_escape_string($conn, $_POST['tgl_posting']);
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/berita/";
        $new_filename = uniqid() . '.' . strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_filename);
        $foto = $new_filename;
    }

    if (isset($_POST['tambah_berita'])) {
        mysqli_query($conn, "INSERT INTO berita (judul, id_kategori, gambar, isi_berita, tgl_posting) VALUES ('$judul', $id_kategori, '$foto', '$isi', '$tgl_posting')");
        $sukses = "Berita berhasil terbit!";
    } else {
        $id = (int)$_POST['id_berita'];
        if ($foto != "") mysqli_query($conn, "UPDATE berita SET gambar = '$foto' WHERE id = $id");
        mysqli_query($conn, "UPDATE berita SET judul='$judul', id_kategori=$id_kategori, isi_berita='$isi', tgl_posting='$tgl_posting' WHERE id=$id");
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <?php include 'admin-styles.php'; ?>
    <style>
        /* FIX LINK & IMAGE POPUP DI MODAL */
        :root { --ck-z-modal: 1060 !important; }
        .ck-editor__editable { min-height: 400px; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
        <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
        <li><a href="manage-berita.php" class="active"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>
        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold mb-2" style="font-size: 2rem;">Kelola Berita</h2>
        <p class="text-muted mb-0">Publikasikan berita dan informasi terbaru untuk masyarakat</p>
    </div>

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0 section-heading">Manajemen Konten Berita</h3>
        <div class="d-flex gap-2">
            <button onclick="openModal('modalKategori')" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-tags me-2"></i>Kategori
            </button>
            <button onclick="openModal('modalBerita')" class="btn btn-primary rounded-pill px-4 shadow">
                <i class="bi bi-plus-circle me-2"></i>Tulis Berita
            </button>
        </div>
    </div>

    <?php if($sukses): ?> 
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo $sukses; ?>
        </div> 
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Judul & Kategori</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($data_berita)): ?>
                    <tr>
                        <td class="px-4 py-3">
                            <img src="../assets/img/berita/<?php echo $row['gambar']; ?>" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                        </td>
                        <td class="px-4 py-3">
                            <div class="fw-bold text-dark mb-1"><?php echo $row['judul']; ?></div>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                <?php echo $row['nama_kategori']; ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick='editBerita(<?php echo json_encode($row); ?>)' class="btn btn-sm btn-warning rounded-circle me-1">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus berita ini?')" class="btn btn-sm btn-danger rounded-circle">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Kategori -->
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Kelola Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" class="mb-4">
                    <label class="form-label small fw-bold text-muted">TAMBAH KATEGORI BARU</label>
                    <div class="input-group">
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Nama kategori..." required>
                        <button type="submit" name="tambah_kategori" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                <label class="form-label small fw-bold text-muted">DAFTAR KATEGORI</label>
                <ul class="list-group list-group-flush">
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $cat['nama_kategori']; ?>
                            <a href="?hapus_kategori=<?php echo $cat['id']; ?>" class="text-danger" onclick="return confirm('Hapus kategori ini?')">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Berita -->
<div class="modal fade" id="modalBerita" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_berita" id="id_berita">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tulis Berita Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <input type="text" name="judul" id="judul" class="form-control form-control-lg fw-bold mb-3" placeholder="Judul Berita" required>
                            <textarea name="isi_berita" id="editor"></textarea>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <label class="form-label small fw-bold text-muted">KATEGORI</label>
                                    <select name="id_kategori" id="id_kategori" class="form-select mb-3" required>
                                        <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nama_kategori']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                    
                                    <label class="form-label small fw-bold text-muted">TANGGAL POSTING</label>
                                    <input type="date" name="tgl_posting" id="tgl_posting" class="form-control mb-3" value="<?php echo date('Y-m-d'); ?>" required>
                                    
                                    <label class="form-label small fw-bold text-muted">FOTO SAMPUL</label>
                                    <input type="file" name="foto" class="form-control mb-4">
                                    
                                    <button type="submit" name="tambah_berita" id="btnSubmit" class="btn btn-primary w-100 shadow">
                                        Publikasikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Modal Logic with Bootstrap 5
    function openModal(id) {
        const modal = new bootstrap.Modal(document.getElementById(id));
        modal.show();
    }
    function closeModal(id) {
        const modalEl = document.getElementById(id);
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
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
        document.getElementById('tgl_posting').value = data.tgl_posting;
        editorInstance.setData(data.isi_berita);
        openModal('modalBerita');
    }
</script>
</body>
</html>