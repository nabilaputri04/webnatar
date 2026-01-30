<?php
require 'auth_check.php';
require '../config/db.php';

$success = ""; $error = "";

// Cek success dari redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "✅ Data berhasil disimpan!";
}

$jabatan_opts = ['Ketua LPMD','Wakil Ketua','Sekretaris','Anggota'];

// Debug error
if (isset($_GET['error'])) {
    $error = "❌ " . urldecode($_GET['error']);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM lpmd WHERE id={$id}");
    header('Location: manage-lpmd.php'); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM lpmd WHERE id={$id} LIMIT 1");
    if ($res && mysqli_num_rows($res)) { $edit = mysqli_fetch_assoc($res); }
}

if (isset($_POST['simpan'])) {
    $id_edit = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $urutan = (int)($_POST['urutan'] ?? 0);

    if (empty($nama)) {
        header('Location: manage-lpmd.php?error=' . urlencode('Nama wajib diisi') . '&edit=' . $id_edit);
        exit;
    } elseif (empty($jabatan)) {
        header('Location: manage-lpmd.php?error=' . urlencode('Jabatan wajib dipilih') . '&edit=' . $id_edit);
        exit;
    } elseif (!in_array($jabatan, $jabatan_opts, true)) {
        header('Location: manage-lpmd.php?error=' . urlencode('Jabatan tidak valid: ' . $jabatan) . '&edit=' . $id_edit);
        exit;
    }
    
    // Escape untuk SQL
    $nama_esc = mysqli_real_escape_string($conn, $nama);
    $jabatan_esc = mysqli_real_escape_string($conn, $jabatan);
    $foto_esc = mysqli_real_escape_string($conn, $foto);
    
    if ($id_edit > 0) {
        // UPDATE
        $sql = "UPDATE lpmd SET nama='$nama_esc', jabatan='$jabatan_esc', foto='$foto_esc', urutan=$urutan WHERE id=$id_edit";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: manage-lpmd.php?success=1&t=' . time());
            exit;
        } else {
            header('Location: manage-lpmd.php?error=' . urlencode('Error SQL: ' . mysqli_error($conn)) . '&edit=' . $id_edit);
            exit;
        }
    } else {
        // INSERT
        $sql = "INSERT INTO lpmd (nama, jabatan, foto, urutan) VALUES ('$nama_esc','$jabatan_esc','$foto_esc',$urutan)";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: manage-lpmd.php?success=1&t=' . time());
            exit;
        } else {
            header('Location: manage-lpmd.php?error=' . urlencode('Error SQL: ' . mysqli_error($conn)));
            exit;
        }
    }
}

$list = mysqli_query($conn, "SELECT * FROM lpmd ORDER BY urutan ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola LPMD</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f8fafc; }
.sidebar { width:260px; background:#0f172a; min-height:100vh; position:fixed; color:#e2e8f0; padding:24px 18px; display: flex; flex-direction: column; }
.sidebar a { color:#cbd5e1; text-decoration:none; display:block; padding:10px 12px; border-radius:10px; margin-bottom:6px; }
.sidebar a.active, .sidebar a:hover { background:#0ea5e9; color:#fff; }
.content { margin-left:280px; padding:28px; }
</style>
</head>
<body>
<div class="sidebar">
    <h4 class="fw-bold mb-3 text-white">Admin Desa</h4>
    <div style="flex: 1; overflow-y: auto;">
        <a href="index.php"><i class="bi bi-house-door me-2"></i>Dashboard</a>
        <a href="manage-struktur.php"><i class="bi bi-people me-2"></i>Perangkat Desa</a>
        <a href="manage-bpd.php" style="padding-left: 30px; font-size: 0.9em;"><i class="bi bi-dot me-2"></i>BPD</a>
        <a href="manage-lpmd.php" class="active" style="padding-left: 30px; font-size: 0.9em;"><i class="bi bi-dot me-2"></i>LPMD</a>
        <a href="manage-rt.php" style="padding-left: 30px; font-size: 0.9em;"><i class="bi bi-dot me-2"></i>RT</a>
        <a href="manage-berita.php"><i class="bi bi-journal-text me-2"></i>Kelola Berita</a>
        <a href="manage-apbdesa.php"><i class="bi bi-cash-stack me-2"></i>APB Desa</a>
        <a href="manage-potensi.php"><i class="bi bi-map me-2"></i>Potensi Desa</a>
        <a href="manage-sarana.php"><i class="bi bi-building me-2"></i>Sarana & Prasarana</a>
        <a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill me-2"></i>Pengaduan</a>
        <a href="manage-kontak.php"><i class="bi bi-telephone me-2"></i>Kontak</a>
    </div>
    <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid #334155;">
        <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Kelola LPMD</h3>
            <p class="text-muted mb-0">Tambah/edit/hapus anggota LPMD (urutan menentukan tampilan).</p>
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
                        <?php if ($edit): ?><a href="manage-lpmd.php" class="btn btn-outline-secondary ms-2">Batal</a><?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Daftar LPMD</h5>
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
                                                <a class="btn btn-sm btn-outline-primary" href="manage-lpmd.php?edit=<?php echo $row['id']; ?>">Edit</a>
                                                <a class="btn btn-sm btn-outline-danger" href="manage-lpmd.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
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
