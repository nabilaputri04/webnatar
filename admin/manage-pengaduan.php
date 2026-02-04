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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Kelola Pengaduan - Desa Natar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'admin-styles.php'; ?>
    <style>
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
            z-index: 10001 !important;
            pointer-events: none !important;
        }
        .modal-backdrop {
            z-index: 10000 !important;
            pointer-events: none !important;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-dialog {
            z-index: 10002 !important;
            position: relative;
            pointer-events: auto !important;
        }
        .modal-content {
            z-index: 10003 !important;
            pointer-events: auto !important;
            background: white;
        }
        .modal-body,
        .modal-body *,
        .modal-footer,
        .modal-footer *,
        .modal-header,
        .modal-header * {
            pointer-events: auto !important;
        }
        .form-control,
        .form-select,
        textarea,
        button,
        .btn {
            pointer-events: auto !important;
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
                                        <small class="realtime-date" data-timestamp="<?= strtotime($row['tanggal_dibuat']) ?>">
                                            <?= date('d/m/Y H:i', strtotime($row['tanggal_dibuat'])) ?>
                                        </small>
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
    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $row['id'] ?>" aria-hidden="true" data-bs-backdrop="true">
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
                                <span class="realtime-date" data-timestamp="<?= strtotime($row['tanggal_dibuat']) ?>">
                                    <?= date('d F Y, H:i', strtotime($row['tanggal_dibuat'])) ?>
                                </span>
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
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge status-badge-display 
                                    <?php
                                    $badge_class = [
                                        'Baru' => 'bg-warning text-dark',
                                        'Diproses' => 'bg-info',
                                        'Selesai' => 'bg-success',
                                        'Ditolak' => 'bg-danger'
                                    ];
                                    echo $badge_class[$row['status']];
                                    ?>" 
                                    style="font-size: 1rem; padding: 0.5rem 1rem;">
                                    <?= $row['status'] ?>
                                </span>
                            </div>
                            
                            <select name="status" class="form-select mt-3 status-select" data-current-status="<?= $row['status'] ?>" required>
                                <option value="Baru" <?= $row['status'] == 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Diproses" <?= $row['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="Ditolak" <?= $row['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                            
                            <button type="button" class="btn btn-primary mt-2 w-100 update-status-btn" data-id="<?= $row['id'] ?>">
                                <i class="bi bi-check-circle me-1"></i>Update Status
                            </button>
                            
                            <div class="alert alert-success mt-2 d-none status-update-success">
                                <i class="bi bi-check-circle-fill me-1"></i>Status berhasil diperbarui!
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggapan/Keterangan</label>
                            <textarea name="tanggapan" class="form-control tanggapan-textarea" rows="4" placeholder="Berikan tanggapan atau keterangan terkait pengaduan ini..."><?= htmlspecialchars($row['tanggapan'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aggressive backdrop cleanup - remove any existing backdrops on page load
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
    
    // Fix modal backdrop issue - ensure proper cleanup on every modal event
    document.querySelectorAll('.modal').forEach(modal => {
        // Before modal shows
        modal.addEventListener('show.bs.modal', function () {
            // Clean up any existing backdrops first
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        });
        
        // After modal is shown
        modal.addEventListener('shown.bs.modal', function () {
            // Ensure only one backdrop exists
            const backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length > 1) {
                for (let i = 1; i < backdrops.length; i++) {
                    backdrops[i].remove();
                }
            }
        });
        
        // When modal is hidden
        modal.addEventListener('hidden.bs.modal', function () {
            // Remove all backdrops
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
            // Clean up body classes and styles
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
        
        // On hide event (before hidden)
        modal.addEventListener('hide.bs.modal', function () {
            // Start cleanup process
            setTimeout(() => {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
            }, 100);
        });
    });

    // Realtime Date Update
    function updateRealtimeDates() {
        const dateElements = document.querySelectorAll('.realtime-date');
        const now = new Date();
        
        dateElements.forEach(element => {
            const timestamp = parseInt(element.getAttribute('data-timestamp')) * 1000;
            const date = new Date(timestamp);
            
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            let displayText = '';
            const isTableCell = element.closest('td') !== null;
            
            if (diffMins < 1) {
                displayText = 'Baru saja';
            } else if (diffMins < 60) {
                displayText = diffMins + (isTableCell ? ' mnt lalu' : ' menit yang lalu');
            } else if (diffHours < 24) {
                displayText = diffHours + (isTableCell ? ' jam lalu' : ' jam yang lalu');
            } else if (diffDays < 7) {
                displayText = diffDays + (isTableCell ? ' hari lalu' : ' hari yang lalu');
            } else {
                // Format normal untuk tanggal lebih dari 7 hari
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 
                               'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                const monthsFull = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const day = date.getDate();
                const month = isTableCell ? months[date.getMonth()] : monthsFull[date.getMonth()];
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                
                if (isTableCell) {
                    displayText = `${day}/${month}/${year} ${hours}:${minutes}`;
                } else {
                    displayText = `${day} ${month} ${year}, ${hours}:${minutes}`;
                }
            }
            
            element.textContent = displayText;
        });
    }
    
    // Update setiap 30 detik
    updateRealtimeDates();
    setInterval(updateRealtimeDates, 30000);
    
    // Handle Status Update with AJAX
    const updateButtons = document.querySelectorAll('.update-status-btn');
    
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const pengaduanId = this.getAttribute('data-id');
            const modal = this.closest('.modal-content');
            const statusSelect = modal.querySelector('.status-select');
            const tanggapanTextarea = modal.querySelector('.tanggapan-textarea');
            const statusBadge = modal.querySelector('.status-badge-display');
            const successAlert = modal.querySelector('.status-update-success');
            
            const newStatus = statusSelect.value;
            const tanggapan = tanggapanTextarea.value;
            
            // Disable button saat proses
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memperbarui...';
            
            // Kirim AJAX request
            const formData = new FormData();
            formData.append('id', pengaduanId);
            formData.append('status', newStatus);
            formData.append('tanggapan', tanggapan);
            
            fetch('update-pengaduan-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge status
                    const badgeClasses = {
                        'Baru': 'bg-warning text-dark',
                        'Diproses': 'bg-info',
                        'Selesai': 'bg-success',
                        'Ditolak': 'bg-danger'
                    };
                    
                    statusBadge.className = 'badge status-badge-display ' + badgeClasses[newStatus];
                    statusBadge.textContent = newStatus;
                    
                    // Tampilkan pesan sukses
                    successAlert.classList.remove('d-none');
                    setTimeout(() => {
                        successAlert.classList.add('d-none');
                    }, 3000);
                    
                    // Update status di tabel utama
                    const tableRow = document.querySelector(`button[data-bs-target="#detailModal${pengaduanId}"]`).closest('tr');
                    const statusCell = tableRow.querySelector('td:nth-child(6)');
                    statusCell.innerHTML = `<span class="badge ${badgeClasses[newStatus]}">${newStatus}</span>`;
                    
                    // Update statistik
                    updateStatistics();
                    
                } else {
                    alert('Gagal memperbarui status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            })
            .finally(() => {
                // Enable button kembali
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle me-1"></i>Update Status';
            });
        });
    });
    
    // Update statistics
    function updateStatistics() {
        // Hitung ulang statistik dari tabel
        const rows = document.querySelectorAll('tbody tr');
        let baru = 0, diproses = 0, selesai = 0;
        
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(6)');
            if (statusCell) {
                const statusText = statusCell.textContent.trim();
                if (statusText === 'Baru') baru++;
                else if (statusText === 'Diproses') diproses++;
                else if (statusText === 'Selesai') selesai++;
            }
        });
        
        // Update cards
        document.querySelector('.col-md-3:nth-child(2) h3').textContent = baru;
        document.querySelector('.col-md-3:nth-child(3) h3').textContent = diproses;
        document.querySelector('.col-md-3:nth-child(4) h3').textContent = selesai;
    }
    
    // Additional global cleanup - run every 500ms to catch any stray backdrops
    setInterval(() => {
        const openModals = document.querySelectorAll('.modal.show');
        if (openModals.length === 0) {
            // No modals are open, remove all backdrops
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }
    }, 500);
});
</script>
</body>
</html>
