<?php
// admin/upload_image.php
if (isset($_FILES['upload']['name'])) {
    $file = $_FILES['upload']['tmp_name'];
    $file_name = $_FILES['upload']['name'];
    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validasi format
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowed)) {
        echo json_encode(["error" => ["message" => "Format file tidak didukung"]]);
        exit;
    }

    $new_name = uniqid() . '.' . $extension;
    // Lokasi penyimpanan (pastikan folder assets/img/berita/ sudah ada)
    $location = '../assets/img/berita/' . $new_name;
    
    if (move_uploaded_file($file, $location)) {
        // Balasan JSON yang dibutuhkan CKEditor agar gambar tampil
        echo json_encode([
            "uploaded" => 1,
            "fileName" => $new_name,
            "url" => "../assets/img/berita/" . $new_name
        ]);
    } else {
        echo json_encode(["error" => ["message" => "Gagal mengunggah ke server"]]);
    }
}