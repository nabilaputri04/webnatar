/* =========================================================
   DATABASE DESA NATAR 2 - FULL LATEST (January 30, 2026)

   File ini menggabungkan dan MEMPERBAIKI semua skema + update dari:
   - db_natar2.sql
   - update-struktur.sql
   - update-struktur-bpd-lpmd.sql
   - update-apb-decimal.sql
   - create-table-sarana.sql + update-sarana-data.sql
   - create-pengaduan-table.sql + sample-data-pengaduan.sql
   - fix-sosmed.sql

   Tujuan:
   - Bisa langsung di-import ke MySQL (fresh install) tanpa error urutan.
   - Skema cocok dengan PHP (enum APB, kolom tiktok, struktur perangkat_desa).
   ========================================================= */

-- Pastikan pakai UTF8MB4
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- 2) RESET
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS pengaduan;
DROP TABLE IF EXISTS sarana_prasarana;
DROP TABLE IF EXISTS unduhan;
DROP TABLE IF EXISTS kategori_unduhan;
DROP TABLE IF EXISTS potensi_desa;
DROP TABLE IF EXISTS kategori_potensi;
DROP TABLE IF EXISTS galeri;
DROP TABLE IF EXISTS apb_desa;
DROP TABLE IF EXISTS potensi_visual;
DROP TABLE IF EXISTS layanan;
DROP TABLE IF EXISTS rt;
DROP TABLE IF EXISTS lpmd;
DROP TABLE IF EXISTS bpd;
DROP TABLE IF EXISTS perangkat_desa;
DROP TABLE IF EXISTS berita;
DROP TABLE IF EXISTS kategori_berita;
DROP TABLE IF EXISTS kontak;
DROP TABLE IF EXISTS profil;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS site_settings;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- 3) MASTER SETTING WEBSITE
-- =========================================================
CREATE TABLE site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul_website VARCHAR(255) DEFAULT 'Desa Natar',
  tagline VARCHAR(255),
  logo VARCHAR(255),
  favicon VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 4) USERS (ADMIN)
-- =========================================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 5) PROFIL DESA
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 6) BERITA
-- =========================================================
CREATE TABLE kategori_berita (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE berita (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT,
  judul VARCHAR(255) NOT NULL,
  isi_berita TEXT NOT NULL,
  gambar VARCHAR(255),
  tgl_posting TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_berita_kategori
    FOREIGN KEY (id_kategori) REFERENCES kategori_berita(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 7) PERANGKAT DESA (BAGAN ORGANISASI)
-- =========================================================
CREATE TABLE perangkat_desa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jabatan VARCHAR(100) NOT NULL,
  foto VARCHAR(255),
  urutan INT DEFAULT 0,
  level INT DEFAULT 1,
  parent_id INT DEFAULT NULL,
  color_scheme VARCHAR(50) DEFAULT 'emerald'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 8) LAYANAN
-- =========================================================
CREATE TABLE layanan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_layanan VARCHAR(255) NOT NULL,
  persyaratan TEXT,
  prosedur TEXT,
  biaya VARCHAR(100) DEFAULT 'Gratis',
  estimasi_waktu VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 9) POTENSI VISUAL
-- =========================================================
CREATE TABLE potensi_visual (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul_peta VARCHAR(255) NOT NULL,
  gambar_peta VARCHAR(255) NOT NULL,
  keterangan TEXT,
  sumber ENUM('Arsitektur','Pertanian') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 10) DATA RT PER DUSUN
-- (Disesuaikan dengan pemakaian di admin/manage-rt.php)
-- =========================================================
CREATE TABLE rt (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dusun VARCHAR(10) NOT NULL,
  nomor_rt VARCHAR(10) NOT NULL,
  nama_ketua VARCHAR(100) NOT NULL,
  UNIQUE KEY unik_dusun_rt (dusun, nomor_rt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 11) APB DESA (Support DESIMAL & Enum terbaru)
-- =========================================================
CREATE TABLE apb_desa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tahun YEAR NOT NULL,
  jenis ENUM('Pendapatan','Belanja','Pembiayaan Desa') NOT NULL,
  rincian VARCHAR(255),
  anggaran DECIMAL(15,2) NOT NULL,
  realisasi DECIMAL(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 12) GALERI
-- =========================================================
CREATE TABLE galeri (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul_foto VARCHAR(255),
  file_gambar VARCHAR(255) NOT NULL,
  tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 13) POTENSI DESA
-- =========================================================
CREATE TABLE kategori_potensi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE potensi_desa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_potensi VARCHAR(255) NOT NULL,
  id_kategori INT,
  deskripsi TEXT,
  gambar VARCHAR(255),
  lokasi_maps TEXT,
  tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_potensi_kategori
    FOREIGN KEY (id_kategori) REFERENCES kategori_potensi(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 14) UNDUHAN
-- =========================================================
CREATE TABLE kategori_unduhan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE unduhan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_dokumen VARCHAR(255) NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  id_kategori INT,
  tgl_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_unduhan_kategori
    FOREIGN KEY (id_kategori) REFERENCES kategori_unduhan(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 15) KONTAK DESA (SINGLE ROW)
-- (Sudah termasuk kolom tiktok agar INSERT tidak error)
-- =========================================================
CREATE TABLE kontak (
  id INT PRIMARY KEY,
  alamat TEXT,
  telepon VARCHAR(20),
  email VARCHAR(100),
  whatsapp VARCHAR(20),
  facebook VARCHAR(255),
  instagram VARCHAR(255),
  tiktok VARCHAR(255),
  maps_embed TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 16) SARANA PRASARANA
-- =========================================================
CREATE TABLE sarana_prasarana (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  kategori VARCHAR(100) NOT NULL,
  jumlah INT DEFAULT 0,
  kondisi ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') DEFAULT 'Baik',
  keterangan TEXT,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 17) PENGADUAN MASYARAKAT
-- =========================================================
CREATE TABLE pengaduan (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 18) BPD & LPMD
-- =========================================================
CREATE TABLE bpd (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jabatan ENUM('Ketua BPD', 'Wakil Ketua', 'Sekretaris', 'Anggota') NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lpmd (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jabatan ENUM('Ketua LPMD', 'Wakil Ketua', 'Sekretaris', 'Anggota') NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 19) DATA DEFAULT / SEED
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
INSERT INTO kontak (id, alamat, telepon, email, whatsapp, facebook, instagram, tiktok, maps_embed)
VALUES (
  1,
  'Jl. Raya Natar No. 1, Natar, Lampung Selatan',
  '08123456789',
  'admin@desanatar.id',
  '628123456789',
  'https://www.facebook.com/profile.php?id=61586508316500',
  'https://www.instagram.com/kec_natar',
  'https://www.tiktok.com/@kecamatan.natar',
  '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.403786278965!2d105.1977783147651!3d-5.355056996115384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c56789abcdef%3A0x123456789abcdef!2sKantor%20Desa%20Natar!5e0!3m2!1sid!2sid!4v1625000000000!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
)
ON DUPLICATE KEY UPDATE
  alamat = VALUES(alamat),
  telepon = VALUES(telepon),
  email = VALUES(email),
  whatsapp = VALUES(whatsapp),
  facebook = VALUES(facebook),
  instagram = VALUES(instagram),
  tiktok = VALUES(tiktok),
  maps_embed = VALUES(maps_embed);

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

-- G. LAYANAN PUBLIK
INSERT INTO layanan (nama_layanan, persyaratan, prosedur, biaya, estimasi_waktu) VALUES
('Surat Keterangan Domisili', '<ul><li>Surat Pengantar RT/RW</li><li>Fotocopy KTP & KK</li><li>Pas Foto 3x4 (1 lembar)</li></ul>', '<ol><li>Pemohon meminta surat pengantar dari RT/RW.</li><li>Datang ke Kantor Desa membawa berkas lengkap.</li><li>Petugas memverifikasi data.</li><li>Surat dicetak dan ditandatangani Kepala Desa.</li></ol>', 'Gratis', '15 Menit'),
('Surat Pengantar SKCK', '<ul><li>Surat Pengantar RT/RW</li><li>Fotocopy KTP & KK</li><li>Fotocopy Akta Kelahiran</li></ul>', '<ol><li>Serahkan berkas ke loket pelayanan.</li><li>Petugas membuatkan surat pengantar ke Polsek.</li></ol>', 'Gratis', '10 Menit'),
('Surat Keterangan Usaha (SKU)', '<ul><li>Pengantar RT/RW</li><li>KTP & KK</li><li>Bukti Lunas PBB Tahun Terakhir</li></ul>', '<ol><li>Lapor ke Kepala Dusun untuk verifikasi usaha.</li><li>Bawa berkas ke Balai Desa.</li><li>Penerbitan SKU.</li></ol>', 'Gratis', '1 Hari Kerja');

-- H. KATEGORI POTENSI
INSERT INTO kategori_potensi (nama_kategori) VALUES ('Pertanian'), ('Pariwisata'), ('Kerajinan');

-- I. POTENSI DESA
INSERT INTO potensi_desa (nama_potensi, id_kategori, deskripsi, gambar, lokasi_maps) VALUES
('Lahan Persawahan Produktif', 1, '<p>Desa Natar memiliki hamparan sawah seluas 50 hektar yang mampu panen 3 kali setahun dengan sistem irigasi teknis yang baik. Menjadi lumbung padi bagi kecamatan.</p>', 'sawah.jpg', ''),
('Kerajinan Tapis Lampung', 3, '<p>Kelompok wanita tani desa mengembangkan kerajinan kain Tapis khas Lampung dengan motif modern yang diminati pasar luar daerah.</p>', 'tapis.jpg', ''),
('Embung Desa Wisata', 2, '<p>Embung desa yang berfungsi sebagai pengairan sekaligus tempat rekreasi memancing bagi warga sore hari.</p>', 'embung.jpg', '');

-- J. KATEGORI UNDUHAN
INSERT INTO kategori_unduhan (nama_kategori) VALUES ('Peraturan Desa'), ('Formulir'), ('Laporan');

-- K. UNDUHAN
INSERT INTO unduhan (nama_dokumen, nama_file, id_kategori, tgl_upload) VALUES
('Perdes No 1 Tahun 2025 tentang APBDes', 'perdes_apbdes_2025.pdf', 1, NOW()),
('Formulir Permohonan KTP', 'form_ktp.pdf', 2, NOW()),
('Laporan Realisasi Anggaran 2024', 'lra_2024.pdf', 3, NOW());

-- L. APB DESA
INSERT INTO apb_desa (tahun, jenis, rincian, anggaran, realisasi) VALUES
(2026, 'Pendapatan', 'Dana Desa (DD)', 850000000.00, 425000000.00),
(2026, 'Pendapatan', 'Alokasi Dana Desa (ADD)', 350000000.00, 175000000.00),
(2026, 'Pendapatan', 'Pendapatan Asli Desa (PADes)', 50000000.00, 20000000.00),
(2026, 'Belanja', 'Bidang Penyelenggaraan Pemerintahan', 300000000.00, 150000000.00),
(2026, 'Belanja', 'Bidang Pembangunan Desa', 500000000.00, 200000000.00),
(2026, 'Belanja', 'Bidang Pembinaan Kemasyarakatan', 100000000.00, 40000000.00),
(2026, 'Belanja', 'Bidang Pemberdayaan Masyarakat', 150000000.00, 50000000.00);

-- M. PERANGKAT DESA (DATA TERBARU BAGAN ORGANISASI)
TRUNCATE TABLE perangkat_desa;
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('M. ARIF, S.Pd.', 'Kepala Desa', 'kepala-desa.jpg', 1, 1, NULL, 'emerald'),
('VIKI REZA PURNAMA', 'Kepala Seksi Pemerintahan', 'kasi-pemerintahan.jpg', 2, 2, 1, 'blue'),
('HERTATI', 'Kepala Seksi Kesejahteraan', 'kasi-kesejahteraan.jpg', 3, 2, 1, 'purple'),
('SUHARTI TI', 'Kepala Seksi Pelayanan', 'kasi-pelayanan.jpg', 4, 2, 1, 'pink'),
('ESI MERAWAN', 'Sekretaris Desa', 'sekretaris.jpg', 5, 2, 1, 'orange'),
('YEKO BAGUS CAHYANI', 'Kaur Umum & Tata Usaha', 'kaur-umum.jpg', 6, 3, 5, 'orange'),
('EKKI REYNALDI', 'Kaur Perencanaan', 'kaur-perencanaan.jpg', 7, 3, 5, 'orange'),
('SUCI RAHAYU', 'Kaur Keuangan', 'kaur-keuangan.jpg', 8, 3, 5, 'orange'),
('DESMIATI ARMAINI', 'Operator', 'operator.jpg', 9, 4, 1, 'gray'),
('ERA NOVIYANTI', 'Bendahara Barang', 'bendahara.jpg', 10, 4, 1, 'gray'),
('KOSIR', 'Kepala Dusun I', 'kadus-1.jpeg', 11, 5, 1, 'teal'),
('NURMANSYAH', 'Kepala Dusun II', 'kadus-2.jpeg', 12, 5, 1, 'blue'),
('SUKARDI', 'Kepala Dusun III', 'kadus-3.jpeg', 13, 5, 1, 'teal'),
('TUKMINI', 'Kepala Dusun IV', 'kadus-4.jpeg', 14, 6, 1, 'blue'),
('NURSYADI', 'Kepala Dusun V', 'kadus-5.jpeg', 15, 6, 1, 'blue'),
('DEDEN HANDOKO', 'Kepala Dusun VI', 'kadus-6.jpeg', 16, 6, 1, 'blue'),
('EKO SESWANTO', 'Kepala Dusun VII', 'kadus-7.jpeg', 17, 7, 1, 'purple'),
('KOKO WAHOHO', 'Kepala Dusun VII', 'kadus-8.jpg', 18, 7, 1, 'purple'),
('SUMARSONO', 'Kepala Dusun IX', 'kadus-9.jpeg', 19, 7, 1, 'purple'),
('EDI SUHENORA', 'Kepala Dusun X', 'kadus-10.jpeg', 20, 8, 1, 'orange'),
('SUGITO', 'Kepala Dusun XI', 'kadus-11.jpeg', 21, 8, 1, 'orange');

-- N. RT (DATA TERBARU - DUSUN I s/d XI)
TRUNCATE TABLE rt;
INSERT INTO rt (dusun, nomor_rt, nama_ketua) VALUES
('I', '001', 'EFFERI EFRIYANSYAH'),
('I', '002', 'NURMAN'),
('I', '003', 'ADEK'),
('I', '004', 'BASRI'),
('I', '005', 'HARUN JEFRI'),
('II', '006', 'SUHARYADI'),
('II', '007', 'FUAD YUSUF'),
('II', '008-A', 'JUNIARTO'),
('II', '008-B', 'SUKOCO'),
('II', '009-A', 'ABDUL RONI'),
('II', '009-B', 'AGUS MAULANA'),
('III', '010', 'WAHID HIDAYAT'),
('III', '011', 'SAPUTRA'),
('III', '012-A', 'SAPRIJAL'),
('III', '012-B', 'ENDRA YADI'),
('IV', '013', 'HERMAN'),
('IV', '014', 'SUPRIYONO'),
('IV', '015', 'TULUS RYANTO'),
('IV', '016-A', 'SUPRIYANTO'),
('IV', '016-B', 'SUHARYADI'),
('IV', '017', 'SUKARDI YUSUF'),
('V', '018', 'RIYANTO'),
('V', '019', 'SUGINEN'),
('V', '020', 'PAIJO'),
('VI', '021-A', 'SUMAWAN'),
('VI', '021-B', 'HERIYANDA'),
('VI', '022', 'PAIJO'),
('VI', '023', 'RUDI ALFIAN'),
('VII', '024', 'M. UNTUNG'),
('VII', '025', 'SUNARSO'),
('VII', '026', 'DENDI SETIADI'),
('VII', '027', 'WALUYO'),
('VII', '028', 'RIYADI'),
('VII', '029', 'AGUS SUHARTOKO'),
('VIII', '030', 'DJOKO YUDIANTO'),
('VIII', '031', 'SUPRAPTO'),
('VIII', '032', 'SUGIONO'),
('VIII', '033', 'KASYADI'),
('VIII', '034', 'AGUS NURYANTO'),
('IX', '035', 'SUMARNO'),
('IX', '036', 'SUYONO'),
('IX', '037', 'SUROSO'),
('IX', '038', 'SUPRIYADI'),
('IX', '039', 'SURONO'),
('IX', '040', 'TONI JUNAIDI'),
('X', '041', 'SYAMSUDIN EFFENDI'),
('X', '042', 'TRIONO'),
('X', '043', 'FATAHHILAH'),
('X', '044', 'IDA ROBAINI'),
('X', '045', 'ANTONI'),
('X', '046', 'PALUPI'),
('X', '047', 'SUTAN SYAHRIL'),
('X', '048', 'JOKO SUTANTO'),
('XI', '049', 'INDAH PRIHATIN'),
('XI', '050', 'MUHARTONO'),
('XI', '050-A', 'JUNTORO');

-- O. BPD
INSERT INTO bpd (nama, jabatan, foto, urutan) VALUES
('BADRILLAH', 'Ketua BPD', 'bpd-ketua.jpeg', 1),
('BAKRI, S.Kom', 'Wakil Ketua', 'bpd-wakil.jpeg', 2),
('ASRUDI, ST', 'Sekretaris', 'bpd-sekretaris.jpeg', 3),
('BAHERAN', 'Anggota', NULL, 4),
('MUHAMMAD YAMIN', 'Anggota', NULL, 5),
('YULIANA', 'Anggota', NULL, 6),
('NURHAMIDAH', 'Anggota', NULL, 7),
('SIDIK SUPRIADI', 'Anggota', NULL, 8),
('YUDHESMO', 'Anggota', NULL, 9);

-- P. LPMD
INSERT INTO lpmd (nama, jabatan, foto, urutan) VALUES
('ABDULLAH ASRUL SANI', 'Ketua LPMD', 'lpmd-ketua.jpeg', 1),
('TUKIMIN', 'Wakil Ketua', 'lpmd-wakil.jpeg', 2),
('JAROD SUTORO', 'Sekretaris', 'lpmd-sekretaris.jpeg', 3),
('KHOIRUL ANWAR', 'Anggota', NULL, 4);

-- Q. SARANA PRASARANA (DATA TERBARU)
TRUNCATE TABLE sarana_prasarana;

-- Data Kesehatan
INSERT INTO sarana_prasarana (nama, kategori, jumlah, kondisi, keterangan, urutan) VALUES
('Posyandu', 'Kesehatan', 3, 'Baik', 'Diumah Kader', 1),
('Gedung Posyandu Permanen', 'Kesehatan', 8, 'Baik', '', 2),
('Puskesmas', 'Kesehatan', 1, 'Baik', '', 3),
('Poliklinik/balai pengobatan', 'Kesehatan', 4, 'Baik', '', 4),
('Perawat Kesehatan', 'Kesehatan', 7, 'Baik', 'Aktif', 6),
('Dokter', 'Kesehatan', 1, 'Baik', 'Aktif', 7),
('Mantri Kesehatan', 'Kesehatan', 7, 'Baik', 'Aktif', 8);

-- Data Pendidikan
INSERT INTO sarana_prasarana (nama, kategori, jumlah, kondisi, keterangan, urutan) VALUES
('PAUD Bunda Pertiwi', 'Pendidikan', 1, 'Baik', 'Dusun VII (Sukajawa Rejo I)', 1),
('PAUD Flamboyan', 'Pendidikan', 1, 'Baik', 'Dusun X (Natar I)', 2),
('PAUD Anggrek', 'Pendidikan', 1, 'Baik', 'Dusun XI (Sukarame Pasar)', 3),
('TK Al-Munawaroh', 'Pendidikan', 1, 'Baik', 'Dusun VII (Sukarame)', 4),
('TK Seyang Ibu', 'Pendidikan', 1, 'Baik', 'Dusun IV (Sari Rejo)', 5),
('TK Bina Asih', 'Pendidikan', 1, 'Baik', 'Dusun IX (Tanjung Rejo II)', 6),
('TK Tunas Mulya', 'Pendidikan', 1, 'Baik', 'Dusun X (Natar I)', 7),
('SD N 1 Natar', 'Pendidikan', 1, 'Baik', 'Dusun IV (Sari Rejo)', 8),
('SD N 2 Natar', 'Pendidikan', 1, 'Baik', 'Dusun VII (Sukarame)', 9),
('SD N 3 Natar', 'Pendidikan', 1, 'Baik', 'Dusun V (Marga Jaya)', 10),
('SD N 4 Natar', 'Pendidikan', 1, 'Baik', 'Dusun VIII (Tanjung Rejo I)', 11),
('SLTP Muhammadiyah Natar', 'Pendidikan', 1, 'Baik', 'Dusun IV (Sari Rejo)', 12),
('SLTP Budi Karya Natar', 'Pendidikan', 1, 'Baik', 'Dusun VIII (Tanjung Rejo I)', 13),
('SMA N 1 Natar', 'Pendidikan', 1, 'Baik', 'Dusun I (Natar I)', 14),
('SMK Budi Karya Natar', 'Pendidikan', 1, 'Baik', 'Dusun VIII (Tanjung Rejo I)', 15);

-- R. PENGADUAN (SAMPLE DATA)
INSERT INTO pengaduan (nama, email, telepon, judul, isi_pengaduan, kategori, status, tanggapan) VALUES
('Budi Santoso', 'budi@email.com', '081234567890', 'Jalan Rusak di RT 03', 'Jalan di depan rumah saya di RT 03 sudah rusak parah dan berlubang. Mohon segera diperbaiki karena sangat berbahaya terutama saat musim hujan.', 'Infrastruktur', 'Baru', NULL),
('Siti Nurhaliza', 'siti@email.com', '082345678901', 'Lampu Jalan Mati', 'Lampu jalan di Jalan Raya Natar sudah mati sejak 3 hari yang lalu. Mohon diperbaiki karena jalan menjadi gelap di malam hari.', 'Infrastruktur', 'Diproses', 'Tim kami sudah turun untuk mengecek dan akan segera diperbaiki dalam 2 hari.'),
('Ahmad Fadli', 'ahmad@email.com', '083456789012', 'Pelayanan Administrasi Lambat', 'Saya mengurus KTP sudah 2 minggu tapi belum jadi. Mohon prosesnya bisa dipercepat.', 'Administrasi', 'Selesai', 'KTP sudah selesai dan bisa diambil di kantor desa. Terima kasih atas kesabarannya.'),
('Dewi Lestari', NULL, '084567890123', 'Sampah Menumpuk di TPS', 'Tempat pembuangan sampah di dekat pasar sudah penuh dan sampah berserakan. Tolong segera diangkut.', 'Kebersihan', 'Diproses', 'Sudah dikonfirmasi ke dinas kebersihan untuk pengangkutan besok pagi.'),
('Andi Wijaya', 'andi@email.com', NULL, 'Pencurian di Malam Hari', 'Terjadi beberapa kasus pencurian di lingkungan kami. Mohon keamanan ditingkatkan.', 'Keamanan', 'Diproses', 'Koordinasi dengan kepolisian sudah dilakukan. Ronda malam akan diintensifkan.');

-- S. SAFETY UPDATE SOSMED (opsional)
UPDATE kontak SET
  facebook = 'https://www.facebook.com/profile.php?id=61586508316500',
  instagram = 'https://www.instagram.com/kec_natar',
  tiktok = 'https://www.tiktok.com/@kecamatan.natar'
WHERE id = 1;

-- =========================================================
-- SELESAI
-- =========================================================
