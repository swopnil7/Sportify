// Unified Add to Cart handler for all product cards (index, shop, etc.)
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.add-cart-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const card = btn.closest('.product-card');
      const productId = card ? card.getAttribute('data-product-id') : btn.getAttribute('data-product-id');
      if (!productId) {
        alert('Product not found.');
        return;
      }
      btn.disabled = true;
      btn.textContent = 'Adding...';
      // Always use the correct relative path from both index.php and shop.php
      fetch('../src/add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json'
        },
        body: `product_id=${encodeURIComponent(productId)}&quantity=1`
      })
      .then(async res => {
        let data;
        try {
          data = await res.json();
        } catch (err) {
          alert('Server error: Invalid JSON response.');
          btn.textContent = 'Add to Cart';
          btn.disabled = false;
          return;
        }
        if (data.success) {
          btn.textContent = 'Added!';
          btn.classList.add('added');
          setTimeout(() => {
            btn.textContent = 'Add to Cart';
            btn.classList.remove('added');
            btn.disabled = false;
          }, 1200);
        } else if (data.error === 'not_logged_in') {
          alert('Please log in to add to cart.');
          window.location.href = 'register.php';
        } else {
          alert('Add to cart error: ' + (data.error || 'Unknown error'));
          if (window.console) console.error('Add to cart error:', data);
          btn.textContent = 'Add to Cart';
          btn.disabled = false;
        }
      })
      .catch(err => {
        alert('Network error.');
        if (window.console) console.error('Fetch error:', err);
        btn.textContent = 'Add to Cart';
        btn.disabled = false;
      });
    });
  });
});
