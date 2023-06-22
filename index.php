<?php

//Main page - displays shop items stored in the database and handles adding items to the cart.

include 'connectdb.php';
session_start();


$html = file_get_contents("templates/home.html");

$split = explode("<!--nav-->", $html);

$start = $split[0];
$nav = $split[1];
$end = $split[2];

$split = explode("<!--item-->", $end);

$middle = $split[0];
$item = $split[1];
$end = $split[2];

//Handle cart items array
if (!empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart']; // Get the existing cart from session
    $start = str_replace("TEMPCART", "carthasitems", $start);
} else {
    $cart = array(); // Create a new empty cart
}

//Check if user is logged in and handle the links to other pages
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $start = str_replace("LOGINLINK", "account.php", $start);
    $start = str_replace("TEMPCLASS", "success", $start);
} else {
    $start = str_replace("LOGINLINK", "login.php", $start);
}

//Add categores to the navigation menu
if ($result = getCategories()) {
    while ($obj = $result->fetch_object()) {
        $categoryName = $obj->name;
        $imgsrc = $obj->image;
        $newItem = $nav;
        $newItem = str_replace("CATEGORYNAME", $categoryName, $newItem);
        $newItem = str_replace("IMGSRC", $imgsrc, $newItem);
        $start .= $newItem;
    }
}

//Get all products found in selected category
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['category'])) {
    $category = htmlspecialchars($_GET['category']);
    $middle = str_replace("Products", $category, $middle);
    $result = getCategoriesByName($category);

    //Display message if there are no products available in this category
    if ($result->num_rows < 1) {
        $end = str_replace("<!--MISSINGPRODUCT-->", "Sorry! There are no Products in this category", $end);
    }
} else { //Get result from  promotedproducts
    $result = getPromotedProducts();
}

// Add item to the cart if users have clicked the buy button
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    array_push($cart, $_GET['id']);
    $_SESSION['cart'] = $cart;
    $start = str_replace("TEMPCART", "carthasitems", $start);
}

try {
    //Get products from database and display them on page
    while ($obj = $result->fetch_object()) {
        $itemID = $obj->id;
        $name = $obj->name;
        $description = $obj->description;
        $image = $obj->image;
        $price = $obj->price;
        $quantity = $obj->quantity;
        $cat = $obj->category;
        if ($quantity > 0) {
            $newItem = $item;
            $newItem = str_replace("ITEMID", $itemID, $newItem);
            $newItem = str_replace("NAME", $name, $newItem);
            $newItem = str_replace("DESCRIPTION", $description, $newItem);
            $newItem = str_replace("PRICE", $price, $newItem);
            $newItem = str_replace("IMAGE", $image, $newItem);
            $newItem = str_replace("QUANTITY", $quantity, $newItem);
            $newItem = str_replace("CATGORY", $cat, $newItem);
            $middle .= $newItem;
        }
    }
} catch (mysqli_sql_exception $e) {
    $middle .= $e->getMessage();
}

echo $start . $middle . $end;
?>