<?php
$conn = mysqli_connect("localhost", "root", "", "millersgames");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
