  // Add to cart from wishlist
  document.querySelectorAll('.wishlist-cart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const productId = btn.getAttribute('data-product-id');
      if (!productId) return;
      btn.disabled = true;
      let productImage = '';
      const card = btn.closest('.wishlist-card');
      if (card) {
        const img = card.querySelector('img');
        if (img) {
          productImage = img.getAttribute('src') || '';
        }
      }
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${encodeURIComponent(productId)}&quantity=1&image_url=${encodeURIComponent(productImage)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.innerHTML = '<i class="fa fa-check"></i>';
          setTimeout(() => {
            btn.innerHTML = '<i class="fa fa-shopping-cart"></i>';
            btn.disabled = false;
          }, 1200);
        } else if (data.error === 'not_logged_in') {
          window.location.href = 'register.php';
        } else {
          alert('Could not add to cart.');
          btn.disabled = false;
        }
      })
      .catch(() => {
        alert('Network error.');
        btn.disabled = false;
      });
    });
  });
document.addEventListener('DOMContentLoaded', function() {
  // Wishlist toggle
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const card = btn.closest('.product-card');
      const productId = card.getAttribute('data-product-id');
      if (!productId) return;
      const checked = btn.classList.toggle('active');
      fetch('wishlist_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${encodeURIComponent(productId)}&action=${checked ? 'add' : 'remove'}`
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          btn.classList.toggle('active'); // revert
        }
      });
    });
  });

  // Remove from wishlist page
  document.querySelectorAll('.wishlist-remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const wishlistId = btn.getAttribute('data-wishlist-id');
      if (!wishlistId) return;
      fetch('wishlist_remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `wishlist_id=${encodeURIComponent(wishlistId)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.closest('.wishlist-card').remove();
        }
      });
    });
  });
});
