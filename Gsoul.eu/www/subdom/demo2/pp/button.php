<?php
class PayPalButton{
  private $clientIdTester='ARxnP1qDbordUcsrGFkLM-pPLgUZZ1wtRj4LqgOU0DbpKLfn04Pfq6yT5TK9CT0XWf6mHqk5FGpfL2yk';//'Ac3mTnOZK2oFrNlceedwWot53S2HlzO25EHIPg-_ndBAalalj5tL3G9xlA15LlSd_mBf9Z9yedI860dh';
  private $clientSecretTester='EP5N_25EPZ0NzyrLB6mPqz33vmJILCbWZZKnf37WdkR8byPDTjEVNSBGl1g3DawuUgfyCwc-lgpLf0I1';//'EMhQUasWcsR6m--HU_caQLhgNeGb3ssFA5xCw7n5HxsBRTtgWHme7jmszl3Srw3woxIVB6rVCuPmavZ2';
  private $clientIdProduction='ASZVWJkeY2mkO7aYaJOjB61fU6AowcvJ95rapwK0mKd12HiLXNbSQfywE1stQSjWmvPNFi3a_CZG_Wns';
  private $clientSecretProduction='EGFj3wgVIHDuSCifJssCytq8YJIMiGlCi0GLkISM2GUnh6dmX7sEw78F8N01B61gl81udiLynGDeAuER';
  private $urlTester='https://api.sandbox.paypal.com';
  private $urlProduction='https://api.paypal.com';
  private $mode='sandbox'; // tester OR production 
  public function getMainJS(){ 
    echo '<script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>';
    } 
  public function getButtonCode(){
    echo '<div id="paypal-button"></div>';
    echo '<script>
    
    
          </script>';
  
    }
  public function getListeningCode(){
    
    
    echo '<script>
      var express = require(\'express\');
      var request = require(\'request\');
      var CLIENT = \''.($this->mode=='sandbox'?$this->clientIdTester:$this->clientIdProduction).'\';
      var SECRET = \''.($this->mode=='sandbox'?$this->clientSecretTester:$this->clientSecretProduction).'\';      
      var PAYPAL_API = \''.($this->mode=='sandbox'?$this->urlTester:$this->urlProduction).'\';                                    
      express()
        // 2. Set up a URL to handle requests from the PayPal button
        .post(\'/pp/create-order/\', function(req, res) {
          // 3. Call /v2/checkout/orders to set up the payment
          request.post(PAYPAL_API + \'/v2/checkout/orders\', {
            auth: {
              user: CLIENT,
              pass: SECRET
            },
            body: {
              intent: \'sale\',
              payer: {
                payment_method: \'paypal\'
              },
              transactions: [{
                amount: {
                  total: \'5.99\',
                  currency: \'USD\'
                }
              }],
              redirect_urls: {
                return_url: \'https://example.com\',
                cancel_url: \'https://example.com\'
              }
            },
            json: true
          }, function (err, response) {
              if (err) {
                console.error(err);
                return res.sendStatus(500);
              }
      
              // 4. Return the order ID to the client
              res.json({
                id: response.body.id
              });
          });
        })
        // 5. Set up a URL to handle requests from the PayPal button.
        .post(\'/my-api/capture-payment/\', function(req, res) {
          // 6. Get the order ID from the request body.
          var OrderID = req.body.id;
      
          // 7. Call /v2/checkout/orders to finalize the payment.
          request.post(PAYPAL_API + \'/v2/checkout/orders/\' + orderID + \'/capture\', {
            auth: {
              user: CLIENT,
              pass: SECRET
            },
            body: {
              transactions: [{
                amount: {
                  total: \'10.99\',
                  currency: \'USD\'
                }
              }]
            },
            json: true
          }, function (err, response) {
            if (err) {
              console.error(err);
              return res.sendStatus(500);
            }
      
            // 8. Return a success response to the client
            res.json({
              status: \'success\'
            });
          });
        })
        .listen(3000, function() {
          console.log(\'Server listening at http://localhost:3000/\');
        });
      </script>';
  
    }

  }
if(isset($_GET['action'])){
  $action=$_GET['action'];
}else{
  $action='create-button';
} 
$header='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head><body>';
$footer='</body></html>'; 
if($action=='create-button'){
  echo $header;
  echo '<script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>';
  
  
  echo $footer;
  }  
  



