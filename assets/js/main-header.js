document.addEventListener('DOMContentLoaded', function() {
  const searchBtn = document.getElementById('searchBtn');
  const searchBar = document.getElementById('mainSearchBar');
  const searchClose = document.getElementById('searchCloseBtn');

  if (searchBtn && searchBar) {
    searchBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      if (!searchBar.classList.contains('active')) {
        searchBar.classList.add('active');
        setTimeout(() => {
          const input = searchBar.querySelector('input');
          if (input) input.focus();
        }, 100);
      }
    });
  }
  if (searchClose && searchBar) {
    searchClose.addEventListener('click', function(e) {
      e.stopPropagation();
      searchBar.classList.remove('active');
    });
  }
  // Hide search bar on outside click
  document.addEventListener('mousedown', function(e) {
    if (searchBar.classList.contains('active')) {
      if (!searchBar.contains(e.target) && e.target !== searchBtn) {
        searchBar.classList.remove('active');
      }
    }
  });
  // Hide on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && searchBar.classList.contains('active')) {
      searchBar.classList.remove('active');
    }
  });
});
