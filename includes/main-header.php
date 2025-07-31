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
          <button class="dropdown-item category-link" data-category="Clothing" type="button">Clothing</button>
          <button class="dropdown-item category-link" data-category="Footwear" type="button">Footwear</button>
          <button class="dropdown-item category-link" data-category="Accessories" type="button">Accessories</button>
        </div>
      </li>
      <li><a href="../src/index.php#contact">CONTACT</a></li>
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
  <form id="mainSearchForm" action="../src/shop.php" method="get" style="display: flex; align-items: center; width: 100%;">
    <input type="text" id="mainSearchInput" name="search" placeholder="Search products..." autocomplete="off" style="flex:1;">
    <button type="submit" style="display:none"></button>
    <button id="searchCloseBtn" class="icon-btn search-close" aria-label="Close" type="button"><i class="fa fa-times"></i></button>
  </form>
</div>
</div>
<script src="../assets/js/main-header.js"></script>
<script>

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

  // Search bar submit logic
  const searchForm = document.getElementById('mainSearchForm');
  const searchInput = document.getElementById('mainSearchInput');
  if (searchForm && searchInput) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const val = searchInput.value.trim();
      if (val.length > 0) {
        window.location.href = '../src/shop.php?search=' + encodeURIComponent(val);
      }
    });
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchForm.dispatchEvent(new Event('submit'));
      }
    });
  }

  document.querySelectorAll('.category-link').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const cat = btn.getAttribute('data-category');
      if (cat) {
        window.location.href = '../src/shop.php?category=' + encodeURIComponent(cat);
      }
    });
  });
});
</script>
