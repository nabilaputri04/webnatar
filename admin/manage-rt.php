<?php
require 'auth_check.php';
require '../config/db.php';

$success = ""; $error = "";
$dusun_options = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI'];

// Delete
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM rt WHERE id={$id}");
    header('Location: manage-rt.php'); exit;
}

// Fetch row for edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM rt WHERE id={$id} LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    }
}

// Add / Update
if (isset($_POST['simpan_rt'])) {
    $dusun = mysqli_real_escape_string($conn, $_POST['dusun']);
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor_rt']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_ketua']);
    $id_edit = isset($_POST['id_rt']) ? (int)$_POST['id_rt'] : 0;

    if (!in_array($dusun, $dusun_options, true) || $nomor === '' || $nama === '') {
        $error = "Lengkapi dusun, nomor RT, dan nama.";
    } else {
        if ($id_edit > 0) {
            $sql = "UPDATE rt SET dusun='{$dusun}', nomor_rt='{$nomor}', nama_ketua='{$nama}' WHERE id={$id_edit}";
            if (mysqli_query($conn, $sql)) { $success = "Data RT diperbarui."; }
            else { $error = "Gagal menyimpan: " . mysqli_error($conn); }
        } else {
            $sql = "INSERT INTO rt (dusun, nomor_rt, nama_ketua) VALUES ('{$dusun}', '{$nomor}', '{$nama}')";
            if (mysqli_query($conn, $sql)) { $success = "RT baru ditambahkan."; }
            else { $error = "Gagal menambah: " . mysqli_error($conn); }
        }
    }
    header('Location: manage-rt.php'); exit;
}

// Fetch list grouped by dusun
$data_rt = [];
$res = mysqli_query($conn, "SELECT * FROM rt ORDER BY FIELD(dusun,'I','II','III','IV','V','VI','VII','VIII','IX','X','XI'), nomor_rt ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data_rt[$row['dusun']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola RT per Dusun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php include 'admin-styles.php'; ?>
</head>
<body>
    <nav class="sidebar">
        <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="bi bi-house-door"></i> <span>Dashboard</span></a></li>
            <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> <span>Profil Desa</span></a></li>
            <li><a href="manage-struktur.php"><i class="bi bi-people"></i> <span>Perangkat Desa</span></a></li>
            <li><a href="manage-bpd.php"><i class="bi bi-people-fill"></i> <span>BPD</span></a></li>
            <li><a href="manage-lpmd.php"><i class="bi bi-diagram-3"></i> <span>LPMD</span></a></li>
            <li><a href="manage-rt.php" class="active"><i class="bi bi-house"></i> <span>RT</span></a></li>
            <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> <span>Kelola Berita</span></a></li>
            <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> <span>APB Desa</span></a></li>
            <li><a href="manage-potensi.php"><i class="bi bi-map"></i> <span>Potensi Desa</span></a></li>
            <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> <span>Kontak</span></a></li>
        </ul>
        <div class="logout-section">
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Keluar</span></a>
        </div>
    </nav>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Kelola Nama Ketua RT</h3>
                <p class="text-muted mb-0">Per dusun, bisa tambah/edit/hapus.</p>
            </div>
            <a class="btn btn-outline-secondary" href="struktur-organisasi.php" target="_blank">Lihat Halaman Publik</a>
        </div>

        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><?php echo $edit_data ? 'Edit RT' : 'Tambah RT'; ?></h5>
                        <form method="post">
                            <input type="hidden" name="id_rt" value="<?php echo $edit_data['id'] ?? ''; ?>">
                            <div class="mb-3">
                                <label class="form-label">Dusun</label>
                                <select name="dusun" class="form-select" required>
                                    <option value="">Pilih Dusun</option>
                                    <?php foreach ($dusun_options as $opt): ?>
                                        <option value="<?php echo $opt; ?>" <?php echo (isset($edit_data['dusun']) && $edit_data['dusun']===$opt)?'selected':''; ?>>Dusun <?php echo $opt; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor RT</label>
                                <input type="text" name="nomor_rt" class="form-control" placeholder="cth: 001 atau 012-A" value="<?php echo $edit_data['nomor_rt'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Ketua RT</label>
                                <input type="text" name="nama_ketua" class="form-control" value="<?php echo $edit_data['nama_ketua'] ?? ''; ?>" required>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="simpan_rt" class="btn btn-primary">Simpan</button>
                                <?php if ($edit_data): ?><a href="manage-rt.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Daftar RT per Dusun</h5>
                        <?php if (empty($data_rt)): ?>
                            <p class="text-muted">Belum ada data RT.</p>
                        <?php else: ?>
                            <?php foreach ($dusun_options as $d): ?>
                                <?php if (empty($data_rt[$d])) continue; ?>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Dusun <?php echo $d; ?></h6>
                                        <span class="badge bg-light text-dark"><?php echo count($data_rt[$d]); ?> RT</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:15%">RT</th>
                                                    <th>Nama Ketua</th>
                                                    <th style="width:120px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data_rt[$d] as $row): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($row['nomor_rt']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($row['nama_ketua']); ?></td>
                                                        <td>
                                                            <a class="btn btn-sm btn-outline-primary" href="manage-rt.php?edit=<?php echo $row['id']; ?>">Edit</a>
                                                            <a class="btn btn-sm btn-outline-danger" href="manage-rt.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus data RT ini?');">Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
