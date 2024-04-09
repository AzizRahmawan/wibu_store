<?php
session_start();

require_once '../connection.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$query_cek_email = "SELECT * FROM users WHERE email = '$email'";

try {
    $stmt = $conn->query($query_cek_email);
    
    if ($stmt->rowCount() == 1) {
        $_SESSION['error_message'] = "Email sudah terdaftar. Silahkan gunakan email lain.";
        header('Location: ../pages/daftar.php');
        exit();
    } else {
        $query_insert_user = "INSERT INTO users (nama_pengguna, email, kata_sandi) VALUES ('$name', '$email', '$password')";
        $conn->query($query_insert_user);

        // Ambil user_id dari pengguna yang baru saja didaftarkan
        $user_id = $conn->lastInsertId();

        // Buat cart untuk pengguna yang baru saja didaftarkan
        $query_create_cart = "INSERT INTO carts (user_id) VALUES ('$user_id')";
        $conn->query($query_create_cart);

        $_SESSION['success_message'] = "Akun berhasil dibuat. Silahkan Login.";
        header('Location: ../pages/login.php');
        exit();
    }
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
