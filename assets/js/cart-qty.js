document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.cart-qty-controls').forEach(function(ctrl) {
    const input = ctrl.querySelector('.cart-qty-input');
    const up = ctrl.querySelector('.cart-qty-up');
    const down = ctrl.querySelector('.cart-qty-down');
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
