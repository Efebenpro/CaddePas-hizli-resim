<?php
// DÜZELTME: Bir üst dizine çıkıp (../) core/db.php'yi dahil et
include_once '../core/db.php'; 

// session_start() core/db.php'nin içinde olduğu için burada gerekmez.

if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true) {
    redirect('index.php'); // Zaten giriş yapılmışsa ana panele yönlendir
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    // ADMIN_PASSWORD core/db.php'den geliyor
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['admin_loggedin'] = true;
        redirect('index.php');
    } else {
        $error_message = 'Hatalı şifre.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= SITE_TITLE ?> | Yönetici Girişi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #343a40; }
        .login-box { 
            width: 350px; 
            margin: 100px auto; 
            padding: 30px; 
            background: #fff; 
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center mb-4 text-primary">Admin Girişi</h3>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
    </div>
</body>
</html>