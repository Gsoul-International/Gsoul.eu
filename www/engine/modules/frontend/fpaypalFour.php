<?php
class FPaypal extends Module{   
  private $clientIdTester='ARxnP1qDbordUcsrGFkLM-pPLgUZZ1wtRj4LqgOU0DbpKLfn04Pfq6yT5TK9CT0XWf6mHqk5FGpfL2yk';
  private $clientSecretTester='EP5N_25EPZ0NzyrLB6mPqz33vmJILCbWZZKnf37WdkR8byPDTjEVNSBGl1g3DawuUgfyCwc-lgpLf0I1';
  private $clientIdProduction='ASZVWJkeY2mkO7aYaJOjB61fU6AowcvJ95rapwK0mKd12HiLXNbSQfywE1stQSjWmvPNFi3a_CZG_Wns';
  private $clientSecretProduction='EGFj3wgVIHDuSCifJssCytq8YJIMiGlCi0GLkISM2GUnh6dmX7sEw78F8N01B61gl81udiLynGDeAuER';
  private $mode='production'; // tester OR production  
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $action=getget('action','');
    if($action=='pay-in'){$this->PagePayIn();} 
    elseif($action=='pay-in-button'){$this->PagePayInButton();}
    elseif($action=='pay-in-confirm'){$this->PagePayInConfirm();}         
    elseif($action=='pay-out'){$this->PagePayOutInternal();}          
    elseif($action=='paypal-error'){$this->PagePaypalError();} 
    elseif($action=='paypal-waiting'){$this->PagePaypalWaiting();}    
    elseif($action=='paypal-success'){$this->PagePaypalSuccess();}                
    elseif($action=='webhook'){$this->PageWebhook();}     
    else{$this->PageMain();}    
    }      
  public function PagePayIn(){
    $amountP=(int)getpost('amount');
    $currencyP=getpost('currency');
    if($this->kernel->user->uid>0&&$this->kernel->user->data->overen_paypal_email==1&&$amountP>=1&&$amountP>=$this->kernel->settings['paypal_minimalni_castka']&&($currencyP=='USD')){ 
      $cost=($amountP*(1+($this->kernel->settings['paypal_procentualni_poplatek']/100)))+$this->kernel->settings['paypal_fixni_poplatek'];
      $idOrder=$this->kernel->models->DBpaypalOrders->store(0,array(
        'credit'=>$amountP,
        'cost'=>$cost,
        'id_uzivatele'=>$this->kernel->user->uid,
        'den'=>strftime('%d',time()),
        'mesic'=>strftime('%m',time()),
        'rok'=>strftime('%Y',time()),
        'id_paypal'=>'',
        'status'=>'at-button',
        'mena'=>$currencyP
        ));
      $this->Redirect(array('action'=>'pay-in-button','ido'=>$idOrder));
      }
    $this->Redirect(array('action'=>'paypal-error'));               
    }
  public function PagePayInButton(){
    $ido=(int)getget('ido');
    $order=$this->kernel->models->DBpaypalOrders->getLine('*','WHERE id_uzivatele="'.$this->kernel->user->uid.'" AND idpo="'.$ido.'"');
    if($order->idpo<1){$this->Redirect(array('action'=>'paypal-error'));}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];          
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];               
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu']; 
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');          
    $tpl=new Templater(); 
    $tpl->add('apaypalConfirm',$this->Anchor(array('action'=>'pay-in-confirm','ido'=>$ido),false));
    $tpl->add('mainpagename',$mainpagename); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $tpl->add('order',$order);
    $tpl->add('clientId',$this->mode=='tester'?$this->clientIdTester:$this->clientIdProduction);          
    $this->content=$tpl->fetch('frontend/paypalFour/payInButton.tpl');  
    $this->execute(); 
    }
  public function PagePayInConfirm(){
    $ido=(int)getget('ido');
    $order=$this->kernel->models->DBpaypalOrders->getLine('*','WHERE id_uzivatele="'.$this->kernel->user->uid.'" AND idpo="'.$ido.'" AND status="at-button"');
    if($order->idpo<1){$this->Redirect(array('action'=>'paypal-error'));}
    $paypal_id=getget('paypal_id');
    $uri='v2/checkout/orders/'.$paypal_id;    
    if($this->mode=='tester'){
      $access=$this->getAccessToken($this->clientIdTester,$this->clientSecretTester,'tester');
      $returnData=$this->getRequest('',$access,$uri,'tester');
    }else{
      $access=$this->getAccessToken($this->clientIdProduction,$this->clientSecretProduction,'production');
      $returnData=$this->getRequest('',$access,$uri,'production');
    }
    if($returnData->id!=''&&$returnData->status=='COMPLETED'){
      foreach($returnData->purchase_units as $pU){        
        foreach($pU->payments as $pMx){
          $pM=$pMx[0];          
          if($pM->status=='COMPLETED'&&$pM->amount->currency_code==$order->mena&&round($pM->amount->value,2)==round($order->cost,2)&&$this->kernel->user->uid>0){            
            $this->kernel->models->DBpaypalPayments->store(0,array(
              'userId'=>$this->kernel->user->uid,
              'dateTime'=>time(),
              'credit'=>$order->credit,
              'cost'=>$order->cost,
              'currency'=>$order->mena,
              'type'=>'pay-in',
              'paymentId'=>$returnData->id,
              'paymentToken'=>$returnData->id,
              'status'=>'success'
              ));              
            $this->kernel->models->DBpaypalOrders->store($ido,array('id_paypal'=>$returnData->id,'status'=>'success-done'));                          
            $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$this->kernel->user->uid.'"');
            if(isset($user)&&$user->uid>0){
              $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$order->credit),2))));     
              $this->kernel->models->DBusersCoins->store(0,array('idu'=>$user->uid,'datum_cas'=>time(),'coins'=>$order->credit,'duvod'=>'Dokoupení kreditu'));
              $this->kernel->models->DBusers->store($user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$order->credit)));
              }                
            $this->Redirect(array('action'=>'paypal-success'));
            }
          }
        }        
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
    $this->content=$tpl->fetch('frontend/paypalFour/error.tpl');  
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
    $this->content=$tpl->fetch('frontend/paypalFour/success.tpl');  
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
    $this->content=$tpl->fetch('frontend/paypalFour/waiting.tpl');  
    $this->execute();     
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
  public function PageMain(){    
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];         
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];                 
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];   
    $page=(int)getget('page','0');
    $counter=10;//10  
    $coins=$this->kernel->models->DBpaypalPayments->getLines('*',' WHERE userId="'.$this->kernel->user->uid.'" ORDER BY id DESC LIMIT '.($page*$counter).', '.$counter);     
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
    $tpl->add('settings',$this->kernel->settings);
    $this->content=$tpl->fetch('frontend/paypalFour/main.tpl');  
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
    }  
  public function getAccessToken($clientId,$clientSecret,$mode='production'){
    if($mode=='production'){$url='https://api.paypal.com/v1/oauth2/token';}else{$url='https://api.sandbox.paypal.com/v1/oauth2/token';}
    $requestHeaders=array('Accept: application/json','Accept-Language: en_US');
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,'grant_type=client_credentials');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$requestHeaders);
    curl_setopt($ch,CURLOPT_USERPWD,''.$clientId.':'.$clientSecret.'');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $responseBody=curl_exec($ch);    
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);    
    if($httpCode==200){
      $decoded=json_decode($responseBody);
      if(isset($decoded->access_token)){
        return $decoded->access_token;
      }else{
        return 'ERROR> No access token found.';
      }      
    }
    return 'ERROR> '.$httpCode.' - '.$responseBody;     
    }
  public function postRequest($object,$access,$uri,$mode='production'){
    if($mode=='production'){$server='https://api.paypal.com/';}else{$server='https://api.sandbox.paypal.com/';}
    $requestHeaders=array('Content-Type: application/json','Authorization: Bearer '.$access /*,'PayPal-Partner-Attribution-Id: EXAMPLE_MP'*/ );
    $url=$server.$uri;
    $objectJson=($object);   //  json_encode
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$objectJson);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$requestHeaders);    
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_VERBOSE,TRUE);
    $responseBody=curl_exec($ch);    
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);    
    if($httpCode==200||$httpCode==201){
      $decoded=json_decode($responseBody);
      if(isset($decoded)){
        return $decoded;
      }else{
        return 'ERROR> No data found.';
      }      
    }
    return 'ERROR> '.$httpCode.$responseBody; 
    }
  public function getRequest($object,$access,$uri,$mode='production'){
    if($mode=='production'){$server='https://api.paypal.com/';}else{$server='https://api.sandbox.paypal.com/';}
    $requestHeaders=array('Content-Type: application/json','Authorization: Bearer '.$access /*,'PayPal-Partner-Attribution-Id: EXAMPLE_MP'*/ );
    $url=$server.$uri;
    $objectJson=($object);   //  json_encode
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,FALSE);
    //curl_setopt($ch,CURLOPT_POSTFIELDS,$objectJson); // Request we need call doesnt support this fields.
    curl_setopt($ch,CURLOPT_HTTPHEADER,$requestHeaders);    
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_VERBOSE,TRUE);
    $responseBody=curl_exec($ch);    
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);    
    if($httpCode==200||$httpCode==201){
      $decoded=json_decode($responseBody);
      if(isset($decoded)){
        return $decoded;
      }else{
        return 'ERROR> No data found.';
      }      
    }
    return 'ERROR> '.$httpCode.$responseBody; 
    }  
  }