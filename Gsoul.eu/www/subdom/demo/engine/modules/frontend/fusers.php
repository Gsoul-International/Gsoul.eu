<?php
class FUsers extends Module{     
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title='Uživatelský účet';
    $action=getget('action','');
    if($action=='userLogIn'){$this->PageLogIn();}      
    elseif($action=='login'){$this->PageLogInPost();} 
    elseif($action=='fb-login'){$this->PageLogInFBPost();}      
    elseif($action=='userLogOut'){$this->PageLogout();}
    elseif($action=='userPassword'){$this->PagePassword();}
    elseif($action=='new-password'){$this->PageNewPassword();}   
    elseif($action=='userRegistration'){$this->PageRegistration();}
    elseif($action=='new-post'){$this->PageNewPost();} 
    elseif($action==''){$this->PageDetail();}  
    elseif($action=='userGsc'){$this->PageGSC();} 
    elseif($action=='userSettings'){$this->PageUserSettings();}                                       
    elseif($action=='save-post'){$this->PageSavePost();}                  
    elseif($action=='password'){$this->PagePasswordSave();}                           
    else{$this->Redirect();}    
    }
  public function PageLogIn(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title='Přihlášení uživatele';        
    $this->seo_keywords='Přihlášení uživatele';                
    $this->seo_description='Přihlášení uživatele';        
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('auserpassword',$this->anchor(array('action'=>'userPassword')));           
    $tpl->add('auserregistration',$this->anchor(array('action'=>'userRegistration')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'login')));
    $this->content=$tpl->fetch('frontend/users/userLogIn.tpl');  
    $this->execute(); 
    }    
  public function PageLogInPost(){$this->kernel->LoginUser(getpost('email',''),getpost('pass',''),'FUsers');}
  public function PageLogInFBPost(){
    $fbData=json_decode($_POST['userData']);    
    $fb_id=prepare_get_data_safely($fbData->id);
    $email=prepare_get_data_safely($fbData->email);
    $jmeno=prepare_get_data_safely($fbData->first_name);
    $prijmeni=prepare_get_data_safely($fbData->last_name);
    $fb_profile=prepare_get_data_safely($fbData->link);
    $fb_gender=prepare_get_data_safely($fbData->gender);
    $fb_locale=prepare_get_data_safely($fbData->locale);
    $fb_picture=prepare_get_data_safely($fbData->picture->data->url);
    if($fb_id==''||$email==''){$this->Redirect(array('action'=>'userLogIn','LoginError'=>'1'));}
    $exist_profile=$this->kernel->models->DBusers->getLine('uid,email,fb_id,pocet_prihlaseni','WHERE fb_id="'.$fb_id.'"');    
    if($exist_profile->uid>0){
      $this->kernel->models->DBusers->Mquery('UPDATE users SET session="'.session_id().'",posledni_prihlaseni="'.time().'",posledni_prihlaseni_ip="'.$_SERVER['REMOTE_ADDR'].'",pocet_prihlaseni="'.($exist_profile->pocet_prihlaseni+1).'" WHERE uid="'.$exist_profile->uid.'"');
      $this->Redirect();
      exit();   
      }
    $exist_mail_uid=$this->kernel->models->DBusers->getLine('uid,email,fb_id,pocet_prihlaseni','WHERE email="'.$email.'" AND fb_id IS NULL');
    if($exist_mail_uid>0){    
      $this->kernel->models->DBusers->Mquery('UPDATE users SET session="'.session_id().'",posledni_prihlaseni="'.time().'",posledni_prihlaseni_ip="'.$_SERVER['REMOTE_ADDR'].'",pocet_prihlaseni="'.($exist_profile->pocet_prihlaseni+1).'",fb_id="'.$fb_id.'",fb_gender="'.$fb_gender.'",fb_locale="'.$fb_locale.'",fb_picture="'.$fb_picture.'",fb_profile="'.$fb_profile.'" WHERE uid="'.$exist_mail_uid->uid.'"');
      $this->Redirect();
      exit(); 
      }
    $newpass=hash('sha512',substr(md5('h3slo'.time().rand(10000,99999)),3,10));
    $this->kernel->models->DBusers->Mquery('INSERT INTO users 
    (session,posledni_prihlaseni,posledni_prihlaseni_ip,pocet_prihlaseni,prava,heslo,registrace,odber_novinek,email,osloveni,jmeno,prijmeni,fb_id,fb_gender,fb_locale,fb_picture,fb_profile) 
    VALUES 
    ("'.session_id().'","'.time().'","'.$_SERVER['REMOTE_ADDR'].'","1","0","'.$newpass.'","'.time().'","0","'.$email.'","'.$jmeno.' '.$prijmeni.'","'.$jmeno.'","'.$prijmeni.'","'.$fb_id.'","'.$fb_gender.'","'.$fb_locale.'","'.$fb_picture.'","'.$fb_profile.'") ');
    $this->Redirect();      
    exit();
    }
  public function PageLogout(){$this->kernel->LogoutUser('FUsers','user-success-logout');}          
  public function PagePassword(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title='Zapomenuté heslo';        
    $this->seo_keywords='Zapomenuté heslo';                
    $this->seo_description='Zapomenuté heslo';        
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('anewpassword',$this->anchor(array('action'=>'new-password')));
    $tpl->add('auserregistration',$this->anchor(array('action'=>'userRegistration')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'userLogIn')));
    $tpl->add('message',getget('message',''));
    $this->content=$tpl->fetch('frontend/users/userPassword.tpl');  
    $this->execute();     
    }
  public function PageNewPassword(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $email=prepare_get_data_safely(getpost('email',''));   
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$email.'"');
    if($exist_mail>0){
      $newpass=substr (md5('h3slo'.time().rand(10000,99999)),3,10);
      $this->kernel->models->DBusers->updateId($exist_mail,array('heslo_2'=>hash('sha512',$newpass)));    
      $mailer=new PHPMailer();;
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='Vygenerovali jsme Vám nové heslo';              
      $mailer->MsgHTML('Na Vaše přání jsme Vám vygenerovali nové heslo <b>'.$newpass.'</b> k uživatelskému účtu <b>'.$email.'</b>. Přihlásit se můžete také pod původním heslem.');
      $mailer->AddAddress($email);
      $mailer->Send();                   
      $this->Redirect(array('action'=>'userPassword','message'=>'password-send'));
      }
    $this->Redirect(array('action'=>'userPassword','message'=>'password-not-send'));
    }
  public function PageRegistration(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title='Registrace uživatele';        
    $this->seo_keywords='Registrace uživatele';                
    $this->seo_description='Registrace uživatele';        
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('auserpassword',$this->anchor(array('action'=>'userPassword')));
    $tpl->add('auserregistration',$this->anchor(array('action'=>'new-post')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'userLogIn')));
    $tpl->add('message',getget('message',''));
    $tpl->add('userreg',$_SESSION['frontend-new-user']);   
    $this->content=$tpl->fetch('frontend/users/userRegistration.tpl');  
    $this->execute();       
    } 
  public function PageNewPost(){  
    if($this->kernel->user->uid>0){$this->Redirect();}
    $postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){
      if($k!='heslo_1'&&$k!='heslo_2'){
        $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        $a=prepare_get_data_safely($k);
        $sesdata->$a=prepare_get_data_safely($v);
        }    
      } 
    $_SESSION['frontend-new-user']=$sesdata;   
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'"');
    if($exist_mail>0){$this->Redirect(array('action'=>'userRegistration','message'=>'email-exists'));}   
    if($postdata['email']==''){$this->Redirect(array('action'=>'userRegistration','message'=>'email-required'));}
    $heslo_1=getpost('heslo_1','');$heslo_2=getpost('heslo_2','');
    if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){$this->Redirect(array('action'=>'userRegistration','message'=>'password-short'));}
    if($heslo_1!=$heslo_2){$this->Redirect(array('action'=>'userRegistration','message'=>'password-not-same'));}
    $postdata['heslo']=hash('sha512',$heslo_1);    
    $postdata['registrace']=time();
    $_SESSION['frontend-new-user']=new stdClass();
    $uid=$this->kernel->models->DBusers->store(0,$postdata);        
    $this->Redirect(array('action'=>'userRegistration','message'=>'user-registered'));
    }
  public function PageDetail(){ 
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title='Uživatelský účet';        
    $this->seo_keywords='Uživatelský účet';        
    $this->seo_description='Uživatelský účet';
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('user',$this->kernel->user->data);   
    $tpl->add('mainpagename',$mainpagename);
    $this->content=$tpl->fetch('frontend/users/userDetail.tpl');  
    $this->execute();           
    }
  public function PageGSC(){
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title='Účetní zůstatek';        
    $this->seo_keywords='Účetní zůstatek';        
    $this->seo_description='Účetní zůstatek';
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $page=(int)getget('page','0');
    $counter=10;//10  
    $coins=$this->kernel->models->DBusersCoins->getLines('*',' WHERE idu="'.$this->kernel->user->uid.'" ORDER BY iduc DESC LIMIT '.($page*$counter).', '.$counter); 
    $coins_count=$this->kernel->models->DBusersCoins->getOne('count(iduc)','WHERE idu="'.$this->kernel->user->uid.'"');
    $paginnator=$this->GSCPaginnator($page,$coins_count,$counter);    
    $tpl=new Templater();
    $tpl->add('user',$this->kernel->user->data);
    $tpl->add('apayinusd',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in','currency'=>'usd'),false));
    $tpl->add('apayineur',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in','currency'=>'eur'),false));
    $tpl->add('apayinczk',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-in','currency'=>'czk'),false));
    $tpl->add('apayoutusd',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out','currency'=>'usd'),false));
    $tpl->add('apayouteur',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out','currency'=>'eur'),false));
    $tpl->add('apayoutczk',$this->Anchor(array('module'=>'FPaypal','action'=>'pay-out','currency'=>'czk'),false));
    $tpl->add('apaypal',$this->Anchor(array('module'=>'FPaypal'),false));
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('coins',$coins);     
    $tpl->add('mainpagename',$mainpagename);
    $this->content=$tpl->fetch('frontend/users/userGsc.tpl');  
    $this->execute();            
    }
  private function GSCPaginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('action'=>'userGsc','page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('action'=>'userGsc','page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('action'=>'userGsc','page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('action'=>'userGsc','page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('action'=>'userGsc','page'=>($page+1)),false);
    }    
    return $pages;          
    }    
  public function PageUserSettings(){
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title='Nastavení';        
    $this->seo_keywords='Nastavení';        
    $this->seo_description='Nastavení';
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('user',$this->kernel->user->data);   
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('asave',$this->anchor(array('action'=>'save-post')));
    $tpl->add('apassword',$this->anchor(array('action'=>'password')));
     $tpl->add('message',getget('message',''));
    $this->content=$tpl->fetch('frontend/users/userSettings.tpl');  
    $this->execute();                
    }      
  public function PageSavePost(){  
    if($this->kernel->user->uid>0){
      $postdata=array();
      foreach($_POST as $k=>$v){
        if($k=='prava'||$k=='heslo'||$k=='heslo_2'||$k=='uid'||$k=='session'||$k=='registrace'||$k=='posledni_prihlaseni'||$k=='posledni_prihlaseni_ip'||$k=='pocet_prihlaseni'){continue;}              
        $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);    
        }
      $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'" AND uid!="'.$this->kernel->user->uid.'"');
      if($exist_mail>0){$this->Redirect(array('action'=>'userSettings','message'=>'email-exists'),false);} 
      if($postdata['email']==''){$this->Redirect(array('action'=>'userSettings','message'=>'email-required'),false);}      
      $this->kernel->models->DBusers->updateId($this->kernel->user->uid,$postdata);        
      }      
    $this->Redirect(array('action'=>'userSettings','message'=>'user-saved'));    
    }
  public function PagePasswordSave(){  
    if($this->kernel->user->uid>0){
      $heslo_1=getpost('heslo_1','');$heslo_2=getpost('heslo_2','');
      if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){$this->Redirect(array('action'=>'userSettings','message'=>'password-short'));}
      if($heslo_1!=$heslo_2){$this->Redirect(array('action'=>'userSettings','message'=>'password-not-same'));}
      $this->kernel->models->DBusers->updateId($this->kernel->user->uid,array('heslo'=>hash('sha512',$heslo_1)));  
      }
    $this->Redirect(array('action'=>'userSettings','message'=>'password-saved'));
    }    
  }