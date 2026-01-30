<?php
require 'auth_check.php';
require '../config/db.php';

$success = ""; $error = "";

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "✅ Data berhasil disimpan!";
}

// Delete
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM sarana_prasarana WHERE id={$id}");
    header('Location: manage-sarana.php'); exit;
}

// Edit fetch
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM sarana_prasarana WHERE id={$id} LIMIT 1");
    if ($res && mysqli_num_rows($res)) { $edit = mysqli_fetch_assoc($res); }
}

// Save
if (isset($_POST['simpan'])) {
    $id_edit = (int)($_POST['id'] ?? 0);
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $kategori = mysqli_real_escape_string($conn, trim($_POST['kategori']));
    $jumlah = (int)($_POST['jumlah'] ?? 0);
    $kondisi = mysqli_real_escape_string($conn, trim($_POST['kondisi']));
    $keterangan = mysqli_real_escape_string($conn, trim($_POST['keterangan']));
    $urutan = (int)($_POST['urutan'] ?? 0);

    if ($id_edit > 0) {
        $sql = "UPDATE sarana_prasarana SET nama='$nama', kategori='$kategori', jumlah=$jumlah, kondisi='$kondisi', keterangan='$keterangan', urutan=$urutan WHERE id=$id_edit";
    } else {
        $sql = "INSERT INTO sarana_prasarana (nama, kategori, jumlah, kondisi, keterangan, urutan) VALUES ('$nama','$kategori',$jumlah,'$kondisi','$keterangan',$urutan)";
    }
    
    if (mysqli_query($conn, $sql)) {
        header('Location: manage-sarana.php?success=1'); exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$list = mysqli_query($conn, "SELECT * FROM sarana_prasarana ORDER BY urutan ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sarana & Prasarana - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <?php include 'admin-styles.php'; ?>
    
    <style>
        .page-header h3 { font-weight: 700; color: #1e293b; font-size: 1.75rem; }
        .page-header p { color: #64748b; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-geo-alt-fill"></i>
        <span>Desa Natar</span>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
        <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
        <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>
        <li><a href="manage-sarana.php" class="active"><i class="bi bi-building"></i> Sarana & Prasarana</a></li>
        <li><a href="manage-pengaduan.php"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="mb-2">Sarana dan Prasarana</h3>
                <p class="text-muted mb-0">Tambah, edit, atau hapus data sarana dan prasarana desa</p>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><?php echo $edit ? 'Edit Data' : 'Tambah Data'; ?></h5>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Sarana/Prasarana</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $edit['nama'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <?php
                                $kategoris = ['Pemerintahan', 'Pendidikan', 'Kesehatan', 'Keagamaan', 'Olahraga', 'Keamanan', 'Lainnya'];
                                foreach ($kategoris as $kat):
                                ?>
                                    <option value="<?= $kat ?>" <?= (isset($edit['kategori']) && $edit['kategori']===$kat)?'selected':'' ?>><?= $kat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="<?php echo $edit['jumlah'] ?? 1; ?>" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="Baik" <?= (isset($edit['kondisi']) && $edit['kondisi']==='Baik')?'selected':'' ?>>Baik</option>
                                <option value="Rusak Ringan" <?= (isset($edit['kondisi']) && $edit['kondisi']==='Rusak Ringan')?'selected':'' ?>>Rusak Ringan</option>
                                <option value="Rusak Berat" <?= (isset($edit['kondisi']) && $edit['kondisi']==='Rusak Berat')?'selected':'' ?>>Rusak Berat</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"><?php echo $edit['keterangan'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Urutan Tampilan</label>
                            <input type="number" name="urutan" class="form-control" value="<?php echo $edit['urutan'] ?? 0; ?>">
                            <small class="text-muted">Angka lebih kecil akan tampil lebih dulu</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Tambah Data
                            </button>
                            <?php if ($edit): ?>
                                <a href="manage-sarana.php" class="btn btn-outline-secondary">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Daftar Sarana & Prasarana</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">No</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">Nama</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">Kategori</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">Jumlah</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">Kondisi</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;">Urutan</th>
                                    <th style="padding: 14px; font-weight: 600; color: #475569;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($row = mysqli_fetch_assoc($list)): 
                                ?>
                                    <tr>
                                        <td style="padding: 14px;"><?= $no++ ?></td>
                                        <td style="padding: 14px; font-weight: 500;"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td style="padding: 14px;">
                                            <span class="badge rounded-pill" style="background: #64748b; padding: 6px 12px;"><?= $row['kategori'] ?></span>
                                        </td>
                                        <td style="padding: 14px;"><?= $row['jumlah'] ?></td>
                                        <td style="padding: 14px;">
                                            <?php
                                            $badge = [
                                                'Baik' => 'background: #10b981;',
                                                'Rusak Ringan' => 'background: #f59e0b;',
                                                'Rusak Berat' => 'background: #ef4444;'
                                            ];
                                            ?>
                                            <span class="badge rounded-pill" style="<?= $badge[$row['kondisi']] ?? 'background: #64748b;' ?> padding: 6px 12px;"><?= $row['kondisi'] ?></span>
                                        </td>
                                        <td style="padding: 14px;"><?= $row['urutan'] ?></td>
                                        <td style="padding: 14px;" class="text-center">
                                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm" style="background: #f59e0b; color: white; padding: 6px 12px;" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button onclick="if(confirm('Yakin hapus?')) window.location.href='?hapus=<?= $row['id'] ?>'" class="btn btn-sm" style="background: #ef4444; color: white; padding: 6px 12px;" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
