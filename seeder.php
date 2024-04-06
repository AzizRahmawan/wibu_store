<?php
require_once 'connection.php';

try {
    function truncateCategories($conn) {
        $sql = "DELETE FROM categories";
        $conn->exec($sql);
        echo "Tabel kategori telah dikosongkan.\n";
    }

    function truncateProducts($conn) {
        $sql = "DELETE FROM products";
        $conn->exec($sql);
        echo "Tabel produk telah dikosongkan.\n";
    }

    function truncateUsers($conn) {
        $sql = "DELETE FROM users";
        $conn->exec($sql);
        echo "Tabel pengguna telah dikosongkan.\n";
    }

    function truncateCarts($conn) {
        $sql = "DELETE FROM carts";
        $conn->exec($sql);
        echo "Tabel cart telah dikosongkan.\n";
    }

    function truncateCartItems($conn) {
        $sql = "DELETE FROM cart_items";
        $conn->exec($sql);
        echo "Tabel cart_items telah dikosongkan.\n";
    }

    function truncateOrders($conn) {
        $sql = "DELETE FROM orders";
        $conn->exec($sql);
        echo "Tabel order telah dikosongkan.\n";
    }

    function truncateOrderItems($conn) {
        $sql = "DELETE FROM order_items";
        $conn->exec($sql);
        echo "Tabel order_items telah dikosongkan.\n";
    }

    function seedCategories($conn) {
        $categories = [
            [1, 'Fashion'],
            [2, 'Anime Merchandise'],
            [3, 'Manga']
        ];
    
        $sql = "INSERT INTO categories (category_id, nama_kategori) VALUES ";
        $sql .= "(?, ?)";
        
        $stmt = $conn->prepare($sql);
    
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    
        echo "Data kategori berhasil dimasukkan.\n";
    }    

    function seedProducts($conn) {
        $products = [
            [1, 'T-Shirt Naruto', 'T-Shirt Naruto dengan desain unik', 20.00, 50, 1, 't-shirt-naruto.png'],
            [2, 'Poster One Piece', 'Poster One Piece ukuran besar', 10.00, 100, 1, 'one-piece-poster.jpg'],
            [3, 'Action Figure Goku', 'Action Figure Goku Super Saiyan', 30.00, 30, 2, 'action-figure-goku.jpg'],
            [4, 'Sweater Attack on Titan', 'Sweater dengan tema Attack on Titan', 25.00, 20, 1, 'sweater-attack-on-titan.png'],
            [5, 'Manga One Piece Vol. 1', 'Manga One Piece volume pertama', 15.00, 80, 3, 'one-piece-vol-1.jpg'],
            [6, 'Nendoroid Pikachu', 'Nendoroid Pikachu dengan ekspresi lucu', 35.00, 15, 2, 'nendoroid-pikachu.jpg'],
            [7, 'T-Shirt My Hero Academia', 'T-Shirt My Hero Academia dengan logo Deku', 18.00, 60, 1, 't-shirts-my-hero-academia.png'],
            [8, 'Figurine Luffy', 'Figurine Luffy ukuran besar', 40.00, 10, 2, 'figure-luffy.jpg'],
            [9, 'Hoodie Naruto', 'Hoodie dengan desain simbol Naruto', 30.00, 25, 1, 'hoodie-naruto.jpg'],
            [10, 'Plushie Totoro', 'Plushie Totoro berukuran kecil', 12.00, 50, 2, 'totoro.jpg']
        ];
    
        $sql = "INSERT INTO products (product_id, nama_produk, deskripsi, harga, stok, kategori_id, images) VALUES ";
        $values = [];
        foreach ($products as $product) {
            $values[] = "('" . implode("','", $product) . "')";
        }
        $sql .= implode(",", $values);
        $conn->exec($sql);
        echo "Data produk berhasil dimasukkan.\n";
    }
     
    
    function seedUsers($conn) {
        $users = [
            [1, 'John Doe', 'john@example.com', 'password123', '123 Main Street', '123456789'],
            [2, 'Jane Doe', 'jane@example.com', 'password456', '456 Elm Street', '987654321'],
            [3, 'Alice Smith', 'alice@example.com', 'password789', '789 Oak Street', '456789123'],
            [4, 'Bob Johnson', 'bob@example.com', 'passwordabc', '321 Pine Street', '789123456'],
            [5, 'Eve Wilson', 'eve@example.com', 'passworddef', '654 Maple Street', '321654987']
        ];
    
        $sql = "INSERT INTO users (user_id, nama_pengguna, email, kata_sandi, alamat, nomor_telepon) VALUES ";
        $sql .= "(?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
    
        foreach ($users as $user) {
            $stmt->execute($user);
        }
    
        echo "Data pengguna berhasil dimasukkan.\n";
    }
    
      

    truncateOrderItems($conn);
    truncateCartItems($conn);
    truncateOrders($conn);
    truncateCarts($conn);
    truncateProducts($conn);
    truncateCategories($conn);
    truncateUsers($conn);

    seedUsers($conn);
    seedCategories($conn); 
    seedProducts($conn);

} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
