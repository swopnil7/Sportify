<!-- Main Header Bar -->
<!-- Main Header Bar -->
<div class="main-header-bar">
  <div class="main-header-left">
    <a href="../src/index.php" class="main-logo">Sportify</a>
  </div>
  <nav class="main-nav">
    <ul>
      <li><a href="../src/index.php" class="active">HOME</a></li>
      <li><a href="../src/shop.php">SHOP</a></li>
      <li class="nav-categories">
        <button class="categories-btn" id="categoriesToggle" aria-haspopup="true" aria-expanded="false">
          CATEGORIES <span class="categories-arrow" id="categoriesArrow">&#9662;</span>
        </button>
        <div class="categories-dropdown" id="categoriesMenu" role="menu">
          <a href="../src/category.php?cat=jerseys" class="dropdown-item">Jerseys</a>
          <a href="../src/category.php?cat=footwear" class="dropdown-item">Footwear</a>
          <a href="../src/category.php?cat=accessories" class="dropdown-item">Accessories</a>
        </div>
      </li>
      <li><a href="#contact">CONTACT</a></li>
    </ul>
  </nav>
  <div class="main-header-actions">
    <button id="searchBtn" class="icon-btn" aria-label="Search"><i class="fa fa-search"></i></button>
    <a href="/my-cart.php" class="icon-btn cart-btn" aria-label="Cart">
      <i class="fa fa-shopping-cart"></i>
      <span class="cart-badge">1</span>
    </a>
  </div>
</div>
<!-- Search Bar Popout -->
<div id="mainSearchBar" class="main-search-bar">
  <input type="text" placeholder="Search products...">
  <button id="searchCloseBtn" class="icon-btn search-close" aria-label="Close"><i class="fa fa-times"></i></button>
</div>
</div>
<script>
// Polished Categories Dropdown Toggle
// (Replaces any previous dropdown script)
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('categoriesToggle');
  const menu = document.getElementById('categoriesMenu');
  const arrow = document.getElementById('categoriesArrow');
  let open = false;
  toggle.addEventListener('click', function(e) {
    e.preventDefault();
    open = !open;
    menu.classList.toggle('show', open);
    arrow.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', open);
  });
  document.addEventListener('click', function(e) {
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      open = false;
      menu.classList.remove('show');
      arrow.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
});
</script>
