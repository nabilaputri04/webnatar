<?php
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi_pengaduan = mysqli_real_escape_string($conn, $_POST['isi_pengaduan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    
    $query = "INSERT INTO pengaduan (nama, email, telepon, judul, isi_pengaduan, kategori) 
              VALUES ('$nama', '$email', '$telepon', '$judul', '$isi_pengaduan', '$kategori')";
    
    if (mysqli_query($conn, $query)) {
        $pengaduan_id = mysqli_insert_id($conn);
        header("Location: kontak.php?status=success&id=" . $pengaduan_id);
        exit;
    } else {
        header("Location: kontak.php?status=error");
        exit;
    }
}
?>
