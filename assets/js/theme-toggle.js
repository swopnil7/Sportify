document.addEventListener('DOMContentLoaded', function() {
  const themeToggle = document.querySelector('.toggleDarkInput');
  const iconImg = document.getElementById('toggleDarkIcon');
  if (!themeToggle || !iconImg) return;

  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    iconImg.src = theme === 'mocha'
      ? '../assets/images/icons/moon.svg'
      : '../assets/images/icons/sun.svg';
    themeToggle.checked = theme === 'mocha';
  }

  const savedTheme = localStorage.getItem('theme') || 'latte';
  setTheme(savedTheme);

  themeToggle.checked = (savedTheme === 'mocha');

  themeToggle.addEventListener('change', function() {
    const newTheme = themeToggle.checked ? 'mocha' : 'latte';
    setTheme(newTheme);
  });

  document.querySelectorAll('button').forEach(function(btn) {
    btn.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  });
});