<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sportify</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;
        const icon = themeToggle.querySelector('i');
        function setTheme(theme) {
          document.documentElement.setAttribute('data-theme', theme);
          localStorage.setItem('theme', theme);
          icon.className = theme === 'mocha' ? 'fa fa-sun' : 'fa fa-moon';
        }
        // Load saved theme or default to latte
        const savedTheme = localStorage.getItem('theme') || 'latte';
        setTheme(savedTheme);
        themeToggle.addEventListener('click', () => {
          const current = document.documentElement.getAttribute('data-theme');
          setTheme(current === 'mocha' ? 'latte' : 'mocha');
        });
      });
    </script>
</body>
</html>