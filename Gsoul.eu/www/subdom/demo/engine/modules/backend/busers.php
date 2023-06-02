<?php
class BUsers extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    }
  public function Main(){
    $this->seo_title='Uživatelé';
    $action=getget('action','list');
      if($action=='list'){$this->PageList();}
      elseif($action=='detail'){$this->PageView();}
      elseif($action=='coins'){$this->PageCoins();}
      elseif($action=='add-coins'){$this->PageAddCoins();}
      elseif($action=='gobyid'){$this->PageGoBy('id');}
      elseif($action=='gobyemail'){$this->PageGoBy('email');}
      elseif($action=='edit'){$this->PageEdit();}
      elseif($action=='save'){$this->PageSave();}
      elseif($action=='change-pass'){$this->PageChangePass();}
      elseif($action=='new'){$this->PageNew();}      
      elseif($action=='new-post'){$this->PageNewPost();} 
      elseif($action=='delete'){$this->PageDelete();}
      elseif($action=='export'){$this->PageExport();}
      elseif($action=='export-generate'){$this->PageExportGenerate();}
      elseif($action=='export-emails'){$this->PageExportEmails();}
      elseif($action=='export-phones'){$this->PageExportPhones();}
      elseif($action=='newsletter'){$this->PageNewsletter();}
      elseif($action=='newsletter-post'){$this->PageNewsletterPost();}
      elseif($action=='newsletter-2'){$this->PageNewsletter2();}
      elseif($action=='newsletter-post-2'){$this->PageNewsletterPost2();}
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
      $this->Anchor(array('action'=>'newsletter'))=>'<span class="icon"><i class="fa fa-envelope-o"></i></span> Odeslat newsletter',            
      $this->Anchor(array('action'=>'export'))=>'<span class="icon"><i class="fa fa-reply-all"></i></span> Exportovat uživatele',
      $this->Anchor(array('action'=>'export-emails'))=>'<span class="icon"><i class="fa fa-reply"></i></span> Exportovat e-maily uživatelů',
      $this->Anchor(array('action'=>'export-phones'))=>'<span class="icon"><i class="fa fa-reply"></i></span> Exportovat telefony uživatelů',
      );
    $active='list';
    if(getget('action','')=='export'){$active='export';}      
    if(getget('action','')=='export-emails'){$active='export-emails';}
    if(getget('action','')=='export-phones'){$active='export-phones';}      
    if(getget('action','')=='newsletter'){$active='newsletter';}      
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
    $tpl->add('prava',$this->kernel->user->data->prava);              
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
    $tpl=new Templater();
    $tpl->add('user',$user);
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));     
    $tpl->add('ainfo',$this->Anchor(array('action'=>'detail','uid'=>$user->uid),false));     
    $tpl->add('asave',$this->Anchor(array('action'=>'save','uid'=>$user->uid),false));     
    $tpl->add('achangepass',$this->Anchor(array('action'=>'change-pass','uid'=>$user->uid),false)); 
    $tpl->add('acoins',$this->Anchor(array('action'=>'coins','uid'=>$user->uid),false));
    $tpl->add('prava',$this->kernel->user->data->prava);        
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
    $this->kernel->models->DBusers->updateId($uid,array('heslo'=>hash('sha512',$heslo_1)));  
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
    $postdata['heslo']=hash('sha512',$heslo_1);    
    $postdata['registrace']=time();
    $_SESSION['backend-new-user']=new stdClass();
    $uid=$this->kernel->models->DBusers->store(0,$postdata);    
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
  }
?>