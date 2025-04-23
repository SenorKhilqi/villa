<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah role adalah "user"
if ($_SESSION['role'] !== 'user') {
    echo "Akses ditolak! Halaman ini hanya untuk pengguna biasa.";
    exit();
}
?>
