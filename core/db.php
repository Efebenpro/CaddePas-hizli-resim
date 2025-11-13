<?php
// core/db.php

// YÖNETİCİ ŞİFRESİ VE GÜVENLİK AYARLARI
define('ADMIN_PASSWORD', '123'); // LÜTFEN GERÇEK PROJEDE DEĞİŞTİRİN!
define('DB_FILE', __DIR__ . '/../database.sqlite');

try {
    $db = new PDO('sqlite:' . DB_FILE);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 1. images Tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS images (
        id INTEGER PRIMARY KEY,
        file_name TEXT NOT NULL,
        upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address TEXT,
        is_deleted INTEGER DEFAULT 0
    )");

    // 2. settings Tablosu (Yeni! Site Adı için)
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT
    )");
    
    // Site adını kontrol et ve yoksa varsayılanı ekle
    $stmt = $db->query("SELECT COUNT(*) FROM settings WHERE key = 'site_title'");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO settings (key, value) VALUES ('site_title', 'Ultra Hızlı Resim Yükle')");
    }
    
    // Site Başlığını Çekme
    $stmt = $db->query("SELECT value FROM settings WHERE key = 'site_title'");
    $settings = $stmt->fetch();
    define('SITE_TITLE', $settings['value']);

} catch (PDOException $e) {
    // Hata mesajı verirken SESSION kullanmadık, direkt çıktı verdik.
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

session_start(); // Session'ı db.php'de başlatmak en pratik çözüm
function redirect($url) {
    header("Location: $url");
    exit;
}
?>