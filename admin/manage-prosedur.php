<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// 1. LOGIKA TAMBAH & EDIT LAYANAN
if (isset($_POST['tambah_layanan']) || isset($_POST['edit_layanan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $syarat = mysqli_real_escape_string($conn, $_POST['persyaratan']);
    $prosedur = mysqli_real_escape_string($conn, $_POST['prosedur']);
    $biaya = mysqli_real_escape_string($conn, $_POST['biaya']);
    $estimasi = mysqli_real_escape_string($conn, $_POST['estimasi_waktu']);

    if (isset($_POST['tambah_layanan'])) {
        $query = "INSERT INTO layanan (nama_layanan, persyaratan, prosedur, biaya, estimasi_waktu) 
                  VALUES ('$nama', '$syarat', '$prosedur', '$biaya', '$estimasi')";
        if (mysqli_query($conn, $query)) { $sukses = "Layanan baru berhasil ditambahkan!"; }
    } else {
        $id = (int)$_POST['id_layanan'];
        $query = "UPDATE layanan SET nama_layanan='$nama', persyaratan='$syarat', prosedur='$prosedur', 
                  biaya='$biaya', estimasi_waktu='$estimasi' WHERE id=$id";
        if (mysqli_query($conn, $query)) { $sukses = "Data layanan berhasil diperbarui!"; }
    }
}

// 2. LOGIKA HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM layanan WHERE id = $id");
    header("Location: manage-prosedur.php"); exit;
}

$data_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; --ck-z-modal: 1060 !important; --bs-primary: #10b981; --bs-primary-rgb: 16, 185, 129; }
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
        .ck-editor__editable { min-height: 200px; }
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
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link active"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Layanan Publik</h3>
            <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalLayanan">
                <i class="bi bi-plus-circle me-2"></i> Tambah Layanan
            </button>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="card card-premium p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-secondary fw-bold">
                            <th>NAMA LAYANAN</th>
                            <th>ESTIMASI & BIAYA</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_layanan)): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_layanan']); ?></td>
                            <td>
                                <div class="small text-muted mb-1"><i class="bi bi-clock me-1"></i> <?php echo $row['estimasi_waktu']; ?></div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Rp <?php echo $row['biaya']; ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning border-0" onclick='editLayanan(<?php echo json_encode($row); ?>)'><i class="bi bi-pencil-square"></i></button>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus layanan ini?')"><i class="bi bi-trash3-fill"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalLayanan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 rounded-4">
            <form action="" method="POST">
                <input type="hidden" name="id_layanan" id="id_layanan">
                <div class="modal-header border-0"><h5 class="fw-bold" id="modalTitle">Tambah Layanan Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-lg-8 border-end">
                            <div class="mb-3"><label class="form-label small fw-bold">NAMA LAYANAN</label><input type="text" name="nama_layanan" id="nama_layanan" class="form-control" placeholder="Misal: Surat Keterangan Tidak Mampu" required></div>
                            <div class="mb-3"><label class="form-label small fw-bold">DOKUMEN PERSYARATAN</label><textarea name="persyaratan" id="editor_syarat"></textarea></div>
                            <div class="mb-0"><label class="form-label small fw-bold">ALUR PROSEDUR</label><textarea name="prosedur" id="editor_prosedur"></textarea></div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-3 bg-light rounded-4">
                                <div class="mb-3"><label class="form-label small fw-bold">ESTIMASI WAKTU</label><input type="text" name="estimasi_waktu" id="estimasi_waktu" class="form-control" placeholder="Contoh: 15 Menit / 1 Hari Kerja"></div>
                                <div class="mb-3"><label class="form-label small fw-bold">BIAYA (Rp)</label><input type="text" name="biaya" id="biaya" class="form-control" value="Gratis"></div>
                                <button type="submit" name="tambah_layanan" id="btnSubmit" class="btn btn-primary w-100 py-3 mt-4 rounded-pill fw-bold">Simpan Layanan</button>
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
    // Script toggle sidebar universal sudah ada di admin-styles.php

    let editorSyarat, editorProsedur;
    ClassicEditor.create(document.querySelector('#editor_syarat'), { toolbar: [ 'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo' ] }).then(editor => { editorSyarat = editor; });
    ClassicEditor.create(document.querySelector('#editor_prosedur'), { toolbar: [ 'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo' ] }).then(editor => { editorProsedur = editor; });

    function editLayanan(data) {
        document.getElementById('modalTitle').innerText = "Edit Layanan";
        document.getElementById('btnSubmit').innerText = "Simpan Perubahan";
        document.getElementById('btnSubmit').name = "edit_layanan";
        document.getElementById('id_layanan').value = data.id;
        document.getElementById('nama_layanan').value = data.nama_layanan;
        document.getElementById('biaya').value = data.biaya;
        document.getElementById('estimasi_waktu').value = data.estimasi_waktu;
        editorSyarat.setData(data.persyaratan);
        editorProsedur.setData(data.prosedur);
        new bootstrap.Modal(document.getElementById('modalLayanan')).show();
    }
</script>
</body>
</html>