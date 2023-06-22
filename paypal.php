<?php
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require_once 'vendor/autoload.php';

// Set up PayPal API context
$clientId = '';
$clientSecret = '';
$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

$total = 1.00;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['total'])) {
    $total = $_GET['total'];
}


//Set up sandbox mode and logs
$apiContext->setConfig(
    array(
        'mode' => 'sandbox',
        //'log.LogEnabled' => true,
        //'log.FileName' => 'PayPal.log',
        //'log.LogLevel' => 'FINE'
    )
);

// Set up the payer
$payer = new Payer();
$payer->setPaymentMethod('paypal');

// Set up the amount
$amount = new Amount();
$amount->setCurrency('SEK')
    ->setTotal(strval($total));

// Set up the transaction
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setDescription('XSTORE Payment');

// Set up  the redirect URLs
$redirectUrls = new RedirectUrls();
$url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url = explode('paypal.php', $url);
$url = $url[0];
$redirectUrls->setReturnUrl($url . 'paypalsuccess.php')
    ->setCancelUrl($url . 'paypalfail.php');

// Set up the payment
$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

// Create the payment
$payment->create($apiContext);

// Get the redirect URL
$approvalUrl = $payment->getApprovalLink();

// Redirect the user to PayPal for payment
header("Location: $approvalUrl");
?>