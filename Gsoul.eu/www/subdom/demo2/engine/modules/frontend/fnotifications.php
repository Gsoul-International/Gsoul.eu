<?php
class FNotifications extends Module{     
  public function Main(){ 
    if($this->kernel->user->uid<1){
      header("Location: /");
      exit();
      }      
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['notifikace']; 
    $action=getget('action','');
    if($action=='show-notify'){$this->PageShowNotify();}
    elseif($action=='read-all'){$this->PageReadAll();}        
    else{$this->PageMain();}    
    }          
  public function PageMain(){    
    $this->seo_title=$this->kernel->systemTranslator['notifikace'];         
    $this->seo_keywords=$this->kernel->systemTranslator['notifikace'];                 
    $this->seo_description=$this->kernel->systemTranslator['notifikace'];   
    $page=(int)getget('page','0');
    $counter=10;//10  
    $notifies=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM notifications WHERE idu="'.$this->kernel->user->uid.'" ORDER BY idn DESC LIMIT '.($page*$counter).', '.$counter);
    $notifies_count=(int)$this->kernel->models->DBusers->MqueryGetOne('SELECT count(idn) as cnt FROM notifications WHERE idu="'.$this->kernel->user->uid.'"'); 
    foreach($notifies as $ck=>$cv){
      $notifies[$ck]->aDetail=$this->Anchor(array('action'=>'show-notify','id'=>$cv->idn),false);      
      }        
    $paginnator=$this->Paginnator($page,$notifies_count,$counter);    
    $tpl=new Templater();       
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('notifies',$notifies);
    $tpl->add('areadall',$this->Anchor(array('action'=>'read-all'),false));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/notifications/main.tpl');  
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
  private function PageReadAll(){
    $this->kernel->models->DBusers->Mquery('UPDATE notifications SET precteno=1 WHERE idu="'.$this->kernel->user->uid.'"');    
    $this->Redirect();
    }
  private function PageShowNotify(){
    $idn=(int)getget('id');
    $notify=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM notifications WHERE idu="'.$this->kernel->user->uid.'" AND idn="'.$idn.'" ');
    if($notify->idn<1){$this->redirect(array('message'=>'notification-not-found'),false);} 
    if($notify->precteno==0){
      $this->kernel->models->DBusers->Mquery('UPDATE notifications SET precteno=1 WHERE idu="'.$this->kernel->user->uid.'" AND idn="'.$idn.'" ');  
      }
    header('Location: '.$notify->link);
    exit();
    }  
  }