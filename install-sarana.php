<?php
require 'config/db.php';

$sql = file_get_contents('create-table-sarana.sql');

if (mysqli_multi_query($conn, $sql)) {
    echo "✅ Tabel sarana_prasarana berhasil dibuat!<br>";
    echo "✅ Sample data berhasil diinput!<br>";
    echo "<br><a href='admin/manage-sarana.php'>Kelola Sarana & Prasarana</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
