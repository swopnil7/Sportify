<?php
// Prevent any accidental whitespace before output
ob_clean();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=UTF-8');

session_start();
require_once __DIR__ . '/../config/db.php';

// Check DB connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'db_connection_failed', 'details' => $conn->connect_error]);
    exit;
}

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
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
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
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('iiss', $user_id, $product_id, $size, $color);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // Set quantity to 1 (do not increment)
        $stmt = $conn->prepare('UPDATE cart SET quantity = 1 WHERE id = ?');
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param('i', $cart_item['id']);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Update failed: ' . $stmt->error]);
            $stmt->close();
            exit;
        }
        $stmt->close();
    } else {
        // Insert new cart item (no product_image column)
        $stmt = $conn->prepare('INSERT INTO cart (user_id, product_id, product_name, product_price, quantity, size, color) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param(
            'iisdiss',
            $user_id,
            $product_id,
            $product['name'],
            $product['price'],
            $quantity,
            $size,
            $color
        );
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Insert failed: ' . $stmt->error]);
            $stmt->close();
            exit;
        }
        $stmt->close();
    }

    // Ensure no whitespace or output before/after JSON
    echo json_encode(['success' => true]);
    exit;
}

// If not POST, return error
echo json_encode(['success' => false, 'error' => 'invalid_request']);
exit;