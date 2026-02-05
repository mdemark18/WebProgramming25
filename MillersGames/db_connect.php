<?php
$host = "sql206.infinityfree.com"; 
$user = "if0_41067191";
$pass = "prAFm3EWjahTor";
$db   = "if0_41067191_millersgames";
$conn = mysqli_connect("$host", "$user", "$pass", "$db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

