document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.add-cart-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const card = btn.closest('.product-card');
      const productId = card.getAttribute('data-product-id');
      if (!productId) {
        alert('Product not found.');
        return;
      }
      fetch('./add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${encodeURIComponent(productId)}&quantity=1`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.textContent = 'Added!';
          btn.classList.add('added');
          setTimeout(() => {
            btn.textContent = 'Add to Cart';
            btn.classList.remove('added');
          }, 1200);
          // Optionally update cart icon/count here
        } else if (data.error === 'not_logged_in') {
          window.location.href = 'register.php';
        } else {
          alert('Could not add to cart.');
          if (window.console) console.error('Add to cart error:', data);
        }
      })
      .catch(err => {
        alert('Network error.');
        if (window.console) console.error('Fetch error:', err);
      });
    });
  });
});
