<?php
// index.php
include_once 'core/db.php'; 

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
$image_link = isset($_SESSION['image_link']) ? $_SESSION['image_link'] : '';

unset($_SESSION['message'], $_SESSION['message_type'], $_SESSION['image_link']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_TITLE ?> | Hızlı Yükleme</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Genel Tema: Koyu Arka Plan, Beyaz/Neon Yazı */
        body { 
            background-color: #0d1117; /* GitHub Dark Mode Arka Planı */
            color: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
        }
        .container { 
            background-color: #161b22; /* Koyu İç Kutu */
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 188, 212, 0.15); /* Hafif Neon Mavi Gölgelendirme */
            margin-top: 70px;
            border: 1px solid #30363d;
        }
        
        /* Başlıklar */
        .display-3 {
            color: #00bcd4; /* Neon Mavi Başlık */
            text-shadow: 0 0 5px rgba(0, 188, 212, 0.7);
            font-weight: 700;
        }
        .lead {
            color: #99aab5;
        }

        /* Yükleme Kutusu */
        .upload-box { 
            padding: 40px; 
            border: 3px dashed #444444; 
            border-radius: 15px; 
            background-color: #21262d; 
            transition: all 0.3s ease;
        }
        .upload-box:hover {
            border-color: #00bcd4; /* Hover'da canlı mavi çizgi */
            box-shadow: 0 0 15px rgba(0, 188, 212, 0.4);
        }

        /* Inputlar ve Link Alanı */
        .link-input {
            background-color: #30363d;
            border: 1px solid #444;
            color: #fff;
            font-size: 1rem;
        }
        .link-input:focus {
             background-color: #30363d;
             color: #fff;
             border-color: #00bcd4; 
             box-shadow: 0 0 0 0.25rem rgba(0, 188, 212, 0.3);
        }
        .form-control {
            background-color: #30363d;
            border: 1px solid #444;
            color: #fff;
        }

        /* Butonlar */
        .btn-kopyala {
            background-color: #ff6e40; /* Ana Turuncu vurgu rengi */
            border-color: #ff6e40;
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-kopyala:hover {
            background-color: #ff5722;
            border-color: #ff5722;
            box-shadow: 0 0 10px rgba(255, 110, 64, 0.6);
        }
        .btn-primary {
            background-color: #00bcd4; /* Yükleme için Neon Mavi */
            border-color: #00bcd4;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #0097a7;
            border-color: #0097a7;
            box-shadow: 0 0 10px rgba(0, 188, 212, 0.6);
        }
        .btn-outline-info {
            color: #ff6e40;
            border-color: #ff6e40;
        }
        .btn-outline-info:hover {
            background-color: #ff6e40;
            color: #fff;
        }

        /* Dosya Seçici Butonu */
        .form-control::file-selector-button {
            background-color: #ff6e40;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            margin: -0.5rem -1rem -0.5rem -0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .form-control::file-selector-button:hover {
            background-color: #ff5722;
        }

    </style>
</head>
<body>

<div class="container">
    <header class="text-center mb-5">
        <h1 class="display-3">🚀 <?= SITE_TITLE ?></h1>
        <p class="lead">Hemen yükle, anında bağlantıyı al.</p>
    </header>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show border-0" role="alert" style="background-color: #1e3a34; color: #a1e8a1;">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($image_link): ?>
        <div class="row justify-content-center mb-5">
            <div class="col-md-12">
                <div class="alert alert-success text-center border-0" style="background-color: #21262d; color: #00bcd4;">
                    **✅ Resminiz Hazır!** Bağlantıyı kopyalayın:
                </div>
                <div class="input-group input-group-lg">
                    <input type="text" id="imageLinkInput" class="form-control link-input" value="<?= htmlspecialchars($image_link) ?>" readonly>
                    <button class="btn btn-kopyala" type="button" id="copyButton">Kopyala</button>
                </div>
                
                <p class="text-center mt-3">
                    <a href="<?= htmlspecialchars($image_link) ?>" target="_blank" class="btn btn-outline-info btn-lg">Resmi Görüntüle</a>
                </p>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="upload-box text-center">
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <h4 class="mb-4" style="color: #ff6e40;">Yüklemek İstediğiniz Resmi Seçin</h4>
                    <input type="file" name="resim" id="fileInput" class="form-control form-control-lg" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                    <p class="form-text text-muted mt-2">Maksimum 5MB, JPG, PNG, GIF, WEBP</p>
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">YÜKLEMEYİ BAŞLAT</button>
                </form>
            </div>
        </div>
    </div>
    
    <footer class="text-center mt-5 mb-3 text-secondary">
        <p>© 2025 <?= SITE_TITLE ?> | <a href="admin/login.php" class="text-decoration-none" style="color: #00bcd4;">Yönetici Girişi</a></p>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Kopyalama fonksiyonu
    const copyButton = document.getElementById('copyButton');
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            const copyText = document.getElementById('imageLinkInput');
            copyText.select();
            copyText.setSelectionRange(0, 99999); 
            // Modern tarayıcılarda navigator.clipboard kullan
            navigator.clipboard.writeText(copyText.value).then(() => {
                 this.innerText = 'Kopyalandı! ✅';
                 setTimeout(() => {
                     this.innerText = 'Kopyala';
                 }, 2000);
            }).catch(err => {
                 // Geri dönüş olarak document.execCommand kullan
                 document.execCommand("copy");
                 this.innerText = 'Kopyalandı! ✅';
                 setTimeout(() => {
                     this.innerText = 'Kopyala';
                 }, 2000);
            });
        });
    }
</script>
</body>
</html>