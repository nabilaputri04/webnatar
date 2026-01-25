<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";

// 1. Logika Tambah Perangkat (DIBEDAKAN NAMANYA)
if (isset($_POST['tambah_perangkat'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $urutan = (int)$_POST['urutan'];
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/perangkat/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png');
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $new_filename;
            } else {
                $error = "Gagal upload foto.";
            }
        } else {
            $error = "Tipe file tidak diizinkan. Gunakan JPG atau PNG.";
        }
    }
    
    if (empty($error)) {
        $query = "INSERT INTO perangkat_desa (nama, jabatan, foto, urutan) 
                  VALUES ('$nama', '$jabatan', '$foto', $urutan)";
        if (mysqli_query($conn, $query)) {
            $sukses = "Perangkat desa berhasil ditambahkan!";
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}

// 2. Logika Tambah Berita (DIBEDAKAN NAMANYA)
if (isset($_POST['tambah_berita'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']); // Tambahkan escape string
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    
    $foto_berita = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/berita/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png');
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto_berita = $new_filename;
            } else {
                $error = "Gagal upload gambar berita.";
            }
        }
    }
    
    if (empty($error)) {
        $query = "INSERT INTO berita (judul, id_kategori, gambar, isi_berita, tgl_posting) 
                  VALUES ('$judul', (SELECT id FROM kategori_berita WHERE nama_kategori = '$kategori' LIMIT 1), 
                          '$foto_berita', '$isi', NOW())";
        if (mysqli_query($conn, $query)) {
            $sukses = "Berita berhasil dipublikasikan!";
        } else {
            $error = "Gagal menyimpan berita: " . mysqli_error($conn);
        }
    }
}

// Logika Hapus (Tetap Sama)
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $result = mysqli_query($conn, "SELECT foto FROM perangkat_desa WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['foto']) && file_exists("../assets/img/perangkat/" . $row['foto'])) {
            unlink("../assets/img/perangkat/" . $row['foto']);
        }
    }
    mysqli_query($conn, "DELETE FROM perangkat_desa WHERE id = $id");
    header("Location: manage-struktur.php");
    exit;
}

$data_perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
$data_berita = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b LEFT JOIN kategori_berita k ON b.id_kategori = k.id ORDER BY b.tgl_posting DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Perangkat - Desa Natar</title>
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
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover { background: rgba(13, 110, 253, 0.15); color: var(--active-blue); }
        .nav-link.active { background: var(--active-blue); color: #fff; }

        .logout-section { 
            margin-top: auto; 
            padding-top: 20px; 
            border-top: 1px solid #2d3238;
            flex-shrink: 0;
            background: var(--sidebar-bg);
        }
        .logout-link { color: #ea868f !important; }

        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }
        .mobile-header { display: none; background: #fff; padding: 15px 20px; border-bottom: 1px solid #dee2e6; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; }
        }
        
        .card-premium { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.02); background: #fff; }
        .perangkat-foto { width: 60px; height: 60px; object-fit: cover; border-radius: 12px; }
        .berita-thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; }
        .form-label { font-weight: 600; color: #4b5563; font-size: 0.85rem; }
        .form-control, .form-select { padding: 12px; border-radius: 10px; border: 1px solid #e5e7eb; }
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
            <li><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="manage-profil.php" class="nav-link"><i class="bi bi-house-door"></i> Profil Desa</a></li>
            <li><a href="manage-struktur.php" class="nav-link active"><i class="bi bi-people"></i> Perangkat Desa</a></li>
            <li><a href="manage-berita.php" class="nav-link"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
            <li><a href="manage-apbdesa.php" class="nav-link"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
            <li><a href="manage-potensi.php" class="nav-link"><i class="bi bi-map"></i> Potensi Desa</a></li>
            <li><a href="manage-kontak.php" class="nav-link"><i class="bi bi-telephone"></i> Kontak</a></li>
        </ul>
        <div class="logout-section">
            <a href="logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <main class="main-content w-100">
        <div class="mb-5">
            <h3 class="fw-bold mb-1">Manajemen Perangkat Desa</h3>
            <p class="text-muted">Kelola struktur organisasi Desa Natar.</p>
        </div>

        <?php if($sukses): ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i> <?php echo $sukses; ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Tambah Perangkat</h5>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama tanpa gelar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JABATAN</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Sekretaris Desa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">FOTO PROFIL</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">URUTAN TAMPIL</label>
                            <input type="number" name="urutan" class="form-control" value="0" required>
                        </div>
                        <button type="submit" name="tambah_perangkat" class="btn btn-primary w-100 fw-bold py-3 rounded-3">Simpan Data</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Daftar Perangkat Desa</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="small text-secondary fw-bold">
                                    <th>NO</th>
                                    <th>FOTO</th>
                                    <th>NAMA & JABATAN</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($row = mysqli_fetch_assoc($data_perangkat)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><img src="../assets/img/perangkat/<?php echo $row['foto']; ?>" class="perangkat-foto"></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($row['jabatan']); ?></div>
                                    </td>
                                    <td class="text-center">
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-pill"><i class="bi bi-trash3-fill"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h3 class="fw-bold mb-1">Manajemen Berita</h3>
            <p class="text-muted">Publikasikan informasi terkini desa.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Tambah Berita</h5>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">JUDUL BERITA</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KATEGORI</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">Pilih kategori</option>
                                <?php
                                $kategori_result = mysqli_query($conn, "SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
                                while($kategori = mysqli_fetch_assoc($kategori_result)): 
                                ?>
                                <option value="<?php echo $kategori['nama_kategori']; ?>"><?php echo $kategori['nama_kategori']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ISI BERITA</label>
                            <textarea name="isi" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GAMBAR</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="tambah_berita" class="btn btn-primary w-100 fw-bold py-3 rounded-3">Publikasikan Berita</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-premium p-4">
                    <h5 class="fw-bold mb-4">Daftar Berita</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="small text-secondary fw-bold">
                                    <th>GAMBAR</th>
                                    <th>JUDUL</th>
                                    <th>KATEGORI</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($data_berita)): ?>
                                <tr>
                                    <td><img src="../assets/img/berita/<?php echo $row['gambar']; ?>" class="berita-thumbnail"></td>
                                    <td><div class="fw-bold"><?php echo htmlspecialchars($row['judul']); ?></div></td>
                                    <td><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?php echo $row['nama_kategori'] ?? 'Umum'; ?></span></td>
                                    <td class="text-center">
                                        <a href="?hapus_berita=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-pill"><i class="bi bi-trash3-fill"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
</script>
</body>
</html>