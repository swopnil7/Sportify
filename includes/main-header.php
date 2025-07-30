<!-- Main Header Bar -->
<div class="main-header-bar">
  <div class="main-header-left">
    <a href="../src/index.php" class="main-logo">Sportify</a>
  </div>
  <nav class="main-nav">
    <ul>
      <li><a href="../src/index.php" class="active">HOME</a></li>
      <li><a href="#">SHOP</a></li>
      <li class="dropdown">
        <a href="#">CATEGORIES<span class="dropdown-arrow">&#9662;</span></a>
        <!-- Dropdown can be implemented here if needed -->
      </li>
      <li><a href="#">CONTACT</a></li>
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
