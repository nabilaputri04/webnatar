<?php
require 'auth_check.php';
require '../config/db.php';

$success = ""; $error = "";

// Cek success dari redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "✅ Data berhasil disimpan!";
}

$jabatan_opts = ['Ketua BPD','Wakil Ketua','Sekretaris','Anggota'];

// Debug error
if (isset($_GET['error'])) {
    $error = "❌ " . urldecode($_GET['error']);
}

// delete
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM bpd WHERE id={$id}");
    header('Location: manage-bpd.php'); exit;
}

// edit fetch
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM bpd WHERE id={$id} LIMIT 1");
    if ($res && mysqli_num_rows($res)) { $edit = mysqli_fetch_assoc($res); }
}

// save
if (isset($_POST['simpan'])) {
    $id_edit = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $urutan = (int)($_POST['urutan'] ?? 0);

    if (empty($nama)) {
        header('Location: manage-bpd.php?error=' . urlencode('Nama wajib diisi') . '&edit=' . $id_edit);
        exit;
    } elseif (empty($jabatan)) {
        header('Location: manage-bpd.php?error=' . urlencode('Jabatan wajib dipilih') . '&edit=' . $id_edit);
        exit;
    } elseif (!in_array($jabatan, $jabatan_opts, true)) {
        header('Location: manage-bpd.php?error=' . urlencode('Jabatan tidak valid: ' . $jabatan) . '&edit=' . $id_edit);
        exit;
    }
    
    // Escape untuk SQL
    $nama_esc = mysqli_real_escape_string($conn, $nama);
    $jabatan_esc = mysqli_real_escape_string($conn, $jabatan);
    $foto_esc = mysqli_real_escape_string($conn, $foto);
    
    if ($id_edit > 0) {
        // UPDATE
        $sql = "UPDATE bpd SET nama='$nama_esc', jabatan='$jabatan_esc', foto='$foto_esc', urutan=$urutan WHERE id=$id_edit";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: manage-bpd.php?success=1&t=' . time());
            exit;
        } else {
            header('Location: manage-bpd.php?error=' . urlencode('Error SQL: ' . mysqli_error($conn)) . '&edit=' . $id_edit);
            exit;
        }
    } else {
        // INSERT
        $sql = "INSERT INTO bpd (nama, jabatan, foto, urutan) VALUES ('$nama_esc','$jabatan_esc','$foto_esc',$urutan)";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: manage-bpd.php?success=1&t=' . time());
            exit;
        } else {
            header('Location: manage-bpd.php?error=' . urlencode('Error SQL: ' . mysqli_error($conn)));
            exit;
        }
    }
}

$list = mysqli_query($conn, "SELECT * FROM bpd ORDER BY urutan ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola BPD</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php include 'admin-styles.php'; ?>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="bi bi-house-door"></i> <span>Dashboard</span></a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> <span>Profil Desa</span></a></li>
        <li><a href="manage-struktur.php"><i class="bi bi-people"></i> <span>Perangkat Desa</span></a></li>
        <li><a href="manage-bpd.php" class="active"><i class="bi bi-people-fill"></i> <span>BPD</span></a></li>
        <li><a href="manage-lpmd.php"><i class="bi bi-diagram-3"></i> <span>LPMD</span></a></li>
        <li><a href="manage-rt.php"><i class="bi bi-house"></i> <span>RT</span></a></li>
        <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> <span>Kelola Berita</span></a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> <span>APB Desa</span></a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> <span>Potensi Desa</span></a></li>
        <li><a href="manage-sarana.php"><i class="bi bi-building"></i> <span>Sarana & Prasarana</span></a></li>
        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> <span>Pengaduan</span></a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> <span>Kontak</span></a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Keluar</span></a>
    </div>
</div>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Kelola BPD</h3>
            <p class="text-muted mb-0">Tambah/edit/hapus anggota BPD (urutan menentukan tampilan).</p>
        </div>
        <a class="btn btn-outline-secondary" href="../struktur-organisasi.php" target="_blank">Lihat Halaman Publik</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><?php echo $edit ? 'Edit Anggota' : 'Tambah Anggota'; ?></h5>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $edit['nama'] ?? ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">Pilih jabatan</option>
                                <?php foreach ($jabatan_opts as $opt): ?>
                                    <option value="<?php echo $opt; ?>" <?php echo (isset($edit['jabatan']) && $edit['jabatan']===$opt)?'selected':''; ?>><?php echo $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto (path, opsional)</label>
                            <input type="text" name="foto" class="form-control" placeholder="assets/img/perangkat/xxx.jpg" value="<?php echo $edit['foto'] ?? ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="<?php echo $edit['urutan'] ?? 0; ?>">
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <?php if ($edit): ?><a href="manage-bpd.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Daftar BPD</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Urutan</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Foto</th>
                                    <th style="width:140px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($list && mysqli_num_rows($list)): ?>
                                    <?php while($row = mysqli_fetch_assoc($list)): ?>
                                        <tr>
                                            <td><?php echo $row['urutan']; ?></td>
                                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                            <td><?php echo $row['jabatan']; ?></td>
                                            <td class="text-truncate" style="max-width:120px;"><?php echo htmlspecialchars($row['foto']); ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="manage-bpd.php?edit=<?php echo $row['id']; ?>">Edit</a>
                                                <a class="btn btn-sm btn-outline-danger" href="manage-bpd.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
