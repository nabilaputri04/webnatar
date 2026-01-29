-- Update struktur tabel apb_desa untuk mendukung desimal
-- Mengubah kolom anggaran dan realisasi dari BIGINT ke DECIMAL(15,2)

ALTER TABLE apb_desa 
MODIFY COLUMN anggaran DECIMAL(15,2) NOT NULL,
MODIFY COLUMN realisasi DECIMAL(15,2) NOT NULL;

-- Update juga enum untuk menambahkan 'Pembiayaan Desa' jika belum ada
ALTER TABLE apb_desa 
MODIFY COLUMN jenis ENUM('Pendapatan','Belanja','Pembiayaan Desa') NOT NULL;
