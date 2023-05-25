<?php
require __DIR__ . '/PayPal-PHP-SDK/autoload.php';
$apiContext = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    'Ac3mTnOZK2oFrNlceedwWot53S2HlzO25EHIPg-_ndBAalalj5tL3G9xlA15LlSd_mBf9Z9yedI860dh', // ClientID
    'EMhQUasWcsR6m--HU_caQLhgNeGb3ssFA5xCw7n5HxsBRTtgWHme7jmszl3Srw3woxIVB6rVCuPmavZ2'  // ClientSecret
    )
  );  
$payouts = new \PayPal\Api\Payout();  
$senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
$senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
$senderItem = new \PayPal\Api\PayoutItem();
$senderItem->setRecipientType('Email')
    ->setNote('Thanks for your patronage!')
    ->setReceiver('info@mhmsys.cz')
    ->setSenderItemId("20170001")
    ->setAmount(new \PayPal\Api\Currency('{"value":"1.0","currency":"USD"}'));
$payouts->setSenderBatchHeader($senderBatchHeader)
    ->addItem($senderItem);  
$request = clone $payouts;
try {
    $output = $payouts->createSynchronous($apiContext); 
    echo 'SUCCESS!<br>';
    print_r($output);
} catch (Exception $ex) {
    echo 'UNSUCCESSED!<br>';
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
   // print_r($request);
    print_r($ex);
    die();
}
 //ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
/*


  
  
  
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
    echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    echo $ex->getData();
}       */