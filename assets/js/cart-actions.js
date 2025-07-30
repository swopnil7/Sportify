document.addEventListener('DOMContentLoaded', function() {
  // Remove item
  document.querySelectorAll('.cart-remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const cartItemId = btn.getAttribute('data-cart-id');
      if (!cartItemId) return;
      fetch('remove_cart_item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart_item_id=${encodeURIComponent(cartItemId)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          alert('Could not remove item.');
        }
      })
      .catch(() => alert('Network error.'));
    });
  });

  // Clear cart
  const clearBtn = document.querySelector('.clear-cart-btn');
  if (clearBtn) {
    clearBtn.addEventListener('click', function() {
      if (!confirm('Are you sure you want to clear your cart?')) return;
      fetch('clear_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          alert('Could not clear cart.');
        }
      })
      .catch(() => alert('Network error.'));
    });
  }

  // Quantity +/-
  document.querySelectorAll('.cart-qty-controls').forEach(ctrl => {
    const input = ctrl.querySelector('.cart-qty-input');
    const up = ctrl.querySelector('.cart-qty-increase');
    const down = ctrl.querySelector('.cart-qty-decrease');
    const row = ctrl.closest('tr');
    const cartItemId = row.getAttribute('data-cart-id');
    up.addEventListener('click', function() {
      updateCartQty(cartItemId, parseInt(input.value) + 1, input);
    });
    down.addEventListener('click', function() {
      if (parseInt(input.value) > 1) {
        updateCartQty(cartItemId, parseInt(input.value) - 1, input);
      }
    });
  });
});

function updateCartQty(cartItemId, newQty, inputElem) {
  fetch('update_cart_qty.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `cart_item_id=${encodeURIComponent(cartItemId)}&quantity=${encodeURIComponent(newQty)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      inputElem.value = newQty;
      window.location.reload();
    } else {
      alert('Could not update quantity.');
    }
  })
  .catch(() => alert('Network error.'));
}
