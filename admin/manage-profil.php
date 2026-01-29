<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = false;
$error = false;

// 1. Ambil data profil (ID=1)
$query = mysqli_query($conn, "SELECT * FROM profil WHERE id = 1");
$data = mysqli_fetch_assoc($query);

// 2. Logika Update
if (isset($_POST['update'])) {
    $sejarah = mysqli_real_escape_string($conn, $_POST['sejarah']);
    $visi = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi = mysqli_real_escape_string($conn, $_POST['misi']);
    $populasi = (int)$_POST['populasi'];
    $luas = mysqli_real_escape_string($conn, $_POST['luas_wilayah']);

    $update = mysqli_query($conn, "UPDATE profil SET 
                sejarah = '$sejarah', visi = '$visi', misi = '$misi', 
                populasi = $populasi, luas_wilayah = '$luas' 
                WHERE id = 1");

    if ($update) {
        $sukses = true;
        $query = mysqli_query($conn, "SELECT * FROM profil WHERE id = 1");
        $data = mysqli_fetch_assoc($query);
    } else { $error = true; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Profil - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; --bs-primary: #10b981; --bs-primary-rgb: 16, 185, 129; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
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
            list-style: none; 
            padding: 0 15px; 
            padding-bottom: 80px;
            margin: 0; 
            flex-grow: 1;
            overflow-y: auto;
        }
        .sidebar-menu li { margin-bottom: 6px; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
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
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #ef4444;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .logout-section a i { font-size: 1.2rem; }
        .logout-section a:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* Content Styling */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .form-card { background: #fff; border-radius: 20px; border: none; box-shadow: var(--card-shadow); padding: 40px; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: #343a40; margin-bottom: 20px; display: flex; align-items: center; }
        .section-title i { color: var(--primary-blue); margin-right: 10px; }
        
        .form-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #dee2e6; background-color: #fdfdfd; }
        .form-control:focus { box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); border-color: var(--primary-blue); }

        .btn-save { background: var(--primary-blue); border: none; padding: 15px 40px; border-radius: 12px; font-weight: 700; transition: 0.3s; }
        .btn-save:hover { background: #0b5ed7; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3); }

        /* Responsive */
        .mobile-nav { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; position: sticky; top: 0; z-index: 999; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-nav { display: flex; justify-content: space-between; align-items: center; }
        }
    </style>
</head>
<body>

<div class="mobile-nav">
    <span class="fw-bold text-primary">Admin Desa Natar</span>
    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
</div>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="active"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>            <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section">
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="mb-4 d-flex justify-content-between align-items-end">
            <div>
                <h3 class="fw-bold mb-1">Pengaturan Profil Desa</h3>
                <p class="text-muted mb-0">Perbarui informasi identitas dan statistik kependudukan desa.</p>
            </div>
            <a href="../index.php" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">Lihat Website <i class="bi bi-box-arrow-up-right ms-1"></i></a>
        </div>

        <?php if ($sukses) : ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm py-3 mb-4 animate__animated animate__fadeIn">
                <i class="bi bi-check-circle-fill me-2"></i> Data profil berhasil diperbarui secara sistematis!
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-card mb-4">
                <div class="section-title"><i class="bi bi-bar-chart-fill"></i> Data Statistik Utama</div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Penduduk (Jiwa)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-people text-muted"></i></span>
                            <input type="number" name="populasi" class="form-control bg-light border-start-0" value="<?php echo $data['populasi']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Wilayah</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-bounding-box text-muted"></i></span>
                            <input type="text" name="luas_wilayah" class="form-control bg-light border-start-0" value="<?php echo $data['luas_wilayah']; ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="section-title"><i class="bi bi-file-earmark-text-fill"></i> Narasi & Visi Misi</div>
                
                <div class="mb-4">
                    <label class="form-label">Sejarah Desa</label>
                    <textarea name="sejarah" class="form-control" rows="6" placeholder="Tuliskan sejarah singkat desa..."><?php echo $data['sejarah']; ?></textarea>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-bold">Visi Desa</label>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-lightbulb"></i> Pastikan kata "Desa Natar" ditulis dengan spasi yang benar (bukan "DesaNatar")
                        </small>
                        <textarea name="visi" class="form-control" rows="8" style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 1.1rem; line-height: 1.8;" placeholder="Contoh: Pemerintahan Desa NATAR dan Masyarakat setempat sepakat bahwa Visi adalah gambaran umum dari kondisi yang ideal..."><?php echo $data['visi']; ?></textarea>
                        <small class="text-muted mt-2 d-block">Preview akan ditampilkan dengan font Georgia dan rata kanan-kiri (justify)</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Misi Desa</label>
                        <textarea name="misi" class="form-control" rows="6" placeholder="Langkah-langkah pencapaian visi..."><?php echo $data['misi']; ?></textarea>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small italic"><i class="bi bi-info-circle me-1"></i> Perubahan akan langsung tampil di widget "Peta Lokasi Desa".</span>
                    <button type="submit" name="update" class="btn btn-primary btn-save text-white shadow">
                        Simpan Perubahan Data
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('active'); });
</script>

</body>
</html>