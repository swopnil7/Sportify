<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

function get_db_conn() {
    global $conn;
    if (!$conn) {
        die('Database connection failed.');
    }
    return $conn;
}

function user_exists($email) {
    $conn = get_db_conn();
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function register_user($first_name, $last_name, $email, $phone, $password) {
    $conn = get_db_conn();
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, phone, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
    $stmt->bind_param('sssss', $first_name, $last_name, $email, $phone, $hashed);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function login_user($email, $password) {
    $conn = get_db_conn();
    $stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $stmt->close();
            return true;
        }
    }
    $stmt->close();
    return false;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function logout_user() {
    session_unset();
    session_destroy();
}
