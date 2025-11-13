<?php
session_start();
include_once '../core/db.php'; 

// Oturumu sonlandır
session_unset();
session_destroy();

// Giriş sayfasına yönlendir
redirect('login.php');
?>