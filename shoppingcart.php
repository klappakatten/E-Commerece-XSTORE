<?php
session_start();
//Shoppingcartpag - handle and verify customers purchase order

//Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header("Location: login.php");
}

$paypal_url = '';
$paypal_email = "";

$DELIVERYFEE = 49;
$addedItems = array();
$sum = 0;

include("connectdb.php");

$html = file_get_contents("templates/shoppingcart.html");

$split = explode("<!--item-->", $html);

$start = $split[0];
$item = $split[1]; //Item template
$end = $split[2];

//Set cart variable
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
} else {
    $cart = array();
}

//Handle GET requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //Remove item from cart
    if (isset($_GET['removeID'])) {
        $removeID = $_GET['removeID'];
        if (in_array($removeID, $cart)) {
            $cart = array_diff($cart, array($removeID));
            $_SESSION['cart'] = $cart;
        }
    }
}

//Add new item article to cart
if (count($cart) > 0) {
    foreach ($cart as $itemID) {
        $newItem = $item;

        if (in_array($itemID, $addedItems)) {
            continue;
        } else {
            array_push($addedItems, $itemID);
        }

        //Get item from database
        $row = getUserByID($itemID);
        $id = $row->id;
        $name = $row->name;
        $quantity = $row->quantity;
        $price = $row->price;

        $newItem = str_replace("NAME", $name, $newItem);
        $newItem = str_replace("999", $quantity, $newItem);
        $newItem = str_replace("PRICE", $price, $newItem);
        $newItem = str_replace("ITEMID", $id, $newItem);

        //Prevent duplicate items and find correct quantity
        $cartQuantity = 0;
        foreach ($cart as $i) {
            if ($i == $itemID && $cartQuantity < $quantity) {
                $cartQuantity++;
            }
        }
        //INCREMENT quantity of items in cart
        if (isset($_GET['plus']) && $_GET['plus'] == true && $cartQuantity < $quantity && $id == $_GET['itemNo']) {
            $cartQuantity++;
            array_push($cart, $_GET['itemNo']);
            $_SESSION['cart'] = $cart;
        } else if (isset($_GET['minus']) && $_GET['minus'] == true && $cartQuantity > 1 && $id == $_GET['itemNo']) {
            $cartQuantity--;
            $cart = array_reverse($cart);
            foreach ($cart as $key => $itm) {
                if ($itm == $_GET['itemNo']) {
                    unset($cart[$key]);
                    $cart = array_reverse($cart);
                    break;
                }
            }
            $_SESSION['cart'] = $cart;
        }
        $newItem = str_replace("998", $cartQuantity, $newItem);

        $start .= $newItem;

        $sum += $price * $cartQuantity;
    }

    //Replace placeholders with cart sales information
    $end = str_replace("TOTALTAX", ($sum - ($sum / 1.25)), $end);
    $end = str_replace("TOTALSUM", $sum + $DELIVERYFEE, $end);
    $end = str_replace("SUM", ($sum / 1.25), $end);
    $end = str_replace("DELIVERYFEE", $DELIVERYFEE, $end);

} else {
    //Handle if cart is emptry
    $start = str_replace("<!--EMPTY1-->", 'Cart is currently empty', $start);
    $start = str_replace("<!--EMPTY2-->", 'Add some items to cart to proceed to checkout', $start);
    $split = explode("<!--SUM-->", $html);
    $end = $split[2];
}

//Dont print delivery form if there are no items in the cart
if (empty($cart)) {
    $split = explode("<!--DELIVERYINFO-->", $end);
    $end = $split[0] . $split[2];
} else {
    $totalRepl = "?total=$sum";
    $end = str_replace("<!--PAYPAL-->", '<a href="paypal.php' . $totalRepl . '"><img
    src="https://www.paypalobjects.com/digitalassets/c/website/marketing/apac/C2/logos-buttons/44_Blue_CheckOut_Pill_Button.png"
    alt="Checkout Button"></a>', $end);

}

echo $start . $end;

?>