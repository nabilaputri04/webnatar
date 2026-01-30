-- Hapus data lama
TRUNCATE TABLE sarana_prasarana;

-- Data Kesehatan
INSERT INTO sarana_prasarana (nama, kategori, jumlah, kondisi, keterangan, urutan) VALUES
('Posyandu', 'Kesehatan', 3, 'Baik', 'Diumah Kader', 1),
('Gedung Posyandu Permanen', 'Kesehatan', 8, 'Baik', '', 2),
('Puskesmas', 'Kesehatan', 1, 'Baik', '', 3),
('Poliklinik/balai pengobatan', 'Kesehatan', 4, 'Baik', '', 4),
('Bidan desa', 'Kesehatan', 2, 'Baik', 'Aktif', 5),
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
