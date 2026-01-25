<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// Ambil data profil (ID 1)
$query = mysqli_query($conn, "SELECT * FROM profil WHERE id = 1");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['simpan'])) {
    $sejarah = mysqli_real_escape_string($conn, $_POST['sejarah']);
    $visi = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi = mysqli_real_escape_string($conn, $_POST['misi']);
    $populasi = (int) $_POST['populasi'];
    $luas = mysqli_real_escape_string($conn, $_POST['luas_wilayah']);
    $iframe = mysqli_real_escape_string($conn, $_POST['peta_lokasi_iframe']);

    $update = "UPDATE profil SET 
               sejarah='$sejarah', visi='$visi', misi='$misi', 
               populasi='$populasi', luas_wilayah='$luas', peta_lokasi_iframe='$iframe' 
               WHERE id=1";
    
    if (mysqli_query($conn, $update)) {
        $sukses = "Data profil desa berhasil diperbarui!";
        // Refresh data
        $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil WHERE id = 1"));
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil - Admin Natar 2</title>
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
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }
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
            <li><a href="manage-profil.php" class="nav-link active"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-prosedur.php" class="nav-link"><i class="bi bi-card-checklist"></i> Layanan</a></li>
            <li><a href="manage-unduhan.php" class="nav-link"><i class="bi bi-download"></i> Unduhan</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section"><a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a></div>
    </nav>

    <main class="main-content w-100">
        <h3 class="fw-bold mb-4">Edit Profil Desa</h3>

        <?php if($sukses): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i><?php echo $sukses; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="card border-0 shadow-sm rounded-4 p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold small">POPULASI (JIWA)</label>
                    <input type="number" name="populasi" class="form-control" value="<?php echo $data['populasi']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold small">LUAS WILAYAH</label>
                    <input type="text" name="luas_wilayah" class="form-control" value="<?php echo htmlspecialchars($data['luas_wilayah']); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">SEJARAH DESA</label>
                <textarea name="sejarah" class="form-control" rows="5"><?php echo htmlspecialchars($data['sejarah']); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold small">VISI</label>
                    <textarea name="visi" class="form-control" rows="4"><?php echo htmlspecialchars($data['visi']); ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold small">MISI</label>
                    <textarea name="misi" class="form-control" rows="4"><?php echo htmlspecialchars($data['misi']); ?></textarea>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small">EMBED GOOGLE MAPS (IFRAME)</label>
                <textarea name="peta_lokasi_iframe" class="form-control" rows="3" placeholder='<iframe src="..."></iframe>'><?php echo htmlspecialchars($data['peta_lokasi_iframe']); ?></textarea>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                <i class="bi bi-save me-2"></i>Simpan Perubahan
            </button>
        </form>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>