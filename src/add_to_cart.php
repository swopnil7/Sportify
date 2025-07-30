<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'] ?? null;
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
    $stmt = $conn->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_name = ? AND size = ? AND color = ?');
    $stmt->bind_param('isss', $user_id, $product['name'], $size, $color);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // Update quantity
        $new_qty = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
        $stmt->bind_param('ii', $new_qty, $cart_item['id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new cart item with product_id (no created_at, let DB default)
        $stmt = $conn->prepare('INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, quantity, size, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('iisdssiss', $user_id, $product_id, $product['name'], $product['price'], $product['image_url'], $quantity, $size, $color);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'invalid_request']);
