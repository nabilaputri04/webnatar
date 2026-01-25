<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = ""; $error = "";

// --- LOGIKA KATEGORI (Tambah & Hapus) ---
if (isset($_POST['tambah_kategori'])) {
    $nama_kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori_unduhan (nama_kategori) VALUES ('$nama_kat')");
    $sukses = "Kategori dokumen berhasil ditambahkan!";
}

if (isset($_GET['hapus_kategori'])) {
    $id_kat = (int)$_GET['hapus_kategori'];
    mysqli_query($conn, "DELETE FROM kategori_unduhan WHERE id = $id_kat");
    header("Location: manage-unduhan.php"); exit;
}

// --- LOGIKA UNGGAH DOKUMEN ---
if (isset($_POST['upload'])) {
    $nama_dokumen = mysqli_real_escape_string($conn, $_POST['nama_dokumen']);
    $id_kategori = (int)$_POST['id_kategori'];
    
    $file_name = $_FILES['dokumen']['name'];
    $file_tmp = $_FILES['dokumen']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'jpg', 'png'];
    
    if (in_array($file_ext, $allowed)) {
        $new_filename = uniqid() . '.' . $file_ext;
        $target_path = '../assets/files/unduhan/' . $new_filename;
        
        if (!file_exists('../assets/files/unduhan/')) mkdir('../assets/files/unduhan/', 0777, true);

        if (move_uploaded_file($file_tmp, $target_path)) {
            $query = "INSERT INTO unduhan (nama_dokumen, nama_file, id_kategori) VALUES ('$nama_dokumen', '$new_filename', $id_kategori)";
            if (mysqli_query($conn, $query)) { $sukses = "Dokumen berhasil dipublikasikan!"; }
        } else { $error = "Gagal mengunggah file ke server."; }
    } else { $error = "Format file tidak didukung."; }
}

// --- LOGIKA HAPUS DOKUMEN ---
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $res = mysqli_query($conn, "SELECT nama_file FROM unduhan WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row['nama_file'] && file_exists("../assets/files/unduhan/" . $row['nama_file'])) {
        unlink("../assets/files/unduhan/" . $row['nama_file']);
    }
    mysqli_query($conn, "DELETE FROM unduhan WHERE id = $id");
    header("Location: manage-unduhan.php"); exit;
}

$data_unduhan = mysqli_query($conn, "SELECT u.*, k.nama_kategori FROM unduhan u LEFT JOIN kategori_unduhan k ON u.id_kategori = k.id ORDER BY u.tgl_upload DESC");
$categories = mysqli_query($conn, "SELECT * FROM kategori_unduhan ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Unduhan - Admin Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; --bs-primary: #10b981; --bs-primary-rgb: 16, 185, 129; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* Sidebar Styling */
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
            overflow: hidden; /* Prevent sidebar from scrolling */
        }

        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.4rem; 
            padding-bottom: 30px; 
            border-bottom: 1px solid #2d3238; 
            margin-bottom: 20px;
            flex-shrink: 0; /* Prevent brand from shrinking */
        }

        .sidebar-menu { 
            flex-grow: 1; 
            list-style: none; 
            padding: 0; 
            margin: 0;
            overflow-y: auto; /* Only menu scrolls */
            overflow-x: hidden;
            /* Custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }
        
        /* Webkit scrollbar styling */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        
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
            white-space: nowrap; /* Prevent text wrapping */
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        /* Logout Section (Fixed at bottom) */
        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0; /* Prevent logout from shrinking */
            background: var(--sidebar-bg); /* Ensure background consistency */
        }
        .logout-link { color: #ea868f !important; }
        .logout-link:hover { background: rgba(234, 134, 143, 0.1); }

        /* Layout Responsif */
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; }
        }

        .stat-card { border: none; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
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
            <li><a href="index.php" class="nav-link active"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
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
            <h3 class="fw-bold">Manajemen File Unduhan</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalKategori">
                    <i class="bi bi-folder me-2"></i>Kategori
                </button>
                <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalUpload">
                    <i class="bi bi-cloud-upload me-2"></i>Unggah Dokumen
                </button>
            </div>
        </div>

        <?php if($sukses): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i><?php echo $sukses; ?>
        </div>
        <?php endif; ?>

        <?php if($error): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="card card-premium p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-secondary fw-bold">
                            <th>DOKUMEN</th>
                            <th>KATEGORI</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_unduhan)): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_dokumen']); ?></div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i><?php echo date('d/m/Y', strtotime($row['tgl_upload'])); ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                    <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="../assets/files/unduhan/<?php echo $row['nama_file']; ?>" 
                                   class="btn btn-sm btn-outline-info border-0 rounded-pill" 
                                   target="_blank" 
                                   title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="?hapus=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger border-0 rounded-pill" 
                                   onclick="return confirm('Hapus file ini?')"
                                   title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- MODAL KATEGORI -->
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mb-0">Kelola Kategori File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" method="POST" class="mb-4">
                    <div class="input-group shadow-sm">
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Nama kategori..." required>
                        <button type="submit" name="tambah_kategori" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                </form>
                <ul class="list-group list-group-flush">
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                            <span><?php echo htmlspecialchars($cat['nama_kategori']); ?></span>
                            <a href="?hapus_kategori=<?php echo $cat['id']; ?>" 
                               class="text-danger small" 
                               onclick="return confirm('Hapus kategori ini?')">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD -->
<div class="modal fade" id="modalUpload" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Unggah Dokumen Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NAMA DOKUMEN</label>
                        <input type="text" name="nama_dokumen" class="form-control shadow-sm" placeholder="Contoh: Surat Pengantar RT" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KATEGORI</label>
                        <select name="id_kategori" class="form-select shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nama_kategori']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">PILIH FILE</label>
                        <input type="file" name="dokumen" class="form-control shadow-sm" required>
                        <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, ZIP, JPG, PNG (Max 10MB)</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="upload" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                        <i class="bi bi-cloud-upload me-2"></i>Unggah Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Menutup sidebar otomatis jika klik di luar pada layar kecil
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>