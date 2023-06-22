<?php
//Destroy session and save the cart if user is logging out

session_start();
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
session_destroy();
session_start();
$_SESSION['cart'] = $cart;
header("Location: index.php");
?>