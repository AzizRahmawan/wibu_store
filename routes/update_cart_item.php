<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $cartItemId = $_POST['cart_item_id'];
    $newQuantity = $_POST['quantity'];

    try {
        $updateQuery = "UPDATE cart_items ci
                        JOIN products p ON ci.product_id = p.product_id
                        SET ci.jumlah = :quantity, ci.subtotal = p.harga * :quantity
                        WHERE ci.cart_item_id = :cart_item_id";

        $stmt = $conn->prepare($updateQuery);

        $stmt->bindParam(':quantity', $newQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':cart_item_id', $cartItemId, PDO::PARAM_INT);

        $stmt->execute();

        $response = [
            'success' => true,
            'subtotal' => calculateSubtotal($conn, $cartItemId),
            'total' => calculateTotal($conn, $user_id)
        ];
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => 'Gagal mengupdate jumlah produk di keranjang: ' . $e->getMessage()
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

function calculateSubtotal($conn, $cartItemId) {
    $subtotalQuery = "SELECT subtotal FROM cart_items WHERE cart_item_id = :cart_item_id";
    $stmt = $conn->prepare($subtotalQuery);
    $stmt->bindParam(':cart_item_id', $cartItemId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function calculateTotal($conn, $user_id) {
    $totalQuery = "SELECT SUM(subtotal) FROM cart_items WHERE cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)";
    $stmt = $conn->prepare($totalQuery);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>
