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
