<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sportify</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/top-header.php'; ?>
    <?php include '../includes/main-header.php'; ?>
    <!-- Removed add_to_cart.php inclusion -->

    <!-- Hero Carousel -->
    <section class="hero-carousel">
      <div class="carousel-track">
        <div class="carousel-slide" style="background-image:url('../assets/images/hero4.webp')"></div>
        <div class="carousel-slide" style="background-image:url('../assets/images/hero1.webp')"></div>
        <div class="carousel-slide" style="background-image:url('../assets/images/hero3.webp')"></div>
      </div>
      <div class="carousel-overlay">
        <h1 class="carousel-title">GEAR UP FOR EVERYDAY</h1>
        <div class="carousel-offer">
          <span class="offer-main">SAVE UP TO 40%</span>
          <span class="offer-desc">Discover fresh styles and unbeatable deals for every adventure.</span>
        </div>
        <div class="carousel-btns">
          <a href="shop.php?category=Clothing" class="carousel-btn">SHOP CLOTHINGS &rarr;</a>
          <a href="shop.php?category=Footwear" class="carousel-btn">SHOP FOOTWEARS &rarr;</a>
          <a href="shop.php?category=Accessories" class="carousel-btn">SHOP ACCESSORIES &rarr;</a>
        </div>
      </div>
    </section>

    <script src="../assets/js/theme-toggle.js"></script>
    <script src="../assets/js/main-header.js"></script>
    <script src="../assets/js/hero-carousel.js"></script>
</body>


<?php
// Featured Products Section
$conn = new mysqli('localhost', 'root', '', 'sportify_db');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$sql = "SELECT * FROM products WHERE badge = 'Popular' LIMIT 3";
$result = $conn->query($sql);
?>


<section class="featured-products">
  <h2 class="section-title">Featured Products</h2>
  <div class="products-grid">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="product-card" data-product-id="<?php echo $row['id']; ?>" data-product-image="<?php echo htmlspecialchars($row['image_url']); ?>">
        <div style="position: relative;">
          <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img">
          <span class="product-badge"><?php echo htmlspecialchars($row['badge']); ?></span>
        </div>
        <div class="product-info">
          <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>

          <?php
            $usd_to_npr = 133;
            $price_npr = isset($row['price']) ? $row['price'] * $usd_to_npr : 0;
            $original_price_npr = (isset($row['original_price']) && $row['original_price'] > $row['price']) ? $row['original_price'] * $usd_to_npr : null;
          ?>
          <div class="product-pricing">
            <span class="product-price">रु<?php echo number_format($price_npr); ?></span>
            <?php if ($original_price_npr): ?>
              <span class="product-original-price">रु<?php echo number_format($original_price_npr); ?></span>
            <?php endif; ?>
          </div>
          <span class="product-rating">⭐ <?php echo number_format($row['rating'], 2); ?></span>
        </div>
        <div class="product-actions">
          <button class="wishlist-btn" title="Add to Wishlist" aria-label="Add to Wishlist" type="button"><i class="fa fa-heart"></i></button>
          <button class="add-cart-btn" data-product-id="<?php echo $row['id']; ?>">Add to Cart</button>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

</section>

<!-- Featured Brands Section -->
<section class="featured-brands">
  <h2 class="section-title">Featured Brands</h2>
  <div class="brands-row">
    <img src="../assets/images/brands/nike.png" alt="Nike" class="brand-logo" />
    <img src="../assets/images/brands/adidas.png" alt="Adidas" class="brand-logo" />
    <img src="../assets/images/brands/puma.png" alt="Puma" class="brand-logo" />
    <img src="../assets/images/brands/reebok.png" alt="Reebok" class="brand-logo" />
    <img src="../assets/images/brands/newbalance.png" alt="New Balance" class="brand-logo" />
  </div>
</section>

<?php
// Top Rated Products Section
$conn2 = new mysqli('localhost', 'root', '', 'sportify_db');
if ($conn2->connect_error) {
    die('Database connection failed: ' . $conn2->connect_error);
}
$sql2 = "SELECT * FROM products ORDER BY rating DESC LIMIT 3";
$result2 = $conn2->query($sql2);
?>

<section class="featured-products top-rated-products">
  <h2 class="section-title">Top Rated Products</h2>
  <div class="products-grid">
    <?php while($row = $result2->fetch_assoc()): ?>
      <div class="product-card" data-product-id="<?php echo $row['id']; ?>" data-product-image="<?php echo htmlspecialchars($row['image_url']); ?>">
    <script src="../assets/js/cart.js"></script>
    <script src="../assets/js/wishlist.js"></script>
</body>
        <div style="position: relative;">
          <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img">
          <span class="product-badge"><?php echo htmlspecialchars($row['badge']); ?></span>
        </div>
        <div class="product-info">
          <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>

          <?php
            $usd_to_npr = 133;
            $price_npr = isset($row['price']) ? $row['price'] * $usd_to_npr : 0;
            $original_price_npr = (isset($row['original_price']) && $row['original_price'] > $row['price']) ? $row['original_price'] * $usd_to_npr : null;
          ?>
          <div class="product-pricing">
            <span class="product-price">रु<?php echo number_format($price_npr); ?></span>
            <?php if ($original_price_npr): ?>
              <span class="product-original-price">रु<?php echo number_format($original_price_npr); ?></span>
            <?php endif; ?>
          </div>
          <span class="product-rating">⭐ <?php echo number_format($row['rating'], 2); ?></span>
        </div>
        <div class="product-actions">
          <button class="wishlist-btn" title="Add to Wishlist" aria-label="Add to Wishlist"><i class="fa fa-heart"></i></button>
          <button class="add-cart-btn">Add to Cart</button>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php $conn2->close(); ?>

<!-- Featured Clubs Section -->


<section class="clubs-banner">
  <h2 class="section-title">Top Clubs</h2>
  <div class="clubs-banner-row">
    <img src="../assets/images/clubs/barcelona.png" alt="FC Barcelona" class="club-logo" />
    <img src="../assets/images/clubs/realmadrid.png" alt="Real Madrid" class="club-logo" />
    <img src="../assets/images/clubs/manutd.png" alt="Manchester United" class="club-logo" />
    <img src="../assets/images/clubs/chelsea.png" alt="Chelsea" class="club-logo" />
    <img src="../assets/images/clubs/acmilan.png" alt="AC Milan" class="club-logo" />
    <!-- Repeat as needed to fill the row -->
  </div>
</section>


<!-- Contact Us Section -->
<section class="contact-section" id="contact">
  <h2 class="section-title">Contact Us</h2>
  <p class="contact-desc">Get in touch with our team for any questions or support</p>
  <div class="contact-cards">
    <div class="contact-card">
      <div class="contact-icon" style="color: var(--ctp-red);"><i class="fa fa-map-marker-alt"></i></div>
      <h3 class="contact-label">Address</h3>
      <p class="contact-info">Sportify Avenue<br>Kathmandu Nepal</p>
    </div>
    <div class="contact-card">
      <div class="contact-icon" style="color: var(--ctp-red);"><i class="fa fa-phone"></i></div>
      <h3 class="contact-label">Phone</h3>
      <p class="contact-info">+01 4016712</p>
    </div>
    <div class="contact-card">
      <div class="contact-icon" style="color: var(--ctp-red);"><i class="fa fa-envelope"></i></div>
      <h3 class="contact-label">Email</h3>
      <p class="contact-info">support@sportify.com</p>
    </div>
  </div>
</section>

    <?php include '../includes/footer.php'; ?>

</body>
</html>