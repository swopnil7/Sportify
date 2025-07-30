<?php
// shop.php - Modern Shop Page with Advanced Filters & Sorting
// Uses: Sidebar filters (category, subcategory, price), search bar, sorting, product grid
// UI: Modern, responsive, matches current Sportify theme

// --- HTML HEAD: Add styles and icons ---
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop | Sportify</title>
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>


<?php
// --- DB Connection (mysqli) ---
require_once __DIR__ . '/../config/db.php';
if (!isset($conn) || !$conn) {
    die('<div style="color:red;text-align:center;margin:2em auto;">Database connection error. Please check your db.php config.</div>');
}
include '../includes/top-header.php';
include '../includes/main-header.php';

// --- Fetch categories and subcategories ---
$categories = [
    ['name' => 'Clothing'],
    ['name' => 'Footwear'],
    ['name' => 'Accessories'],
];
// TODO: Fetch subcategories from DB if needed

// --- Handle filters/search/sort ---
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$subcategory = $_GET['subcategory'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? '';

$usd_to_npr = 133; // Conversion rate from USD to NPR

// --- Build product query (simplified, update as needed) ---
$where = [];
$params = [];
if ($search) {
    $where[] = '(name LIKE ? OR description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category) {
    $where[] = 'category = ?';
    $params[] = $category;
}
if ($subcategory) {
    $where[] = 'subcategory_id = ?';
    $params[] = $subcategory;
}
if ($min_price !== '') {
    $where[] = 'price >= ?';
    $params[] = round($min_price / $usd_to_npr, 2);
}
if ($max_price !== '') {
    $where[] = 'price <= ?';
    $params[] = round($max_price / $usd_to_npr, 2);
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// --- Sorting ---
$sort_sql = '';
switch ($sort) {
    case 'price_asc': $sort_sql = 'ORDER BY price ASC'; break;
    case 'price_desc': $sort_sql = 'ORDER BY price DESC'; break;
    case 'name_asc': $sort_sql = 'ORDER BY name ASC'; break;
    case 'name_desc': $sort_sql = 'ORDER BY name DESC'; break;
    default: $sort_sql = 'ORDER BY id DESC'; break;
}


// --- Fetch products (mysqli) ---
$sql = "SELECT * FROM products $where_sql $sort_sql";
$stmt = $conn->prepare($sql);
if ($stmt && $params) {
    // Dynamically bind params
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
} else {
    $products = [];
}

?>



<section class="shop-section">
  <div class="shop-layout">
    <div class="shop-sidebar">
      <form class="shop-filters styled-filters" method="get">
        <div class="filter-title">Filter & Search</div>
        <div class="filter-group">
          <label for="search"><i class="fa fa-search"></i> Search</label>
          <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search products...">
        </div>
        <div class="filter-group">
          <label for="category"><i class="fa fa-list"></i> Category</label>
          <select id="category" name="category">
            <option value="">All</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat['name']) ?>" <?= $category == $cat['name'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label><i class="fa fa-rupee-sign"></i> Price</label>
          <div class="price-range">
            <input type="number" name="min_price" min="0" step="1" value="<?= htmlspecialchars($min_price) ?>" placeholder="Min">
            <span>-</span>
            <input type="number" name="max_price" min="0" step="1" value="<?= htmlspecialchars($max_price) ?>" placeholder="Max">
          </div>
        </div>
        <div class="filter-group">
          <label for="sort"><i class="fa fa-sort"></i> Sort By</label>
          <select id="sort" name="sort">
            <option value="">Newest</option>
            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
            <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
            <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Name: Z-A</option>
          </select>
        </div>
        <button type="submit" class="filter-btn main-btn"><i class="fa fa-filter"></i> Apply Filters</button>
      </form>
    </div>
    <div class="shop-products">
      <h1 class="section-title">Shop Products</h1>
      <div class="products-grid">
        <?php if ($products): ?>
          <?php foreach ($products as $product): ?>
            <div class="product-card" data-product-id="<?= $product['id'] ?>" data-product-image="<?= htmlspecialchars($product['image_url'] ?? $product['image'] ?? 'assets/images/products/default.jpg') ?>">
              <div style="position: relative;">
                <img src="../<?= htmlspecialchars($product['image_url'] ?? $product['image'] ?? 'assets/images/products/default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                <?php if (!empty($product['badge'])): ?>
                  <span class="product-badge"><?= htmlspecialchars($product['badge']) ?></span>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <h3 class="product-name" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h3>

                <?php
                  $usd_to_npr = 133;
                  $price_npr = isset($product['price']) ? $product['price'] * $usd_to_npr : 0;
                  $original_price_npr = (!empty($product['original_price']) && $product['original_price'] > $product['price']) ? $product['original_price'] * $usd_to_npr : null;
                ?>
                <div class="product-pricing">
                  <span class="product-price">रु<?= number_format($price_npr) ?></span>
                  <?php if ($original_price_npr): ?>
                    <span class="product-original-price">रु<?= number_format($original_price_npr) ?></span>
                  <?php endif; ?>
                </div>
                <?php if (!empty($product['rating'])): ?>
                  <span class="product-rating">⭐ <?= number_format($product['rating'], 2) ?></span>
                <?php endif; ?>
              </div>
              <div class="product-actions">
                <button class="wishlist-btn" title="Add to Wishlist" aria-label="Add to Wishlist" type="button"><i class="fa fa-heart"></i></button>
                <button class="add-cart-btn" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="cart-empty">No products found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>



<?php include '../includes/footer.php'; ?>
<script src="../assets/js/theme-toggle.js"></script>
<script src="../assets/js/main-header.js"></script>
<script src="../assets/js/cart.js"></script>
<script src="../assets/js/wishlist.js"></script>
</body>
</html>
