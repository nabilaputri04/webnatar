<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// --- TAMBAH KATEGORI ---
if (isset($_POST['add_kategori'])) {
    $kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori_berita (nama_kategori) VALUES ('$kat')");
}

// --- HAPUS BERITA ---
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $q = mysqli_query($conn, "SELECT gambar FROM berita WHERE id=$id");
    $img = mysqli_fetch_assoc($q);
    if ($img['gambar'] && file_exists("../assets/img/berita/".$img['gambar'])) {
        unlink("../assets/img/berita/".$img['gambar']);
    }
    mysqli_query($conn, "DELETE FROM berita WHERE id=$id");
    header("Location: manage-berita.php"); exit;
}

// --- SIMPAN BERITA BARU ---
if (isset($_POST['simpan_berita'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_kat = (int)$_POST['id_kategori'];
    $isi = mysqli_real_escape_string($conn, $_POST['isi_berita']);
    
    // Upload Gambar Utama
    $gambar = "";
    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/berita/" . $gambar);
    }

    $query = "INSERT INTO berita (id_kategori, judul, isi_berita, gambar) VALUES ($id_kat, '$judul', '$isi', '$gambar')";
    if (mysqli_query($conn, $query)) $sukses = "Berita berhasil diterbitkan!";
    else $error = "Gagal: " . mysqli_error($conn);
}

$berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
$kategori = mysqli_query($conn, "SELECT * FROM kategori_berita");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin Natar 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #0d6efd; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 280px; background: var(--sidebar-bg); height: 100vh; position: fixed; display: flex; flex-direction: column; padding: 25px 15px; transition: all 0.3s; z-index: 1000; }
        .sidebar-brand { color: var(--active-blue); font-weight: 700; font-size: 1.4rem; padding-bottom: 30px; border-bottom: 1px solid #2d3238; margin-bottom: 20px; }
        .sidebar-menu { flex-grow: 1; list-style: none; padding: 0; margin: 0; overflow-y: auto; }
        .nav-link { color: #adb5bd; padding: 12px 18px; border-radius: 12px; display: flex; align-items: center; transition: 0.3s; font-weight: 500; margin-bottom: 4px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .logout-section { margin-top: auto; padding-top: 20px; border-top: 1px solid #2d3238; }
        .logout-link { color: #ea868f !important; }
        .main-content { margin-left: 280px; padding: 40px; }
        @media (max-width: 991.98px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Natar 2</div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link active"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section"><a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a></div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Berita</h3>
            <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalBerita">
                <i class="bi bi-plus-lg me-2"></i>Tulis Berita
            </button>
        </div>

        <?php if($sukses): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><?php echo $sukses; ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>JUDUL</th><th>KATEGORI</th><th>TANGGAL</th><th>AKSI</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($berita)): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['judul']); ?></div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo $row['nama_kategori']; ?></span></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tgl_posting'])); ?></td>
                            <td>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus berita ini?')"><i class="bi bi-trash3"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- MODAL TAMBAH BERITA -->
<div class="modal fade" id="modalBerita" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Tulis Berita Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">JUDUL BERITA</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">KATEGORI</label>
                            <div class="input-group">
                                <select name="id_kategori" class="form-select" required>
                                    <?php mysqli_data_seek($kategori, 0); while($k = mysqli_fetch_assoc($kategori)): ?>
                                        <option value="<?php echo $k['id']; ?>"><?php echo $k['nama_kategori']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKat">+</button>
                            </div>
                            <div class="collapse mt-2" id="collapseKat">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="new_kat" class="form-control" placeholder="Nama Kategori Baru">
                                    <button type="button" class="btn btn-primary" onclick="addKategori()">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">GAMBAR UTAMA</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ISI BERITA</label>
                        <textarea name="isi_berita" id="editor"></textarea>
                    </div>
                    <button type="submit" name="simpan_berita" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Terbitkan Berita</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: { uploadUrl: 'upload_image.php' }
        })
        .catch(error => { console.error(error); });

    function addKategori() {
        // Implementasi AJAX sederhana bisa ditambahkan di sini jika diperlukan
        alert('Fitur tambah kategori cepat via AJAX bisa diimplementasikan di sini.');
    }
</script>
</body>
</html>