/* =========================================================
   RESET & SETUP DATABASE DESA NATAR 2
   ========================================================= */

-- 1. BUAT DATABASE & PILIH
CREATE DATABASE IF NOT EXISTS db_natar2;
USE db_natar2;

-- 2. DROP SEMUA TABEL (AMAN UNTUK RESET)
DROP TABLE IF EXISTS unduhan;
DROP TABLE IF EXISTS kategori_unduhan;
DROP TABLE IF EXISTS potensi_desa;
DROP TABLE IF EXISTS kategori_potensi;
DROP TABLE IF EXISTS galeri;
DROP TABLE IF EXISTS apb_desa;
DROP TABLE IF EXISTS potensi_visual;
DROP TABLE IF EXISTS layanan;
DROP TABLE IF EXISTS perangkat_desa;
DROP TABLE IF EXISTS berita;
DROP TABLE IF EXISTS kategori_berita;
DROP TABLE IF EXISTS kontak;
DROP TABLE IF EXISTS profil;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS site_settings;

-- =========================================================
-- 3. MASTER SETTING WEBSITE
-- =========================================================
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul_website VARCHAR(255) DEFAULT 'Desa Natar',
    tagline VARCHAR(255),
    logo VARCHAR(255),
    favicon VARCHAR(255)
);

-- =========================================================
-- 4. USERS (ADMIN)
-- =========================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100)
);

-- =========================================================
-- 5. PROFIL DESA
-- =========================================================
CREATE TABLE profil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sejarah TEXT,
    visi TEXT,
    misi TEXT,
    populasi INT DEFAULT 0,
    luas_wilayah VARCHAR(50),
    batas_utara VARCHAR(100),
    batas_selatan VARCHAR(100),
    batas_timur VARCHAR(100),
    batas_barat VARCHAR(100),
    peta_lokasi_iframe TEXT
);

-- =========================================================
-- 6. BERITA
-- =========================================================
CREATE TABLE kategori_berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
);

CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT,
    judul VARCHAR(255) NOT NULL,
    isi_berita TEXT NOT NULL,
    gambar VARCHAR(255),
    tgl_posting TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_berita_kategori
        FOREIGN KEY (id_kategori)
        REFERENCES kategori_berita(id)
        ON DELETE SET NULL
);

-- =========================================================
-- 7. PERANGKAT DESA
-- =========================================================
CREATE TABLE perangkat_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    foto VARCHAR(255),
    urutan INT DEFAULT 0
);

-- =========================================================
-- 8. LAYANAN
-- =========================================================
CREATE TABLE layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(255) NOT NULL,
    persyaratan TEXT,
    prosedur TEXT,
    biaya VARCHAR(100) DEFAULT 'Gratis',
    estimasi_waktu VARCHAR(100)
);

-- =========================================================
-- 9. POTENSI VISUAL
-- =========================================================
CREATE TABLE potensi_visual (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul_peta VARCHAR(255) NOT NULL,
    gambar_peta VARCHAR(255) NOT NULL,
    keterangan TEXT,
    sumber ENUM('Arsitektur','Pertanian') NOT NULL
);

-- =========================================================
-- 10. APB DESA
-- =========================================================
CREATE TABLE apb_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun YEAR NOT NULL,
    jenis ENUM('Pendapatan','Belanja','Pembiayaan') NOT NULL,
    rincian VARCHAR(255),
    anggaran BIGINT NOT NULL,
    realisasi BIGINT NOT NULL
);

-- =========================================================
-- 11. GALERI
-- =========================================================
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul_foto VARCHAR(255),
    file_gambar VARCHAR(255) NOT NULL,
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- 12. POTENSI DESA
-- =========================================================
CREATE TABLE kategori_potensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE potensi_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_potensi VARCHAR(255) NOT NULL,
    id_kategori INT,
    deskripsi TEXT,
    gambar VARCHAR(255),
    lokasi_maps TEXT,
    tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_potensi_kategori
        FOREIGN KEY (id_kategori)
        REFERENCES kategori_potensi(id)
        ON DELETE SET NULL
);

-- =========================================================
-- 13. UNDUHAN
-- =========================================================
CREATE TABLE kategori_unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokumen VARCHAR(255) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    id_kategori INT,
    tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_unduhan_kategori
        FOREIGN KEY (id_kategori)
        REFERENCES kategori_unduhan(id)
        ON DELETE SET NULL
);

-- =========================================================
-- 14. KONTAK DESA (SINGLE ROW)
-- =========================================================
CREATE TABLE kontak (
    id INT PRIMARY KEY,
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    whatsapp VARCHAR(20),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    maps_embed TEXT
);

-- =========================================================
-- 15. DATA DEFAULT (DUMMY DATA)
-- =========================================================

-- A. PENGATURAN SITUS
INSERT INTO site_settings (id, judul_website, tagline)
VALUES (1, 'Desa Natar', 'Website Resmi Pemerintah Desa')
ON DUPLICATE KEY UPDATE judul_website = VALUES(judul_website);

-- B. PROFIL DESA
INSERT INTO profil (id, sejarah, visi, misi, populasi, luas_wilayah)
VALUES (
    1,
    'Desa Natar adalah salah satu desa di Kecamatan Natar, Kabupaten Lampung Selatan. Desa ini terbentuk pada tahun 1950 oleh para transmigran. Seiring berjalannya waktu, Desa Natar berkembang menjadi pusat perekonomian lokal dengan pasar desa yang aktif.\n\nPada tahun 2000, desa ini mengalami pemekaran wilayah namun tetap mempertahankan nilai-nilai gotong royong dan kearifan lokal.',
    'Terwujudnya Desa Natar yang Maju, Mandiri, dan Sejahtera Berlandaskan Iman dan Taqwa.',
    '1. Meningkatkan kualitas pelayanan publik.\n2. Membangun infrastruktur desa yang merata.\n3. Mengembangkan ekonomi kerakyatan berbasis potensi lokal.\n4. Meningkatkan kualitas sumber daya manusia.',
    3540,
    '120 Hektar'
)
ON DUPLICATE KEY UPDATE
    sejarah = VALUES(sejarah),
    visi = VALUES(visi),
    misi = VALUES(misi),
    populasi = VALUES(populasi),
    luas_wilayah = VALUES(luas_wilayah);

-- C. KONTAK
INSERT INTO kontak (id, alamat, telepon, email, whatsapp, facebook, instagram, maps_embed)
VALUES (
    1,
    'Jl. Raya Natar No. 1, Natar, Lampung Selatan',
    '08123456789',
    'admin@desanatar.id',
    '628123456789',
    'https://facebook.com/desanatar',
    'https://instagram.com/desanatar',
    '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.403786278965!2d105.1977783147651!3d-5.355056996115384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c56789abcdef%3A0x123456789abcdef!2sKantor%20Desa%20Natar!5e0!3m2!1sid!2sid!4v1625000000000!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
)
ON DUPLICATE KEY UPDATE
    alamat = VALUES(alamat),
    telepon = VALUES(telepon),
    email = VALUES(email);

-- D. USER ADMIN (Password: 'password')
INSERT INTO users (username, password, nama_lengkap)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Desa')
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- E. KATEGORI BERITA
INSERT INTO kategori_berita (nama_kategori) VALUES 
('Pemerintahan'), ('Pembangunan'), ('Sosial'), ('Ekonomi');

-- F. BERITA
INSERT INTO berita (id_kategori, judul, isi_berita, gambar, tgl_posting) VALUES 
(1, 'Musyawarah Desa Pembahasan RKPDes Tahun 2026', '<p>Pemerintah Desa Natar menggelar Musyawarah Desa (Musdes) dalam rangka penyusunan Rencana Kerja Pemerintah Desa (RKPDes) Tahun Anggaran 2026. Acara ini dihadiri oleh seluruh perangkat desa, BPD, tokoh masyarakat, dan perwakilan pemuda.</p><p>Dalam sambutannya, Kepala Desa menekankan pentingnya partisipasi masyarakat dalam perencanaan pembangunan agar tepat sasaran dan sesuai dengan kebutuhan warga.</p>', 'musdes.jpg', '2025-10-15 09:00:00'),
(3, 'Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa', '<p>Sebanyak 50 Keluarga Penerima Manfaat (KPM) di Desa Natar telah menerima Bantuan Langsung Tunai (BLT) Dana Desa tahap akhir tahun ini. Penyaluran dilakukan di Balai Desa dengan tetap mematuhi protokol kesehatan.</p>', 'blt.jpg', '2025-11-02 10:30:00'),
(3, 'Gotong Royong Bersih Desa Menyambut HUT RI', '<p>Warga Desa Natar antusias mengikuti kegiatan gotong royong membersihkan lingkungan desa. Kegiatan ini meliputi pembersihan selokan, pemangkasan rumput liar, dan pengecatan gapura desa.</p>', 'gotongroyong.jpg', '2025-08-14 07:00:00'),
(4, 'Pelatihan Digitalisasi UMKM Desa', '<p>Untuk meningkatkan daya saing produk lokal, Pemerintah Desa bekerjasama dengan mahasiswa KKN mengadakan pelatihan pemasaran digital bagi pelaku UMKM. Peserta diajarkan cara membuat foto produk yang menarik dan mendaftar di marketplace.</p>', 'umkm.jpg', '2025-09-20 13:00:00');

-- G. PERANGKAT DESA
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan) VALUES 
('Budi Santoso', 'Kepala Desa', 'kades.jpg', 1),
('Siti Aminah', 'Sekretaris Desa', 'sekdes.jpg', 2),
('Ahmad Hidayat', 'Kasi Pemerintahan', 'kasi_pem.jpg', 3),
('Rina Wati', 'Kaur Keuangan', 'kaur_keu.jpg', 4),
('Joko Susilo', 'Kepala Dusun I', 'kadus1.jpg', 5),
('Dewi Sartika', 'Kasi Kesejahteraan', 'kasi_kesra.jpg', 6);

-- H. LAYANAN PUBLIK
INSERT INTO layanan (nama_layanan, persyaratan, prosedur, biaya, estimasi_waktu) VALUES 
('Surat Keterangan Domisili', '<ul><li>Surat Pengantar RT/RW</li><li>Fotocopy KTP & KK</li><li>Pas Foto 3x4 (1 lembar)</li></ul>', '<ol><li>Pemohon meminta surat pengantar dari RT/RW.</li><li>Datang ke Kantor Desa membawa berkas lengkap.</li><li>Petugas memverifikasi data.</li><li>Surat dicetak dan ditandatangani Kepala Desa.</li></ol>', 'Gratis', '15 Menit'),
('Surat Pengantar SKCK', '<ul><li>Surat Pengantar RT/RW</li><li>Fotocopy KTP & KK</li><li>Fotocopy Akta Kelahiran</li></ul>', '<ol><li>Serahkan berkas ke loket pelayanan.</li><li>Petugas membuatkan surat pengantar ke Polsek.</li></ol>', 'Gratis', '10 Menit'),
('Surat Keterangan Usaha (SKU)', '<ul><li>Pengantar RT/RW</li><li>KTP & KK</li><li>Bukti Lunas PBB Tahun Terakhir</li></ul>', '<ol><li>Lapor ke Kepala Dusun untuk verifikasi usaha.</li><li>Bawa berkas ke Balai Desa.</li><li>Penerbitan SKU.</li></ol>', 'Gratis', '1 Hari Kerja');

-- I. KATEGORI POTENSI
INSERT INTO kategori_potensi (nama_kategori) VALUES ('Pertanian'), ('Pariwisata'), ('Kerajinan');

-- J. POTENSI DESA
INSERT INTO potensi_desa (nama_potensi, id_kategori, deskripsi, gambar, lokasi_maps) VALUES 
('Lahan Persawahan Produktif', 1, '<p>Desa Natar memiliki hamparan sawah seluas 50 hektar yang mampu panen 3 kali setahun dengan sistem irigasi teknis yang baik. Menjadi lumbung padi bagi kecamatan.</p>', 'sawah.jpg', ''),
('Kerajinan Tapis Lampung', 3, '<p>Kelompok wanita tani desa mengembangkan kerajinan kain Tapis khas Lampung dengan motif modern yang diminati pasar luar daerah.</p>', 'tapis.jpg', ''),
('Embung Desa Wisata', 2, '<p>Embung desa yang berfungsi sebagai pengairan sekaligus tempat rekreasi memancing bagi warga sore hari.</p>', 'embung.jpg', '');

-- K. KATEGORI UNDUHAN
INSERT INTO kategori_unduhan (nama_kategori) VALUES ('Peraturan Desa'), ('Formulir'), ('Laporan');

-- L. UNDUHAN
INSERT INTO unduhan (nama_dokumen, nama_file, id_kategori, tgl_upload) VALUES 
('Perdes No 1 Tahun 2025 tentang APBDes', 'perdes_apbdes_2025.pdf', 1, NOW()),
('Formulir Permohonan KTP', 'form_ktp.pdf', 2, NOW()),
('Laporan Realisasi Anggaran 2024', 'lra_2024.pdf', 3, NOW());

-- M. APB DESA
INSERT INTO apb_desa (tahun, jenis, rincian, anggaran, realisasi) VALUES 
(2026, 'Pendapatan', 'Dana Desa (DD)', 850000000, 425000000),
(2026, 'Pendapatan', 'Alokasi Dana Desa (ADD)', 350000000, 175000000),
(2026, 'Pendapatan', 'Pendapatan Asli Desa (PADes)', 50000000, 20000000),
(2026, 'Belanja', 'Bidang Penyelenggaraan Pemerintahan', 300000000, 150000000),
(2026, 'Belanja', 'Bidang Pembangunan Desa', 500000000, 200000000),
(2026, 'Belanja', 'Bidang Pembinaan Kemasyarakatan', 100000000, 40000000),
(2026, 'Belanja', 'Bidang Pemberdayaan Masyarakat', 150000000, 50000000);
