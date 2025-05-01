<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle add/remove actions
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    header("Location: wishlist.php");
    exit;
}

if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    header("Location: wishlist.php");
    exit;
}

// Fetch wishlist items
$stmt = $conn->prepare("
    SELECT p.product_id, p.name, p.price, p.image_path
    FROM wishlist w
    JOIN products p ON w.product_id = p.product_id
    WHERE w.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist - Miller's Games</title>
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
    <h2 class="text-center mb-4">My Wishlist</h2>

    <?php if (empty($items)): ?>
        <div class="alert alert-info text-center">Your wishlist is empty.</div>
    <?php else: ?>
        <div class="row justify-content-center">
            <?php foreach ($items as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center p-3 d-flex flex-column">
                    <a href="product.php?id=<?= $item['product_id'] ?>" class="text-decoration-none text-dark">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="fw-bold">$<?= number_format($item['price'], 2) ?></p>
                    </a>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="product.php?id=<?= $item['product_id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                            <a href="wishlist.php?remove=<?= $item['product_id'] ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                        </div>
                    </div>
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
