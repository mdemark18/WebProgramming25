<?php
session_start();
require_once 'db_connect.php';

$query = "SELECT product_id, name, price, category, image_path FROM products ORDER BY RAND() LIMIT 6";
$result = mysqli_query($conn, $query);

$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
$featured = array_slice($products, 0, 3);
$popular = array_slice($products, 3, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Miller's Games</title>
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
    <div class="alert alert-info text-center">
        🎮 New arrivals just dropped — check the catalog for the latest!
    </div>



    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row justify-content-center">
        <?php foreach ($featured as $row): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card text-center p-3 h-100 d-flex flex-column">
                <a href="product.php?id=<?= $row['product_id'] ?>" class="text-decoration-none text-dark">
                    <img src="<?= htmlspecialchars($row['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="fw-bold">$<?= number_format($row['price'], 2) ?></p>
                </a>
                <div class="mt-auto">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="cart.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
                        <a href="wishlist.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-secondary">♡ Wishlist</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <h3 class="text-center mt-5 mb-3">Shop by Category</h3>
    <div class="row justify-content-center mb-4">
        <div class="col-md-3 mb-2"><a href="catalog.php?category=Game" class="btn btn-outline-primary w-100">Games</a></div>
        <div class="col-md-3 mb-2"><a href="catalog.php?category=Accessory" class="btn btn-outline-success w-100">Accessories</a></div>
        <div class="col-md-3 mb-2"><a href="catalog.php?category=Console" class="btn btn-outline-dark w-100">Consoles</a></div>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="text-center mb-5">
        <a href="wishlist.php" class="btn btn-outline-secondary mx-2">Wishlist</a>
        <a href="cart.php" class="btn btn-outline-secondary mx-2">View Cart</a>
        <a href="orders.php" class="btn btn-outline-secondary mx-2">Order History</a>
        <a href="profile.php" class="btn btn-outline-secondary mx-2">Change Password</a>
        
    </div>
    <?php endif; ?>

    <h3 class="text-center mt-5 mb-4">Popular Picks</h3>
    <div class="row justify-content-center">
        <?php foreach ($popular as $row): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card text-center p-3 h-100 d-flex flex-column">
                <a href="product.php?id=<?= $row['product_id'] ?>" class="text-decoration-none text-dark">
                    <img src="<?= htmlspecialchars($row['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="fw-bold">$<?= number_format($row['price'], 2) ?></p>
                </a>
                <div class="mt-auto">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="cart.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
                        <a href="wishlist.php?add=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-secondary">♡ Wishlist</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <h3 class="text-center mt-5 mb-3">What Our Customers Say</h3>
    <div class="text-center text-muted mb-4">
        <blockquote>“Fast shipping and great prices. Love this site!”</blockquote>
        <blockquote>“Got my PS5 controller in 2 days. Will order again.”</blockquote>
        <blockquote>“Easy to find what I needed. Checkout was simple and fast.”</blockquote>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
