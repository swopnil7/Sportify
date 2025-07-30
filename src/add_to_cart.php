<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    $size = $_POST['size'] ?? 'M';
    $color = $_POST['color'] ?? 'Default';

    if (!$user_id) {
        echo json_encode(['success' => false, 'error' => 'not_logged_in']);
        exit;
    }

    // Get product info
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'product_not_found']);
        exit;
    }

    // Check if already in cart
    $stmt = $conn->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?');
    $stmt->bind_param('iiss', $user_id, $product_id, $size, $color);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // Set quantity to 1 (do not increment)
        $stmt = $conn->prepare('UPDATE cart SET quantity = 1 WHERE id = ?');
        $stmt->bind_param('i', $cart_item['id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new cart item
        $stmt = $conn->prepare('INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, quantity, size, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param(
            'iisdisss',
            $user_id,
            $product_id,
            $product['name'],
            $product['price'],
            $product['image_url'],
            $quantity,
            $size,
            $color
        );
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            $stmt->close();
            exit;
        }
        $stmt->close();
    }

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'invalid_request']);