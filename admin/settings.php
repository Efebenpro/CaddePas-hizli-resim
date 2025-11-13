<?php
// admin/settings.php
include_once '../core/db.php'; 

// Oturum kontrolü
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    redirect('login.php');
}

$message = '';
$message_type = '';

// Ayarları Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    $new_title = trim($_POST['site_title']);
    
    if (!empty($new_title)) {
        try {
            // Sadece site_title anahtarını güncelle
            $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key = 'site_title'");
            $stmt->execute([$new_title]);
            
            $message = 'Site başlığı başarıyla güncellendi. Yeni başlık: ' . htmlspecialchars($new_title);
            $message_type = 'success';
            
            // Site başlığını anında güncellemek için kendimizi yönlendiriyoruz.
            redirect('settings.php?msg=' . urlencode("success:$message"));

        } catch (PDOException $e) {
            $message = 'Güncelleme hatası: ' . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = 'Site başlığı boş bırakılamaz.';
        $message_type = 'warning';
    }
}

// Mesajı URL'den al
if (isset($_GET['msg'])) {
    list($type, $text) = explode(':', urldecode($_GET['msg']), 2);
    $message_type = $type;
    $message = $text;
}

// Güncel site başlığını çek
$stmt = $db->query("SELECT value FROM settings WHERE key = 'site_title'");
$current_title = $stmt->fetch()['value'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli | Ayarlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { background-color: #2c2c2c; min-height: 100vh; padding-top: 20px; }
        .sidebar a { color: #f0f0f0; }
        .content { padding: 30px; }
        .card { background-color: #1e1e1e; color: #f0f0f0; border: 1px solid #333; }
        .card-header { background-color: #2c2c2c; border-bottom: 1px solid #333; }
        .form-control { background-color: #383838; color: #f0f0f0; border: 1px solid #555; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3">
        <h4 class="mb-4 text-info">Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link btn btn-outline-light text-white" href="index.php">🖼️ Resim Yönetimi</a>
            </li>
             <li class="nav-item mb-2">
                <a class="nav-link active btn btn-info text-white" href="settings.php">⚙️ Site Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-outline-danger mt-3" href="logout.php">Çıkış Yap</a>
            </li>
        </ul>
    </div>

    <div class="content flex-grow-1">
        <h1 class="mb-4 text-white">⚙️ Site Ayarları</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header">
                Site Başlığını Değiştir
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="site_title" class="form-label">Site Başlığı</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" 
                               value="<?= htmlspecialchars($current_title) ?>" required>
                    </div>
                    <button type="submit" name="update_settings" class="btn btn-success">Ayarları Kaydet</button>
                </form>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>