<?php
session_start();
require 'config/db.php';

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: manage-profil.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['status'] = "login";
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['nama_admin'] = $row['nama_lengkap'];
            header("Location: manage-profil.php");
            exit;
        }
    }
    $error = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Natar 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-control { padding: 12px; border-radius: 10px; }
        .btn-primary { padding: 12px; border-radius: 10px; font-weight: 600; }
    </style>
</head>
<body>

<div class="card login-card p-4 p-md-5">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary">Admin Panel</h4>
        <p class="text-muted small">Silakan login untuk mengelola website</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small text-center rounded-3 mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">USERNAME</label>
            <input type="text" name="username" class="form-control bg-light border-0" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">PASSWORD</label>
            <input type="password" name="password" class="form-control bg-light border-0" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 shadow-sm">
            Masuk Dashboard
        </button>
    </form>
    
    <div class="text-center mt-4">
        <small class="text-muted">&copy; <?php echo date('Y'); ?> Desa Natar 2</small>
    </div>
</div>

</body>
</html>