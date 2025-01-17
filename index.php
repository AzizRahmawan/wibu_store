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
    <title>Distro Wibu</title>
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
            <a class="navbar-brand" href="index.php">
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
                        <form class="form-inline my-2 my-lg-0 navbar-form" style="width: 100%;" action="index.php" method="GET">
                            <input class="form-control mr-sm-2 search-input w-100" type="search" name="search_query" placeholder="Cari produk" aria-label="Search">
                            <button class="form-control mr-sm-2 btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                </ul>

                <!-- Keranjang, Nama Pengguna, dan Logout -->
                <ul class="navbar-nav">
                    <li class="nav-item mx-auto d-none d-lg-block text-center">
                        <button class="nav-link" data-toggle="modal" data-target="#cartModal" style="background-color: #1782e6; border: none; font-weight: bold; color: #fff;"><i class="fas fa-shopping-cart"></i></button>
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

        <!-- Modal -->
        <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartModalLabel">Keranjang Belanja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container cart-container">
                            <?php
                            require_once 'connection.php';
                            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                            if ($user_id !== null) {
                                $sql = "SELECT cart_items.cart_item_id, products.nama_produk, products.images, cart_items.subtotal, cart_items.jumlah FROM cart_items INNER JOIN products ON cart_items.product_id = products.product_id WHERE cart_items.cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindParam(':user_id', $user_id);
                                    $stmt->execute();

                                    $total = 0; 
                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <div class="cart-item row align-items-center text-center my-3">
                                                <div class="col-md-3 text-center">
                                                    <img src="./assets/images/products/<?php echo $row['images']; ?>" alt="Product Image" class="img-fluid product-img">
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h4 class="text-center"><?php echo $row['nama_produk']; ?></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" class="form-control product-quantity" value="<?php echo $row['jumlah']; ?>" min="1" data-cart-item-id="<?php echo $row['cart_item_id']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <h4 class="subtotal cart-item-subtotal" data-cart-item-id="<?php echo $row['cart_item_id']; ?>" id="subtotal">$<?php echo $row['subtotal']; ?></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-danger delete-cart-item" data-cart-item-id="<?php echo $row['cart_item_id']; ?>"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                            <?php
                                            $total += $row['subtotal'];
                                        }
                                    } else {
                                        ?>
                                        <div class="text-center">
                                            <p>Keranjang belanja Anda kosong.</p>
                                            <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                                        </div>
                                        <?php
                                    }
                                } catch(PDOException $e) {
                                    echo "Koneksi gagal: " . $e->getMessage();
                                }
                            } else {
                                ?>
                                <div class="text-center">
                                    <p>Silakan login untuk melihat keranjang belanja Anda.</p>
                                    <a href="./pages/login.php" class="btn btn-primary">Login</a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php if ($user_id !== null && $total > 0) : ?>
                            <div class="text-right">
                                <h3>Total: $<span id="total"><?php echo $total; ?>.00</span></h3>
                                <button class="btn btn-primary" id="checkout">Checkout</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="mt-4">Selamat datang di Distro Wibu</h1>
        <!-- Isi halaman index -->
        <div class="row">
            <?php
            require_once 'connection.php';

            try {
                $sql = "SELECT products.product_id, products.nama_produk, products.deskripsi, products.harga, products.stok, categories.nama_kategori, products.images FROM products JOIN categories ON products.kategori_id = categories.category_id";

                if(isset($_GET['search_query']) && !empty($_GET['search_query'])) {
                    $search_query = $_GET['search_query'];

                    $sql .= " WHERE products.nama_produk LIKE '%$search_query%'";
                }

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
                                <h6 class="card-text"><?php echo $row['nama_kategori']; ?></h6>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted font-weight-bold">Stok: <?php echo $row['stok']; ?></small>
                                <div class="button-group">
                                    <!-- Tambahkan tombol "Tambah ke Keranjang" dengan atribut data-product-id -->
                                    <button type="button" class="mx-1 btn btn-dark add-to-cart-btn" data-product-id="<?php echo $row['product_id']; ?>"><i class="fas fa-shopping-cart"></i></button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.product-quantity').on('change', function() {
                let cartItemId = $(this).data('cart-item-id');
                let newQuantity = $(this).val();

                console.log("Cart Item ID: " + cartItemId);
                console.log("New Quantity: " + newQuantity);

                $.ajax({
                    url: 'routes/update_cart_item.php',
                    method: 'POST',
                    data: {
                        cart_item_id: cartItemId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        console.log(response);
                        let result = JSON.parse(response);
                        let subtotal = result.subtotal;
                        let total = result.total;
                        $('#subtotal[data-cart-item-id="' + cartItemId + '"]').text('$' + subtotal);
                        $('#total').text(total);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
        $(document).ready(function() {
            $('.add-to-cart-btn').on('click', function() {
                let productId = $(this).data('product-id');

                $.ajax({
                    url: 'routes/add_to_cart.php',
                    method: 'POST',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        console.log(response);
                        alert("Produk berhasil ditambahkan ke keranjang.");
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Produk gagal ditambahkan ke keranjang.");
                    }
                });
            });
        });
        $(document).ready(function() {
            $('.delete-cart-item').on('click', function() {
                let cartItemId = $(this).data('cart-item-id');

                $.ajax({
                    url: 'routes/delete_cart_item.php',
                    method: 'POST',
                    data: {
                        cart_item_id: cartItemId
                    },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Gagal menghapus item dari keranjang.");
                    }
                });
            });
        });

    </script>
</body>
</html>
