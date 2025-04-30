<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart and remove from wishlist if applicable
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;

    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
        $stmt->execute();
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
}

$cart_items = $_SESSION['cart'];
$product_data = [];

if (!empty($cart_items)) {
    $placeholders = implode(',', array_fill(0, count($cart_items), '?'));
    $types = str_repeat('i', count($cart_items));
    $stmt = $conn->prepare("SELECT product_id, name, price FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param($types, ...array_keys($cart_items));
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $product_data[$row['product_id']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - Miller's Games</title>
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
    <h2 class="text-center mb-4">Your Cart</h2>
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-warning text-center">Your cart is empty.</div>
    <?php else: ?>
        <form action="checkout_address.php" method="post">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th><th>Remove</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($cart_items as $id => $qty): 
                            $product = $product_data[$id];
                            $subtotal = $product['price'] * $qty;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= $qty ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                            <td><a href="cart.php?remove=<?= $id ?>" class="btn btn-sm btn-danger">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="fw-bold">
                            <td colspan="3">Total</td>
                            <td>$<?= number_format($total, 2) ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success mt-3">Checkout</button>
            </div>
        </form>
    <?php endif; ?>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
