

# 🚀 Ultra Hızlı Resim Yükleme Merkezi (Fast Image Uploader)

**MIT Lisansı altında yayınlanan, minimal ve yüksek performanslı bir resim yükleme ve bağlantı paylaşma çözümüdür.**

Bu proje, kullanıcılara anında resim yükleme ve temiz bir URL alma imkanı sunarken, yöneticilere de tam kontrol sağlayan basit bir yönetim paneli sunar. Projenin ana felsefesi **hız**, **güvenlik** ve **kolay kurulum** üzerine kuruludur.

-----

## ✨ Temel Özellikler

| Kategori | Özellikler | Açıklama |
| :--- | :--- | :--- |
| **Hız & Kullanıcı Deneyimi** | **Tek Tıkla Yükleme** | Minimalist arayüz ile anında dosya seçimi ve yükleme. |
| | **Anında Bağlantı** | Yükleme tamamlanır tamamlanmaz resme ait doğrudan URL sunulur. |
| | **Kopyala Düğmesi** | Tek bir tuşla bağlantıyı panoya kopyalama özelliği (JavaScript ile). |
| **Güvenlik & Kontrol** | **Gelişmiş Dosya Kontrolü** | Yükleme sırasında dosya boyutu (5MB limit) ve dosya tipi (MIME Type) doğrulaması. |
| | **Benzersiz Dosya Adı** | Güvenlik ve çakışma önleme için `uniqid()` ile benzersiz dosya adları oluşturulur. |
| **Yönetim** | **Admin Paneli** | Şifre korumalı yönetim arayüzü. |
| | **Dinamik Ayarlar** | Admin panelinden site başlığını (Title) anında değiştirme imkanı. |
| | **Resim Yönetimi** | Yüklenen tüm resimleri listeleme, IP adresi görme ve kolayca silme yeteneği. |

-----

## 💻 Kullanılan Teknolojiler

Bu proje, hafif yapısını korumak için en yaygın ve erişilebilir teknolojileri kullanır.

  * **Ana Dil (Backend):** **PHP** (Sürüm 7.4 ve üzeri önerilir)
  * **Veritabanı:** **SQLite** (PDO ile)
      * *Neden SQLite?* Harici bir veritabanı sunucusu (MySQL gibi) gerektirmez, kurulumu ve taşınması son derece kolaydır. Meta verileri depolamak için idealdir.
  * **Depolama:** Yerel Sunucu Dosya Sistemi (`/public/resimler/` klasörü)
  * **Tasarım (Frontend):** **HTML5, CSS3, JavaScript**
  * **Çerçeve (CSS Framework):** **Bootstrap 5** (Koyu Tema, Cyber Speed stili ile özelleştirilmiş)

-----

## ⚙️ Kurulum Kılavuzu

1.  **Kodları İndirin:** Tüm dosya ve klasörleri (core, admin, public, index.php, upload.php) yerel veya sunucu dizininize (`/htdocs/`) kopyalayın.

2.  **Klasör İzni:** `public/resimler/` klasörüne **yazma izni (CHMOD 755 veya 777)** verin.

3.  **Veritabanı:** İlk çalıştırmada, `core/db.php` dosyası otomatik olarak `database.sqlite` dosyasını oluşturacaktır.

4.  **Admin Şifresi:** `core/db.php` dosyasını açarak **`ADMIN_PASSWORD`** sabitini güvenli bir şifreyle değiştirin.

    ```php
    define('ADMIN_PASSWORD', 'GUCLU_SIFRENIZ');
    ```

5.  **Kullanım:** Tarayıcınızdan ana dizine (`/index.php`) giderek yüklemeye başlayın veya `/admin/login.php` adresinden yönetime erişin.

-----

## 📜 Lisans Bilgisi

Bu proje, **MIT Lisansı** altında yayınlanmıştır. Bu, projenin kaynak kodunu dilediğinizce kullanmakta, değiştirmekte ve dağıtmakta tamamen özgür olduğunuz anlamına gelir. Ticari projelerde dahi kullanılabilir. Tek şart, ÜCRETLİ SATIŞ YAPILMAMASI.

-----

**Geliştirici Notu:** Projenin basitlik ve hız hedefleri doğrultusunda, geliştirme sürecinizde daha yüksek trafik beklentisi varsa SQLite yerine MySQL/MariaDB'ye geçiş yapılması tavsiye edilir.
