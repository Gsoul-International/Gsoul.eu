<?php
class FPaypal extends Module{   
  private $clientIdTester='AQAhdmNc617IELCpuuxoNyYTy4PGfSGLmebXQs76yRVGbvKboFO2WnkoC6Eg0p_7fjijeNyJSSzbiakr';
  private $clientSecretTester='EGaLJfMpg070uD-ibXho5a4hIzacM9lIoMgqjgQ3fw0neCEMoZoqYt7twwphz1SaDdXcfmj7AwiJE8Cw';
  private $clientIdProduction='';
  private $clientSecretProduction='';
  //private $clientIdTester='ARxnP1qDbordUcsrGFkLM-pPLgUZZ1wtRj4LqgOU0DbpKLfn04Pfq6yT5TK9CT0XWf6mHqk5FGpfL2yk';
  //private $clientSecretTester='EP5N_25EPZ0NzyrLB6mPqz33vmJILCbWZZKnf37WdkR8byPDTjEVNSBGl1g3DawuUgfyCwc-lgpLf0I1';
  //private $clientIdProduction='ASZVWJkeY2mkO7aYaJOjB61fU6AowcvJ95rapwK0mKd12HiLXNbSQfywE1stQSjWmvPNFi3a_CZG_Wns';
  //private $clientSecretProduction='EGFj3wgVIHDuSCifJssCytq8YJIMiGlCi0GLkISM2GUnh6dmX7sEw78F8N01B61gl81udiLynGDeAuER';
  private $urlTester='https://api.sandbox.paypal.com';
  private $urlProduction='https://api.paypal.com';
  private $mode='tester'; // tester OR production  
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $action=getget('action','');
    if($action=='pay-in'){$this->PagePayIn();}          
    elseif($action=='pay-out'){$this->PagePayOutInternal();}  
    elseif($action=='paypal-return'){$this->PagePaypalReturn();}  
    elseif($action=='paypal-error'){$this->PagePaypalError();}  
    elseif($action=='paypal-success'){$this->PagePaypalSuccess();} 
    elseif($action=='paypal-waiting'){$this->PagePaypalWaiting();}    
    elseif($action=='paypal-failed'){$this->PagePaypalFailed();}              
    elseif($action=='webhook'){$this->PageWebhook();}                 
    else{$this->PageMain();}    
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
    $this->content=$tpl->fetch('frontend/paypalTwo/main.tpl');  
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
  public function PagePayOutInternal(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    $mailP=$this->kernel->user->data->email_paypal;
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD')&&$this->kernel->user->data->ucetni_zustatek>=$amountP){       
      $xcredit=number_format(round($amountP,2),2,'.','');          
      $xcost=number_format(round($amountP,2),2,'.','');                                                                       
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
  public function PagePayIn(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&($currencyP=='USD')){
      $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev'); 
      $tpl=new Templater();       
      $tpl->add('amountP',$amountP);  
      $tpl->add('currencyP',$currencyP);
      $tpl->add('mainpagename',$mainpagename); 
      $tpl->add('clientIdTester',$this->clientIdTester); 
      $tpl->add('clientIdProduction',$this->clientIdProduction); 
      
      $tpl->add('mode',$this->mode); 
      $tpl->add('user',$this->kernel->user->data);      
      $tpl->add('apaymentin',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in'),false));
      $tpl->add('apaymentout',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out'),false));
      $tpl->add('systemTranslator',$this->kernel->systemTranslator);
      $this->content=$tpl->fetch('frontend/paypalTwo/payIn.tpl');  
      $this->execute(); 
    }else{$this->Redirect(array('action'=>'paypal-error'));}             
    }
 
  /**********************************************************************/
  
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
  
  public function PageWebhook(){
    die('webhook');
    $this->Redirect(array('action'=>'blabla'));
    }
  
  }