<?php
session_start();
require_once '../config/db.php';
require_once '../config/auth.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$account_error = '';
$account_success = '';

// Fetch user info
global $conn;

$stmt = $conn->prepare('SELECT first_name, last_name, email, phone FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $phone);
$stmt->fetch();
$stmt->close();
// If phone is null or '0', display as empty
if (empty($phone) || $phone === '0') {
    $phone = '';
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_fname = trim($_POST['first_name']);
        $new_lname = trim($_POST['last_name']);
        $new_email = trim($_POST['email']);
        $new_phone = trim($_POST['phone']);
        if ($new_fname && $new_email) {
            // Check if email is taken by another user
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $stmt->bind_param('si', $new_email, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $account_error = 'Email already in use.';
            } else {
                // If phone is empty, store as NULL
                if ($new_phone === '' || $new_phone === '0') {
                    $stmt2 = $conn->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=NULL WHERE id=?');
                    $stmt2->bind_param('sssi', $new_fname, $new_lname, $new_email, $user_id);
                } else {
                    $stmt2 = $conn->prepare('UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?');
                    $stmt2->bind_param('ssssi', $new_fname, $new_lname, $new_email, $new_phone, $user_id);
                }
                if ($stmt2->execute()) {
                    $account_success = 'Profile updated successfully.';
                    $first_name = $new_fname;
                    $last_name = $new_lname;
                    $email = $new_email;
                    $phone = $new_phone === '' || $new_phone === '0' ? '' : $new_phone;
                } else {
                    $account_error = 'Failed to update profile.';
                }
                $stmt2->close();
            }
            $stmt->close();
        } else {
            $account_error = 'First name and email are required.';
        }
    }
    // Handle password change
    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        if ($current && $new && $confirm) {
            if ($new !== $confirm) {
                $account_error = 'New passwords do not match.';
            } else if (!verify_user_password($user_id, $current)) {
                $account_error = 'Current password is incorrect.';
            } else {
                if (update_user_password($user_id, $new)) {
                    $account_success = 'Password changed successfully.';
                } else {
                    $account_error = 'Failed to change password.';
                }
            }
        } else {
            $account_error = 'Please fill in all password fields.';
        }
    }
    // Handle logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: register.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - Sportify</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/top-header.php'; ?>
    <?php include '../includes/main-header.php'; ?>


    <main>
      <div style="max-width: 1000px; margin: 0 auto; padding: 2.5rem 0 2rem 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <h2 style="font-size:2.2rem; font-weight:800; margin-bottom:0.2em; text-align:center; letter-spacing:0.01em;">My Account</h2>
        <p style="text-align:center; color:var(--ctp-subtext1,#a6adc8); font-size:1.08rem; margin-bottom:2.2rem; margin-top:-0.5em;">Manage your profile and security settings</p>
        <?php if ($account_error): ?>
          <div class="auth-error" style="margin-bottom:1.2em;max-width:420px;text-align:center;"><?php echo $account_error; ?></div>
        <?php endif; ?>
        <?php if ($account_success): ?>
          <div class="auth-success" style="margin-bottom:1.2em;max-width:420px;text-align:center;"><?php echo $account_success; ?></div>
        <?php endif; ?>
        <div style="display:flex;gap:2.5rem;align-items:stretch;justify-content:center;width:100%;flex-wrap:wrap;">
          <div class="account-card" style="flex:1 1 340px;min-width:300px;max-width:400px;background:var(--ctp-surface0,#313244);border-radius:16px;padding:2.2rem 1.5rem 1.5rem 1.5rem;box-shadow:0 2px 16px rgba(0,0,0,0.09);display:flex;flex-direction:column;justify-content:center;">
            <form class="auth-form" method="post" autocomplete="off">
              <h3 class="auth-title" style="font-size:1.22rem;margin-bottom:1.3em;color:var(--ctp-mauve,#b4befe);text-align:left;letter-spacing:0.01em;">Profile Info</h3>
              <div class="form-row">
                <div class="form-group">
                  <label for="first_name">First Name</label>
                  <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required autocomplete="given-name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="last_name">Last Name</label>
                  <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" autocomplete="family-name">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required autocomplete="email">
                </div>
              </div>
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-phone"></i>
                  <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" autocomplete="tel">
                </div>
              </div>
              <button type="submit" name="update_profile" class="auth-btn" style="margin-top:1.2em;width:100%;"><i class="fa-solid fa-save"></i> Save Changes</button>
            </form>
          </div>
          <div class="account-card" style="flex:1 1 340px;min-width:300px;max-width:400px;background:var(--ctp-surface0,#313244);border-radius:16px;padding:2.2rem 1.5rem 1.5rem 1.5rem;box-shadow:0 2px 16px rgba(0,0,0,0.09);display:flex;flex-direction:column;justify-content:center;">
            <form class="auth-form" method="post" autocomplete="off">
              <h3 class="auth-title" style="font-size:1.22rem;margin-bottom:1.3em;color:var(--ctp-mauve,#b4befe);text-align:left;letter-spacing:0.01em;">Change Password</h3>
              <div class="form-group">
                <label for="current_password">Current Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="current_password" name="current_password" placeholder="Current Password" required autocomplete="current-password">
                </div>
              </div>
              <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="new_password" name="new_password" placeholder="New Password" required autocomplete="new-password">
                </div>
              </div>
              <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required autocomplete="new-password">
                </div>
              </div>
              <button type="submit" name="change_password" class="auth-btn" style="margin-top:1.2em;width:100%;"><i class="fa-solid fa-key"></i> Change Password</button>
            </form>
          </div>
        </div>
        <div style="width:100%;display:flex;justify-content:center;margin-top:2.5rem;">
          <form method="post" style="display:inline-block;">
            <button type="submit" name="logout" class="auth-btn" style="background:#f38ba8;color:#fff;width:220px;"><i class="fa-solid fa-sign-out-alt"></i> Logout</button>
          </form>
        </div>
      </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
<?php
// Helper: verify current password
function verify_user_password($user_id, $password) {
    global $conn;
    $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($hash);
    $stmt->fetch();
    $stmt->close();
    return password_verify($password, $hash);
}
// Helper: update password
function update_user_password($user_id, $new_password) {
    global $conn;
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->bind_param('si', $hash, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>
