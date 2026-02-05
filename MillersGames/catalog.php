<?php
session_start();
require_once 'db_connect.php';

$categoryFilter = $_GET['category'] ?? '';
$filterSql = $categoryFilter ? "WHERE category = ?" : "";
$stmt = $conn->prepare("SELECT product_id, name, price, category, image_path FROM products $filterSql");

if ($categoryFilter) {
    $stmt->bind_param("s", $categoryFilter);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalog - Miller's Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<header class="bg-dark text-white text-center py-3">
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
    <h2 class="text-center mb-4">Browse by Category</h2>

    <form method="get" class="text-center mb-4">
        <label for="category" class="form-label me-2">Choose category:</label>
        <select name="category" id="category" class="form-select d-inline-block w-auto">
            <option value="">All</option>
            <?php
            $cat_query = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
            while ($cat = $cat_query->fetch_assoc()):
                $selected = ($cat['category'] === $categoryFilter) ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($cat['category']) ?>" <?= $selected ?>>
                    <?= htmlspecialchars($cat['category']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-primary ms-2">Filter</button>
    </form>

    <div class="row justify-content-center">
        <?php while($row = $result->fetch_assoc()): ?>
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
        <?php endwhile; ?>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Miller's Games
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

