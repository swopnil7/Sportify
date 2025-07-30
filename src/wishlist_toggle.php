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
    $action = $_POST['action'] ?? '';
    if (!$user_id || !$product_id || !in_array($action, ['add','remove'])) {
        echo json_encode(['success' => false, 'error' => 'invalid_request']);
        exit;
    }
    if ($action === 'add') {
        $stmt = $conn->prepare('INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true, 'added' => true]);
        exit;
    } else if ($action === 'remove') {
        $stmt = $conn->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?');
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true, 'removed' => true]);
        exit;
    }
}
echo json_encode(['success' => false, 'error' => 'invalid_request']);
