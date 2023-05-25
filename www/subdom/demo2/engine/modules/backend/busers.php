<?php
class BUsers extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    $this->rights=new stdClass();
    }
  public function Main(){
    $this->seo_title='Uživatelé';
    $this->rights=$this->getUserRights();
    if(!in_array('users_views',$this->rights->povoleneKody)&&!in_array('users_payouts_view',$this->rights->povoleneKody)&&!in_array('users_newsletter_send',$this->rights->povoleneKody)&&!in_array('users_export',$this->rights->povoleneKody)&&!in_array('users_export_emails',$this->rights->povoleneKody)&&!in_array('users_export_phones',$this->rights->povoleneKody)&&!in_array('users_newsletter_logouts_views',$this->rights->povoleneKody)){
			$this->Redirect(array('module'=>'Backend'),false);
			}	    
    if(in_array('users_newsletter_logouts_views',$this->rights->povoleneKody)){$action=getget('action','newsletter-logouts');}
    if(in_array('users_export_phones',$this->rights->povoleneKody)){$action=getget('action','export-phones');}
    if(in_array('users_export_emails',$this->rights->povoleneKody)){$action=getget('action','export-emails');}
    if(in_array('users_export',$this->rights->povoleneKody)){$action=getget('action','export');}
    if(in_array('users_newsletter_send',$this->rights->povoleneKody)){$action=getget('action','newsletter');}
    if(in_array('users_payouts_view',$this->rights->povoleneKody)){$action=getget('action','payouts');}
    if(in_array('users_views',$this->rights->povoleneKody)){$action=getget('action','list');}    
    if($action=='list'&&in_array('users_views',$this->rights->povoleneKody)){$this->PageList();}
    elseif($action=='detail'&&in_array('users_views',$this->rights->povoleneKody)){$this->PageView();}
    elseif($action=='coins'&&in_array('users_payments_views',$this->rights->povoleneKody)){$this->PageCoins();}
    elseif($action=='add-coins'&&in_array('users_payments_changes',$this->rights->povoleneKody)){$this->PageAddCoins();}
    elseif($action=='gobyid'&&in_array('users_views',$this->rights->povoleneKody)){$this->PageGoBy('id');}
    elseif($action=='gobyemail'&&in_array('users_views',$this->rights->povoleneKody)){$this->PageGoBy('email');}
    elseif($action=='edit'&&in_array('users_views',$this->rights->povoleneKody)){$this->PageEdit();}
    elseif($action=='save'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageSave();}
    elseif($action=='delete-avatar'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageDeleteAvatar();}
    elseif($action=='change-pass'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageChangePass();}
    elseif($action=='new'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageNew();}      
    elseif($action=='new-post'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageNewPost();} 
    elseif($action=='delete'&&in_array('users_creates_edit',$this->rights->povoleneKody)){$this->PageDelete();}
    elseif($action=='export'&&in_array('users_export',$this->rights->povoleneKody)){$this->PageExport();}
    elseif($action=='export-generate'&&in_array('users_export',$this->rights->povoleneKody)){$this->PageExportGenerate();}
    elseif($action=='export-emails'&&in_array('users_export_emails',$this->rights->povoleneKody)){$this->PageExportEmails();}
    elseif($action=='export-phones'&&in_array('users_export_phones',$this->rights->povoleneKody)){$this->PageExportPhones();}
    elseif($action=='newsletter'&&in_array('users_newsletter_send',$this->rights->povoleneKody)){$this->PageNewsletter();}
    elseif($action=='newsletter-post'&&in_array('users_newsletter_send',$this->rights->povoleneKody)){$this->PageNewsletterPost();}
    elseif($action=='newsletter-2'&&in_array('users_newsletter_send',$this->rights->povoleneKody)){$this->PageNewsletter2();}
    elseif($action=='newsletter-post-2'&&in_array('users_newsletter_send',$this->rights->povoleneKody)){$this->PageNewsletterPost2();}
    elseif($action=='payouts'&&in_array('users_payouts_view',$this->rights->povoleneKody)){$this->PagePayOuts();}
    elseif($action=='payment-not-pay-post'&&in_array('users_payouts_changes',$this->rights->povoleneKody)){$this->PagePayOutsDoNotPay();}
    elseif($action=='payment-pay-post'&&in_array('users_payouts_changes',$this->rights->povoleneKody)){$this->PagePayOutsDoPay();}
    elseif($action=='newsletter-logouts'&&in_array('users_newsletter_logouts_views',$this->rights->povoleneKody)){$this->PageNewslettersLogouts();}
    elseif($action=='newsletter-logouts-set'&&in_array('users_newsletter_logouts_changes',$this->rights->povoleneKody)){$this->PageNewslettersLogoutsSet();}
    elseif($action=='change-rights'&&in_array('users_change_admins',$this->rights->povoleneKody)){$this->PageChangeAdminRights();}
    else{$this->Redirect();}    
    }
  private function PageGoBy($def='id'){
    $obj=prepare_get_data_safely(getpost('data',''));
      if($def=='id'){
        $data=$this->kernel->models->DBusers->getOne('uid','WHERE uid="'.$obj.'" LIMIT 1');
      }else{
        $data=$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$obj.'" LIMIT 1');          
      }
      if($data>0){
        $this->Redirect(array('action'=>'detail','uid'=>$data),false);
      }else{
        $this->Redirect(array('action'=>'list','message'=>'user-not-found-2'),false);
      }
    }
  private function SetLeftMenu(){    
    $menu=array(
      $this->Anchor(array('action'=>'list'))=>'<span class="icon"><i class="fa fa-user"></i></span> Správa uživatelů',
      $this->Anchor(array('action'=>'payouts'))=>'<span class="icon"><i class="fa fa-usd"></i></span> Výplaty uživatelů',      
      $this->Anchor(array('action'=>'newsletter'))=>'<span class="icon"><i class="fa fa-envelope-o"></i></span> Odeslat newsletter',            
      $this->Anchor(array('action'=>'export'))=>'<span class="icon"><i class="fa fa-reply-all"></i></span> Exportovat uživatele',
      $this->Anchor(array('action'=>'export-emails'))=>'<span class="icon"><i class="fa fa-reply"></i></span> Exportovat e-maily uživatelů',
      $this->Anchor(array('action'=>'export-phones'))=>'<span class="icon"><i class="fa fa-reply"></i></span> Exportovat telefony uživatelů',
      $this->Anchor(array('action'=>'newsletter-logouts'))=>'<span class="icon"><i class="fa fa-envelope-o"></i></span> Odhlášené newslettery',      
      );
    if(!in_array('users_views',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'list'))]);}
    if(!in_array('users_payouts_view',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'payouts'))]);}
    if(!in_array('users_newsletter_send',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'newsletter'))]);}
    if(!in_array('users_export',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'export'))]);}
    if(!in_array('users_export_emails',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'export-emails'))]);}
    if(!in_array('users_export_phones',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'export-phones'))]);}
    if(!in_array('users_newsletter_logouts_views',$this->rights->povoleneKody)){unset($menu[$this->Anchor(array('action'=>'newsletter-logouts'))]);}	
		if(in_array('users_newsletter_logouts_views',$this->rights->povoleneKody)){$active='newsletter-logouts';}
    if(in_array('users_export_phones',$this->rights->povoleneKody)){$active='export-phones';}
    if(in_array('users_export_emails',$this->rights->povoleneKody)){$active='export-emails';}
    if(in_array('users_export',$this->rights->povoleneKody)){$active='export';}
    if(in_array('users_newsletter_send',$this->rights->povoleneKody)){$active='newsletter';}
    if(in_array('users_payouts_view',$this->rights->povoleneKody)){$active='payouts';}
    if(in_array('users_views',$this->rights->povoleneKody)){$active='list';}		    
    if(getget('action','')=='payouts'){$active='payouts';}
    if(getget('action','')=='export'){$active='export';}            
    if(getget('action','')=='export-emails'){$active='export-emails';}
    if(getget('action','')=='export-phones'){$active='export-phones';}      
    if(getget('action','')=='newsletter'){$active='newsletter';}      
    if(getget('action','')=='newsletter-logouts'){$active='newsletter-logouts';}
    if(getget('action','')=='newsletter-2'){$active='newsletter';}      
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/users/leftMenu.tpl');    
    }
  private function Paginnator($page=0,$count=0,$counter=0,$order='',$fletter=''){    
    $orderx='';  
    if($order=='uid'){$orderx='uid';}      
    if($order=='last_access'){$orderx='last_access';}
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('order'=>$orderx,'fletter'=>$fletter,'page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('order'=>$orderx,'fletter'=>$fletter,'page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('order'=>$orderx,'fletter'=>$fletter,'page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('order'=>$orderx,'fletter'=>$fletter,'page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('order'=>$orderx,'fletter'=>$fletter,'page'=>($page+1)),false);
    }    
    return $pages;          
    }
  private function PaginnatorCoins($page=0,$count=0,$counter=0,$uid=''){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('action'=>'coins','uid'=>$uid,'page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('action'=>'coins','uid'=>$uid,'page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('action'=>'coins','uid'=>$uid,'page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('action'=>'coins','uid'=>$uid,'page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('action'=>'coins','uid'=>$uid,'page'=>($page+1)),false);
    }    
    return $pages;          
    }
  private function PageList(){
    $fletter='';
    $where='';
    if(getget('fletter','')!=''){
      $x=trim(getget('fletter',''));
      $fletter=$x[0];    
      if($fletter!=''){
        $where.=' WHERE (prijmeni LIKE "'.$fletter.'%" OR jmeno LIKE "'.$fletter.'%" OR email LIKE "'.$fletter.'%" OR osloveni LIKE "'.$fletter.'%" OR firma LIKE "'.$fletter.'%" )';
        }   
      }
    $ordery='';
    $orderx='prijmeni,jmeno,titul_pred';  
    if(getget('order','')=='uid'){$orderx='uid';$ordery='uid';}      
    if(getget('order','')=='last_access'){$orderx='posledni_prihlaseni desc';$ordery='last_access';}      
    $page=(int)getget('page','0');
    $counter=10;  
    $users=$this->kernel->models->DBusers->getLines('uid,prava,posledni_prihlaseni,posledni_prihlaseni_ip,titul_pred,titul_za,jmeno,prijmeni,firma,pocet_prihlaseni,osloveni,ucetni_zustatek',$where.' ORDER BY '.$orderx.' LIMIT '.($page*$counter).', '.$counter); 
    $users_count=$this->kernel->models->DBusers->getOne('count(uid)',$where);
    $paginnator=$this->Paginnator($page,$users_count,$counter,getget('order',''),$fletter);
    foreach($users as $ku=>$vu){
      $users[$ku]->ainfo=$this->Anchor(array('action'=>'detail','uid'=>$vu->uid),false);    
      $users[$ku]->aedit=$this->Anchor(array('action'=>'edit','uid'=>$vu->uid),false);    
      $users[$ku]->adel=$this->Anchor(array('action'=>'delete','uid'=>$vu->uid),false);    
      $users[$ku]->acoins=$this->Anchor(array('action'=>'coins','uid'=>$vu->uid),false);    
      }
    $filtr=array();
    $obj=new stdClass();
    $obj->active=(strtolower($fletter)==''?1:0);
    $obj->nazev='Vše';
    $obj->url=$this->Anchor(array('action'=>'list','order'=>$ordery),false);    
    $filtr[]=$obj;
    for($a='A';$a<'Z';$a++){
      $obj=new stdClass();
      $obj->active=($fletter==strtolower($a)?1:0);
      $obj->nazev=$a;
      $obj->url=$this->Anchor(array('action'=>'list','order'=>$ordery,'fletter'=>strtolower($a)),false);    
      $filtr[]=$obj;
      }
    $tpl=new Templater();
    $tpl->add('filtr',$filtr);
    $tpl->add('anew',$this->Anchor(array('action'=>'new'),false));    
    $tpl->add('agobyid',$this->Anchor(array('action'=>'gobyid'),false));    
    $tpl->add('agobyemail',$this->Anchor(array('action'=>'gobyemail'),false));    
    $tpl->add('users',$users);
    $tpl->add('paginnator',$paginnator);
    $tpl->add('prava',$this->kernel->user->data->prava);        
    $tpl->add('order_1',$this->Anchor(array('fletter'=>$fletter),false));        
    $tpl->add('order_2',$this->Anchor(array('order'=>'uid','fletter'=>$fletter),false));        
    $tpl->add('order_3',$this->Anchor(array('order'=>'last_access','fletter'=>$fletter,),false));
    $tpl->add('thisUserRights',$this->rights);         
    $this->content=$tpl->fetch('backend/users/list.tpl');
    $this->SetLeftMenu();
    $this->execute();     
    }
  private function PageCoins(){
    $this->seo_title='Účetní zůstatek uživatele';
    $uid=(int)getget('uid','0');
    if($uid<1){$this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);}
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){$this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);}
    $page=(int)getget('page','0');
    $counter=10;//10  
    $coins=$this->kernel->models->DBusersCoins->getLines('*',' WHERE idu="'.$uid.'" ORDER BY iduc DESC LIMIT '.($page*$counter).', '.$counter); 
    $coins_count=$this->kernel->models->DBusersCoins->getOne('count(iduc)','WHERE idu="'.$uid.'"');
    $paginnator=$this->PaginnatorCoins($page,$coins_count,$counter,$uid);    
    $tpl=new Templater();
    $tpl->add('prava',$this->kernel->user->data->prava);    
    $tpl->add('user',$user);
    $tpl->add('coins',$coins);
    $tpl->add('paginnator',$paginnator);
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));     
    $tpl->add('aadd',$this->Anchor(array('action'=>'add-coins','uid'=>$user->uid),false));
    $tpl->add('aedit',$this->Anchor(array('action'=>'edit','uid'=>$user->uid),false));   
    $tpl->add('ainfo',$this->Anchor(array('action'=>'detail','uid'=>$user->uid),false));
    $tpl->add('thisUserRights',$this->rights);        
    $this->content=$tpl->fetch('backend/users/coins.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageAddCoins(){
    $uid=(int)getget('uid','0');
    $duvod=prepare_get_data_safely(getpost('duvod',''));  
    $coins=str_replace(',','.',trim((round((float)str_replace(',','.',getpost('coins','')),2))));     
    if($uid<1){$this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);}
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){$this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);}
    if($duvod==''||$coins==0){$this->Redirect(array('action'=>'coins','message'=>'not-added','uid'=>$uid),false);}
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$uid,'datum_cas'=>time(),'coins'=>$coins,'duvod'=>$duvod));
    $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$coins)));
    $this->Redirect(array('action'=>'coins','message'=>'added','uid'=>$uid),false);  
    }
  private function PageView(){
    $this->seo_title='Detail uživatele';
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    $tpl=new Templater();
    $tpl->add('user',$user);
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));     
    $tpl->add('aedit',$this->Anchor(array('action'=>'edit','uid'=>$user->uid),false));   
    $tpl->add('acoins',$this->Anchor(array('action'=>'coins','uid'=>$user->uid),false));
    $tpl->add('adelavatar',$this->Anchor(array('action'=>'delete-avatar','uid'=>$user->uid),false));    
    $tpl->add('prava',$this->kernel->user->data->prava);              
    $tpl->add('thisUserRights',$this->rights);        
    $this->content=$tpl->fetch('backend/users/view.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageEdit(){
    $this->seo_title='Editace uživatele';
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    if($this->kernel->user->data->prava<$user->prava){
      $this->Redirect(array('action'=>'list'),false); 
      }
    $adminsRights=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM administrators_rights ORDER BY poradi ASC, idar ASC');
    $enabledRights=array('0'=>'0');
    $adminEnableRights=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM administrators_rights_users WHERE idu="'.$uid.'"');
    foreach($adminEnableRights as $aER){$enabledRights[$aER->idar]=$aER->idar;}
    unset($adminEnableRights);
    $tpl=new Templater();
    $tpl->add('user',$user);
    $tpl->add('adminsRights',$adminsRights);
    $tpl->add('enabledRights',$enabledRights);
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));     
    $tpl->add('ainfo',$this->Anchor(array('action'=>'detail','uid'=>$user->uid),false));     
    $tpl->add('asave',$this->Anchor(array('action'=>'save','uid'=>$user->uid),false));     
    $tpl->add('achangepass',$this->Anchor(array('action'=>'change-pass','uid'=>$user->uid),false)); 
    $tpl->add('acoins',$this->Anchor(array('action'=>'coins','uid'=>$user->uid),false));
    $tpl->add('achangerights',$this->Anchor(array('action'=>'change-rights','uid'=>$user->uid),false));
    $tpl->add('prava',$this->kernel->user->data->prava);
    $tpl->add('thisUserRights',$this->rights);        
    $this->content=$tpl->fetch('backend/users/edit.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageChangePass(){
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    $heslo_1=getpost('heslo_1','');
    $heslo_2=getpost('heslo_2','');
    if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){
      $this->Redirect(array('action'=>'edit','message'=>'password-short','uid'=>$uid),false);  
      }
    if($heslo_1!=$heslo_2){
      $this->Redirect(array('action'=>'edit','message'=>'password-not-same','uid'=>$uid),false);
      }
    $this->kernel->models->DBusers->updateId($uid,array('heslo'=>saltHashSha($heslo_1,$user->uid,$user->registrace,'SaltOfGSoulEU')));      
    $this->Redirect(array('action'=>'edit','message'=>'password-saved','uid'=>$uid),false);
    }
  private function PageNew(){
    $this->seo_title='Přidání uživatele';
    $user=new stdClass();
    if(isset($_SESSION['backend-new-user'])){
      $user=$_SESSION['backend-new-user'];
      }
    $tpl=new Templater();
    $tpl->add('user',$user);
    $tpl->add('prava',$this->kernel->user->data->prava);
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));          
    $tpl->add('anew',$this->Anchor(array('action'=>'new-post'),false)); 
    $tpl->add('anewuser',$this->Anchor(array('action'=>'detail','uid'=>(int)getget('uid','0')),false));             
    $this->content=$tpl->fetch('backend/users/new.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageNewPost(){
    $postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){
      if($k!='heslo_1'&&$k!='heslo_2'){
        $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        $a=prepare_get_data_safely($k);
        $sesdata->$a=prepare_get_data_safely($v);
        }    
      } 
    $_SESSION['backend-new-user']=$sesdata;   
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'"');
    if($exist_mail>0){
      $this->Redirect(array('action'=>'new','message'=>'email-exists'),false);  
      }   
    if($postdata['email']==''){
      $this->Redirect(array('action'=>'new','message'=>'email-required'),false);  
      }
    $heslo_1=getpost('heslo_1','');
    $heslo_2=getpost('heslo_2','');
    if(mb_strlen($heslo_1,$this->kernel->config->charset['charset'])<4||mb_strlen($heslo_2,$this->kernel->config->charset['charset'])<4){
      $this->Redirect(array('action'=>'new','message'=>'password-short'),false);  
      }
    if($heslo_1!=$heslo_2){
      $this->Redirect(array('action'=>'new','message'=>'password-not-same'),false);
      }
    $postdata['heslo']='NotSetYet';    
    $postdata['registrace']=time();
    $_SESSION['backend-new-user']=new stdClass();
    $uid=$this->kernel->models->DBusers->store(0,$postdata);
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"'); 
    $this->kernel->models->DBusers->updateId($uid,array('heslo'=>saltHashSha($heslo_1,$user->uid,$user->registrace,'SaltOfGSoulEU')));    
    $this->Redirect(array('action'=>'new','message'=>'user-created','uid'=>$uid),false);
    }
  private function PageSave(){
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    $postdata=array();
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);    
      }
    $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$postdata['email'].'" AND uid!="'.$uid.'"');
    if($exist_mail>0){
      $this->Redirect(array('action'=>'edit','message'=>'email-exists','uid'=>$uid),false);  
      } 
    if($postdata['email']==''){
      $this->Redirect(array('action'=>'edit','message'=>'email-required','uid'=>$uid),false);  
      }      
    $this->kernel->models->DBusers->updateId($uid,$postdata);
    $this->Redirect(array('action'=>'edit','message'=>'user-saved','uid'=>$uid),false);  
    }
  private function PageDelete(){
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    if($this->kernel->user->data->prava<$user->prava){
      $this->Redirect(array('action'=>'list'),false); 
      }
    $this->kernel->models->DBusers->deleteId($uid);    
    $this->Redirect(array('action'=>'list','message'=>'user-deleted','uid'=>$uid),false);  
    }    
  private function PageExport(){
    $this->seo_title='Export uživatelů';
    $tpl=new Templater();
    $tpl->add('aexport',$this->Anchor(array('action'=>'export-generate'),false));  
    $this->content=$tpl->fetch('backend/users/export.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PageExportGenerate(){
    $hlavicky=array();
    $prava=array();
    $novinky=array();
    $typ_exportu='excel';
    foreach($_POST as $p=>$v){
      $p=prepare_get_data_safely($p);
      $v=prepare_get_data_safely($v);
      if($p=='e_uzivatele'){
        $prava[]='0';      
      }elseif($p=='e_admini'){
        $prava[]='1';      
      }elseif($p=='e_super'){
        $prava[]='2';      
      }elseif($p=='odebiraji_novinky'){
        $novinky[]='1';      
      }elseif($p=='neodebiraji_novinky'){
        $novinky[]='0';      
      }elseif($p=='typ_exportu'){
        $typ_exportu=$v;      
      }else{
        $hlavicky[]=$p;
        if($p=='posledni_prihlaseni'){
          $hlavicky[]='posledni_prihlaseni_ip';
          }
        }                   
      }    
    if(count($hlavicky)==0||count($prava)==0||count($novinky)==0){
      $this->Redirect(array('action'=>'export','message'=>'no-user-found'),false);
      }  
    $users=$this->kernel->models->DBusers->getLines(implode(',',$hlavicky),'WHERE prava in('.implode(',',$prava).') AND odber_novinek in('.implode(',',$novinky).') order by uid');
    if(count($users)==0){
      $this->Redirect(array('action'=>'export','message'=>'no-user-found'),false);
      }
    $data='';
    $databack='';
    if($typ_exportu=='excel'){
      $data='<table>';
      $databack="</table>";
      $hlavicka='<tr><th>'.implode('</th><th>',$hlavicky).'</th></tr>';
    }elseif($typ_exportu=='html'){
      $data='<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head></html><body><table border="1">';
      $databack="</table></body></html>";
      $hlavicka='<tr><th>'.implode('</th><th>',$hlavicky).'</th></tr>';
    }elseif($typ_exportu=='text'){
      $hlavicka=implode("\t",$hlavicky)."\n";
    }else{
      $hlavicka=implode(';',$hlavicky)."\n";
    }    
    $hlavicka=str_replace(
      array("titul_pred","jmeno","prijmeni","titul_za","firma","ico","dic","ulice","cislo_popisne","mesto","psc","stat","email","telefon","osloveni","prava","odber_novinek","uid","registrace","posledni_prihlaseni_ip","posledni_prihlaseni","pocet_prihlaseni"),
      array("Titul","Jméno","Příjmení","Titul za jménem","Firma","IČ","DIČ","Ulice","č.p.","Město","PSČ","stát","E-mail","Telefon","Nickname","Práva","Odběr novinek","ID uživatele","Registrace","Poslední IP adresa","Poslední přihlášení","Počet přihlášení"),$hlavicka);
    $datau='';
    foreach($users as $u){
      if($typ_exportu=='excel'||$typ_exportu=='html'){
        $datau.='<tr>';
        }
      foreach($hlavicky as $h){
        $xdata='';
          if($h=='registrace'||$h=='posledni_prihlaseni'){
            if($u->$h>0){
              $xdata=TimestampToDateTime($u->$h);
              }
          }elseif($h=='odber_novinek'){
            if($u->$h==0){
              $xdata='Ne';
              }            
            if($u->$h==1){
              $xdata='Ano';
              }    
          }elseif($h=='prava'){
            if($u->$h==0){
              $xdata='Uživatel';
              }            
            if($u->$h==1){
              $xdata='Administrátor';
              }            
            if($u->$h==2){
              $xdata='Super administrátor';
              }            
          }else{
            $xdata=$u->$h;          
          }
          
          if($typ_exportu=='excel'||$typ_exportu=='html'){      
            $datau.='<td>'.$xdata.'</td>';
          }elseif($typ_exportu=='text'){
            $datau.=$xdata."\t";
          }else{
            $datau.=$xdata.';';
          }    
        }
      $datau.="\n";
      if($typ_exportu=='excel'||$typ_exportu=='html'){
        $datau.='</tr>';
        }
      }
    if($typ_exportu=='excel'){
      header("Content-type: text/plain");
      header("Content-Disposition: attachment; filename=uzivatele.xls");
      }
    if($typ_exportu=='html'){
      header("Content-type: text/plain");
      header("Content-Disposition: attachment; filename=uzivatele.html");
      }
    if($typ_exportu=='csv'){
      header("Content-type: text/plain");
      header("Content-Disposition: attachment; filename=uzivatele.csv");
      }
    if($typ_exportu=='text'){
      header("Content-type: text/plain");
      header("Content-Disposition: attachment; filename=uzivatele.txt");
      }    
    echo $data.$hlavicka.$datau.$databack;
    exit();         
    }
  private function PageExportEmails(){
    $this->seo_title='Export E-mailů uživatelů';
    $emails_1a=$this->kernel->models->DBusers->getLines('distinct email as em','WHERE odber_novinek=0 and email is not null and email!=""');    
    $emails_2a=$this->kernel->models->DBusers->getLines('distinct email as em','WHERE odber_novinek=1 and email is not null and email!=""');    
    $emailsA=array();
    $emailsB=array();
    $emailsC=array();
    foreach($emails_1a as $em){
      $emailsB[$em->em]=$em->em;
      $emailsC[$em->em]=$em->em;
      }
    unset($emails_1a);    
    foreach($emails_2a as $em){
      $emailsA[$em->em]=$em->em;
      $emailsC[$em->em]=$em->em;
      }
    unset($emails_2a);     
    $tpl=new Templater();
    $tpl->add('emails_a',$emailsA);
    $tpl->add('emails_b',$emailsB);
    $tpl->add('emails_c',$emailsC);    
    $this->content=$tpl->fetch('backend/users/exportEmails.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PageExportPhones(){
    $this->seo_title='Export telefonů uživatelů';
    $emails_1a=$this->kernel->models->DBusers->getLines('distinct telefon as em','WHERE odber_novinek=0 and telefon is not null and telefon!=""');    
    $emails_2a=$this->kernel->models->DBusers->getLines('distinct telefon as em','WHERE odber_novinek=1 and telefon is not null and telefon!=""');    
    $emailsA=array();
    $emailsB=array();
    $emailsC=array();
    foreach($emails_1a as $em){
      $emailsB[$em->em]=str_replace(' ','',$em->em);
      $emailsC[$em->em]=str_replace(' ','',$em->em);
      }
    unset($emails_1a);    
    foreach($emails_2a as $em){
      $emailsA[$em->em]=str_replace(' ','',$em->em);
      $emailsC[$em->em]=str_replace(' ','',$em->em);
      }
    unset($emails_2a);    
    $tpl=new Templater();
    $tpl->add('emails_a',$emailsA);
    $tpl->add('emails_b',$emailsB);
    $tpl->add('emails_c',$emailsC);    
    $this->content=$tpl->fetch('backend/users/exportPhones.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PageNewsletter(){
    $this->seo_title='Odeslání newsletteru uživatelům';  
    $newsletter=new stdClass();
    $newsletter->prijemci='1';
    $newsletter->zprava='';
    $newsletter->predmet='';
    $newsletter->email_odesilatele=$this->kernel->config->default_email['from_email'];
    $newsletter->jmeno_odesilatele=$this->kernel->config->default_email['from_name'];    
    if(isset($_SESSION['backend-user-newsletter'])){
      $newsletter=$_SESSION['backend-user-newsletter'];
      }
    if($newsletter->email_odesilatele==''){  
      $newsletter->email_odesilatele=$this->kernel->settings['email_odesilatele'];
      }
    if($newsletter->jmeno_odesilatele==''){
      $newsletter->jmeno_odesilatele=$this->kernel->settings['jmeno_odesilatele'];
      }  
    $tpl=new Templater();
    $tpl->add('newsletter',$newsletter);
    $tpl->add('error',(int)getget('error','0'));
    $tpl->add('sended',(int)getget('sended','0'));
    $tpl->add('zprava',$this->kernel->GetEditor('zprava',$newsletter->zprava));
    $tpl->add('asend',$this->Anchor(array('action'=>'newsletter-post'),false));     
    $this->content=$tpl->fetch('backend/users/newsletter.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PageNewsletterPost(){   
    $email_odesilatele=prepare_get_data_safely(getpost('email_odesilatele',''));
    $jmeno_odesilatele=prepare_get_data_safely(getpost('jmeno_odesilatele',''));
    $predmet=prepare_get_data_safely(getpost('predmet',''));
    $prijemci=prepare_get_data_safely(getpost('prijemci',''));
    $zprava=prepare_get_data_safely_editor(getpost('zprava',''));
    $sesdata=new stdClass();
    $sesdata->email_odesilatele=$email_odesilatele;
    $sesdata->jmeno_odesilatele=$jmeno_odesilatele;
    $sesdata->predmet=$predmet;
    $sesdata->prijemci=$prijemci;
    $sesdata->zprava=$zprava;    
    $_SESSION['backend-user-newsletter']=$sesdata;     
    if($email_odesilatele==''||$jmeno_odesilatele==''||$predmet==''||$prijemci==''||$zprava==''){
      $this->Redirect(array('action'=>'newsletter','error'=>'1'),false);      
      }
    $this->Redirect(array('action'=>'newsletter-2'),false);
    }
  private function PageNewsletter2(){
    $this->seo_title='Odeslání newsletteru uživatelům';
    $newsletter=new stdClass();
    $newsletter->prijemci='1';
    $newsletter->zprava='';
    $newsletter->predmet='';
    $newsletter->email_odesilatele=$this->kernel->settings['email_odesilatele'];
    $newsletter->jmeno_odesilatele=$this->kernel->settings['jmeno_odesilatele'];    
    if(isset($_SESSION['backend-user-newsletter'])){
      $newsletter=$_SESSION['backend-user-newsletter'];
      }
    if($newsletter->email_odesilatele==''||$newsletter->jmeno_odesilatele==''||$newsletter->predmet==''||$newsletter->prijemci==''||$newsletter->zprava==''){
      $this->Redirect(array('action'=>'newsletter','error'=>'1'),false);      
      }
    $user=$this->kernel->user->data;
    $newsletter->predmet2=str_replace(
      array('{osloveni}','{titul-jmeno-prijmeni}','{telefon}','{e-mail}'),
      array($user->osloveni,trim($user->titul_pred.' '.$user->jmeno.' '.$user->prijmeni.' '.$user->titul_za),$user->telefon,$user->email),
      $newsletter->predmet);
    $newsletter->zprava2=str_replace(
      array('{osloveni}','{titul-jmeno-prijmeni}','{telefon}','{e-mail}'),
      array($user->osloveni,trim($user->titul_pred.' '.$user->jmeno.' '.$user->prijmeni.' '.$user->titul_za),$user->telefon,$user->email),
      $newsletter->zprava);
    $tpl=new Templater();
    $tpl->add('newsletter',$newsletter);
    $tpl->add('asend',$this->Anchor(array('action'=>'newsletter-post-2'),false));     
    $tpl->add('aback',$this->Anchor(array('action'=>'newsletter'),false));     
    $this->content=$tpl->fetch('backend/users/newsletter-2.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PageNewsletterPost2(){
    $newsletter=new stdClass();
    $newsletter->prijemci='1';
    $newsletter->zprava='';
    $newsletter->predmet='';
    $newsletter->email_odesilatele=$this->kernel->settings['email_odesilatele'];
    $newsletter->jmeno_odesilatele=$this->kernel->settings['jmeno_odesilatele'];    
    if(isset($_SESSION['backend-user-newsletter'])){
      $newsletter=$_SESSION['backend-user-newsletter'];
      }
    if($newsletter->email_odesilatele==''||$newsletter->jmeno_odesilatele==''||$newsletter->predmet==''||$newsletter->prijemci==''||$newsletter->zprava==''){
      $this->Redirect(array('action'=>'newsletter','error'=>'1'),false);      
      }
    if($newsletter->prijemci=='1'){
      $users=$this->kernel->models->DBusers->getLines('email ,telefon,osloveni,titul_pred,jmeno,prijmeni,titul_za','WHERE odber_novinek=1 and email is not null and email!=""');        
    }elseif($newsletter->prijemci=='0'){
      $users=$this->kernel->models->DBusers->getLines('email,telefon,osloveni,titul_pred,jmeno,prijmeni,titul_za','WHERE odber_novinek=0 and email is not null and email!=""');      
    }else{
      $users=$this->kernel->models->DBusers->getLines('email,telefon,osloveni,titul_pred,jmeno,prijmeni,titul_za','WHERE email is not null and email!=""');      
    }
    $xusers=$users;
    unset($users);    
    foreach($xusers as $u){
      $newsletter->predmet2=str_replace(
        array('{osloveni}','{titul-jmeno-prijmeni}','{telefon}','{e-mail}'),
        array($u->osloveni,trim($u->titul_pred.' '.$u->jmeno.' '.$u->prijmeni.' '.$u->titul_za),$u->telefon,$u->email),
        $newsletter->predmet);
      $newsletter->zprava2=str_replace(
        array('{osloveni}','{titul-jmeno-prijmeni}','{telefon}','{e-mail}','href="/','src="/'),
        array($u->osloveni,trim($u->titul_pred.' '.$u->jmeno.' '.$u->prijmeni.' '.$u->titul_za),$u->telefon,$u->email,'href="http://'.$_SERVER['SERVER_NAME'].'/','src="http://'.$_SERVER['SERVER_NAME'].'/'),
        $newsletter->zprava);
      //$text=wordwrap(html_entity_decode(strip_tags($newsletter->zprava2),ENT_COMPAT,'UTF-8'), 80);
      $mailer=new PHPMailer();;
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($newsletter->email_odesilatele,$newsletter->jmeno_odesilatele);
      $mailer->Subject=$newsletter->predmet2;              
      $mailer->MsgHTML($newsletter->zprava2);
      $mailer->AddAddress($u->email);
      $mailer->Send();                   
      }   
    $_SESSION['backend-user-newsletter']=new stdClass(); 
    $this->Redirect(array('action'=>'newsletter','sended'=>'1'),false);      
    }
  private function PageDeleteAvatar(){
    $uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }            
    $this->kernel->models->DBusers->updateId($uid,array('user_picture'=>''));
    $this->Redirect(array('action'=>'detail','message'=>'user-saved','uid'=>$uid),false);    
    }
  private function PagePayOuts(){
    $this->seo_title='Výplaty uživatelů';
    $page=(int)getget('page','0');
    $counter=10; 
    $list=$this->kernel->models->DBpaypalPayments->getLines('*','WHERE type="pay-out" AND status="at-paypal" order by id ASC LIMIT '.($page*$counter).', '.$counter);
    $list_count=$this->kernel->models->DBpaypalPayments->getOne('count(id)','WHERE type="pay-out" AND status="at-paypal"');    
    $paginnator=$this->PaginnatorPays($page,$list_count,$counter);
    foreach($list as $lk=>$lv){
      $list[$lk]->anotpay=$this->Anchor(array('action'=>'payment-not-pay-post','id'=>$lv->id,'bp'=>$page));
      $list[$lk]->apay=$this->Anchor(array('action'=>'payment-pay-post','id'=>$lv->id,'bp'=>$page));
      $list[$lk]->auser=$this->Anchor(array('action'=>'coins','uid'=>$lv->userId));
      $list[$lk]->user=$this->kernel->models->DBusers->getLine('uid,osloveni,jmeno,prijmeni,firma','WHERE uid="'.$lv->userId.'"');
      }
    $tpl=new Templater();
    $tpl->add('list',$list);
    $tpl->add('paginnator',$paginnator);  
    $this->content=$tpl->fetch('backend/users/payouts.tpl');
    $this->SetLeftMenu();
    $this->execute(); 
    }
  private function PaginnatorPays($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('action'=>'payouts','page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('action'=>'payouts','page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('action'=>'payouts','page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('action'=>'payouts','page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('action'=>'payouts','page'=>($page+1)),false);
    }    
    return $pages;          
    }
  private function PagePayOutsDoNotPay(){
    $id=(int)getget('id');
    $page=(int)getget('bp');
    $paymentSystemData=$this->kernel->models->DBpaypalPayments->getLine('*',' WHERE id="'.$id.'" AND type="pay-out" AND status="at-paypal" ');
    $uzivatel=$this->kernel->models->DBusers->getLine('uid,osloveni,jmeno,prijmeni,firma,ucetni_zustatek','WHERE uid="'.((int)$paymentSystemData->userId).'"');
    if($paymentSystemData->id>0&&$uzivatel->uid==$paymentSystemData->userId){
      $this->kernel->models->DBpaypalPayments->store($paymentSystemData->id,array('status'=>'unsuccess'));                           
      $coins=str_replace(',','.',trim((round((float)str_replace(',','.',$paymentSystemData->credit),2))));     
      $this->kernel->models->DBusersCoins->store(0,array('idu'=>$paymentSystemData->userId,'datum_cas'=>time(),'coins'=>$coins,'duvod'=>'Vraceni zamitnute transakce'));
      $this->kernel->models->DBusers->store($paymentSystemData->userId,array('ucetni_zustatek'=>str_replace(',','.',$uzivatel->ucetni_zustatek+$coins)));            
      $mailer=new PHPMailer();
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='GSOUL.EU / payout storno by admin';           
      $mailer->MsgHTML('<h1>Payout has been canceled by admin</h1><p>Data: '.$paymentSystemData->cost.' '.$paymentSystemData->currency.' at paypal account '.$paymentSystemData->paymentId.'</p>');    
      $mailer->AddAddress('payout@gsoul.eu');  
      $mailer->Send();
      $this->Redirect(array('action'=>'payouts','page'=>$page,'message'=>'not-payed'),false);
      }
    $this->Redirect(array('action'=>'payouts','page'=>$page,'message'=>'not-found'),false);      
    }
  private function PagePayOutsDoPay(){
    $id=(int)getget('id');
    $page=(int)getget('bp');
    $paymentSystemData=$this->kernel->models->DBpaypalPayments->getLine('*',' WHERE id="'.$id.'" AND type="pay-out" AND status="at-paypal" ');
    if($paymentSystemData->id>0){
      $mailer=new PHPMailer();
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='GSOUL.EU / payout payed by admin';           
      $mailer->MsgHTML('<h1>Payout has been payed by admin</h1><p>Data: '.$paymentSystemData->cost.' '.$paymentSystemData->currency.' at paypal account '.$paymentSystemData->paymentId.'</p>');    
      $mailer->AddAddress('payout@gsoul.eu');  
      $mailer->Send();
      $this->kernel->models->DBpaypalPayments->store($paymentSystemData->id,array('status'=>'success','paymentToken'=>'Done'));  
      $this->Redirect(array('action'=>'payouts','page'=>$page,'message'=>'payed'),false);
      }
    $this->Redirect(array('action'=>'payouts','page'=>$page,'message'=>'not-found'),false);
    }
  private function PageNewslettersLogouts(){
    $this->seo_title='Odhlášené newslettery';
    $page=(int)getget('page','0');
    $type=(int)getget('type','0');
    $where='';
    if($type==1){$where=' WHERE hotovo=1 ';}
    if($type==2){$where=' WHERE hotovo=0 ';}
    $counter=10;     
    $list=$this->kernel->models->DBnewsletterLogouts->getLines('*',$where.' order by idnl DESC LIMIT '.($page*$counter).', '.$counter);
    $list_count=$this->kernel->models->DBnewsletterLogouts->getOne('count(idnl)',$where);    
    $paginnator=$this->PaginnatorNWLOuts($page,$list_count,$counter,$type);
    foreach($list as $lk=>$lv){
      $list[$lk]->asetup=$this->Anchor(array('action'=>'newsletter-logouts-set','set'=>'on','id'=>$lv->idnl,'type'=>$type));
      $list[$lk]->asetdown=$this->Anchor(array('action'=>'newsletter-logouts-set','set'=>'off','id'=>$lv->idnl,'type'=>$type));      
      }
    $tpl=new Templater();
    $tpl->add('list',$list);
    $tpl->add('paginnator',$paginnator);
    $tpl->add('type',$type);  
    $tpl->add('ahotovo',$this->Anchor(array('action'=>'newsletter-logouts','type'=>'1')));
    $tpl->add('anehotovo',$this->Anchor(array('action'=>'newsletter-logouts','type'=>'2')));
    $tpl->add('avse',$this->Anchor(array('action'=>'newsletter-logouts','type'=>'0')));
    $this->content=$tpl->fetch('backend/users/newsletterLogouts.tpl');
    $this->SetLeftMenu();
    $this->execute();   
    }
  private function PaginnatorNWLOuts($page=0,$count=0,$counter=0,$type=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('action'=>'newsletter-logouts','type'=>$type,'page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('action'=>'newsletter-logouts','type'=>$type,'page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('action'=>'newsletter-logouts','type'=>$type,'page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('action'=>'newsletter-logouts','type'=>$type,'page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('action'=>'newsletter-logouts','type'=>$type,'page'=>($page+1)),false);
    }    
    return $pages;          
    }
  private function PageNewslettersLogoutsSet(){
    $id=(int)getget('id');    
    $type=(int)getget('type');
    $set=getget('set');
    $setX=$set=='on'?'1':'0';
    $exist=$this->kernel->models->DBnewsletterLogouts->getLine('*',' WHERE idnl="'.$id.'" ');
    if($exist->idnl>0){
      $this->kernel->models->DBnewsletterLogouts->store($id,array('hotovo'=>$setX));  
      $this->Redirect(array('action'=>'newsletter-logouts','type'=>$type,'message'=>'changed'),false);
      }
    $this->Redirect(array('action'=>'newsletter-logouts','type'=>$type,'message'=>'not-found'),false);
    }
  private function PageChangeAdminRights(){
  	$uid=(int)getget('uid','0');
    if($uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);
      }
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$uid.'"');
    if(!isset($user)||$user->uid<1){
      $this->Redirect(array('action'=>'list','message'=>'user-not-found','uid'=>$uid),false);  
      }
    if($this->kernel->user->data->prava<1){
      $this->Redirect(array('action'=>'list'),false); 
      }
    $this->kernel->models->DBusers->Mquery('DELETE FROM administrators_rights_users WHERE idu="'.$uid.'"');
    $idar=getpost('idar');
    if(is_array($idar)&&count($idar)>0){
    	foreach($idar as $idr){
    		$idr=(int)$idr;
    		if($idr>0){
    			$this->kernel->models->DBusers->Mquery('INSERT INTO administrators_rights_users (idu,idar) VALUES ("'.$uid.'","'.$idr.'")');
    			}
    		}
    	}
    $this->Redirect(array('action'=>'edit','message'=>'rights-changed','uid'=>$uid),false);    
  	}
  }
