<?php
// Memuat variabel lingkungan dari file .env
$env = parse_ini_file('.env');

// Mendefinisikan variabel koneksi database
$host = $env['DB_HOST'];
$port = $env['DB_PORT'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$dbname = $env['DB_NAME'];

try {
    // Membuat koneksi ke database
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $user, $pass);
    
    // Atur mode error untuk laporan error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mencetak pesan koneksi berhasil
    echo "Koneksi berhasil";
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die(); // Hentikan eksekusi jika koneksi gagal
}
?>
