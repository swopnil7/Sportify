<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/main-header.php';
require_once __DIR__ . '/../includes/top-header.php';

$user_id = $_SESSION['id'] ?? null;
$cart_items = [];
$total = 0;

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['product_price'] * $row['quantity'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Sportify</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/main-header.php'; ?>
    <div class="cart-container">
        <h1 class="cart-title"><i class="fa fa-shopping-cart"></i> Shopping Cart</h1>
        <p class="cart-subtitle">Review your items before checkout</p>
        <div class="cart-content">
            <div class="cart-items-section">
                <h2>Cart Items</h2>
                <?php if (count($cart_items) > 0): ?>
                <form method="post" action="">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><img src="../assets/images/products/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="cart-product-img"></td>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['size']); ?></td>
                                <td><?php echo htmlspecialchars($item['color']); ?></td>
                                <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                                <td><?php echo (int)$item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                                <td><button type="submit" name="remove" value="<?php echo $item['id']; ?>" class="cart-remove-btn">&times;</button></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
                <?php else: ?>
                    <div class="cart-empty">Your cart is empty.</div>
                <?php endif; ?>
            </div>
            <div class="cart-summary-section">
                <h2>Order Summary</h2>
                <div class="cart-summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="cart-summary-row">
                    <span>Shipping:</span>
                    <span>$15.00</span>
                </div>
                <div class="cart-summary-row">
                    <span>Tax:</span>
                    <span>$10.00</span>
                </div>
                <div class="cart-summary-row cart-summary-total">
                    <span>Total:</span>
                    <span style="color: var(--primary); font-weight: bold;">$<?php echo number_format($total + 15 + 10, 2); ?></span>
                </div>
                <button class="auth-btn cart-checkout-btn">Proceed to Checkout</button>
                <a href="index.php" class="cart-continue-link">&larr; Continue Shopping</a>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
