<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;

require_once 'vendor/autoload.php';

session_start();

if (!isset($_GET['paymentId'])) {
    header("location:index.php");
}

$html = file_get_contents('templates/paypalsuccess.html');

// Set up PayPal API context
$clientId = '';
$clientSecret = '';
$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

include 'connectdb.php';

//Set up sandbox mode
$apiContext->setConfig(array('mode' => 'sandbox'));

// Retrieve the payment ID from the query string
$paymentId = $_GET['paymentId'];

// Use the Payments API to get details about the payment
$payment = Payment::get($paymentId, $apiContext);

// Get the customer's shipping address
$shippingAddress = $payment->getTransactions()[0]->getItemList()->getShippingAddress();

$adress = $shippingAddress->getLine1();
$city = $shippingAddress->getCity();
$state = $shippingAddress->getState();
$zip = $shippingAddress->getPostalCode();
$country = $shippingAddress->getCountryCode();
$email = $payment->getPayer()->getPayerInfo()->getEmail();
$paySum = $payment->getTransactions()[0]->getAmount()->getTotal();
$paymentMethod = $payment->getPayer()->getPaymentMethod();

//Shipping information
$html = str_replace('ADRESS', $adress, $html);
$html = str_replace('CITY', $city, $html);
$html = str_replace('STATE', $state, $html);
$html = str_replace('ZIP', $zip, $html);
$html = str_replace('COUNTRY', $country, $html);
$html = str_replace('EMAIL', $email, $html);

//Payment information
$html = str_replace('TOTAL', $paySum, $html);
$html = str_replace('PAYMETHOD', $paymentMethod, $html);

$cart = $_SESSION['cart'];

//Insert new order to database
$email = $_SESSION['email'];

try {
    //Make sure transaction is successful or else rollback the transaction
    $conn->begin_transaction();

    $result = getUserByEmail($email);
    $userID = $result->fetch_object()->user_id;

    //Add order to database
    addOrder($paySum, $userID);

    //Get added ID
    $orderID = getAddedOrderId();
    //Save quantity information in map
    $map = array();
    foreach ($cart as $productID) {
        if (!isset($map[$productID])) {
            $map[$productID] = 1;
        } else {
            $map[$productID] = $map[$productID] + 1;
        }
    }
    //Commit transaction on success
    $conn->commit();
} catch (Exception $e) {
    error_reporting(0);
    //Rollback transaction on failure
    $conn->rollback();

    foreach ($map as $key => $value) {
        $itm .= $key . '-' . $map[$key] . ';';
    }

    mail("kleptokatten@gmail.com", "ERROR on ORDER by customer: " . $email . 'PaymentID = ' . $paymentId . " Total: " . $paySum . "Paymentmethod: " . $paymentMethod . "Items: " . $itm, 'kleptokatten@gmail.com');
    header("Refresh: 5; URL=paypalfail.php");
}
$productPrice = 0;

//Insert order details into database
foreach ($map as $key => $value) {
    addOrderDetails($map, $key, $orderID);
}



//Format email content
$emailString = "Dear customer,
Thank you for your purchase!

You have ordered:

";
foreach ($cart as $itemID) {
    $result = getProductById($itemID);
    $row = $result->fetch_object();
    $name = $row->name;
    $price = $row->price;
    $quantity = $row->quantity;

    //Decrease stock by one if there is stock available
    if ($quantity > 0) {
        updateProductQuantity($quantity - 1, $itemID);
    }
    $emailString .= $name;
    $emailString .= ": $price kr";
    $emailString .= " ";
}
$emailString . "to the total sum of: $paySum .
" . " " . "
The order will be sent to:
$adress.
$zip.
$city.
$country
";


//Send confirmation email
if (mail($email, "Thank you for choosing XSTORE!", nl2br($emailString), 'kleptokatten@gmail.com')) {
    //SUCCESS
} else {
    //FAILURE
}

//Remove items from the cart
unset($_SESSION['cart']);

echo $html;

header("Refresh: 15; URL=index.php");

?>