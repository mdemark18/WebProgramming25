<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT order_id, total_price, address FROM orders WHERE user_id = ? ORDER BY order_id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Orders - Miller's Games</title>
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
        </div>
    </nav>
</header>

<main class="container my-5">
    <h2 class="text-center mb-4">Order History</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">You haven't placed any orders yet.</div>
    <?php else: ?>
        <div class="d-flex flex-column align-items-center gap-3">
            <?php foreach ($orders as $order): ?>
            <div class="card w-75">
                <div class="card-body">
                    <h5 class="card-title">Order #<?= htmlspecialchars($order['order_id']) ?></h5>
                    <p class="card-text"><strong>Total:</strong> $<?= number_format($order['total_price'], 2) ?></p>
                    <p class="card-text"><strong>Shipping Address:</strong><br><?= nl2br(htmlspecialchars($order['address'])) ?></p>
                    <a href="receipt.php?order_id=<?= $order['order_id'] ?>" class="btn btn-outline-primary">View Receipt</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
