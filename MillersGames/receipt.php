<?php
session_start();
require_once 'db_connect.php';

$order_id = $_GET['order_id'] ?? 0;

$stmt = $conn->prepare("
    SELECT o.address, p.name, oi.quantity, oi.price_each
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN orders o ON o.order_id = oi.order_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
$address = '';
$total = 0;

while ($row = $result->fetch_assoc()) {
    $address = $row['address'];
    $order_items[] = $row;
    $total += $row['price_each'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?= htmlspecialchars($order_id) ?></title>
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
    <h2 class="text-center mb-4">Receipt - Order #<?= htmlspecialchars($order_id) ?></h2>

    <h4 class="mb-2">Shipping Address</h4>
    <p><?= nl2br(htmlspecialchars($address)) ?></p>

    <div class="table-responsive mt-4">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price Each</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): 
                    $subtotal = $item['quantity'] * $item['price_each'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price_each'], 2) ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="fw-bold">
                    <td colspan="3">Total</td>
                    <td>$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
