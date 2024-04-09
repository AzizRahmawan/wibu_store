<?php
require_once '../connection.php';

$cartItemId = $_POST['cart_item_id'];

try {
    $sql = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cart_item_id', $cartItemId);
    
    if ($stmt->execute()) {
        $response = array(
            'status' => 'success',
            'message' => 'Item berhasil dihapus dari keranjang.'
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Gagal menghapus item dari keranjang.'
        );
        echo json_encode($response);
    }
} catch (PDOException $e) {
    $response = array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    );
    echo json_encode($response);
}
?>
