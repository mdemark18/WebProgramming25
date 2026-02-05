<?php
session_start();
require_once 'db_connect.php';

$product_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Miller's Games</title>
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
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <div class="card p-4 text-center">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" class="img-fluid mb-3" alt="<?= htmlspecialchars($product['name']) ?>">
                <h2 class="card-title"><?= htmlspecialchars($product['name']) ?></h2>
                <p><strong>Description:</strong> <?= htmlspecialchars($product['description'] ?? 'No description available.') ?></p>
                <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                <a href="cart.php?add=<?= $product['product_id'] ?>" class="btn btn-success mt-3">Add to Cart</a>
                <a href="wishlist.php?add=<?= $product['product_id'] ?>" class="btn btn-outline-secondary mt-2">♡ Add to Wishlist</a>
            </div>
        </div>
    </div>

    <h3 class="text-center mb-4">Related Products</h3>
    <div class="row justify-content-center">
        <?php if ($related_products->num_rows > 0): ?>
            <?php while ($related = $related_products->fetch_assoc()): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <a href="product.php?id=<?= $related['product_id'] ?>" class="text-decoration-none text-dark">
                    <div class="card text-center p-3 h-100">
                        <img src="<?= htmlspecialchars($related['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($related['name']) ?>">
                        <h5><?= htmlspecialchars($related['name']) ?></h5>
                        <p class="fw-bold">$<?= number_format($related['price'], 2) ?></p>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No related products found.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


