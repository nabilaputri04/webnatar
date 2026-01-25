<?php
require 'auth_check.php';
require '../config/db.php';

$sukses = "";
$error = "";
$active_tab = $_GET['tab'] ?? 'perangkat';

// === PERANGKAT DESA ===
if (isset($_POST['tambah_perangkat'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $urutan = (int)$_POST['urutan'];
    
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/img/perangkat/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $new_filename;
            }
        }
    }
    
    $query = "INSERT INTO perangkat_desa (nama, jabatan, foto, urutan) VALUES ('$nama', '$jabatan', '$foto', $urutan)";
    if (mysqli_query($conn, $query)) {
        $sukses = "Perangkat desa berhasil ditambahkan!";
        $active_tab = 'perangkat';
    } else {
        $error = "Gagal menyimpan data.";
    }
}

if (isset($_GET['hapus_perangkat'])) {
    $id = (int)$_GET['hapus_perangkat'];
    $result = mysqli_query($conn, "SELECT foto FROM perangkat_desa WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['foto']) && file_exists("../assets/img/perangkat/" . $row['foto'])) {
            unlink("../assets/img/perangkat/" . $row['foto']);
        }
    }
    mysqli_query($conn, "DELETE FROM perangkat_desa WHERE id = $id");
    header("Location: manage-struktur.php?tab=perangkat");
    exit;
}

// === BPD ===
if (isset($_POST['tambah_bpd'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $urutan = (int)$_POST['urutan'];
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);
    
    $query = "INSERT INTO bpd (nama, jabatan, foto, urutan) VALUES ('$nama', '$jabatan', '$foto', $urutan)";
    if (mysqli_query($conn, $query)) {
        $sukses = "Anggota BPD berhasil ditambahkan!";
        $active_tab = 'bpd';
    } else {
        $error = "Gagal menyimpan data BPD.";
    }
}

if (isset($_GET['hapus_bpd'])) {
    $id = (int)$_GET['hapus_bpd'];
    mysqli_query($conn, "DELETE FROM bpd WHERE id = $id");
    header("Location: manage-struktur.php?tab=bpd");
    exit;
}

// === LPMD ===
if (isset($_POST['tambah_lpmd'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $urutan = (int)$_POST['urutan'];
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);
    
    $query = "INSERT INTO lpmd (nama, jabatan, foto, urutan) VALUES ('$nama', '$jabatan', '$foto', $urutan)";
    if (mysqli_query($conn, $query)) {
        $sukses = "Anggota LPMD berhasil ditambahkan!";
        $active_tab = 'lpmd';
    } else {
        $error = "Gagal menyimpan data LPMD.";
    }
}

if (isset($_GET['hapus_lpmd'])) {
    $id = (int)$_GET['hapus_lpmd'];
    mysqli_query($conn, "DELETE FROM lpmd WHERE id = $id");
    header("Location: manage-struktur.php?tab=lpmd");
    exit;
}

// === RT ===
if (isset($_POST['tambah_rt'])) {
    $dusun = mysqli_real_escape_string($conn, $_POST['dusun']);
    $nomor_rt = mysqli_real_escape_string($conn, $_POST['nomor_rt']);
    $nama_ketua = mysqli_real_escape_string($conn, $_POST['nama_ketua']);
    
    $query = "INSERT INTO rt (dusun, nomor_rt, nama_ketua) VALUES ('$dusun', '$nomor_rt', '$nama_ketua')";
    if (mysqli_query($conn, $query)) {
        $sukses = "Data RT berhasil ditambahkan!";
        $active_tab = 'rt';
    } else {
        $error = "Gagal menyimpan data RT.";
    }
}

if (isset($_GET['hapus_rt'])) {
    $id = (int)$_GET['hapus_rt'];
    mysqli_query($conn, "DELETE FROM rt WHERE id = $id");
    header("Location: manage-struktur.php?tab=rt");
    exit;
}

// Fetch data
$data_perangkat = mysqli_query($conn, "SELECT * FROM perangkat_desa ORDER BY urutan ASC");
$data_bpd = mysqli_query($conn, "SELECT * FROM bpd ORDER BY urutan ASC");
$data_lpmd = mysqli_query($conn, "SELECT * FROM lpmd ORDER BY urutan ASC");
$data_rt = mysqli_query($conn, "SELECT * FROM rt ORDER BY dusun ASC, nomor_rt ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Perangkat Desa - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #1a1d20; --active-blue: #10b981; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            padding: 25px 15px;
            overflow-y: auto;
        }
        .sidebar-brand { 
            color: var(--active-blue); 
            font-weight: 700; 
            font-size: 1.4rem; 
            padding-bottom: 20px; 
            margin-bottom: 20px;
            border-bottom: 1px solid #2d3238;
        }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin-bottom: 8px; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: #94a3b8;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: var(--active-blue);
            color: white;
        }
        .main-content {
            margin-left: 280px;
            padding: 30px;
        }
        .nav-tabs .nav-link {
            color: #64748b;
            border: none;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            background: var(--active-blue);
            color: white;
            border-radius: 8px;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .btn-primary { background: var(--active-blue); border-color: var(--active-blue); }
        .btn-primary:hover { background: #059669; border-color: #059669; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
        <li><a href="manage-struktur.php" class="active"><i class="bi bi-people"></i> Perangkat Desa</a></li>
        <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="mt-auto pt-4 border-top border-secondary">
        <a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Manajemen Struktur Organisasi</h3>
        <p class="text-muted">Kelola Perangkat Desa, BPD, LPMD, dan RT dalam satu halaman.</p>
    </div>

    <?php if($sukses): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo $sukses; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'perangkat' ? 'active' : ''; ?>" href="?tab=perangkat">
                <i class="bi bi-people-fill me-2"></i>Perangkat Desa
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'bpd' ? 'active' : ''; ?>" href="?tab=bpd">
                <i class="bi bi-diagram-3-fill me-2"></i>BPD
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'lpmd' ? 'active' : ''; ?>" href="?tab=lpmd">
                <i class="bi bi-diagram-2-fill me-2"></i>LPMD
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'rt' ? 'active' : ''; ?>" href="?tab=rt">
                <i class="bi bi-pin-map-fill me-2"></i>RT
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        
        <!-- PERANGKAT DESA TAB -->
        <?php if ($active_tab == 'perangkat'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Tambah Perangkat</h5>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" required>
                        </div>
                        <button type="submit" name="tambah_perangkat" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Daftar Perangkat Desa</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_perangkat)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <?php if ($row['foto']): ?>
                                            <img src="../assets/img/perangkat/<?php echo $row['foto']; ?>" width="40" height="40" class="rounded-circle" style="object-fit:cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle" style="width:40px;height:40px;"></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['jabatan']; ?></td>
                                    <td><?php echo $row['urutan']; ?></td>
                                    <td>
                                        <a href="?hapus_perangkat=<?php echo $row['id']; ?>&tab=perangkat" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- BPD TAB -->
        <?php if ($active_tab == 'bpd'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Tambah Anggota BPD</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Wakil Ketua">Wakil Ketua</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto (opsional)</label>
                            <input type="text" name="foto" class="form-control" placeholder="nama-file.jpg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" required>
                        </div>
                        <button type="submit" name="tambah_bpd" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Daftar Anggota BPD</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_bpd)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['jabatan']; ?></td>
                                    <td><?php echo $row['urutan']; ?></td>
                                    <td>
                                        <a href="?hapus_bpd=<?php echo $row['id']; ?>&tab=bpd" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- LPMD TAB -->
        <?php if ($active_tab == 'lpmd'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Tambah Anggota LPMD</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Wakil Ketua">Wakil Ketua</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto (opsional)</label>
                            <input type="text" name="foto" class="form-control" placeholder="nama-file.jpg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" required>
                        </div>
                        <button type="submit" name="tambah_lpmd" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Daftar Anggota LPMD</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_lpmd)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['jabatan']; ?></td>
                                    <td><?php echo $row['urutan']; ?></td>
                                    <td>
                                        <a href="?hapus_lpmd=<?php echo $row['id']; ?>&tab=lpmd" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- RT TAB -->
        <?php if ($active_tab == 'rt'): ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Tambah Data RT</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Dusun</label>
                            <select name="dusun" class="form-select" required>
                                <option value="">Pilih...</option>
                                <?php foreach (['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI'] as $d): ?>
                                    <option value="<?php echo $d; ?>">Dusun <?php echo $d; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor RT</label>
                            <input type="text" name="nomor_rt" class="form-control" placeholder="001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Ketua</label>
                            <input type="text" name="nama_ketua" class="form-control" required>
                        </div>
                        <button type="submit" name="tambah_rt" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Daftar RT</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dusun</th>
                                    <th>RT</th>
                                    <th>Nama Ketua</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_rt)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>Dusun <?php echo $row['dusun']; ?></td>
                                    <td><?php echo $row['nomor_rt']; ?></td>
                                    <td><?php echo $row['nama_ketua']; ?></td>
                                    <td>
                                        <a href="?hapus_rt=<?php echo $row['id']; ?>&tab=rt" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
