<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pass = $_POST['old_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!password_verify($old_pass, $user['password'])) {
        $message = '<div class="alert alert-danger text-center">Old password is incorrect.</div>';
    } elseif ($new_pass !== $confirm) {
        $message = '<div class="alert alert-warning text-center">New passwords do not match.</div>';
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed, $_SESSION['user_id']);
        if ($update_stmt->execute()) {
            $message = '<div class="alert alert-success text-center">Password updated successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger text-center">Error updating password.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Miller's Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<header class="bg-dark text-white py-3 text-center">
    <h1>Miller's Games</h1>
    <nav class="d-flex justify-content-between align-items-center mt-2 px-3">
        <div>
            <a href="index.php" class="text-white mx-2">Home</a>
            <a href="catalog.php" class="text-white mx-2">Catalog</a>
            <a href="cart.php" class="text-white mx-2">Cart</a>
        </div>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <a class="text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Hello, <?= htmlspecialchars($_SESSION['first_name']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Change Password</a></li>
                        <li><a class="dropdown-item" href="orders.php">Order History</a></li>
                        <li><a class="dropdown-item" href="wishlist.php">My Wishlist</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-white mx-2">Login</a>
                <a href="register.php" class="text-white mx-2">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container my-5">
    <h2 class="text-center mb-4">Change Password</h2>
    <?= $message ?>
    <form method="post" class="w-50 mx-auto">
        <div class="mb-3">
            <label class="form-label">Old Password:</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm New Password:</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="text-center">
            <button class="btn btn-primary">Change Password</button>
        </div>
    </form>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
