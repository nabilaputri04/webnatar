<?php
require 'config/db.php';

$visi = "BERSAMA MENJADIKAN DESA NATAR YANG LEBIH BAIK KE DEPAN";

$misi = "1. Siap mendukung dan memfasilitasi pemekaran Desa Natar
2. Mempermudah administrasi dan birokrasi
3. Transparansi pengelolaan anggaran Dana Desa
4. Menumbuh kembangkan ekonomi kerakyatan
5. Mengaktifkan dan menggiatkan peran pemuda";

$visi_clean = mysqli_real_escape_string($conn, $visi);
$misi_clean = mysqli_real_escape_string($conn, $misi);

$query = "UPDATE profil SET visi = '$visi_clean', misi = '$misi_clean' WHERE id = 1";

if (mysqli_query($conn, $query)) {
    echo "✅ <strong>Visi dan Misi berhasil diupdate!</strong><br><br>";
    echo "<div style='font-family: Arial; padding: 20px; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 8px;'>";
    echo "<h3 style='color: #10b981; margin-top: 0;'>📋 Visi Desa Natar (2020-2025)</h3>";
    echo "<p style='font-size: 18px; font-style: italic; color: #065f46; font-weight: bold;'>\"$visi\"</p>";
    echo "<br>";
    echo "<h3 style='color: #f97316; margin-top: 20px;'>🎯 Misi Desa Natar</h3>";
    $misi_array = explode("\n", $misi);
    echo "<ol style='line-height: 2; color: #1f2937;'>";
    foreach ($misi_array as $item) {
        $clean_item = trim(preg_replace('/^\d+\.\s*/', '', $item));
        if (!empty($clean_item)) {
            echo "<li><strong>$clean_item</strong></li>";
        }
    }
    echo "</ol>";
    echo "</div>";
    echo "<br><br>";
    echo "🔗 <a href='profil.php' style='color: #10b981; font-weight: bold; text-decoration: none;'>Lihat Profil Desa →</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
