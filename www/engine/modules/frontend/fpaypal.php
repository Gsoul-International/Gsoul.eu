<?php
//client id ASZVWJkeY2mkO7aYaJOjB61fU6AowcvJ95rapwK0mKd12HiLXNbSQfywE1stQSjWmvPNFi3a_CZG_Wns
//secret EGFj3wgVIHDuSCifJssCytq8YJIMiGlCi0GLkISM2GUnh6dmX7sEw78F8N01B61gl81udiLynGDeAuER 
class FPaypal extends Module{   
  private $clientIdTester='AQAhdmNc617IELCpuuxoNyYTy4PGfSGLmebXQs76yRVGbvKboFO2WnkoC6Eg0p_7fjijeNyJSSzbiakr';//mh
  private $clientSecretTester='EGaLJfMpg070uD-ibXho5a4hIzacM9lIoMgqjgQ3fw0neCEMoZoqYt7twwphz1SaDdXcfmj7AwiJE8Cw';//mh
  //private $clientIdTester='ARxnP1qDbordUcsrGFkLM-pPLgUZZ1wtRj4LqgOU0DbpKLfn04Pfq6yT5TK9CT0XWf6mHqk5FGpfL2yk';//'Ac3mTnOZK2oFrNlceedwWot53S2HlzO25EHIPg-_ndBAalalj5tL3G9xlA15LlSd_mBf9Z9yedI860dh';
  //private $clientSecretTester='EP5N_25EPZ0NzyrLB6mPqz33vmJILCbWZZKnf37WdkR8byPDTjEVNSBGl1g3DawuUgfyCwc-lgpLf0I1';//'EMhQUasWcsR6m--HU_caQLhgNeGb3ssFA5xCw7n5HxsBRTtgWHme7jmszl3Srw3woxIVB6rVCuPmavZ2';
  private $clientIdProduction='ASZVWJkeY2mkO7aYaJOjB61fU6AowcvJ95rapwK0mKd12HiLXNbSQfywE1stQSjWmvPNFi3a_CZG_Wns';
  private $clientSecretProduction='EGFj3wgVIHDuSCifJssCytq8YJIMiGlCi0GLkISM2GUnh6dmX7sEw78F8N01B61gl81udiLynGDeAuER';
  private $mode='production'; // tester OR production  
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $action=getget('action','');
    if($action=='pay-in'){$this->PagePayIn();}      
    /*elseif($action=='pay-out'){$this->PagePayOut();}*/
    elseif($action=='pay-out'){$this->PagePayOutInternal();}  
    elseif($action=='paypal-return'){$this->PagePaypalReturn();}  
    elseif($action=='paypal-error'){$this->PagePaypalError();}  
    elseif($action=='paypal-success'){$this->PagePaypalSuccess();} 
    elseif($action=='paypal-waiting'){$this->PagePaypalWaiting();}    
    elseif($action=='paypal-failed'){$this->PagePaypalFailed();}              
    elseif($action=='webhook'){$this->PageWebhook();}   
    elseif($action=='paypal-refresh-out'){$this->PagePaypalRefreshPayout();}          
    else{$this->PageMain();}    
    }
  public function PagePayIn(){
    $this->Redirect(array('action'=>'paypal-error'));
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD')){ 
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
        $apiContext->setConfig(array('mode'=>'live'));
      }
      $xcost=number_format(round($amountP,2),2,'.','');
      $xcredit=number_format(round($amountP,2),2,'.',''); 
      $payer = new \PayPal\Api\Payer();
      $payer->setPaymentMethod("paypal"); //["credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment"]   
      $item1 = new \PayPal\Api\Item();
      $item1->setName('Credit')->setCurrency($currencyP)->setQuantity(1)->setPrice($xcost);
      $itemList = new \PayPal\Api\ItemList();
      $itemList->setItems(array($item1));
      $details = new \PayPal\Api\Details();
      $details->setShipping(0)->setTax(0)->setSubtotal($xcost);                 
      $amount = new \PayPal\Api\Amount();                                             
      $amount->setTotal($xcost)->setCurrency($currencyP)->setDetails($details);                            
      $transaction = new \PayPal\Api\Transaction();
      $transaction->setAmount($amount)->setItemList($itemList)->setDescription("Credit recharge")->setInvoiceNumber(uniqid());      
      $redirectUrls = new \PayPal\Api\RedirectUrls();
      $redirectUrls->setReturnUrl("https://www.gsoul.eu/paypal/paypal-return/payin-success/")->setCancelUrl("https://www.gsoul.eu/paypal/paypal-return/payin-failed/");
      $payment = new \PayPal\Api\Payment();
      $payment->setIntent('order')->setPayer($payer)->setTransactions(array($transaction))->setRedirectUrls($redirectUrls);
      try {      
        $payment->create($apiContext);
        $this->kernel->models->DBpaypalPayments->store(0,array(
          'userId'=>$this->kernel->user->uid,
          'dateTime'=>time(),
          'credit'=>$xcredit,
          'cost'=>$xcost,
          'currency'=>$currencyP,
          'type'=>'pay-in',
          'paymentId'=>$payment->getId(),
          'paymentToken'=>$payment->getToken(),
          'status'=>'at-paypal'
          ));
        header("LOCATION: ".$payment->getApprovalLink());
        exit();        
        }catch (\PayPal\Exception\PayPalConnectionException $ex) {
          print_r($ex->getData());
          die();
          $this->Redirect(array('action'=>'paypal-error'));
          /*print_r($ex->getData());*/
        }
      }
    $this->Redirect(array('action'=>'paypal-error'));              
    }      
  /*public function PagePayIn(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD'||$currencyP=='EUR'||$currencyP=='CZK')){ 
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        try{
          $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
          $apiContext->setConfig(array('mode'=>'live'));
        } catch(Exception $e){
          //var_dump($e);
          die('err');
        }
      }
      $payer = new \PayPal\Api\Payer();
      //$payer->setPaymentMethod("paypal"); //["credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment"]   
      $payer->setPaymentMethod("paypal"); //["credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment"]
      $amount = new \PayPal\Api\Amount();
      if($currencyP=='EUR'){
        $xcost=number_format(round($amountP*0.8,2),2,'.','');
        $xcredit=number_format(round($amountP,2),2,'.','');         
        $amount->setTotal($xcost);    
        $amount->setCurrency('EUR');
      }elseif($currencyP=='CZK'){
        $xcost=number_format(round($amountP*22,2),2,'.','');
        $xcredit=number_format(round($amountP,2),2,'.','');         
        $amount->setTotal($xcost);    
        $amount->setCurrency('CZK');       
      }else{
        $xcost=number_format(round($amountP,2),2,'.','');
        $xcredit=number_format(round($amountP,2),2,'.','');                                
        $amount->setTotal($xcost);    
        $amount->setCurrency('USD');                  
      }
      $transaction = new \PayPal\Api\Transaction();
      $transaction->setAmount($amount);
      $redirectUrls = new \PayPal\Api\RedirectUrls();
      $redirectUrls->setReturnUrl("https://www.gsoul.eu/paypal/paypal-return/payin-success/")->setCancelUrl("https://www.gsoul.eu/paypal/paypal-return/payin-failed/");
      $payment = new \PayPal\Api\Payment();
      $payment->setIntent('order')
        ->setPayer($payer)
        ->setTransactions(array($transaction))
        ->setRedirectUrls($redirectUrls);
      try {      
        $payment->create($apiContext);
        $this->kernel->models->DBpaypalPayments->store(0,array(
          'userId'=>$this->kernel->user->uid,
          'dateTime'=>time(),
          'credit'=>$xcredit,
          'cost'=>$xcost,
          'currency'=>$currencyP,
          'type'=>'pay-in',
          'paymentId'=>$payment->getId(),
          'paymentToken'=>$payment->getToken(),
          'status'=>'at-paypal'
          ));
        header("LOCATION: ".$payment->getApprovalLink());
        exit();        
        }catch (\PayPal\Exception\PayPalConnectionException $ex) {
          $this->Redirect(array('action'=>'paypal-error'));
          /*print_r($ex->getData());* /
        }
      }
    $this->Redirect(array('action'=>'paypal-error'));              
    }*/
  public function PagePayOutInternal(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    $mailP=$this->kernel->user->data->email_paypal;
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD'||$currencyP=='EUR'||$currencyP=='CZK')&&$this->kernel->user->data->ucetni_zustatek>=$amountP){       
      $xcredit=number_format(round($amountP,2),2,'.','');    
      if($currencyP=='EUR'){
        $xcost=number_format(round($amountP*0.8,2),2,'.','');                     
      }elseif($currencyP=='CZK'){
        $xcost=number_format(round($amountP*22,2),2,'.','');                     
      }else{
        $xcost=number_format(round($amountP,2),2,'.','');                                                            
      }     
      $idp=$this->kernel->models->DBpaypalPayments->store(0,array(
        'userId'=>$this->kernel->user->uid,
        'dateTime'=>time(),
        'credit'=>$xcredit,
        'cost'=>$xcost,
        'currency'=>$currencyP,
        'type'=>'pay-out',
        'paymentId'=>$mailP,
        'paymentToken'=>'',
        'status'=>'at-paypal'
        ));
      $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$this->kernel->user->uid.'"');
      if(isset($user)&&$user->uid>0){
        $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$xcredit),2))));     
        $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>(-1)*$xcredit,'duvod'=>'Vyplacení kreditu'));
        $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek-$xcredit)));
        } 
      $mailer=new PHPMailer();
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='GSOUL.EU / New payout created';           
      $mailer->MsgHTML('<h1>New payout has been created</h1><p>Please, send '.$xcost.' '.$currencyP.' at paypal account '.$mailP.'</p>');    
      $mailer->AddAddress('payout@gsoul.eu');        
      $mailer->Send();                  
      $this->Redirect(array('action'=>'paypal-waiting'));      
      }
    $this->Redirect(array('action'=>'paypal-error'));       
    }
  /**********************************************************************/
  public function PagePayOut(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    $mailP=$this->kernel->user->data->email_paypal;
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD'||$currencyP=='EUR'||$currencyP=='CZK')&&$this->kernel->user->data->ucetni_zustatek>=$amountP){ 
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
        $apiContext->setConfig(array('mode'=>'live'));
      }
      $xcredit=number_format(round($amountP,2),2,'.','');    
      if($currencyP=='EUR'){
        $xcost=number_format(round($amountP*0.8,2),2,'.','');                     
      }elseif($currencyP=='CZK'){
        $xcost=number_format(round($amountP*22,2),2,'.','');                     
      }else{
        $xcost=number_format(round($amountP,2),2,'.','');                                                            
      }     
      $redirectUrls = new \PayPal\Api\RedirectUrls();
      $redirectUrls->setReturnUrl("http://demo2.gsoul.eu/paypal/paypal-return/payout-success/")->setCancelUrl("http://demo2.gsoul.eu/paypal/paypal-return/payout-failed/");
      $payouts = new \PayPal\Api\Payout();  
      $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
      $senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
      $senderItem = new \PayPal\Api\PayoutItem();
      $senderItem->setRecipientType('Email')
        ->setReceiver($mailP)
        ->setAmount(new \PayPal\Api\Currency('{"value":"'.$xcost.'","currency":"'.$currencyP.'"}'))
        ->setNote('GSOUL credit')
        //->setRedirectUrls($redirectUrls) // not work yet - maybe in future
        ->setSenderItemId(rand(10000000,99999999));  
      $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);  
      $request = clone $payouts;
        try {
          $output = $payouts->createSynchronous($apiContext); 
          if(isset($output->batch_header->batch_status)&&isset($output->batch_header->payout_batch_id)){            
            $idp=$this->kernel->models->DBpaypalPayments->store(0,array(
              'userId'=>$this->kernel->user->uid,
              'dateTime'=>time(),
              'credit'=>$xcredit,
              'cost'=>$xcost,
              'currency'=>$currencyP,
              'type'=>'pay-out',
              'paymentId'=>$output->batch_header->payout_batch_id,
              'paymentToken'=>'',
              'status'=>'at-paypal'
              ));
            $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$this->kernel->user->uid.'"');
            if(isset($user)&&$user->uid>0){
              $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$xcredit),2))));     
              $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>(-1)*$xcredit,'duvod'=>'Vyplacení kreditu'));
              $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek-$xcredit)));
              } 
            // continue with nex processess?
            $this->Redirect(array('action'=>'paypal-waiting'));                                                       
          }else{
            $this->kernel->models->DBpaypalPayments->store(0,array(
              'userId'=>$this->kernel->user->uid,
              'dateTime'=>time(),
              'credit'=>$xcredit,
              'cost'=>$xcost,
              'currency'=>$currencyP,
              'type'=>'pay-out',
              'paymentId'=>'',
              'paymentToken'=>'',
              'status'=>'unsuccess'
              ));
            $this->Redirect(array('action'=>'paypal-error'));
          }
          /*
          //old api version:
          //print_r($output->batch_header->batch_status);
          $items = $output->getItems();
          $item = $items[0];
          $this->kernel->models->DBpaypalPayments->store(0,array(
            'userId'=>$this->kernel->user->uid,
            'dateTime'=>time(),
            'credit'=>$xcredit,
            'cost'=>$xcost,
            'currency'=>$currencyP,
            'type'=>'pay-out',
            'paymentId'=>$item->getTransactionId(),
            'paymentToken'=>'',
            'status'=>'success'
            ));
          $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$this->kernel->user->uid.'"');
          if(isset($user)&&$user->uid>0){
            $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$xcredit),2))));     
            $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>(-1)*$xcredit,'duvod'=>'Vyplacení kreditu'));
            $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek-$xcredit)));
            }            
          echo 'SUCCESS!<br>';
          //print_r($item->getTransactionId());
          //die();          
          $this->Redirect(array('action'=>'paypal-success'));
          */
        } catch (Exception $ex) {
          $this->kernel->models->DBpaypalPayments->store(0,array(
            'userId'=>$this->kernel->user->uid,
            'dateTime'=>time(),
            'credit'=>$xcredit,
            'cost'=>$xcost,
            'currency'=>$currencyP,
            'type'=>'pay-out',
            'paymentId'=>'',
            'paymentToken'=>'',
            'status'=>'unsuccess'
            ));
          ob_start();
          echo 'UNSUCCESSED PAYPAL PAYMENT!<br>Request:<br><br>';          
          print_r($request);
          echo '<br>Exception:<br><br>';
          print_r($ex);          
          $mailtext=ob_get_clean();
          $mailer=new PHPMailer();
          $mailer->CharSet="UTF-8"; 
          $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
          $mailer->Subject='GSOUL.EU / PAYPAL PAYOUT ERROR';           
          $mailer->MsgHTML($mailtext);    
          $mailer->AddAddress('payout@gsoul.eu');  
          $mailer->Send();                  
          $this->Redirect(array('action'=>'paypal-error'));
        }
      }
    $this->Redirect(array('action'=>'paypal-error'));       
    }
  public function PagePaypalReturn(){
    if($this->mode=='tester'){   
      $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
    }else{
      $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
      $apiContext->setConfig(array('mode'=>'live'));
    }
    $pay=getget('pay','');
    $state=getget('state','');
    //in failed
    if($pay=='in'&&$state=='failed')
    {
      $paymentToken=trim(addslashes(strip_tags(getget('token'))));
      $exist=$this->kernel->models->DBpaypalPayments->getLine('*','WHERE paymentToken="'.$paymentToken.'"');
      $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'unsuccess'));      
      $this->Redirect(array('action'=>'paypal-failed'));      
    }
    //in success
    if($pay=='in'&&$state=='success')
    {
      $paymentId=trim(addslashes(strip_tags(getget('paymentId'))));     
      $payerId=trim(addslashes(strip_tags(getget('PayerID'))));              
      if($paymentId!=''&&$payerId!=''){
        $exist=$this->kernel->models->DBpaypalPayments->getLine('*','WHERE paymentId="'.$paymentId.'"');
        if($exist->id>0&&$exist->status=='at-paypal'&&$exist->type=='pay-in'){
          try{ 
            $paymentCall=new \PayPal\Api\Payment(); 
            $payment=$paymentCall->get($paymentId,$apiContext);     
            $execution=new \PayPal\Api\PaymentExecution(); 
            $execution->setPayerId($payerId);
            $amount=new \PayPal\Api\Amount();
            $amount->setTotal(str_replace(',','.',$exist->cost));    
            $amount->setCurrency($exist->currency);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);
            $result=$payment->execute($execution,$apiContext);
            $payment=$paymentCall->get($paymentId,$apiContext);
            $state=$payment->getState();  
            if($state=='approved'){
            $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'success'));
            $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$exist->userId.'"');
            if(isset($user)&&$user->uid>0){
              $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$exist->credit),2))));     
              $this->kernel->models->DBusersCoins->store(0,array('idu'=>$exist->userId,'datum_cas'=>time(),'coins'=>$exist->credit,'duvod'=>'Dokoupení kreditu'));
              $this->kernel->models->DBusers->store($exist->userId,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$exist->credit)));
              }
            $this->Redirect(array('action'=>'paypal-success'));
            }
          if($state=='failed'){
            $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'unsuccess'));      
            $this->Redirect(array('action'=>'paypal-failed'));    
            }          
          }catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $this->Redirect(array('action'=>'paypal-error'));          
          }
        }
             
      }else{
        $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'unsuccess'));      
        $this->Redirect(array('action'=>'paypal-failed')); 
      }
    }
    // out failed
    if($pay=='out'&&$state=='failed')
    {
      // not work yet - maybe in future
      echo 'out / failed ';
      print_r($_POST);
      print_r($_GET);
      die();
    } 
    // out success
    if($pay=='out'&&$state=='failed')
    {
      // not work yet - maybe in future
      echo 'out / success ';
      print_r($_POST);
      print_r($_GET);
      die();
    } 
    $this->Redirect(array('action'=>'paypal-error'));    
  
    ///////////// 
    print_r($_POST);
    print_r($_GET);
    die(); 
    $paymentId=trim(addslashes(strip_tags(getget('paymentId'))));           
    if($paymentId!=''){
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
        $apiContext->setConfig(array('mode'=>'live'));
      }
      $paymentCall = new \PayPal\Api\Payment();      
      try{  
        $payment=$paymentCall->get($paymentId,$apiContext);        
      }catch(\PayPal\Exception\PayPalConnectionException $ex){       
        $this->Redirect(array('action'=>'paypal-error'));
      }     
      if($payment->getId()==$paymentId){
        $state=$payment->getState();
        $exist=$this->kernel->models->DBpaypalPayments->getLine('*','WHERE paymentId="'.$paymentId.'"');
        //pay in
        if($exist->id>0&&$exist->status=='at-paypal'&&$exist->type=='pay-in'){
          if($state=='approved'){
            $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'success'));
            $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$exist->userId.'"');
            if(isset($user)&&$user->uid>0){
              $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$exist->credit),2))));     
              $this->kernel->models->DBusersCoins->store(0,array('idu'=>$exist->userId,'datum_cas'=>time(),'coins'=>$exist->credit,'duvod'=>'Dokoupení kreditu'));
              $this->kernel->models->DBusers->store($exist->userId,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$exist->credit)));
              }
            $this->Redirect(array('action'=>'paypal-success'));
            }
          if($state=='failed'){
            $this->kernel->models->DBpaypalPayments->store($exist->id,array('status'=>'unsuccess'));      
            $this->Redirect(array('action'=>'paypal-failed'));    
            }          
          }      
        }      
        $this->Redirect(array('action'=>'paypal-waiting')); 
      } 
    $this->Redirect(array('action'=>'paypal-error'));    
    }
  public function PagePaypalError(){    
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];          
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];               
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);         
    $this->content=$tpl->fetch('frontend/paypal/error.tpl');  
    $this->execute(); 
    } 
  public function PagePaypalSuccess(){
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];      
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];                 
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];  
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);         
    $this->content=$tpl->fetch('frontend/paypal/success.tpl');  
    $this->execute();     
    }  
  public function PagePaypalFailed(){
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];        
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];               
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];  
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);          
    $this->content=$tpl->fetch('frontend/paypal/failed.tpl');  
    $this->execute();     
    }  
  public function PagePaypalWaiting(){
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];     
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];               
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);         
    $this->content=$tpl->fetch('frontend/paypal/waiting.tpl');  
    $this->execute();     
    } 
  public function PageMain(){    
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];         
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];                 
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];   
    $page=(int)getget('page','0');
    $counter=10;//10  
    $coins=$this->kernel->models->DBpaypalPayments->getLines('*',' WHERE userId="'.$this->kernel->user->uid.'" ORDER BY id DESC LIMIT '.($page*$counter).', '.$counter); 
    foreach($coins as $ck=>$cv){
      if($cv->type=='pay-out'){$coins[$ck]->aRefreshOut=$this->Anchor(array('action'=>'paypal-refresh-out','paymentId'=>$cv->paymentId,'page'=>$page),false);}
      }
    $coins_count=$this->kernel->models->DBpaypalPayments->getOne('count(id)','WHERE userId="'.$this->kernel->user->uid.'"');
    $paginnator=$this->Paginnator($page,$coins_count,$counter);       
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');        
    $tpl=new Templater();       
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('coins',$coins);
    $tpl->add('mainpagename',$mainpagename); 
    $tpl->add('user',$this->kernel->user->data);      
    $tpl->add('apaymentin',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in'),false));
    $tpl->add('apaymentout',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out'),false));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/paypal/main.tpl');  
    $this->execute(); 
    } 
  private function Paginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('page'=>($page+1)),false);
    }    
    return $pages;          
    }        
  public function PageWebhook(){
    die('webhook');
    $this->Redirect(array('action'=>'blabla'));
    }
  public function PagePaypalRefreshPayout(){
    die('Deprecated');
    $page=(int)getget('page');
    $paymentId=trim(strip_tags(getget('paymentId')));
    $paymentSystemData=$this->kernel->models->DBpaypalPayments->getLine('*',' WHERE paymentId="'.$paymentId.'" AND userId="'.$this->kernel->user->uid.'" ');
    if($paymentId!=''&&$paymentSystemData->id>0&&$paymentSystemData->status=='at-paypal'){
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
        $apiContext->setConfig(array('mode'=>'live'));
      }
      $payouts = new \PayPal\Api\Payout();  
        try{
          $output=$payouts->get($paymentId,$apiContext);
          if($output->batch_header->batch_status=='SUCCESS'){
            // half success 
            if(isset($output->items[0])){
              $item = $output->items[0];
              if($item->transaction_status=='SUCCESS'){
                // full success
                $this->kernel->models->DBpaypalPayments->store($paymentSystemData->id,array('status'=>'success','paymentToken'=>$item->payout_item_id.' / '.$item->transaction_id));                
                }
              if($item->transaction_status=='DENIED'||$item->transaction_status=='FAILED'||$item->transaction_status=='UNCLAIMED'||$item->transaction_status=='RETURNED'||$item->transaction_status=='REFUNDED'||$item->transaction_status=='REVERSED'){
                //unsuccess
                $this->kernel->models->DBpaypalPayments->store($paymentSystemData->id,array('status'=>'unsuccess'));                           
                $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$paymentSystemData->credit),2))));     
                $this->kernel->models->DBusersCoins->store(0,array('idu'=>$paymentSystemData->userId,'datum_cas'=>time(),'coins'=>$coins,'duvod'=>'Vraceni zamitnute transakce'));
                $this->kernel->models->DBusers->store($paymentSystemData->userId,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek+$coins)));                  
                }
              }
            } 
          if($output->batch_header->batch_status=='DENIED'||$output->batch_header->batch_status=='CANCELED'){
            //unsuccess            
            $this->kernel->models->DBpaypalPayments->store($paymentSystemData->id,array('status'=>'unsuccess'));                           
            $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$paymentSystemData->credit),2))));     
            $this->kernel->models->DBusersCoins->store(0,array('idu'=>$paymentSystemData->userId,'datum_cas'=>time(),'coins'=>$coins,'duvod'=>'Vraceni zamitnute transakce'));
            $this->kernel->models->DBusers->store($paymentSystemData->userId,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek+$coins)));
            }  
          //var_dump($output);
          //die();
        }catch (\PayPal\Exception\PayPalConnectionException $ex) {
          $this->Redirect(array('action'=>'paypal-error'));          
        }  
      }    
    $this->Redirect(array('page'=>$page,'message'=>'refresh-success'));
    }   
  }