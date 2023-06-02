<?php
require __DIR__ . '/PayPal-PHP-SDK/autoload.php';
$apiContext = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    'Ac3mTnOZK2oFrNlceedwWot53S2HlzO25EHIPg-_ndBAalalj5tL3G9xlA15LlSd_mBf9Z9yedI860dh', // ClientID
    'EMhQUasWcsR6m--HU_caQLhgNeGb3ssFA5xCw7n5HxsBRTtgWHme7jmszl3Srw3woxIVB6rVCuPmavZ2'  // ClientSecret
    )
  );
/*$apiContext->setConfig(array('mode'=>'live')); // for production mode only*/
$payer = new \PayPal\Api\Payer();
$payer->setPaymentMethod('paypal');
$amount = new \PayPal\Api\Amount();
$amount->setTotal('1.00');
$amount->setCurrency('USD');
$transaction = new \PayPal\Api\Transaction();
$transaction->setAmount($amount);
$redirectUrls = new \PayPal\Api\RedirectUrls();
$redirectUrls->setReturnUrl("http://demo.gsoul.cz/paypal/")
    ->setCancelUrl("http://demo.gsoul.cz/paypal/");
$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions(array($transaction))
    ->setRedirectUrls($redirectUrls);
try {
    $payment->create($apiContext);
    echo $payment;
    echo "\n\nRedirect user to approval_url: <a href=". $payment->getApprovalLink() .">Create payment ". $payment->getApprovalLink() ."</a>\n";
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    echo $ex->getData();
}