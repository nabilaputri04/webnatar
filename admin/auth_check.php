<?php
// Start session dengan error handling
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}
?>