<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database connection
include '../config/db.php';
?>
<div class="top-bar">
  <div class="top-bar-links">
    <ul class="top-bar-list">
      <li><a href="../src/account.php"><i class="fa fa-user"></i> My Account</a></li>
      <li><a href="../src/wishlist.php"><i class="fa fa-heart"></i> Wishlist</a></li>
      <li><a href="../src/cart.php"><i class="fa fa-shopping-cart"></i> My Cart</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li>
          <form method="post" action="../src/account.php" style="display:inline;">
            <button type="submit" name="logout" style="background:none;color:inherit;padding:0;font:inherit;border:none;cursor:pointer;display:flex;align-items:center;gap:0.4em;font-size:0.7rem;line-height:1.2;">
              <i class="fa fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </li>
      <?php else: ?>
        <li><a href="../src/register.php"><i class="fa fa-sign-in-alt"></i> Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="top-bar-center">
    <div class="theme-switch-wrapper">
      <icon>
        <img id="toggleDarkIcon" class="fillH" src="../assets/images/icons/moon.svg" alt="Theme icon">
      </icon>
      <label class="theme-switch" for="theme-toggle">
        <input class="toggleDarkInput" type="checkbox" id="theme-toggle">
        <div class="slider round"></div>
      </label>
    </div>
  </div>
  <div class="top-bar-action">
    <a href="#" class="track-order">Track Order</a>
  </div>
</div>
<script src="../assets/js/theme-toggle.js"></script>