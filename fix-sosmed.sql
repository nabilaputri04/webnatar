-- Tambahkan kolom tiktok jika belum ada
ALTER TABLE kontak ADD COLUMN IF NOT EXISTS tiktok VARCHAR(255) AFTER instagram;

-- Update link sosial media
UPDATE kontak SET 
    facebook = 'https://www.facebook.com/profile.php?id=61586508316500',
    instagram = 'https://www.instagram.com/kec_natar',
    tiktok = 'https://www.tiktok.com/@kecamatan.natar'
WHERE id = 1;
