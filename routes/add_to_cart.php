<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $productId = $_POST['product_id'];

    try {
        $checkCartQuery = "SELECT COUNT(*) FROM cart_items WHERE product_id = :product_id AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)";
        $checkStmt = $conn->prepare($checkCartQuery);
        $checkStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $updateQuantityQuery = "UPDATE cart_items SET jumlah = jumlah + 1, subtotal = (SELECT harga * (jumlah) FROM products WHERE product_id = :product_id) WHERE product_id = :product_id AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)";
            $updateStmt = $conn->prepare($updateQuantityQuery);
            $updateStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $updateStmt->execute();
        } else {
            $addToCartQuery = "INSERT INTO cart_items (cart_id, product_id, jumlah, subtotal) VALUES ((SELECT cart_id FROM carts WHERE user_id = :user_id), :product_id, 1, (SELECT harga FROM products WHERE product_id = :product_id))";
            $addStmt = $conn->prepare($addToCartQuery);
            $addStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $addStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $addStmt->execute();
        }        

        $response = [
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang'
        ];
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => 'Gagal menambahkan produk ke keranjang: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Metode request tidak diizinkan'
    ];
    echo json_encode($response);
}
?>
