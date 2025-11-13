<?php
session_start();
include_once 'core/db.php'; 

// ***************************************************************
// RESİM KAYDETME SORUNUNU GİDEREN GÜVENLİ KOD BLOĞU BAŞLANGICI
// ***************************************************************

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['resim'])) {
    $_SESSION['message'] = "Geçersiz istek.";
    $_SESSION['message_type'] = 'danger';
    redirect('index.php');
}

$uploadedFile = $_FILES['resim'];

if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['message'] = "Dosya yüklenirken hata oluştu.";
    $_SESSION['message_type'] = 'danger';
    redirect('index.php');
}

// 1. Boyut Kontrolü (5MB Max)
$maxFileSize = 5 * 1024 * 1024; 
if ($uploadedFile['size'] > $maxFileSize) {
    $_SESSION['message'] = "Dosya boyutu 5MB'ı aşıyor.";
    $_SESSION['message_type'] = 'warning';
    redirect('index.php');
}

// 2. Tip Kontrolü (Sadece Resim)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $uploadedFile['tmp_name']);
finfo_close($finfo);

$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($mime_type, $allowedMimeTypes)) {
    $_SESSION['message'] = "Geçersiz dosya tipi ({$mime_type}). Sadece resim yüklenebilir.";
    $_SESSION['message_type'] = 'danger';
    redirect('index.php');
}

$extensionMap = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
];
$extension = $extensionMap[$mime_type];

// 3. Benzersiz Dosya Adı Oluşturma
$uniqueFileName = uniqid() . '.' . $extension;
$uploadPath = 'public/resimler/' . $uniqueFileName;

// 4. Dosyayı Kalıcı Konuma TAŞI (Hata düzeltildi!)
if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
    
    // 5. Veritabanına Kayıt
    try {
        $stmt = $db->prepare("INSERT INTO images (file_name, ip_address) VALUES (?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt->execute([$uniqueFileName, $ip]);

        // Bağlantıyı oluştur
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $imageLink = $protocol . $_SERVER['HTTP_HOST'] . "/public/resimler/" . $uniqueFileName;
        
        // Yönlendirme için linki oturuma kaydet
        $_SESSION['message'] = "Yükleme başarılı!";
        $_SESSION['message_type'] = 'success';
        $_SESSION['image_link'] = $imageLink;
        
    } catch (PDOException $e) {
        unlink($uploadPath); // DB kaydı başarısız olursa dosyayı geri sil
        $_SESSION['message'] = "Veritabanı hatası oluştu.";
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION['message'] = "Dosya sunucuya taşınamadı. İzinleri kontrol edin!";
    $_SESSION['message_type'] = 'danger';
}

redirect('index.php');
?>