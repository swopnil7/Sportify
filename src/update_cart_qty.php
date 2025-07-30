<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $cart_item_id = intval($_POST['cart_item_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    if (!$user_id || $cart_item_id <= 0 || $quantity < 1) {
        echo json_encode(['success' => false, 'error' => 'invalid_request']);
        exit;
    }
    $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
    $stmt->bind_param('iii', $quantity, $cart_item_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit;
}
echo json_encode(['success' => false, 'error' => 'invalid_request']);
