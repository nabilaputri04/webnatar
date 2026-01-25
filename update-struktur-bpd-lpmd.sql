-- =========================================================
-- UPDATE DATABASE: TAMBAH TABEL BPD, LPMD, dan RT
-- =========================================================

USE db_natar2;

-- =========================================================
-- 1. TABEL BPD (Badan Permusyawaratan Desa)
-- =========================================================
CREATE TABLE IF NOT EXISTS bpd (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan ENUM('Ketua BPD', 'Wakil Ketua', 'Sekretaris', 'Anggota') NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- 2. TABEL LPMD (Lembaga Pemberdayaan Masyarakat Desa)
-- =========================================================
CREATE TABLE IF NOT EXISTS lpmd (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan ENUM('Ketua LPMD', 'Wakil Ketua', 'Sekretaris', 'Anggota') NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- 3. TABEL RT (Rukun Tetangga)
-- =========================================================
CREATE TABLE IF NOT EXISTS rt (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_rt VARCHAR(10) NOT NULL,
    nama_ketua VARCHAR(100) NOT NULL,
    dusun VARCHAR(10) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    telepon VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- DATA DEFAULT BPD
-- =========================================================
INSERT INTO bpd (nama, jabatan, foto, urutan) VALUES 
('BADRILLAH', 'Ketua BPD', 'bpd-ketua.jpeg', 1),
('BAKRI, S.Kom', 'Wakil Ketua', 'bpd-wakil.jpeg', 2),
('ASRUL, ST', 'Sekretaris', 'bpd-sekretaris.jpeg', 3),
('BANERJAN', 'Anggota', NULL, 4),
('MUHAMMAD TAHIR', 'Anggota', NULL, 5),
('YULIANA', 'Anggota', NULL, 6),
('NURRAHIMAH', 'Anggota', NULL, 7),
('SIDIK SUPRIADI', 'Anggota', NULL, 8),
('YUWOHESHO', 'Anggota', NULL, 9);

-- =========================================================
-- DATA DEFAULT LPMD
-- =========================================================
INSERT INTO lpmd (nama, jabatan, foto, urutan) VALUES 
('ABDULLAH ASRUL SANI', 'Ketua LPMD', 'lpmd-ketua.jpeg', 1),
('TUKIMIN', 'Wakil Ketua', 'lpmd-wakil.jpeg', 2),
('JAROD SUTORO', 'Sekretaris', 'lpmd-sekretaris.jpeg', 3),
('KHOIRUL ANWAR', 'Anggota', NULL, 4);

-- =========================================================
-- DATA DEFAULT RT (DUSUN I - XI)
-- =========================================================

-- DUSUN I
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('001', 'EFFERI EFRIYANSYAH', 'I'),
('002', 'NURMAN', 'I'),
('003', 'ADEK', 'I'),
('004', 'BASRI', 'I'),
('005', 'HARUN JEFRI', 'I');

-- DUSUN II
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('006', 'SUHARYADI', 'II'),
('007', 'FUAD YUSUF', 'II'),
('008-A', 'JUNIARTO', 'II'),
('008-B', 'SUKOCO', 'II'),
('009-A', 'ABDUL RONI', 'II'),
('009-B', 'AGUS MAULANA', 'II');

-- DUSUN III
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('010', 'WAHID HIDAYAT', 'III'),
('011', 'SAPUTRA', 'III'),
('012-A', 'SAPRIJAL', 'III'),
('012-B', 'ENDRA YADI', 'III');

-- DUSUN IV
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('013', 'HERMAN', 'IV'),
('014', 'SUPRIYONO', 'IV'),
('015', 'TULUS RYANTO', 'IV'),
('016-A', 'SUPRIYANTO', 'IV'),
('016-B', 'SUHARYADI', 'IV'),
('017', 'SUKARDI YUSUF', 'IV');

-- DUSUN V
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('018', 'RIYANTO', 'V'),
('019', 'SUGINEN', 'V'),
('020', 'PAIJO', 'V');

-- DUSUN VI
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('021-A', 'SUMAWAN', 'VI'),
('021-B', 'HERIYANDA', 'VI'),
('022', 'PAIJO', 'VI'),
('023', 'RUDI ALFIAN', 'VI');

-- DUSUN VII
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('024', 'M. UNTUNG', 'VII'),
('025', 'DJOKO YUGIANTO', 'VII'),
('026', 'SUWARNO', 'VII'),
('027', 'WALLIYO', 'VII'),
('028', 'SUPRAPTO', 'VII'),
('029', 'KASTARI', 'VII');

-- DUSUN VIII
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('030', 'SUWARNO', 'VIII'),
('031', 'BUYONO', 'VIII'),
('032', 'SUROSO', 'VIII'),
('033', 'SUPRIADI', 'VIII'),
('034', 'AGUS NURYANTO', 'VIII');

-- DUSUN IX
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('035', 'BUROSO', 'IX'),
('036', 'SURIONO', 'IX'),
('037', 'BUROSO', 'IX'),
('038', 'SUPRIYADI', 'IX'),
('039', 'BUROSO', 'IX'),
('040', 'TONI JUNAIDI', 'IX');

-- DUSUN X
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('041', 'SAMSUDIN EFFENDI', 'X'),
('042', 'TRIYONO', 'X'),
('043', 'MARJATO', 'X'),
('044', 'EDI ROHENDRA', 'X'),
('045', 'HARIADI', 'X'),
('046', 'JUNARYO', 'X'),
('047', 'SUKIMAN', 'X'),
('048', 'RUTAN SYAHRUL', 'X');

-- DUSUN XI
INSERT INTO rt (nomor_rt, nama_ketua, dusun) VALUES 
('049', 'PAIJAN', 'XI'),
('050', 'HASAN', 'XI'),
('050-A', 'LAILI KUFRI', 'XI');

-- =========================================================
-- SELESAI
-- =========================================================
