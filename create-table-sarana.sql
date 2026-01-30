CREATE TABLE IF NOT EXISTS sarana_prasarana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    jumlah INT DEFAULT 0,
    kondisi ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') DEFAULT 'Baik',
    keterangan TEXT,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data default
INSERT INTO sarana_prasarana (nama, kategori, jumlah, kondisi, keterangan, urutan) VALUES
('Balai Desa', 'Pemerintahan', 1, 'Baik', 'Kantor pemerintahan desa', 1),
('Kantor Kepala Desa', 'Pemerintahan', 1, 'Baik', 'Ruang kantor kepala desa', 2),
('Pos Keamanan', 'Keamanan', 2, 'Baik', 'Pos untuk keamanan lingkungan', 3),
('Masjid', 'Keagamaan', 3, 'Baik', 'Tempat ibadah umat Islam', 4),
('Musholla', 'Keagamaan', 5, 'Baik', 'Tempat ibadah kecil', 5),
('SD Negeri', 'Pendidikan', 2, 'Baik', 'Sekolah Dasar Negeri', 6),
('PAUD', 'Pendidikan', 1, 'Baik', 'Pendidikan Anak Usia Dini', 7),
('Puskesmas', 'Kesehatan', 1, 'Baik', 'Pusat Kesehatan Masyarakat', 8),
('Posyandu', 'Kesehatan', 4, 'Baik', 'Pos Pelayanan Terpadu', 9),
('Lapangan Olahraga', 'Olahraga', 1, 'Baik', 'Lapangan untuk kegiatan olahraga', 10);
