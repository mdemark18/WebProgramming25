<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container mt-5"><div class="alert alert-danger text-center">You must be logged in to checkout.</div></div>';
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container mt-5"><div class="alert alert-warning text-center">Your cart is empty.</div></div>';
    exit;
}

// Get and format address fields
$street = trim($_POST['street'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$zip = trim($_POST['zip'] ?? '');
$address = "$street\n$city, $state $zip";

// Validate address
if (empty($street) || empty($city) || empty($state) || empty($zip)) {
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container mt-5"><div class="alert alert-warning text-center">Complete shipping address is required.</div></div>';
    exit;
}

// Calculate total
$total = 0;
$product_prices = [];

$placeholders = implode(',', array_fill(0, count($cart), '?'));
$types = str_repeat('i', count($cart));
$stmt = $conn->prepare("SELECT product_id, price FROM products WHERE product_id IN ($placeholders)");
$stmt->bind_param($types, ...array_keys($cart));
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $product_prices[$row['product_id']] = $row['price'];
    $total += $row['price'] * $cart[$row['product_id']];
}

// Insert order with address
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, address) VALUES (?, ?, ?)");
$stmt->bind_param("ids", $_SESSION['user_id'], $total, $address);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_each) VALUES (?, ?, ?, ?)");
foreach ($cart as $product_id => $qty) {
    $price = $product_prices[$product_id];
    $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
    $stmt->execute();
}

// Clear cart
$_SESSION['cart'] = [];

// Redirect to receipt
header("Location: receipt.php?order_id=$order_id");
exit;
