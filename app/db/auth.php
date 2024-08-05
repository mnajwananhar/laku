<!-- // auth.php -->
<?php
    // session_start();
function checkAccess($allowedRoles) {
    // Pastikan session sudah dimulai
    
    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['peran'])) {
        header("Location: index.php");
        exit();
    }
    
    // Cek apakah peran pengguna diizinkan untuk mengakses halaman ini
    if (!in_array($_SESSION['peran'], $allowedRoles)) {
        echo "<script>window.location.href = '404.php';</script>";
        exit();
    }
}
?>
