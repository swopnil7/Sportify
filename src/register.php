<?php
session_start();
require_once '../config/db.php';
require_once '../config/auth.php';
$login_error = '';
$register_error = '';
$register_success = '';

// Handle Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];
    if ($email && $password) {
        if (login_user($email, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $login_error = 'Invalid credentials.';
        }
    } else {
        $login_error = 'Please fill in all fields.';
    }
}

// Handle Register
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $first_name = trim($_POST['register_name']);
    $last_name = trim($_POST['register_lname']);
    $email = trim($_POST['register_email']);
    $phone = trim($_POST['register_phone']);
    $password = $_POST['register_password'];
    $confirm = $_POST['register_confirm_password'];
    if ($first_name && $email && $password && $confirm) {
        if ($password !== $confirm) {
            $register_error = 'Passwords do not match.';
        } else if (user_exists($email)) {
            $register_error = 'Email already registered.';
        } else {
            if (register_user($first_name, $last_name, $email, $phone, $password)) {
                $register_success = 'Registration successful! <a href="#" onclick="switchTab(\'login\')">Login here</a>.';
            } else {
                $register_error = 'Registration failed. Try again.';
            }
        }
    } else {
        $register_error = 'Please fill in all fields.';
    }
}

// Redirect to index if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Register - Sportify</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
// Password strength logic for register form
document.addEventListener('DOMContentLoaded', function() {
  // Force clear all password fields on load and after a short delay (for stubborn autofill)
  function clearPasswords() {
    var pwdFields = document.querySelectorAll('input[type="password"]');
    pwdFields.forEach(function(field) { field.value = ''; });
  }
  clearPasswords();
  setTimeout(clearPasswords, 100); // Try again after 100ms for autofill
  setTimeout(clearPasswords, 500); // Try again after 500ms for stubborn browsers

  // Password strength
  var pwdInput = document.getElementById('register_password');
  var strengthBar = document.querySelector('#register-form .strength-fill');
  var strengthText = document.querySelector('#register-form .strength-text');
  if (pwdInput && strengthBar && strengthText) {
    pwdInput.addEventListener('input', function() {
      var val = pwdInput.value;
      var score = 0;
      if (val.length >= 8) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[a-z]/.test(val)) score++;
      if (/[0-9]/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;
      // Score: 0-1 weak, 2-3 medium, 4-5 strong
      if (val.length === 0) {
        strengthBar.className = 'strength-fill';
        strengthText.textContent = 'Password strength';
      } else if (score <= 2) {
        strengthBar.className = 'strength-fill weak';
        strengthText.textContent = 'Weak';
      } else if (score <= 4) {
        strengthBar.className = 'strength-fill medium';
        strengthText.textContent = 'Medium';
      } else {
        strengthBar.className = 'strength-fill strong';
        strengthText.textContent = 'Strong';
      }
    });
  }

  // Show/hide password toggle for all .password-toggle buttons
  var toggleBtns = document.querySelectorAll('.password-toggle');
  toggleBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var targetId = btn.getAttribute('data-target');
      var input = document.getElementById(targetId);
      if (input) {
        if (input.type === 'password') {
          input.type = 'text';
          btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
        } else {
          input.type = 'password';
          btn.innerHTML = '<i class="fa-solid fa-eye"></i>';
        }
      }
    });
  });
});
</script>
    <!-- Styles moved to style.css and theme.css -->
    <script>
        function switchTab(tab) {
            document.getElementById('login-tab').classList.remove('active');
            document.getElementById('register-tab').classList.remove('active');
            document.getElementById('login-form').classList.remove('active');
            document.getElementById('register-form').classList.remove('active');
            if (tab === 'login') {
                document.getElementById('login-tab').classList.add('active');
                document.getElementById('login-form').classList.add('active');
            } else {
                document.getElementById('register-tab').classList.add('active');
                document.getElementById('register-form').classList.add('active');
            }
        }
        window.onload = function() {
            // Default to login tab unless registration was just successful
            var regSuccess = <?php echo $register_success ? 'true' : 'false'; ?>;
            if (regSuccess) {
                switchTab('login');
            } else {
                switchTab('<?php echo (isset($_POST['action']) && $_POST['action'] === 'register') ? 'register' : 'login'; ?>');
            }
        };
    </script>
</head>
<body>
    <?php include '../includes/top-header.php'; ?>
    <?php include '../includes/main-header.php'; ?>
    <main>
      <section class="auth-section">
        <div class="auth-container">
          <div class="auth-tabs">
            <button id="login-tab" class="auth-tab active" type="button" onclick="switchTab('login')"><i class="fa-solid fa-sign-in-alt"></i> Login</button>
            <button id="register-tab" class="auth-tab" type="button" onclick="switchTab('register')"><i class="fa-solid fa-user-plus"></i> Sign Up</button>
          </div>
          <!-- Login Form -->
          <div id="login-form" class="auth-form-container login-form active">
            <div class="auth-header">
              <h2>Welcome Back!</h2>
              <p>Sign in to your account to continue shopping</p>
            </div>
            <?php if ($login_error): ?>
              <div class="auth-error"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form class="auth-form" method="post" autocomplete="on">
              <input type="hidden" name="action" value="login">
              <div class="form-group">
                <label for="login_email">Email Address</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" id="login_email" name="login_email" placeholder="Enter your email" required autocomplete="username">
                </div>
              </div>
              <div class="form-group">
                <label for="login_password">Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="login_password" name="login_password" placeholder="Enter your password" required autocomplete="off" autocorrect="off" spellcheck="false">
                  <button type="button" class="password-toggle" data-target="login_password"><i class="fa-solid fa-eye"></i></button>
                </div>
              </div>
              <div class="form-options">
                <label class="checkbox-container">
                  <input type="checkbox" name="remember">
                  <span class="checkmark"></span>
                  Remember me
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
              </div>
              <button type="submit" class="auth-btn"><i class="fa-solid fa-sign-in-alt"></i> Sign In</button>
            </form>
            <div class="auth-divider"><span>Or continue with</span></div>
            <div class="social-login">
              <button type="button" class="social-btn google"><i class="fa-brands fa-google"></i> Google</button>
              <button type="button" class="social-btn facebook"><i class="fa-brands fa-facebook-f"></i> Facebook</button>
            </div>
          </div>
          <!-- Register Form -->
          <div id="register-form" class="auth-form-container register-form">
            <div class="auth-header">
              <h2>Create Account</h2>
              <p>Join Sportify and get access to exclusive deals</p>
            </div>
            <?php if ($register_error): ?>
              <div class="auth-error"><?php echo $register_error; ?></div>
            <?php endif; ?>
            <?php if ($register_success): ?>
              <div class="auth-success"><?php echo $register_success; ?></div>
            <?php endif; ?>
            <form class="auth-form" method="post" autocomplete="on">
              <input type="hidden" name="action" value="register">
              <div class="form-row">
                <div class="form-group">
                  <label for="register_name">First Name</label>
                  <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="register_name" name="register_name" placeholder="First Name" required autocomplete="given-name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="register_lname">Last Name</label>
                  <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="register_lname" name="register_lname" placeholder="Last Name" autocomplete="family-name">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="register_email">Email Address</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" id="register_email" name="register_email" placeholder="Email Address" required autocomplete="email">
                </div>
              </div>
              <div class="form-group">
                <label for="register_phone">Phone Number</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-phone"></i>
                  <input type="tel" id="register_phone" name="register_phone" placeholder="Phone Number" autocomplete="tel">
                </div>
              </div>
              <div class="form-group">
                <label for="register_password">Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="register_password" name="register_password" placeholder="Password" required autocomplete="new-password" autocorrect="off" spellcheck="false">
                  <button type="button" class="password-toggle" data-target="register_password"><i class="fa-solid fa-eye"></i></button>
                </div>
                <div class="password-strength">
                  <div class="strength-bar"><div class="strength-fill"></div></div>
                  <span class="strength-text">Password strength</span>
                </div>
              </div>
              <div class="form-group">
                <label for="register_confirm_password">Confirm Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="register_confirm_password" name="register_confirm_password" placeholder="Confirm Password" required autocomplete="new-password" autocorrect="off" spellcheck="false">
                  <button type="button" class="password-toggle" data-target="register_confirm_password"><i class="fa-solid fa-eye"></i></button>
                </div>
              </div>
              <button type="submit" class="auth-btn"><i class="fa-solid fa-user-plus"></i> Create Account</button>
            </form>
            <div class="auth-divider"><span>Or sign up with</span></div>
            <div class="social-login">
              <button type="button" class="social-btn google"><i class="fa-brands fa-google"></i> Google</button>
              <button type="button" class="social-btn facebook"><i class="fa-brands fa-facebook-f"></i> Facebook</button>
            </div>
