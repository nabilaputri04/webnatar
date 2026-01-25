<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// 1. LOGIKA TAMBAH DATA
if (isset($_POST['tambah'])) {
    $tahun = $_POST['tahun'];
    $jenis = $_POST['jenis'];
    $rincian = mysqli_real_escape_string($conn, $_POST['rincian']);
    $anggaran = (int)$_POST['anggaran'];
    $realisasi = (int)$_POST['realisasi'];

    $query = "INSERT INTO apb_desa (tahun, jenis, rincian, anggaran, realisasi) 
              VALUES ('$tahun', '$jenis', '$rincian', $anggaran, $realisasi)";
    if (mysqli_query($conn, $query)) { $sukses = "Data berhasil disimpan!"; }
}

// 2. LOGIKA EDIT DATA
if (isset($_POST['edit_apb'])) {
    $id = (int)$_POST['id_apb'];
    $tahun = $_POST['tahun'];
    $jenis = $_POST['jenis'];
    $rincian = mysqli_real_escape_string($conn, $_POST['rincian']);
    $anggaran = (int)$_POST['anggaran'];
    $realisasi = (int)$_POST['realisasi'];

    $query = "UPDATE apb_desa SET tahun='$tahun', jenis='$jenis', rincian='$rincian', anggaran=$anggaran, realisasi=$realisasi WHERE id=$id";
    if (mysqli_query($conn, $query)) { $sukses = "Data berhasil diperbarui!"; }
}

// 3. LOGIKA HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM apb_desa WHERE id = $id");
    header("Location: manage-apbdesa.php"); exit;
}

// 4. HITUNG RINGKASAN ANGGARAN
$q_total = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN jenis = 'Pendapatan' THEN realisasi ELSE 0 END) as total_pendapatan,
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as total_belanja
    FROM apb_desa");
$res_total = mysqli_fetch_assoc($q_total);
$sisa_anggaran = $res_total['total_pendapatan'] - $res_total['total_belanja'];

$data_apb = mysqli_query($conn, "SELECT * FROM apb_desa ORDER BY tahun DESC, jenis ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen APB Desa - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
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
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; border: 1px solid #e5e7eb; }
        
        .stat-card { border-radius: 15px; padding: 20px; border: none; }
        .progress { height: 6px; border-radius: 10px; }
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
            <li><a href="manage-apbdesa.php" class="nav-link active"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Transparansi APB Desa</h3>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i> Tambah Data
            </button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card bg-success text-white shadow-sm">
                    <small class="opacity-75">Total Pendapatan Cair</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($res_total['total_pendapatan'], 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-warning text-dark shadow-sm">
                    <small class="opacity-75">Total Belanja Terpakai</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($res_total['total_belanja'], 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-primary text-white shadow-sm">
                    <small class="opacity-75">Sisa Anggaran Desa</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($sisa_anggaran, 0, ',', '.'); ?></h4>
                </div>
            </div>
        </div>

        <?php if($sukses): ?> <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><?php echo $sukses; ?></div> <?php endif; ?>

        <div class="card card-premium p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-secondary fw-bold">
                            <th>RINCIAN & TAHUN</th>
                            <th>JENIS</th>
                            <th>ANGGARAN & REALISASI</th>
                            <th>PROGRESS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_apb)): 
                            $persen = ($row['anggaran'] > 0) ? round(($row['realisasi'] / $row['anggaran']) * 100) : 0;
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['rincian']); ?></div>
                                <small class="text-muted">Tahun <?php echo $row['tahun']; ?></small>
                            </td>
                            <td><span class="badge <?php echo ($row['jenis'] == 'Pendapatan') ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?> border px-3 py-2 rounded-pill"><?php echo $row['jenis']; ?></span></td>
                            <td>
                                <div class="small text-muted">Target: Rp <?php echo number_format($row['anggaran'], 0, ',', '.'); ?></div>
                                <div class="fw-bold">Real: Rp <?php echo number_format($row['realisasi'], 0, ',', '.'); ?></div>
                            </td>
                            <td width="150">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1"><div class="progress-bar <?php echo ($row['jenis'] == 'Pendapatan') ? 'bg-success' : 'bg-warning'; ?>" style="width: <?php echo $persen; ?>%"></div></div>
                                    <small class="fw-bold"><?php echo $persen; ?>%</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning border-0" onclick='editAPB(<?php echo json_encode($row); ?>)'><i class="bi bi-pencil-square"></i></button>
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="POST">
                <div class="modal-header border-0"><h5 class="fw-bold">Tambah Anggaran Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label small fw-bold">TAHUN</label><input type="number" name="tahun" class="form-control" value="2026" required></div>
                        <div class="col-6"><label class="form-label small fw-bold">JENIS</label><select name="jenis" class="form-select"><option value="Pendapatan">Pendapatan</option><option value="Belanja">Belanja</option></select></div>
                        <div class="col-12"><label class="form-label small fw-bold">RINCIAN KEGIATAN</label><input type="text" name="rincian" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">TARGET ANGGARAN (Rp)</label><input type="number" name="anggaran" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">REALISASI SAAT INI (Rp)</label><input type="number" name="realisasi" class="form-control" value="0" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0"><button type="submit" name="tambah" class="btn btn-primary w-100 py-2 fw-bold">Simpan Data</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="POST">
                <input type="hidden" name="id_apb" id="edit_id">
                <div class="modal-header border-0"><h5 class="fw-bold">Edit Data Anggaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label small fw-bold">TAHUN</label><input type="number" name="tahun" id="edit_tahun" class="form-control" required></div>
                        <div class="col-6"><label class="form-label small fw-bold">JENIS</label><select name="jenis" id="edit_jenis" class="form-select"><option value="Pendapatan">Pendapatan</option><option value="Belanja">Belanja</option></select></div>
                        <div class="col-12"><label class="form-label small fw-bold">RINCIAN KEGIATAN</label><input type="text" name="rincian" id="edit_rincian" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">TARGET ANGGARAN (Rp)</label><input type="number" name="anggaran" id="edit_anggaran" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">REALISASI SAAT INI (Rp)</label><input type="number" name="realisasi" id="edit_realisasi" class="form-control" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0"><button type="submit" name="edit_apb" class="btn btn-warning w-100 py-2 fw-bold">Simpan Perubahan</button></div>
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

    function editAPB(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_tahun').value = data.tahun;
        document.getElementById('edit_jenis').value = data.jenis;
        document.getElementById('edit_rincian').value = data.rincian;
        document.getElementById('edit_anggaran').value = data.anggaran;
        document.getElementById('edit_realisasi').value = data.realisasi;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
</script>
</body>
</html>