<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/main-header.php';
require_once __DIR__ . '/../includes/top-header.php';

$user_id = $_SESSION['user_id'] ?? null;
$wishlist_items = [];
if ($user_id) {
    $stmt = $conn->prepare('SELECT w.id as wishlist_id, p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $wishlist_items[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Sportify</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>
    <div class="wishlist-container">
        <h1 class="wishlist-title">Wishlist</h1>
        <?php if (count($wishlist_items) > 0): ?>
        <div class="wishlist-grid">
            <?php foreach ($wishlist_items as $item): ?>
            <div class="wishlist-card" data-product-id="<?php echo $item['id']; ?>">
                <img src="../<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="wishlist-img">
                <div class="wishlist-info">
                    <div class="wishlist-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <button class="wishlist-remove-btn" data-wishlist-id="<?php echo $item['wishlist_id']; ?>">&#128465;</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="wishlist-empty">Your wishlist is empty.</div>
        <?php endif; ?>
    </div>
    <script src="../assets/js/wishlist.js"></script>
</body>
</html>
