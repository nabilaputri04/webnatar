<?php
require 'auth_check.php';
require '../config/db.php';

// Handle status update
if (isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $tanggapan = mysqli_real_escape_string($conn, $_POST['tanggapan']);
    
    $update_query = "UPDATE pengaduan SET status='$status', tanggapan='$tanggapan' WHERE id='$id'";
    mysqli_query($conn, $update_query);
    header("Location: manage-pengaduan.php?msg=updated");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM pengaduan WHERE id='$id'");
    header("Location: manage-pengaduan.php?msg=deleted");
    exit;
}

// Fetch all pengaduan dengan error handling
$query = "SELECT * FROM pengaduan ORDER BY tanggal_dibuat DESC";
$result = mysqli_query($conn, $query);

// Check if table exists, if not show error
if (!$result) {
    $table_error = "Tabel pengaduan belum dibuat. Silakan jalankan file SQL: create-pengaduan-table.sql";
    $total_pengaduan = 0;
    $baru = 0;
    $diproses = 0;
    $selesai = 0;
} else {
    // Statistics
    $total_pengaduan = mysqli_num_rows($result);
    
    $q_baru = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaduan WHERE status='Baru'");
    $baru = $q_baru ? mysqli_fetch_assoc($q_baru)['count'] : 0;
    
    $q_diproses = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaduan WHERE status='Diproses'");
    $diproses = $q_diproses ? mysqli_fetch_assoc($q_diproses)['count'] : 0;
    
    $q_selesai = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaduan WHERE status='Selesai'");
    $selesai = $q_selesai ? mysqli_fetch_assoc($q_selesai)['count'] : 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengaduan - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'admin-styles.php'; ?>
    <style>
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
        .main-content { margin-left: 280px; padding: 40px; }
        
        /* Fix modal z-index and backdrop */
        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1060 !important;
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand"><i class="bi bi-geo-alt-fill"></i> Desa Natar</div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
        <li><a href="manage-profil.php"><i class="bi bi-house-door"></i> Profil Desa</a></li>
        <li><a href="manage-struktur.php"><i class="bi bi-people"></i> Perangkat Desa</a></li>
        <li><a href="manage-berita.php"><i class="bi bi-journal-text"></i> Kelola Berita</a></li>
        <li><a href="manage-apbdesa.php"><i class="bi bi-cash-stack"></i> APB Desa</a></li>
        <li><a href="manage-potensi.php"><i class="bi bi-map"></i> Potensi Desa</a></li>
        <li><a href="manage-sarana.php"><i class="bi bi-building"></i> Sarana & Prasarana</a></li>
        <li><a href="manage-pengaduan.php" class="active"><i class="bi bi-megaphone-fill"></i> Pengaduan</a></li>
        <li><a href="manage-kontak.php"><i class="bi bi-telephone"></i> Kontak</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </div>
</nav>

<main class="main-content">
    <!-- Alert Messages -->
    <?php if(isset($table_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error Database:</strong> <?= $table_error ?>
            <hr>
            <p class="mb-1"><strong>Cara Mengatasi:</strong></p>
            <ol class="mb-0 ps-3">
                <li>Buka phpMyAdmin</li>
                <li>Pilih database: <code>db_natar2</code></li>
                <li>Klik tab "SQL"</li>
                <li>Jalankan file: <code>create-pengaduan-table.sql</code></li>
            </ol>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>Status pengaduan berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-trash-fill me-2"></i>Pengaduan berhasil dihapus!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 fw-bold"><i class="bi bi-megaphone-fill text-primary me-2"></i>Kelola Pengaduan</h2>
            <p class="text-muted mb-0">Pantau dan tanggapi pengaduan masyarakat</p>
        </div>
        <a href="../kontak.php" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-box-arrow-up-right me-1"></i>Form Pengaduan
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Pengaduan</p>
                            <h3 class="mb-0"><?= $total_pengaduan ?></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-inbox-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pengaduan Baru</p>
                            <h3 class="mb-0"><?= $baru ?></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-exclamation-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Dalam Proses</p>
                            <h3 class="mb-0"><?= $diproses ?></h3>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Selesai</p>
                            <h3 class="mb-0"><?= $selesai ?></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan List -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Daftar Pengaduan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Nama</th>
                            <th width="10%">Kategori</th>
                            <th width="23%">Judul</th>
                            <th width="10%">Status</th>
                            <th width="15%">Kontak</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <small><?= date('d/m/Y H:i', strtotime($row['tanggal_dibuat'])) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['nama']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $row['kategori'] ?></span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($row['judul']) ?>">
                                            <?= htmlspecialchars($row['judul']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'Baru' => 'bg-warning text-dark',
                                            'Diproses' => 'bg-info',
                                            'Selesai' => 'bg-success',
                                            'Ditolak' => 'bg-danger'
                                        ];
                                        ?>
                                        <span class="badge <?= $badge_class[$row['status']] ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($row['telepon']): ?>
                                            <small><i class="bi bi-telephone me-1"></i><?= $row['telepon'] ?></small><br>
                                        <?php endif; ?>
                                        <?php if($row['email']): ?>
                                            <small><i class="bi bi-envelope me-1"></i><?= substr($row['email'], 0, 20) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="if(confirm('Yakin ingin menghapus?')) window.location.href='manage-pengaduan.php?delete=<?= $row['id'] ?>'">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada pengaduan masuk
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Details - Moved Outside Table -->
    <?php 
    if ($result && mysqli_num_rows($result) > 0) {
        // Reset result pointer
        mysqli_data_seek($result, 0);
        while($row = mysqli_fetch_assoc($result)): 
    ?>
    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $row['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel<?= $row['id'] ?>">
                        <i class="bi bi-file-text me-2"></i>Detail Pengaduan #<?= $row['id'] ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="manage-pengaduan.php">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama:</strong><br>
                                <?= htmlspecialchars($row['nama']) ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong><br>
                                <?= date('d F Y, H:i', strtotime($row['tanggal_dibuat'])) ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Email:</strong><br>
                                <?= $row['email'] ?: '-' ?>
                            </div>
                            <div class="col-md-6">
                                <strong>Telepon:</strong><br>
                                <?= $row['telepon'] ?: '-' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Kategori:</strong>
                            <span class="badge bg-secondary ms-2"><?= $row['kategori'] ?></span>
                        </div>

                        <div class="mb-3">
                            <strong>Judul:</strong><br>
                            <div class="alert alert-light mb-0 mt-2">
                                <?= htmlspecialchars($row['judul']) ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Isi Pengaduan:</strong><br>
                            <div class="alert alert-light mb-0 mt-2">
                                <?= nl2br(htmlspecialchars($row['isi_pengaduan'])) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Pengaduan</label>
                            <select name="status" class="form-select" required>
                                <option value="Baru" <?= $row['status'] == 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Diproses" <?= $row['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="Ditolak" <?= $row['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggapan/Keterangan</label>
                            <textarea name="tanggapan" class="form-control" rows="4" placeholder="Berikan tanggapan atau keterangan terkait pengaduan ini..."><?= htmlspecialchars($row['tanggapan']) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="update_status" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        endwhile; 
    } // End if result check
    ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
