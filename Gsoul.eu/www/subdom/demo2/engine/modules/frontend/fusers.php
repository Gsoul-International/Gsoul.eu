<?php
class FUsers extends Module{     
  public function Main(){ 
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];
    $action=getget('action','');
    if($action=='userLogIn'){$this->PageLogIn();}      
    elseif($action=='login'){$this->PageLogInPost();} 
    elseif($action=='fb-login'){$this->PageLogInFBPost();} 
    elseif($action=='email-reset'){$this->PageEmailResetPost();}  
    elseif($action=='email-paypal-reset'){$this->PageEmailResetPayPalPost();}      
    elseif($action=='email-confirm'){$this->PageEmailConfirmPost();}  
    elseif($action=='email-paypal-confirm'){$this->PageEmailConfirmPayPalPost();}                          
    elseif($action=='userLogOut'){$this->PageLogout();}
    elseif($action=='userPassword'){$this->PagePassword();}
    elseif($action=='new-password'){$this->PageNewPassword();}   
    elseif($action=='userRegistration'){$this->PageRegistration();}
    elseif($action=='new-post'){$this->PageNewPost();} 
    elseif($action==''){$this->PageUserSettings();}  
    elseif($action=='userGsc'){$this->PageGSC();} 
    elseif($action=='userSettings'){$this->PageUserSettings();}                                       
    elseif($action=='save-post'){$this->PageSavePost();}                  
    elseif($action=='password'){$this->PagePasswordSave();}      
    elseif($action=='delete-avatar-post'){$this->PageDeleteAvatarPost();}    
    elseif($action=='userCalendar'){$this->PageUserCalendar();}
    elseif($action=='change-language'){$this->PageChangeLanguage();}                           
    else{$this->Redirect();}    
    }
  public function PageChangeLanguage(){
    $i=(int)getget('i');
    if($i>0){
      $_SESSION['language']=$i;
      if($this->kernel->user->uid>0){
        if($this->kernel->user->data->last_selected_language!=$i){
          $this->kernel->models->DBusers->store($this->kernel->user->uid,array('last_selected_language'=>$i));
          }
        } 
      }
    header("Location: /");
    exit();
    }
  public function PageLogIn(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_prihlaseni_uzivatele'];        
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_prihlaseni_uzivatele'];                
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_prihlaseni_uzivatele'];        
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('auserpassword',$this->anchor(array('action'=>'userPassword')));           
    $tpl->add('auserregistration',$this->anchor(array('action'=>'userRegistration')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'login')));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
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
      $this->kernel->models->DBusers->Mquery('UPDATE users SET session="'.session_id().'",posledni_prihlaseni="'.time().'",posledni_prihlaseni_ip="'.$_SERVER['REMOTE_ADDR'].'",pocet_prihlaseni="'.($exist_profile->pocet_prihlaseni+1).'",fb_gender="'.$fb_gender.'",fb_locale="'.$fb_locale.'",fb_picture="'.$fb_picture.'",fb_profile="'.$fb_profile.'" WHERE uid="'.$exist_profile->uid.'"');
      $this->Redirect();
      exit();   
      }
    $exist_mail_uid=$this->kernel->models->DBusers->getLine('*','WHERE email="'.$email.'" AND fb_id IS NULL ');    
    if($exist_mail_uid->uid>0){    
      $this->kernel->models->DBusers->Mquery('UPDATE users SET session="'.session_id().'",posledni_prihlaseni="'.time().'",posledni_prihlaseni_ip="'.$_SERVER['REMOTE_ADDR'].'",pocet_prihlaseni="'.($exist_profile->pocet_prihlaseni+1).'",fb_id="'.$fb_id.'",fb_gender="'.$fb_gender.'",fb_locale="'.$fb_locale.'",fb_picture="'.$fb_picture.'",fb_profile="'.$fb_profile.'" WHERE uid="'.$exist_mail_uid->uid.'"');      
      $this->Redirect();
      exit(); 
      }    
    $newpass=saltHashSha(substr(md5('h3slo'.time().rand(10000,99999)),3,10),rand(10000,99999),rand(10000,99999),'SaltOfGSoulEUthisPasswordWillBeUseNever');
    $this->kernel->models->DBusers->Mquery('INSERT INTO users 
    (session,posledni_prihlaseni,posledni_prihlaseni_ip,pocet_prihlaseni,prava,heslo,registrace,odber_novinek,email,email_paypal,osloveni,jmeno,prijmeni,fb_id,fb_gender,fb_locale,fb_picture,fb_profile) 
    VALUES 
    ("'.session_id().'","'.time().'","'.$_SERVER['REMOTE_ADDR'].'","1","0","'.$newpass.'","'.time().'","0","'.$email.'","'.$email.'","'.$jmeno.' '.$prijmeni.'","'.$jmeno.'","'.$prijmeni.'","'.$fb_id.'","'.$fb_gender.'","'.$fb_locale.'","'.$fb_picture.'","'.$fb_profile.'") ');
    $exist_mail_uid=$this->kernel->models->DBusers->getLine('uid,email,fb_id,pocet_prihlaseni','WHERE fb_id = "'.$fb_id.'" ');
    $mailer=new PHPMailer();
    $mailer->CharSet="UTF-8"; 
    $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
    $mailer->Subject=$this->kernel->systemTranslator['uzivatel_overeni_emailu_u_vaseho_zakaznickeho_uctu'];           
    $mailer->MsgHTML(
    $this->kernel->systemTranslator['uzivatel_dekujeme_za_registraci_nyni_overte_emailove_ucty']. 
    '<br />  
    '.$this->kernel->systemTranslator['uzivatel_pro_overeni_emailu_kliknete_zde'].
    ': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$exist_mail_uid->uid,'h'=>md5('gsoul-'.$exist_mail_uid->uid.'-'.strftime('%m-%Y-',time()).$email),'m'=>$email)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$exist_mail_uid->uid,'h'=>md5('gsoul-'.$exist_mail_uid->uid.'-'.strftime('%m-%Y-',time()).$email),'m'=>$email)).'</a>.<br />       
    '.$this->kernel->systemTranslator['uzivatel_pro_overeni_paypal_emailu_kliknete_zde'].
    ': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$exist_mail_uid->uid,'h'=>md5('gsoul-'.$exist_mail_uid->uid.'-'.strftime('%m-%Y-',time()).$email),'m'=>$email)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$exist_mail_uid->uid,'h'=>md5('gsoul-'.$exist_mail_uid->uid.'-'.strftime('%m-%Y-',time()).$email),'m'=>$email)).'</a>
    '.$this->kernel->systemTranslator['obecne_dekujeme'].'
    .');    
    $mailer->AddAddress($email);  
    $mailer->Send();        
    $this->Redirect();      
    exit();
    }
  public function PageLogout(){
    //$this->kernel->LogoutUser('FUsers','user-success-logout');
    if($this->kernel->user->uid>0){
      $this->kernel->models->DBusers->Mquery('UPDATE users SET session="" WHERE uid="'.$this->kernel->user->uid.'"');
      $this->Redirect(array('action'=>'userLogIn','message'=>'user-success-logout'));
      }          
    $this->Redirect(array('action'=>'userLogIn'));
    }          
  public function PagePassword(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_zapomenute_heslo'];        
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_zapomenute_heslo'];                
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_zapomenute_heslo'];        
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('anewpassword',$this->anchor(array('action'=>'new-password')));
    $tpl->add('auserregistration',$this->anchor(array('action'=>'userRegistration')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'userLogIn')));
    $tpl->add('message',getget('message',''));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/users/userPassword.tpl');  
    $this->execute();     
    }
  public function PageNewPassword(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $email=prepare_get_data_safely(getpost('email',''));   
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$email.'"');
    if($exist_mail>0){
      $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$exist_mail.'"');
      $newpass=substr (md5('h3slo'.time().rand(10000,99999)),3,10);
      $this->kernel->models->DBusers->updateId($exist_mail,array('heslo_2'=>saltHashSha($newpass,$user->uid,$user->registrace,'SaltOfGSoulEU')));    
      $mailer=new PHPMailer();
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject=$this->kernel->systemTranslator['uzivatel_email_vygenerovali_jsme_vam_nove_heslo'];              
      $mailer->MsgHTML(
      $this->kernel->systemTranslator['uzivatel_na_vase_prani_jsme_vam_vygenerovali_nove_heslo'].
      ' <b>'.$newpass.'</b> '.
      $this->kernel->systemTranslator['uzivatel_k_uzivatelskemu_uctu'].                                
      ' <b>'.$email.'</b>. '.
      $this->kernel->systemTranslator['uzivatel_prihlasit_se_muzete_take_pod_svym_puvodnim_heslem']      
      );
      $mailer->AddAddress($email);
      $mailer->Send();                   
      $this->Redirect(array('action'=>'userPassword','message'=>'password-send'));
      }
    $this->Redirect(array('action'=>'userPassword','message'=>'password-not-send'));
    }
  public function PageRegistration(){
    if($this->kernel->user->uid>0){$this->Redirect();}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_registrace_uzivatele'];        
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_registrace_uzivatele'];                    
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_registrace_uzivatele'];               
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('auserpassword',$this->anchor(array('action'=>'userPassword')));
    $tpl->add('auserregistration',$this->anchor(array('action'=>'new-post')));       
    $tpl->add('alogin',$this->anchor(array('action'=>'userLogIn')));
    $tpl->add('message',getget('message',''));
    $tpl->add('userreg',$_SESSION['frontend-new-user']);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);   
    $this->content=$tpl->fetch('frontend/users/userRegistration.tpl');  
    $this->execute();       
    } 
  public function PageNewPost(){  
    if($this->kernel->user->uid>0){$this->Redirect();}
    $postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){
      if($k!='heslo_1'&&$k!='heslo_2'&&$k!='terms_agree'){
        $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        $a=prepare_get_data_safely($k);
        $sesdata->$a=prepare_get_data_safely($v);
        }    
      } 
    $heslo_1=getpost('heslo_1','');$heslo_2=getpost('heslo_2','');$terms_agree=getpost('terms_agree','');
    $sesdata->heslo_1=$heslo_1;$sesdata->heslo_2=$heslo_2;$sesdata->terms_agree=$terms_agree;
    $_SESSION['frontend-new-user']=$sesdata;   
    if($terms_agree!=1){$this->Redirect(array('action'=>'userRegistration','message'=>'confirm-required'));}
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'"');
    if($exist_mail>0){$this->Redirect(array('action'=>'userRegistration','message'=>'email-exists'));} 
    if($exist_mail>0){$this->Redirect(array('action'=>'userRegistration','message'=>'email-exists'));}
    if($postdata['email']==''||$postdata['email_paypal']==''){$this->Redirect(array('action'=>'userRegistration','message'=>'email-required'));}        
    if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){$this->Redirect(array('action'=>'userRegistration','message'=>'password-short'));}
    if($heslo_1!=$heslo_2){$this->Redirect(array('action'=>'userRegistration','message'=>'password-not-same'));}
    $postdata['heslo']='NotSetYet';    
    $postdata['registrace']=time();
    $_SESSION['frontend-new-user']=new stdClass();
    $uid=$this->kernel->models->DBusers->store(0,$postdata); 
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');    
    $this->kernel->models->DBusers->updateId($uid,array('heslo'=>saltHashSha($heslo_1,$user->uid,$user->registrace,'SaltOfGSoulEU')));  
    $mailer=new PHPMailer();
    $mailer->CharSet="UTF-8"; 
    $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
    $mailer->Subject=$this->kernel->systemTranslator['uzivatel_overeni_emailu_u_vaseho_zakaznickeho_uctu'];                     
    $mailer->MsgHTML($this->kernel->systemTranslator['uzivatel_dekujeme_za_registraci_nyni_overte_emailove_ucty']. 
    '<br />  
    '.$this->kernel->systemTranslator['uzivatel_pro_overeni_emailu_kliknete_zde'].
    ': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$uid,'h'=>md5('gsoul-'.$uid.'-'.strftime('%m-%Y-',time()).$postdata['email']),'m'=>$postdata['email'])).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$uid,'h'=>md5('gsoul-'.$uid.'-'.strftime('%m-%Y-',time()).$postdata['email']),'m'=>$postdata['email'])).'</a>.<br />       
    '.$this->kernel->systemTranslator['uzivatel_pro_overeni_paypal_emailu_kliknete_zde'].
    ': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$uid,'h'=>md5('gsoul-'.$uid.'-'.strftime('%m-%Y-',time()).$postdata['email']),'m'=>$postdata['email'])).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$uid,'h'=>md5('gsoul-'.$uid.'-'.strftime('%m-%Y-',time()).$postdata['email']),'m'=>$postdata['email'])).'</a>
    '.$this->kernel->systemTranslator['obecne_dekujeme'].'.');
    $mailer->AddAddress($postdata['email']);
    $mailer->Send();
    $this->kernel->LoginUser($postdata['email'],$heslo_1,'FUsers');        
    $this->Redirect(array('action'=>'userRegistration','message'=>'user-registered'));
    }
  public function PageDetail(){ 
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];              
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];        
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('user',$this->kernel->user->data);   
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/users/userDetail.tpl');  
    $this->execute();           
    }
  public function PageGSC(){
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_ucetni_zustatek'];       
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_ucetni_zustatek'];               
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_ucetni_zustatek'];       
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
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
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
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];       
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];     
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_uzivatelsky_ucet'];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tpl=new Templater();
    $tpl->add('user',$this->kernel->user->data);   
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('asave',$this->anchor(array('action'=>'save-post')));
    $tpl->add('adelavatar',$this->anchor(array('action'=>'delete-avatar-post')));
    $tpl->add('apassword',$this->anchor(array('action'=>'password')));
    $tpl->add('message',getget('message',''));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/users/userSettings.tpl');  
    $this->execute();                
    }      
  public function PageSavePost(){  
    if($this->kernel->user->uid>0){
      $postdata=array();
      foreach($_POST as $k=>$v){
        if($k=='prava'||$k=='heslo'||$k=='heslo_2'||$k=='uid'||$k=='session'||$k=='registrace'||$k=='posledni_prihlaseni'||$k=='posledni_prihlaseni_ip'||$k=='pocet_prihlaseni'||$k=='avatar'){continue;}              
        $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);    
        }
      $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'" AND uid!="'.$this->kernel->user->uid.'"');
      if($exist_mail>0){$this->Redirect(array('action'=>'userSettings','message'=>'email-exists'),false);} 
      if($postdata['email']==''||$postdata['email_paypal']==''){$this->Redirect(array('action'=>'userSettings','message'=>'email-required'),false);}                              
      $this->kernel->models->DBusers->updateId($this->kernel->user->uid,$postdata);         
      if($postdata['email']!=$this->kernel->user->data->email){            
        $mailer=new PHPMailer();
        $mailer->CharSet="UTF-8"; 
        $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
        $mailer->Subject=$this->kernel->systemTranslator['uzivatel_zmena_emailu_u_vaseho_z_uctu'];              
        $mailer->MsgHTML($this->kernel->systemTranslator['uzivatel_u_vaseho_uctu_doslo_ke_zmene_mailu'].' '.$this->kernel->systemTranslator['obecne_z_ze'].' '.$this->kernel->user->data->email.' '.$this->kernel->systemTranslator['obecne_na'].' '.$postdata['email'].'.<br /> 
        '.$this->kernel->systemTranslator['uzivatel_pokud_si_prejete_vratit_zmenu_emailu_zpet_kliknete_sem'].': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-reset','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email),'m'=>$this->kernel->user->data->email)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-reset','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email),'m'=>$this->kernel->user->data->email)).'</a>.<br />
        '.$this->kernel->systemTranslator['uzivatel_pokud_si_prejete_email_overit_kliknete_sem'].': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email),'m'=>$this->kernel->user->data->email)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-confirm','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email),'m'=>$this->kernel->user->data->email)).'</a>.<br />
        '.$this->kernel->systemTranslator['uzivatel_pokud_jste_zmenu_neprovedli_zmente_si_hesla'].'');
        $mailer->AddAddress($this->kernel->user->data->email);
        $mailer->Send();
        $this->kernel->models->DBusers->updateId($this->kernel->user->uid,array('overen_email'=>'0')); 
        }       
      if($postdata['email_paypal']!=$this->kernel->user->data->email_paypal){            
        $mailer=new PHPMailer();;
        $mailer->CharSet="UTF-8"; 
        $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
        $mailer->Subject=$this->kernel->systemTranslator['uzivatel_zmena_paypal_emailu_u_vaseho_z_uctu'];        
        $mailer->MsgHTML($this->kernel->systemTranslator['uzivatel_u_vaseho_uctu_doslo_ke_zmene_paypal_mailu'].' '.$this->kernel->systemTranslator['obecne_z_ze'].' '.$this->kernel->user->data->email_paypal.' '.$this->kernel->systemTranslator['obecne_na'].' '.$postdata['email_paypal'].'.<br /> 
        '.$this->kernel->systemTranslator['uzivatel_pokud_si_prejete_vratit_zmenu_emailu_zpet_kliknete_sem'].': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-reset','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email_paypal),'m'=>$this->kernel->user->data->email_paypal)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-reset','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email_paypal),'m'=>$this->kernel->user->data->email_paypal)).'</a>.<br />
        '.$this->kernel->systemTranslator['uzivatel_pokud_si_prejete_email_overit_kliknete_sem'].': <a href="'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email_paypal),'m'=>$this->kernel->user->data->email_paypal)).'">'.$this->kernel->config->domain_http.$this->Anchor(array('action'=>'email-paypal-confirm','i'=>$this->kernel->user->uid,'h'=>md5('gsoul-'.$this->kernel->user->uid.'-'.strftime('%m-%Y-',time()).$this->kernel->user->data->email_paypal),'m'=>$this->kernel->user->data->email_paypal)).'</a>
        '.$this->kernel->systemTranslator['uzivatel_pokud_jste_zmenu_neprovedli_zmente_si_hesla'].'');
        $mailer->AddAddress($this->kernel->user->data->email_paypal);
        $mailer->Send(); 
        $this->kernel->models->DBusers->updateId($this->kernel->user->uid,array('overen_paypal_email'=>'0')); 
        }  
      $file=$_FILES["avatar"];
      if($file["error"]<=0){
        $pripony=explode(',',$this->kernel->settings['povolene_pripony_obrazku']);
        $original_name=explode('.',$file["name"]);
        $suffix=strtolower(end($original_name));
        if(in_array($suffix,$pripony)){
          if(filesize($file["tmp_name"])<=(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){
            $suffix='png';   
            $x=time();
            if(move_uploaded_file($file["tmp_name"],'img/userfiles/avatars/'.md5($this->kernel->user->uid.$x).'_.'.$suffix)){
              $phpThumb=new MHMthumb();
              $is=$phpThumb->thumb('img/userfiles/avatars/'.md5($this->kernel->user->uid.$x).'_.'.$suffix,'img/userfiles/avatars/'.md5($this->kernel->user->uid.$x).'.'.$suffix,50,50,false,25,25,25);         
              $this->kernel->models->DBusers->updateId($this->kernel->user->uid,array('user_picture'=>'img/userfiles/avatars/'.md5($this->kernel->user->uid.$x).'.'.$suffix));         
              }
            }  
          }     
        }          
      }            
    $this->Redirect(array('action'=>'userSettings','message'=>'user-saved'));    
    }
  public function PagePasswordSave(){  
    if($this->kernel->user->uid>0){
      $heslo_1=getpost('heslo_1','');$heslo_2=getpost('heslo_2','');
      if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){$this->Redirect(array('action'=>'userSettings','message'=>'password-short'));}
      if($heslo_1!=$heslo_2){$this->Redirect(array('action'=>'userSettings','message'=>'password-not-same'));}
      $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$this->kernel->user->uid.'"');
      $this->kernel->models->DBusers->updateId($user->uid,array('heslo'=>saltHashSha($heslo_1,$user->uid,$user->registrace,'SaltOfGSoulEU')));        
      }
    $this->Redirect(array('action'=>'userSettings','message'=>'password-saved'));
    }
  public function PageEmailResetPost(){
    $i=(int)getget('i');
    $h=getget('h');
    $m=urldecode(getget('m'));
    $tpl=new Templater();
    if( md5('gsoul-'.$i.'-'.strftime('%m-%Y-',time()).$m) == $h && $i > 0 ){
      $this->kernel->models->DBusers->updateId($i,array('email'=>strip_tags(addslashes($m)))); 
      $tpl->add('message','ok');
    }else{
      $tpl->add('message','ko');
    }   
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);         
    $this->content=$tpl->fetch('frontend/users/userMailResets.tpl');  
    $this->execute();               
    }    
  public function PageEmailResetPayPalPost(){
    $i=(int)getget('i');
    $h=getget('h');
    $m=urldecode(getget('m'));
    $tpl=new Templater();
    if( md5('gsoul-'.$i.'-'.strftime('%m-%Y-',time()).$m) == $h && $i > 0 ){
      $this->kernel->models->DBusers->updateId($i,array('email_paypal'=>strip_tags(addslashes($m)))); 
      $tpl->add('message','pok');
    }else{
      $tpl->add('message','pko');
    }        
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);    
    $this->content=$tpl->fetch('frontend/users/userMailResets.tpl');  
    $this->execute();               
    }         
  public function PageEmailConfirmPost(){
    $i=(int)getget('i');
    $h=getget('h');
    $m=urldecode(getget('m'));
    $tpl=new Templater();
    if( md5('gsoul-'.$i.'-'.strftime('%m-%Y-',time()).$m) == $h && $i > 0 ){
      $this->kernel->models->DBusers->updateId($i,array('overen_email'=>1)); 
      $tpl->add('message','ok');
    }else{
      $tpl->add('message','ko');
    }     
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);       
    $this->content=$tpl->fetch('frontend/users/userMailConfirm.tpl');  
    $this->execute();               
    }             
  public function PageEmailConfirmPayPalPost(){
    $i=(int)getget('i');
    $h=getget('h');
    $m=urldecode(getget('m'));
    $tpl=new Templater();
    if( md5('gsoul-'.$i.'-'.strftime('%m-%Y-',time()).$m) == $h && $i > 0 ){
      $this->kernel->models->DBusers->updateId($i,array('overen_paypal_email'=>1)); 
      $tpl->add('message','pok');
    }else{
      $tpl->add('message','pko');
    }            
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/users/userMailConfirm.tpl');  
    $this->execute();               
    }         
  public function PageDeleteAvatarPost(){    
    if($this->kernel->user->uid>0){    
      $this->kernel->models->DBusers->updateId($this->kernel->user->uid,array('user_picture'=>'')); 
      }
    $this->Redirect(array('action'=>'userSettings','message'=>'password-saved'));
    }
  public function PageUserCalendar(){
    if($this->kernel->user->uid<1){$this->Redirect(array('action'=>'userLogIn'));}
    $this->seo_title=$this->kernel->systemTranslator['uzivatel_kalendar'];
    $this->seo_keywords=$this->kernel->systemTranslator['uzivatel_kalendar'];     
    $this->seo_description=$this->kernel->systemTranslator['uzivatel_kalendar'];
    $day=(int)getget('day');
    $month=(int)getget('month');
    $year=(int)getget('year');
    if($day<1){$day=strftime('%d',time());}
    if($month<1){$month=strftime('%m',time());}
    if($year<1){$year=strftime('%Y',time());} 
    if($day>cal_days_in_month(CAL_GREGORIAN,$month,$year)){$day=cal_days_in_month(CAL_GREGORIAN,$month,$year);}   
    $tournamentscups=(int)getget('tc');
    if($tournamentscups<0){$tournamentscups=0;}
    if($tournamentscups>2){$tournamentscups=2;}
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $data2=array();
    if($tournamentscups==0||$tournamentscups==1){
      $data=$this->kernel->models->DBusers->MqueryGetLines('SELECT gt.idt, gt.datum_cas_startu, g.nazev as GameName, gt.dohrano as dohrano, gt.titul_turnaje as Titulek FROM games_tournaments_players as gtp, games_tournaments as gt, games as g WHERE gt.skryty=0 AND gtp.id_hrace="'.$this->kernel->user->uid.'" AND gtp.id_turnaje=gt.idt AND g.idg=gt.id_hry ORDER BY gt.datum_cas_startu ASC');
      foreach($data as $dt){
        $key=(int)strftime('%d',$dt->datum_cas_startu).'-'.(int)strftime('%m',$dt->datum_cas_startu).'-'.(int)strftime('%Y',$dt->datum_cas_startu);
        $dt->aview=$this->Anchor(array('module'=>'FTournaments','action'=>'tournament-view','idt'=>$dt->idt)); 
        $dt->typ=1;
        $data2[$key]['t'.$dt->idt]=$dt;
        } 
      unset($data); 
      $data=$this->kernel->models->DBusers->MqueryGetLines('SELECT gt.idt, gt.datum_cas_startu, g.nazev as GameName, gt.dohrano as dohrano, gt.titul_turnaje as Titulek FROM games_tournaments as gt, games as g WHERE gt.skryty=0 AND gt.id_uzivatele="'.$this->kernel->user->uid.'" AND g.idg=gt.id_hry ORDER BY gt.datum_cas_startu ASC');
      foreach($data as $dt){
        $key=(int)strftime('%d',$dt->datum_cas_startu).'-'.(int)strftime('%m',$dt->datum_cas_startu).'-'.(int)strftime('%Y',$dt->datum_cas_startu);
        $dt->aview=$this->Anchor(array('module'=>'FTournaments','action'=>'tournament-view','idt'=>$dt->idt)); 
        $dt->typ=1;
        $data2[$key]['t'.$dt->idt]=$dt;
        } 
      unset($data); 
      }
    if($tournamentscups==0||$tournamentscups==2){
      $data=$this->kernel->models->DBusers->MqueryGetLines('SELECT gt.idc, gt.datum_cas_startu, g.nazev as GameName, gt.dohrano as dohrano, gt.titulek_cupu as Titulek FROM games_cups_players as gtp, games_cups as gt, games as g WHERE gt.skryty=0 AND gtp.id_hrace="'.$this->kernel->user->uid.'" AND gtp.id_cupu=gt.idc AND g.idg=gt.id_hry ORDER BY gt.datum_cas_startu ASC');
      foreach($data as $dt){
        $key=(int)strftime('%d',$dt->datum_cas_startu).'-'.(int)strftime('%m',$dt->datum_cas_startu).'-'.(int)strftime('%Y',$dt->datum_cas_startu);
        $dt->aview=$this->Anchor(array('module'=>'FCups','action'=>'cup-view','idt'=>$dt->idc)); 
        $dt->typ=2;
        $data2[$key]['c'.$dt->idc]=$dt;
        } 
      unset($data); 
      $data=$this->kernel->models->DBusers->MqueryGetLines('SELECT gt.idc, gt.datum_cas_startu, g.nazev as GameName, gt.dohrano as dohrano, gt.titulek_cupu as Titulek FROM  games_cups as gt, games as g WHERE gt.skryty=0 AND gt.id_uzivatele="'.$this->kernel->user->uid.'" AND g.idg=gt.id_hry ORDER BY gt.datum_cas_startu ASC');
      foreach($data as $dt){
        $key=(int)strftime('%d',$dt->datum_cas_startu).'-'.(int)strftime('%m',$dt->datum_cas_startu).'-'.(int)strftime('%Y',$dt->datum_cas_startu);
        $dt->aview=$this->Anchor(array('module'=>'FCups','action'=>'cup-view','idt'=>$dt->idc)); 
        $dt->typ=2;
        $data2[$key]['c'.$dt->idc]=$dt;
        } 
      unset($data); 
      }
    $daysLinks=array();
    for($i=1;$i<=31;$i++){$daysLinks[$i]=$this->Anchor(array('action'=>'userCalendar','year'=>($year),'month'=>($month),'day'=>$i));} 
    $tpl=new Templater();
    $tpl->add('d',$day);
    $tpl->add('m',$month);
    $tpl->add('y',$year);
    $tpl->add('tc',$tournamentscups);
    $tpl->add('daysLinks',$daysLinks);
    $tpl->add('ayearplus',$this->Anchor(array('action'=>'userCalendar','year'=>($year+1),'month'=>($month),'day'=>$day)));
    $tpl->add('ayearminus',$this->Anchor(array('action'=>'userCalendar','year'=>($year-1),'month'=>($month),'day'=>$day)));
    if($month<12){
      $tpl->add('amonthplus',$this->Anchor(array('action'=>'userCalendar','year'=>($year),'month'=>($month+1),'day'=>$day)));
    }else{
      $tpl->add('amonthplus',$this->Anchor(array('action'=>'userCalendar','year'=>($year+1),'month'=>1,'day'=>$day)));
    }
    if($month>1){
      $tpl->add('amonthminus',$this->Anchor(array('action'=>'userCalendar','year'=>($year),'month'=>($month-1),'day'=>$day)));
    }else{
      $tpl->add('amonthminus',$this->Anchor(array('action'=>'userCalendar','year'=>($year-1),'month'=>12,'day'=>$day)));
    }
    $tpl->add('aTournamentsCups',$this->Anchor(array('action'=>'userCalendar','year'=>$year,'month'=>$month,'day'=>$day,'tc'=>0)));
    $tpl->add('aOnlyTournaments',$this->Anchor(array('action'=>'userCalendar','year'=>$year,'month'=>$month,'day'=>$day,'tc'=>1)));
    $tpl->add('aOnlyCups',$this->Anchor(array('action'=>'userCalendar','year'=>$year,'month'=>$month,'day'=>$day,'tc'=>2)));
    $tpl->add('user',$this->kernel->user->data);
    $tpl->add('data',$data2);   
    $tpl->add('systemTranslator',$this->kernel->systemTranslator); 
    $tpl->add('mainpagename',$mainpagename);    
    $this->content=$tpl->fetch('frontend/users/userCalendary.tpl');  
    $this->execute();                
    }   
  }