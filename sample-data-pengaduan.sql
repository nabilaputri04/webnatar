-- Data sample untuk testing fitur pengaduan
-- Jalankan query ini SETELAH membuat tabel pengaduan

INSERT INTO pengaduan (nama, email, telepon, judul, isi_pengaduan, kategori, status, tanggapan) VALUES
('Budi Santoso', 'budi@email.com', '081234567890', 'Jalan Rusak di RT 03', 'Jalan di depan rumah saya di RT 03 sudah rusak parah dan berlubang. Mohon segera diperbaiki karena sangat berbahaya terutama saat musim hujan.', 'Infrastruktur', 'Baru', NULL),

('Siti Nurhaliza', 'siti@email.com', '082345678901', 'Lampu Jalan Mati', 'Lampu jalan di Jalan Raya Natar sudah mati sejak 3 hari yang lalu. Mohon diperbaiki karena jalan menjadi gelap di malam hari.', 'Infrastruktur', 'Diproses', 'Tim kami sudah turun untuk mengecek dan akan segera diperbaiki dalam 2 hari.'),

('Ahmad Fadli', 'ahmad@email.com', '083456789012', 'Pelayanan Administrasi Lambat', 'Saya mengurus KTP sudah 2 minggu tapi belum jadi. Mohon prosesnya bisa dipercepat.', 'Administrasi', 'Selesai', 'KTP sudah selesai dan bisa diambil di kantor desa. Terima kasih atas kesabarannya.'),

('Dewi Lestari', NULL, '084567890123', 'Sampah Menumpuk di TPS', 'Tempat pembuangan sampah di dekat pasar sudah penuh dan sampah berserakan. Tolong segera diangkut.', 'Kebersihan', 'Diproses', 'Sudah dikonfirmasi ke dinas kebersihan untuk pengangkutan besok pagi.'),

('Andi Wijaya', 'andi@email.com', NULL, 'Pencurian di Malam Hari', 'Terjadi beberapa kasus pencurian di lingkungan kami. Mohon keamanan ditingkatkan.', 'Keamanan', 'Diproses', 'Koordinasi dengan kepolisian sudah dilakukan. Ronda malam akan diintensifkan.');
