<?php
// admin/index.php
include_once '../core/db.php'; 

// Oturum kontrolü
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    redirect('login.php');
}

$message = '';
$message_type = '';

// Resim Silme İşlemi
if (isset($_GET['delete_id'])) {
    $delete_id = filter_var($_GET['delete_id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // 1. Dosya adını çek
        $stmt = $db->prepare("SELECT file_name FROM images WHERE id = ?");
        $stmt->execute([$delete_id]);
        $image = $stmt->fetch();

        if ($image) {
            $filePath = '../public/resimler/' . $image['file_name'];
            
            // 2. Diskten sil
            if (file_exists($filePath) && unlink($filePath)) {
                // 3. Veritabanından sil 
                $db->prepare("DELETE FROM images WHERE id = ?")->execute([$delete_id]);
                $message = "success: Resim başarıyla silindi.";
            } else {
                // Dosya diskte yoksa sadece DB kaydını sil
                $db->prepare("DELETE FROM images WHERE id = ?")->execute([$delete_id]);
                $message = "warning: Dosya diskte bulunamadı, ancak veritabanı kaydı silindi.";
            }
        } else {
            $message = "danger: Silinecek resim bulunamadı.";
        }
    } catch (PDOException $e) {
        $message = "danger: Silme işleminde hata: " . $e->getMessage();
    }
    // Silme işleminden sonra GET parametrelerini temizlemek için yönlendir
    redirect('index.php?msg=' . urlencode($message));
}

// Mesajı URL'den al
if (isset($_GET['msg'])) {
    list($type, $text) = explode(':', urldecode($_GET['msg']), 2);
    $message_type = $type;
    $message = $text;
}

// İstatistikler ve Tüm Resimleri Listeleme
try {
    $images = $db->query("SELECT * FROM images ORDER BY upload_date DESC")->fetchAll();
    $total_images = count($images);
} catch (PDOException $e) {
    $images = [];
    $total_images = 0;
    $message_type = 'danger';
    $message = 'Veritabanı hatası: Resimler yüklenemedi.';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= SITE_TITLE ?> | Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #f0f0f0; }
        .sidebar { background-color: #2c2c2c; min-height: 100vh; padding-top: 20px; }
        .sidebar a { color: #f0f0f0; }
        .content { padding: 30px; }
        .card { background-color: #1e1e1e; color: #f0f0f0; border: 1px solid #333; }
        .card-title { color: #00bcd4; }
        .table { --bs-table-bg: #1e1e1e; --bs-table-color: #f0f0f0; }
        .table-striped>tbody>tr:nth-of-type(odd)>* { background-color: #2c2c2c; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3">
        <h4 class="mb-4 text-info">Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link active btn btn-info text-white" href="index.php">🖼️ Resim Yönetimi</a>
            </li>
             <li class="nav-item mb-2">
                <a class="nav-link btn btn-outline-light text-white" href="settings.php">⚙️ Site Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-outline-danger mt-3" href="logout.php">Çıkış Yap</a>
            </li>
        </ul>
    </div>

    <div class="content flex-grow-1">
        <h1 class="mb-4 text-white">Resim Yönetimi</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Toplam Yüklenen Resim</h5>
                        <p class="card-text display-4"><?= $total_images ?></p>
                    </div>
                </div>
            </div>
            </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type == 'success' ? 'success' : ($message_type == 'warning' ? 'warning' : 'danger') ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ön İzleme</th>
                        <th>Dosya Adı</th>
                        <th>Yüklenme Tarihi</th>
                        <th>IP Adresi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $img): ?>
                        <tr>
                            <td><?= $img['id'] ?></td>
                            <td>
                                <a href="../public/resimler/<?= $img['file_name'] ?>" target="_blank">
                                    <img src="../public/resimler/<?= $img['file_name'] ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                </a>
                            </td>
                            <td><?= $img['file_name'] ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($img['upload_date'])) ?></td>
                            <td><?= $img['ip_address'] ?></td>
                            <td>
                                <a href="?delete_id=<?= $img['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bu resmi silmek istediğinizden emin misiniz? Geri alınamaz!')">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>