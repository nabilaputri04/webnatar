<?php
require 'auth_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $tanggapan = mysqli_real_escape_string($conn, $_POST['tanggapan']);
    
    $update_query = "UPDATE pengaduan SET status='$status', tanggapan='$tanggapan' WHERE id='$id'";
    
    if (mysqli_query($conn, $update_query)) {
        echo json_encode([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
            'data' => [
                'id' => $id,
                'status' => $status,
                'tanggapan' => $tanggapan
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal memperbarui status: ' . mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
