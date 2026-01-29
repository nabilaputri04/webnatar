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
        header("Location: kontak.php?status=success");
        exit;
    } else {
        header("Location: kontak.php?status=error");
        exit;
    }
}
?>
