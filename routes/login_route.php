<?php
session_start();

require_once '../connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = '$email' AND kata_sandi = '$password'";

try {
    $stmt = $conn->query($query);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['nama_pengguna'] = $user['nama_pengguna'];
        
        header('Location: ../index.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Email atau kata sandi salah. Silakan coba lagi.";
        header('Location: ../pages/login.php');
        exit();
    }
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
