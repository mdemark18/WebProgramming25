<?php
session_start();
require_once 'db_connect.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $first_name, $email, $password);

    if ($stmt->execute()) {
        $success = "Registration successful. <a href='login.php'>Login here</a>.";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Miller's Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="bg-dark text-white py-3 text-center">
        <h1>Miller's Games</h1>
        <nav class="mt-2">
            <a href="index.php" class="text-white mx-2">Home</a>
            <a href="catalog.php" class="text-white mx-2">Catalog</a>
            <a href="cart.php" class="text-white mx-2">Cart</a>
            <a href="login.php" class="text-white mx-2">Login</a>
            <a href="register.php" class="text-white mx-2">Register</a>
        </nav>
    </header>

    <main class="container my-5">
        <h2 class="text-center mb-4">Register</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center"><?= $success ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="w-50 mx-auto">
            <div class="mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="text-center">
                <button class="btn btn-primary">Register</button>
            </div>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        &copy; 2025 Miller's Games
    </footer>
</body>
</html>
