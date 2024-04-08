<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ./pages/login.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Distro Wibu</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./assets/css/index.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <span class="logo-kanji">夢 Store</span> 
        </a>

        <!-- Toggler/collapsible Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav flex-grow-1">
                <!-- Fitur Search -->
                <li class="nav-item" style="width: 100%; padding: 0; margin: 0;">
                    <form class="form-inline my-2 my-lg-0 navbar-form" style="width: 100%;">
                        <input class="form-control mr-sm-2 search-input w-100" type="search" placeholder="Cari produk" aria-label="Search">
                        <button class="form-control mr-sm-2 btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </li>
            </ul>

            <!-- Keranjang, Nama Pengguna, dan Logout -->
            <ul class="navbar-nav">
                <li class="nav-item mx-auto d-none d-lg-block text-center">
                    <a class="nav-link" href="#"><i class="fas fa-shopping-cart" style="color: #fff;"></i></a>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Jika pengguna sudah login, tampilkan nama pengguna dan menu logout -->
                    <li class="nav-item dropdown mx-auto d-none d-lg-block">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" style="color: #fff;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $_SESSION['nama_pengguna']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?logout=true">Logout</a>
                        </div>
                    </li>
                    <!-- Tambahkan juga pada bagian burger menu -->
                    <li class="nav-item d-lg-none mx-auto text-center">
                        <a class="nav-link" href="#" style="color: #fff; font-weight: bold;"><i class="fas fa-shopping-cart"></i> Keranjang</a>
                        <a class="nav-link" href="#" style="color: #fff; font-weight: bold;"><i class="fas fa-user"></i> <?php echo $_SESSION['nama_pengguna']; ?></a>
                        <a class="nav-link" href="?logout=true" style="color: #fff; font-weight: bold;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Jika pengguna belum login, tampilkan menu login dan daftar -->
                    <li class="nav-item dropdown mx-auto d-none d-lg-block">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" style="color: #fff;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user" style="color: #fff;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="./pages/login.php">Login</a>
                            <a class="dropdown-item" href="#">Daftar</a>
                        </div>
                    </li>
                    <!-- Tambahkan juga pada bagian burger menu -->
                    <li class="nav-item d-lg-none mx-auto text-center">
                        <a class="nav-link" href="#" style="color: #fff; font-weight: bold;"><i class="fas fa-shopping-cart"></i> Keranjang</a>
                        <a class="nav-link" href="./pages/login.php" style="color: #fff; font-weight: bold;"><i class="fas fa-user"></i> Login</a>
                        <a class="nav-link" href="#" style="color: #fff; font-weight: bold;"><i class="fas fa-user-plus"></i> Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <div class="container">
        <h1 class="mt-4">Selamat datang di Marketplace Distro Wibu</h1>
        <!-- Isi halaman index -->
        <div class="row">
            <?php
            require_once 'connection.php';

            try {
                $sql = "SELECT products.product_id, products.nama_produk, products.deskripsi, products.harga, products.stok, categories.nama_kategori, products.images FROM products JOIN categories ON products.kategori_id = categories.category_id";
                $stmt = $conn->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="col-lg-3 col-md-6 mb-4"> <!-- Atur lebar agar ada 4 item per baris -->
                        <div class="card h-100">
                            <img class="card-img-top product-img" src="./assets/images/products/<?php echo $row['images']; ?>" alt="Product Image">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $row['nama_produk']; ?></h4>
                                <h5>$ <?php echo $row['harga']; ?></h5>
                                <p class="card-text"><?php echo $row['deskripsi']; ?></p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted font-weight-bold">Stok: <?php echo $row['stok']; ?></small>
                                <div class="button-group">
                                    <button type="button" class="mx-1 btn btn-dark"><i class="fas fa-shopping-cart"></i></button>
                                    <button type="button" class="mx-1 btn btn-dark"><i class="fas fa-shopping-bag"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } catch(PDOException $e) {
                echo "Koneksi gagal: " . $e->getMessage();
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS dan jQuery (diperlukan untuk dropdown) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
