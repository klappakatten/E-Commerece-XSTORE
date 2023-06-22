<?php
//Connect to the database
$server = "";
$username = "";
$password = "";
$db = "";

try {
    $conn = mysqli_connect($server, $username, $password, $db);
    //SUCCESS
} catch (Exception $e) {
    //Error code 17 => failed to connect to database
    echo file_get_contents("templates/dbfail.html");
    exit();
}

//Get user from DB
function getUserByEmail($email)
{
    global $conn;
    $query = "SELECT * FROM users WHERE email = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    return $statement->get_result();
}

function getUserByID($itemID)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM product WHERE id = $itemID ORDER BY name");
    return $result->fetch_object();
}

function addUserToDB($email, $hashedPassword, $fname, $lname, $adress, $postcode, $country, $city, $phone, $admin)
{
    global $conn;
    $statement = $conn->prepare('INSERT INTO users (email, password, fname, lname, adress, postcode, country, city, phone, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $statement->bind_param("sssssissii", $email, $hashedPassword, $fname, $lname, $adress, $postcode, $country, $city, $phone, $admin);
    $statement->execute();
}

function updateUserPassword($newpassword, $email)
{
    global $conn;
    $statement = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $statement->bind_param("ss", $newpassword, $email);
    $statement->execute();
}

function updateUserAdress($newAdress, $email)
{
    global $conn;
    $statement = $conn->prepare("UPDATE users SET adress = ? WHERE email = ?");
    $statement->bind_param("ss", $newAdress, $email);
    $statement->execute();
}

function updatePostcode($newPostcode, $email)
{
    global $conn;
    $statement = $conn->prepare("UPDATE users SET postcode = ? WHERE email = ?");
    $statement->bind_param("ss", $newPostcode, $email);
    $statement->execute();
}

function updateUserCity($newCity, $email)
{
    global $conn;
    $statement = $conn->prepare("UPDATE users SET city = ? WHERE email = ?");
    $statement->bind_param("ss", $newCity, $email);
    $statement->execute();
}

function updateUserCountry($newCountry, $email)
{
    global $conn;
    $statement = $conn->prepare("UPDATE users SET country = ? WHERE email = ?");
    $statement->bind_param("ss", $newCountry, $email);
    $statement->execute();
}

function addProduct($name, $description, $image, $price, $quantity, $cat)
{
    global $conn;
    //Use prepared statement and add if product is not already in the database
    $sql = "INSERT INTO product (name, description, image, price, quantity, category) SELECT ?, ?, ?, ?, ?, ? WHERE NOT EXISTS (SELECT 1 FROM product WHERE name = ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sssiiss", $name, $description, $image, $price, $quantity, $cat, $name);
    $statement->execute();
    return $statement->affected_rows; //return number of affected rows
}

function getProductById($itemID)
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM product WHERE id = $itemID");
}

function deleteProduct($itemID)
{
    global $conn;
    $sql = "DELETE FROM product WHERE id = ? AND EXISTS(SELECT 1 FROM product WHERE id = ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $itemID, $itemID);
    $statement->execute();
    return $statement->affected_rows; //return number of affected rows
}

function addCategory($category, $name, $description, $image)
{
    global $conn;
    $sql = "INSERT INTO category (name, description, image) SELECT ?, ?, ? WHERE NOT EXISTS (SELECT 1 FROM category WHERE name = ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ssss", $name, $description, $image, $name);
    $statement->execute();
    return $statement->affected_rows; //return number of affected rows
}

function getCategories()
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM category ORDER BY name");
}

function getCategoriesByName($category)
{
    global $conn;
    $query = "SELECT * FROM product WHERE category = ? ORDER BY name";
    $statement = $conn->prepare($query);
    $statement->bind_param('s', $category);
    $statement->execute();
    return $statement->get_result();
}

function getOrders()
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM orders");
}

function getPromotedProducts()
{
    global $conn;
    $query = "SELECT * FROM product WHERE id IN (SELECT promotedid FROM promotedproduct)";
    $result = $conn->query($query);
    return $result;
}

function updateProductQuantity($quantity, $itemID)
{
    global $conn;
    mysqli_query($conn, "UPDATE product SET quantity = $quantity WHERE id=$itemID");
}

function addOrder($paySum, $userID)
{
    global $conn;
    mysqli_query($conn, "INSERT INTO orders(date, total, user_id) VALUES (NOW(), $paySum, $userID)");
}

function getAddedOrderId()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_id DESC LIMIT 1");
    return $result->fetch_object()->order_id;
}

function addOrderDetails($map, $key, $orderId)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT price FROM product WHERE id = $key");
    $productPrice = $result->fetch_object()->price;
    $statement = mysqli_prepare($conn, "INSERT INTO orderdetails(product_id, product_qty, product_price, order_id, subtotal) VALUES(?,?,?,?,?)");
    $subTot = $productPrice * $map[$key];
    $statement->bind_param('iiiii', $key, $map[$key], $productPrice, $orderId, $subTot);
    $statement->execute();
}

?>