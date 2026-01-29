-- Tabel untuk menyimpan pengaduan masyarakat
CREATE TABLE IF NOT EXISTS pengaduan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(20),
    judul VARCHAR(255) NOT NULL,
    isi_pengaduan TEXT NOT NULL,
    kategori ENUM('Infrastruktur', 'Pelayanan', 'Administrasi', 'Kebersihan', 'Keamanan', 'Lainnya') DEFAULT 'Lainnya',
    status ENUM('Baru', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Baru',
    tanggapan TEXT,
    tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_diupdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
