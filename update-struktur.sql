-- Update struktur tabel perangkat_desa untuk bagan organisasi
ALTER TABLE perangkat_desa 
ADD COLUMN level INT DEFAULT 1 AFTER urutan,
ADD COLUMN parent_id INT DEFAULT NULL AFTER level,
ADD COLUMN color_scheme VARCHAR(50) DEFAULT 'emerald' AFTER parent_id;

-- Hapus data lama
DELETE FROM perangkat_desa;

-- Data struktur organisasi (sesuai dengan gambar)
-- Level 1: Kepala Desa
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('M. ARIF, S.Pd.', 'Kepala Desa', 'kepala-desa.jpg', 1, 1, NULL, 'emerald');

-- Level 2: Sekretaris Desa dan 3 Kepala Seksi
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('VIKI REZA PURNAMA', 'Kepala Seksi Pemerintahan', 'kasi-pemerintahan.jpg', 2, 2, 1, 'blue'),
('HERTATI', 'Kepala Seksi Kesejahteraan', 'kasi-kesejahteraan.jpg', 3, 2, 1, 'purple'),
('SUHARTI TI', 'Kepala Seksi Pelayanan', 'kasi-pelayanan.jpg', 4, 2, 1, 'pink'),
('ESI MERAWAN', 'Sekretaris Desa', 'sekretaris.jpg', 5, 2, 1, 'orange');

-- Level 3: Kaur (di bawah Sekretaris)
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('YEKO BAGUS CAHYANI', 'Kaur Umum & Tata Usaha', 'kaur-umum.jpg', 6, 3, 5, 'orange'),
('EKKI REYNALDI', 'Kaur Perencanaan', 'kaur-perencanaan.jpg', 7, 3, 5, 'orange'),
('SUCI RAHAYU', 'Kaur Keuangan', 'kaur-keuangan.jpg', 8, 3, 5, 'orange');

-- Level 4: Staf Teknis (Operator dan Bendahara)
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('DESMIATI ARMAINI', 'Operator', 'operator.jpg', 9, 4, 1, 'gray'),
('ERA NOVIYANTI', 'Bendahara Barang', 'bendahara.jpg', 10, 4, 1, 'gray');

-- Level 5: Kepala Dusun
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('KOSIR', 'Kepala Dusun I', 'kadus1.jpg', 11, 5, 1, 'teal'),
('NURMANSYAH', 'Kepala Dusun II', 'kadus2.jpg', 12, 5, 1, 'blue'),
('SUKARDI', 'Kepala Dusun III', 'kadus3.jpg', 13, 5, 1, 'teal');

-- Level 6: RT (Dusun IV, V, VI, IX, X)
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('TUKMINI', 'Kepala Dusun IV', 'kadus4.jpg', 14, 6, 1, 'blue'),
('NURSYADI', 'Kepala Dusun V', 'kadus5.jpg', 15, 6, 1, 'blue'),
('GEDEN HANDOKO', 'Kepala Dusun VI', 'kadus6.jpg', 16, 6, 1, 'blue');

-- Level 7: RT (Dusun VII, IX, X)
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('EKO SESWANTO', 'Kepala Dusun VII', 'kadus7.jpg', 17, 7, 1, 'purple'),
('KOKO WAHDHO', 'Kepala Dusun IX', 'kadus9.jpg', 18, 7, 1, 'purple'),
('SUMARSONO', 'Kepala Dusun X', 'kadus10.jpg', 19, 7, 1, 'purple');

-- Level 8: RT (Dusun X, XI)
INSERT INTO perangkat_desa (nama, jabatan, foto, urutan, level, parent_id, color_scheme) VALUES
('EDI SUHENORA', 'Kepala Dusun X', 'kadus10-2.jpg', 20, 8, 1, 'orange'),
('SUSITO', 'Kepala Dusun XI', 'kadus11.jpg', 21, 8, 1, 'orange');
