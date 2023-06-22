<?php
//Adminpage - Display sales, add new category/item, remove items.

session_start();

// Deny and redirect if user is not adminisrator 
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
    header("Location: index.php");
}

include("connectdb.php");

$html = file_get_contents("templates/admin.html");

$split = explode("<!--category-->", $html);

$start = $split[0];
$option = $split[1];
$end = $split[2];

//Sales
$now = new DateTime();
$week = new DateTime('-7 days');
$month = new DateTime('-30 days');
$totalSales = 0;
$salesToday = 0;
$salesWeek = 0;
$salesMonth = 0;

//query orders
$result = getOrders();

foreach ($result as $order) {
    $totalSales += $order['total'];
    $orderDate = new DateTime($order['date']);

    //Sales today
    if ($now->format("Y-m-d") == $order['date']) {
        $salesToday += $order['total'];
    }

    //Sales last 7 days
    if ($orderDate >= $week && $orderDate <= $now) {
        $salesWeek += $order['total'];
    }
    //Sales last 30 days
    if ($orderDate >= $month && $orderDate <= $now) {
        $salesMonth += $order['total'];
    }
}

$start = str_replace("SALESDAY", $salesToday, $start);
$start = str_replace("SALESWEEK", $salesWeek, $start);
$start = str_replace("SALESMONTH", $salesMonth, $start);
$start = str_replace("SALESLIFE", $totalSales, $start);

//Fill Category options from database
$result = getCategories();
foreach ($result as $category) {
    $newOption = str_replace("CATEGORYNAME", $category['name'], $option);
    $start .= $newOption;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['categoryname'])) {

        $name = $_POST['categoryname'];

        if (!empty($_POST['categoryimage'])) {
            $image = $_POST['categoryimage'];
        } else {
            $image = 'icons/defaulticon.png';
        }
        if (!empty($_POST['categorydescription'])) {
            $description = $_POST['categorydescription'];
        } else {
            $description = null;
        }

        //Add category if not exists
        $numRows = addCategory($category, $name, $description, $image);

        //Feedback on success or fail
        if ($numRows > 0) {
            $start = str_replace('<!--addcategoryfeedback-->', "Added new category to database!", $start);
        } else {
            $start = str_replace('<!--addcategoryfeedback-->', "This category already exists in the database!", $start);
        }

    }

    //Remove items from database if exists
    if (isset($_POST['removeitem'])) {

        $itemID = $_POST['removeitem'];

        //Query database
        try {
            $numRows = deleteProduct($itemID);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        //Feedback on success or fail
        if ($numRows > 0) {
            $end = str_replace('<!--removefeedback-->', "Deleted item from database!", $end);
            $_SESSION['cart'] = null;
        } else {
            $end = str_replace('<!--removefeedback-->', "There was no item with that ID!", $end);
        }
    }

    //Add new product to the database
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['image']) && isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['category'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $cat = $_POST['category'];

        //Use prepared statement and add if product is not already in the database
        $numRows = addProduct($name, $description, $image, $price, $quantity, $cat);

        //Feedback on success or fail
        if (isset($_POST['removeitem']) && !empty($_POST['removeitem'])) {
            if ($numRows > 0) {
                $end = str_replace('<!--removefeedback-->', "Product is already in the database!", $end);
            } else {
                $end = str_replace('<!--removefeedback-->', "Added product to the database!", $end);
            }
        }
    }
}

echo $start . $end;
?>