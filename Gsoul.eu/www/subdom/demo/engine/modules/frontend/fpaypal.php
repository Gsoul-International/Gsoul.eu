<?php
class FPaypal extends Module{   
  private $clientIdTester='Ac3mTnOZK2oFrNlceedwWot53S2HlzO25EHIPg-_ndBAalalj5tL3G9xlA15LlSd_mBf9Z9yedI860dh';
  private $clientSecretTester='EMhQUasWcsR6m--HU_caQLhgNeGb3ssFA5xCw7n5HxsBRTtgWHme7jmszl3Srw3woxIVB6rVCuPmavZ2';
  private $clientIdProduction='';
  private $clientSecretProduction='';
  private $mode='tester'; // tester OR production  
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title='Paypal';
    $action=getget('action','');
    if($action=='pay-in'){$this->PagePayIn();}      
    elseif($action=='pay-out'){$this->PagePayOut();}  
    elseif($action=='paypal-return'){$this->PagePaypalReturn();}  
    elseif($action=='paypal-error'){$this->PagePaypalError();}  
    elseif($action=='paypal-success'){$this->PagePaypalSuccess();} 
    elseif($action=='paypal-waiting'){$this->PagePaypalWaiting();}    
    elseif($action=='paypal-failed'){$this->PagePaypalFailed();}              
    elseif($action=='webhook'){$this->PageWebhook();}          
    else{$this->PageMain();}    
    }      
  public function PagePayIn(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    if($this->kernel->user->uid>0&&$amountP>=1&&($currencyP=='USD'||$currencyP=='EUR'||$currencyP=='CZK')){ 
      if($this->mode=='tester'){   
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdTester, $this->clientSecretTester ) );
      }else{
        $apiContext = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential( $this->clientIdProduction, $this->clientSecretProduction ) );
        $apiContext->setConfig(array('mode'=>'live'));
      }
      $payer = new \PayPal\Api\Payer();
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
      $redirectUrls->setReturnUrl("http://demo.gsoul.cz/paypal/paypal-return/payin-success/")->setCancelUrl("http://demo.gsoul.cz/paypal/paypal-return/payin-failed/");
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
          /*print_r($ex->getData());*/
        }
      }
    $this->Redirect(array('action'=>'paypal-error'));              
    }
  public function PagePayOut(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    $mailP=getpost('mail');
    if($this->kernel->user->uid>0&&$amountP>=1&&($currencyP=='USD'||$currencyP=='EUR'||$currencyP=='CZK')&&$this->kernel->user->data->ucetni_zustatek>=$amountP){ 
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
      $payouts = new \PayPal\Api\Payout();  
      $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
      $senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
      $senderItem = new \PayPal\Api\PayoutItem();
      $senderItem->setRecipientType('Email')->setReceiver($mailP)->setAmount(new \PayPal\Api\Currency('{"value":"'.$xcost.'","currency":"'.$currencyP.'"}'))->setNote('Thanks!')->setSenderItemId(rand(10000000,99999999));  
      $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);  
      $request = clone $payouts;
        try {
          $output = $payouts->createSynchronous($apiContext); 
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
          //echo 'SUCCESS!<br>';
          //print_r($item->getTransactionId());
          //die();
          $this->Redirect(array('action'=>'paypal-success'));
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
          //echo 'UNSUCCESSED!<br>';
          // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
          // print_r($request);
          //print_r($ex);
          //die();
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
    $this->seo_title='Paypal';        
    $this->seo_keywords='Paypal';                
    $this->seo_description='Paypal'; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename);          
    $this->content=$tpl->fetch('frontend/paypal/error.tpl');  
    $this->execute(); 
    } 
  public function PagePaypalSuccess(){
    $this->seo_title='Paypal';        
    $this->seo_keywords='Paypal';                
    $this->seo_description='Paypal'; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename);          
    $this->content=$tpl->fetch('frontend/paypal/success.tpl');  
    $this->execute();     
    }  
  public function PagePaypalFailed(){
    $this->seo_title='Paypal';        
    $this->seo_keywords='Paypal';                
    $this->seo_description='Paypal'; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename);          
    $this->content=$tpl->fetch('frontend/paypal/failed.tpl');  
    $this->execute();     
    }  
  public function PagePaypalWaiting(){
    $this->seo_title='Paypal';        
    $this->seo_keywords='Paypal';                
    $this->seo_description='Paypal'; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('mainpagename',$mainpagename);          
    $this->content=$tpl->fetch('frontend/paypal/waiting.tpl');  
    $this->execute();     
    } 
  public function PageMain(){    
    $this->seo_title='Paypal';        
    $this->seo_keywords='Paypal';                
    $this->seo_description='Paypal';  
    $page=(int)getget('page','0');
    $counter=10;//10  
    $coins=$this->kernel->models->DBpaypalPayments->getLines('*',' WHERE userId="'.$this->kernel->user->uid.'" ORDER BY id DESC LIMIT '.($page*$counter).', '.$counter); 
    foreach($coins as $ck=>$cv){$coins[$ck]->actualIt=$this->Anchor(array('action'=>'paypal-return','paymentId'=>$cv->paymentId),false);}
    $coins_count=$this->kernel->models->DBpaypalPayments->getOne('count(id)','WHERE userId="'.$this->kernel->user->uid.'"');
    $paginnator=$this->Paginnator($page,$coins_count,$counter);       
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');        
    $tpl=new Templater();       
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('coins',$coins);
    $tpl->add('mainpagename',$mainpagename);    
    $tpl->add('apaymentin',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in'),false));
    $tpl->add('apaymentout',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out'),false));
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
  }