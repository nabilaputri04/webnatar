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
    $anggaran = (float)$_POST['anggaran'];
    $realisasi = (float)$_POST['realisasi'];

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
    $anggaran = (float)$_POST['anggaran'];
    $realisasi = (float)$_POST['realisasi'];

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
    SUM(CASE WHEN jenis = 'Belanja' THEN realisasi ELSE 0 END) as total_belanja,
    SUM(CASE WHEN jenis = 'Pembiayaan Desa' THEN realisasi ELSE 0 END) as total_pembiayaan
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
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.25rem;
            padding: 0 20px 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid #2d3238;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0 15px; 
            padding-bottom: 80px;
            margin: 0;
            overflow-y: auto;
        }
        .sidebar-menu li { margin-bottom: 6px; }
        .sidebar-menu a {
            color: #94a3b8;
            padding: 14px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.2s;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
        }
        .sidebar-menu a i { font-size: 1.2rem; }
        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background: var(--active-blue); 
            color: white; 
        }

        .logout-section { 
            position: fixed;
            bottom: 0;
            left: 0;
            width: 280px;
            padding: 20px 15px;
            border-top: 1px solid #2d3238;
            background: var(--sidebar-bg);
            flex-shrink: 0;
        }
        .logout-section a {
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.2s;
        }
        .logout-section a i { font-size: 1.2rem; }
        .logout-section a:hover { background: rgba(239, 68, 68, 0.1); }
        
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
            <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="active"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>            <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>

        <div class="logout-section">
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Transparansi APB Desa</h3>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i> Tambah Data
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white shadow-sm">
                    <small class="opacity-75">Total Pendapatan Cair</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($res_total['total_pendapatan'], 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning text-dark shadow-sm">
                    <small class="opacity-75">Total Belanja Terpakai</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($res_total['total_belanja'], 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info text-white shadow-sm">
                    <small class="opacity-75">Total Pembiayaan Desa</small>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($res_total['total_pembiayaan'], 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
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
                            <th class="text-end" style="min-width: 200px;">ANGGARAN</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_apb)): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['rincian']); ?></div>
                                <small class="text-muted">Tahun <?php echo $row['tahun']; ?></small>
                            </td>
                            <td>
                                <?php 
                                $badge_class = '';
                                if($row['jenis'] == 'Pendapatan') {
                                    $badge_class = 'bg-success-subtle text-success';
                                } elseif($row['jenis'] == 'Belanja') {
                                    $badge_class = 'bg-warning-subtle text-warning';
                                } else {
                                    $badge_class = 'bg-info-subtle text-info';
                                }
                                ?>
                                <span class="badge <?php echo $badge_class; ?> border px-3 py-2 rounded-pill"><?php echo $row['jenis']; ?></span>
                            </td>
                            <td style="min-width: 200px;">
                                <div class="fw-bold text-end" style="font-family: 'Courier New', monospace; font-size: 1rem; letter-spacing: 0.5px;">Rp <?php echo number_format($row['realisasi'], 2, ',', '.'); ?></div>
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
                        <div class="col-6"><label class="form-label small fw-bold">JENIS</label><select name="jenis" class="form-select"><option value="Pendapatan">Pendapatan</option><option value="Belanja">Belanja</option><option value="Pembiayaan Desa">Pembiayaan Desa</option></select></div>
                        <div class="col-12"><label class="form-label small fw-bold">RINCIAN KEGIATAN</label><input type="text" name="rincian" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">ANGGARAN (Rp)</label><input type="text" id="input_anggaran" name="realisasi" class="form-control" value="0,00" required></div>
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
                        <div class="col-6"><label class="form-label small fw-bold">JENIS</label><select name="jenis" id="edit_jenis" class="form-select"><option value="Pendapatan">Pendapatan</option><option value="Belanja">Belanja</option><option value="Pembiayaan Desa">Pembiayaan Desa</option></select></div>
                        <div class="col-12"><label class="form-label small fw-bold">RINCIAN KEGIATAN</label><input type="text" name="rincian" id="edit_rincian" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small fw-bold">ANGGARAN (Rp)</label><input type="text" name="realisasi" id="edit_realisasi" class="form-control" required></div>
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

    // Fungsi format angka dengan pemisah ribuan dan desimal
    function formatRupiah(angka, prefix = '') {
        let number_string = angka.replace(/[^,\d]/g, '').toString();
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        
        if(ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix + rupiah;
    }

    // Auto format untuk input anggaran tambah
    const inputAnggaran = document.getElementById('input_anggaran');
    if(inputAnggaran) {
        inputAnggaran.addEventListener('keyup', function(e) {
            inputAnggaran.value = formatRupiah(this.value);
        });
    }

    // Auto format untuk input anggaran edit
    const editRealisasi = document.getElementById('edit_realisasi');
    if(editRealisasi) {
        editRealisasi.addEventListener('keyup', function(e) {
            editRealisasi.value = formatRupiah(this.value);
        });
    }

    // Convert format ke angka saat submit form tambah
    document.querySelector('#modalTambah form').addEventListener('submit', function(e) {
        let inputVal = inputAnggaran.value;
        let numericValue = inputVal.replace(/\./g, '').replace(',', '.');
        inputAnggaran.value = numericValue;
    });

    // Convert format ke angka saat submit form edit
    document.querySelector('#modalEdit form').addEventListener('submit', function(e) {
        let inputVal = editRealisasi.value;
        let numericValue = inputVal.replace(/\./g, '').replace(',', '.');
        editRealisasi.value = numericValue;
    });

    function editAPB(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_tahun').value = data.tahun;
        document.getElementById('edit_jenis').value = data.jenis;
        document.getElementById('edit_rincian').value = data.rincian;
        
        // Format angka untuk edit
        let realisasi = parseFloat(data.realisasi);
        let formatted = realisasi.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('edit_realisasi').value = formatted;
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
</script>
</body>
</html>