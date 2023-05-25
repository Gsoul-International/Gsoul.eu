<?php
class FTeams extends Module{     
  public function Main(){ 
    if($this->kernel->user->uid<1){
      header("Location: /");
      exit();
      }
    $this->parent_module='Frontend';          
    $this->seo_title=$this->kernel->systemTranslator['tymy']; 
    $action=getget('action','');
    if($action=='create-new'){$this->PageCreateNewTeam();}
    elseif($action=='set-filter'){$this->setFilter();}      
    elseif($action=='team-detail'){$this->PageTeamDetail();} 
    elseif($action=='new-chat'){$this->InsertChatIntoTeam();}  
    elseif($action=='add-player-request'){$this->InsertPlayerRequestIntoTeam();}    
    elseif($action=='delete-player-request'){$this->DeletePlayerRequestFromTeam();} 
    elseif($action=='del-player-admin-request'){$this->DeletePlayerRequestFromTeamAdmin();}       
    elseif($action=='add-player-admin-request'){$this->AddPlayerRequestIntoTeamAdmin();}
    elseif($action=='save-team'){$this->AdminSaveTeam();}    
    else{$this->PageMain();}    
    }      
  public function PageCreateNewTeam(){
    $idg=(int)getpost('idg');
    if($idg>0){
      $exist=$this->kernel->models->DBusers->MqueryGetLine('SELECT idt FROM teams WHERE id_hry="'.$idg.'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
      if($exist->idt>0){$this->Redirect(array('message'=>'team-not-created'));}
      $this->kernel->models->DBusers->Mquery('INSERT INTO teams (id_leadera,id_hry,nazev) VALUES ("'.$this->kernel->user->uid.'","'.$idg.'","Team")');
      $exist=$this->kernel->models->DBusers->MqueryGetLine('SELECT idt FROM teams WHERE id_hry="'.$idg.'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
      if($exist->idt>0){
        $x='a'.time().'b'.$exist->idt.'c'.$idg.'ff'.$this->kernel->user->uid.'x'.rand(100000,999999).'o';
        $hash=substr(md5($x),3,10).'-'.$exist->idt;
        $this->kernel->models->DBrewrites->store($rid,array('system_url'=>'/?module=FTeams&action=team-detail&id='.$exist->idt,'nice_url'=>'teams/view-team/'.$hash.'/','module'=>'FTeams','item_id'=>$exist->idt));
        $this->kernel->models->DBrewrites->RegenerateCacheFile();            
        $this->Redirect(array('action'=>'team-detail','id'=>$exist->idt));
      }else{
        $this->Redirect(array('message'=>'team-not-created'));
      }
    }       
    $this->Redirect(array('message'=>'team-not-created'));           
    }    
  public function PageTeamDetail(){
    $idt=(int)getget('id');    
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac,g.nazev as nazev_hry FROM teams as t,users as u, games as g  WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND t.idt="'.$idt.'"'); 
    if($team->idt<1){$this->Redirect();}
    
    $playersWaitingArr=array('0');
    $playersWaitingArr2=array();
    $playersWaiting=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.*, u.osloveni, u.user_picture, u.fb_picture FROM teams_users as t, users as u WHERE t.id_tymu="'.$team->idt.'" AND t.id_uzivatele=u.uid AND t.potvrdil_leader=0 AND t.potvvrdil_uzivatel=1 ORDER BY u.osloveni ASC');
    foreach($playersWaiting as $pW){
      $playersWaitingArr[]=((int)$pW->id_uzivatele);
      $pW->aadd=$this->Anchor(array('action'=>'add-player-admin-request','idt'=>$idt,'idu'=>$pW->id_uzivatele),false);
      $pW->adel=$this->Anchor(array('action'=>'del-player-admin-request','idt'=>$idt,'idu'=>$pW->id_uzivatele),false);
      $playersWaitingArr2[]=$pW;
      }
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams_users as t, users as u WHERE t.id_tymu="'.$team->idt.'" AND t.id_uzivatele=u.uid AND t.potvrdil_leader=1 AND t.potvvrdil_uzivatel=1 ORDER BY u.osloveni ASC');
    $playersArr=array(((int)$team->id_leadera));    
    foreach($players as $pl){$playersArr[]=((int)$pl->id_uzivatele);}          
    $users=$this->kernel->models->DBusers->getLines('uid,osloveni,user_picture,fb_picture','WHERE uid in ('.implode(',',$playersArr).') ORDER BY osloveni');
    $users2=array();
    $users3=array();
    $leader=new stdClass();
    foreach($users as $us){
      if($us->uid==$team->id_leadera){
        $leader=$us;
      }else{
        $us->adel=$this->Anchor(array('action'=>'del-player-admin-request','idt'=>$idt,'idu'=>$us->uid),false);
        $users2[$us->uid]=$us;
      }
      $users3[$us->uid]=$us->osloveni;
    }    
    $chatData=$this->kernel->models->DBusers->MqueryGetLines('(SELECT * FROM teams_chat WHERE id_tymu="'.$team->idt.'" ORDER BY idtc DESC LIMIT 30) ORDER BY idtc ASC');           
    $isAdmin=$this->kernel->user->data->prava>0?1:0;
    $tpl=new Templater();       
    $tpl->add('team',$team);
    $tpl->add('leader',$leader);
    $tpl->add('users',$users2);
    $tpl->add('users2',$users3);
    $tpl->add('chatData',$chatData);
    $tpl->add('playersArr',$playersArr);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $tpl->add('currentUserID',$this->kernel->user->uid);
    $tpl->add('isAdmin',$isAdmin);  
    $tpl->add('playersWaitingArr',$playersWaitingArr);
    $tpl->add('playersWaitingArr2',$playersWaitingArr2);
    $tpl->add('athis',$this->Anchor(array('action'=>'team-detail','id'=>$idt)));    
    $tpl->add('anewchat',$this->Anchor(array('action'=>'new-chat','idt'=>$idt),false));
    $tpl->add('asave',$this->Anchor(array('action'=>'save-team','idt'=>$idt),false));
    $tpl->add('agetin',$this->Anchor(array('action'=>'add-player-request','idt'=>$idt),false));
    $tpl->add('aleaveout',$this->Anchor(array('action'=>'delete-player-request','idt'=>$idt),false));
    $this->content=$tpl->fetch('frontend/teams/detail.tpl');  
    $this->execute();      
    }
  public function PageMain(){    
    $this->seo_title=$this->kernel->systemTranslator['tymy'];         
    $this->seo_keywords=$this->kernel->systemTranslator['tymy'];                 
    $this->seo_description=$this->kernel->systemTranslator['tymy'];
    $filter=$this->getFilter();   
    $orderBy='t.idt desc';
    if($filter->f_poradi=='idtasc'){$orderBy='t.idt asc';}
    if($filter->f_poradi=='nazevtymuasc'){$orderBy='t.nazev asc';}
    if($filter->f_poradi=='nazevtymudesc'){$orderBy='t.nazev desc';}
    if($filter->f_poradi=='nazevhryasc'){$orderBy='nazev_hry asc';}
    if($filter->f_poradi=='nazevhrydesc'){$orderBy='nazev_hry desc';}
    if($filter->f_poradi=='nazevleaderasc'){$orderBy='hrac asc';}
    if($filter->f_poradi=='nazevleaderdesc'){$orderBy='hrac desc';}  
    $andwhere='';
    if(trim($filter->f_leader)!=''){$andwhere.=' AND u.osloveni LIKE "%'.$filter->f_leader.'%"';}
    if(trim($filter->f_nazev)!=''){$andwhere.=' AND t.nazev LIKE "%'.$filter->f_nazev.'%"';}
    if(trim($filter->f_nazevhry)!=''){$andwhere.=' AND g.nazev LIKE "%'.$filter->f_nazevhry.'%"';}
    if(trim($filter->f_prijima_cleny)=='ano'){$andwhere.=' AND t.prijima_hrace=1';}
    if(trim($filter->f_prijima_cleny)=='ne'){$andwhere.=' AND t.prijima_hrace=0';}
    if(trim($filter->f_zobrazit)=='leader'){$andwhere.=' AND t.id_leadera="'.((int)$this->kernel->user->uid).'"';}
    if(trim($filter->f_zobrazit)=='clen'){$andwhere.=' AND t.idt IN (SELECT id_tymu FROM teams_users WHERE id_uzivatele="'.((int)$this->kernel->user->uid).'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1)';}
    if(trim($filter->f_zobrazit)=='leader_clen'){$andwhere.=' AND (t.idt IN (SELECT id_tymu FROM teams_users WHERE id_uzivatele="'.((int)$this->kernel->user->uid).'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1) OR t.id_leadera="'.((int)$this->kernel->user->uid).'")';}
    $page=(int)getget('page','0');
    $counter=10;//10  
    $teams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.*,u.osloveni as hrac,g.nazev as nazev_hry FROM teams as t,users as u, games as g WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND g.aktivni=1 '.$andwhere.' ORDER BY '.$orderBy.' LIMIT '.($page*$counter).', '.$counter);
    $teams_count=(int)$this->kernel->models->DBusers->MqueryGetOne('SELECT count(t.idt) as cnt FROM teams as t,users as u, games as g WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND g.aktivni=1 '.$andwhere.' '); 
    foreach($teams as $ck=>$cv){
      $teams[$ck]->aDetail=$this->Anchor(array('action'=>'team-detail','id'=>$cv->idt),false);
      $teams[$ck]->pocet=1+((int)$this->kernel->models->DBusers->MqueryGetOne('SELECT count(*) as cnt FROM teams_users WHERE id_tymu="'.$cv->idt.'"'));
      }    
    $gamesForCreateHelper=$this->kernel->models->DBusers->MqueryGetLines('SELECT idg,nazev FROM games WHERE aktivni=1 ORDER BY nazev ASC ');
    $gamesForCreate=array();
    $gamesUsed=$this->kernel->models->DBusers->MqueryGetLines('SELECT id_hry FROM teams WHERE id_leadera="'.$this->kernel->user->uid.'" ');
    $gamesUsedHelper=array('0');
    foreach($gamesUsed as $gU){
      $gamesUsedHelper[]=$gU->id_hry;
      }
    foreach($gamesForCreateHelper as $helper){
      if(!in_array($helper->idg,$gamesUsedHelper)){
        $gamesForCreate[$helper->idg]=$helper->nazev;
        }
      }    
    $paginnator=$this->Paginnator($page,$teams_count,$counter);    
    $tpl=new Templater();       
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('teams',$teams);
    $tpl->add('filter',$filter);
    $tpl->add('gamesForCreate',$gamesForCreate);      
    $tpl->add('acreate',$this->Anchor(array('action'=>'create-new'),false));
    $tpl->add('afilter',$this->Anchor(array('action'=>'set-filter'),false));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/teams/main.tpl');  
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
  private function setFilter(){
    $filter=new stdClass();
    $filter->f_leader=prepare_get_data_safely(getpost('f_leader',''));
    $filter->f_nazev=prepare_get_data_safely(getpost('f_nazev',''));
    $filter->f_nazevhry=prepare_get_data_safely(getpost('f_nazevhry',''));
    $filter->f_zobrazit=prepare_get_data_safely(getpost('f_zobrazit',''));
    $filter->f_prijima_cleny=prepare_get_data_safely(getpost('f_prijima_cleny',''));
    $filter->f_poradi=prepare_get_data_safely(getpost('f_poradi',''));
    $_SESSION['filter_teams']=$filter;
    $this->Redirect();
    }
  private function getFilter(){
    if(isset($_SESSION['filter_teams'])){
      return $_SESSION['filter_teams'];
      }
    $filter=new stdClass();
    $filter->f_leader='';
    $filter->f_nazev='';
    $filter->f_nazevhry='';
    $filter->f_zobrazit='leader';
    $filter->f_prijima_cleny='vse';
    $filter->f_poradi='idtdesc';
    return $filter;
    }
  private function InsertChatIntoTeam(){
    $idt=(int)getget('idt');
    $obsah=prepare_get_data_safely(getpost('obsah',''));
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac FROM teams as t,users as u WHERE t.id_leadera=u.uid AND t.idt="'.$idt.'"');   
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);} 
    $player=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams_users WHERE id_tymu="'.$idt.'" AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');                                   
    if($obsah==''){$this->redirect(array('action'=>'team-detail','message'=>'chat-failed','id'=>$idt),false);}
    if($player->idtu<1&&$team->id_leadera!=$this->kernel->user->uid){$this->redirect(array('action'=>'team-detail','message'=>'chat-failed','id'=>$idt),false);}
    $this->kernel->models->DBusers->Mquery('INSERT INTO teams_chat (id_tymu,id_uzivatele,ts,obsah) VALUES ("'.$idt.'","'.$this->kernel->user->uid.'","'.time().'","'.$obsah.'");');            
    $this->redirect(array('action'=>'team-detail','message'=>'chat-succes','id'=>$idt),false);   
    }
  private function InsertPlayerRequestIntoTeam(){
    $idt=(int)getget('idt');
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac,g.nazev as nazev_hry FROM teams as t,users as u, games as g WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND t.idt="'.$idt.'"');   
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);} 
    if($team->prijima_hrace==0){$this->redirect(array('action'=>'team-detail','message'=>'add-player-request-failed','id'=>$idt),false);}    
    $playerOfTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams_users as t WHERE t.id_tymu="'.$idt.'" AND t.id_uzivatele="'.$this->kernel->user->uid.'"');
    if($playerOfTeam->idtu>0){$this->redirect(array('action'=>'team-detail','message'=>'add-player-request-failed','id'=>$idt),false);}
    $this->kernel->models->DBusers->Mquery('INSERT INTO teams_users (id_tymu,id_uzivatele,potvrdil_leader,potvvrdil_uzivatel) VALUES ("'.$idt.'","'.$this->kernel->user->uid.'","0","1");');
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$team->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'team-detail','id'=>$idt),false).'","notifikace_typ_nova_zadost_o_clenstvi_v_tymu","'.$this->kernel->user->data->osloveni.'","'.$team->nazev.'","'.$team->nazev_hry.'");');                
    $this->redirect(array('action'=>'team-detail','message'=>'add-player-request-succes','id'=>$idt),false);   
    }
  private function DeletePlayerRequestFromTeam(){
    $idt=(int)getget('idt');
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac,g.nazev as nazev_hry FROM teams as t,users as u, games as g WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND t.idt="'.$idt.'"');     
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);}
    $this->kernel->models->DBusers->Mquery('DELETE FROM teams_users WHERE id_tymu="'.$idt.'" AND id_uzivatele="'.$this->kernel->user->uid.'";');  
    
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$team->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'team-detail','id'=>$idt),false).'","notifikace_typ_uzivatel_odesel_z_tymu","'.$this->kernel->user->data->osloveni.'","'.$team->nazev.'","'.$team->nazev_hry.'");');   
              
    $this->redirect(array('action'=>'team-detail','message'=>'delete-player-request-succes','id'=>$idt),false);   
    } 
  private function DeletePlayerRequestFromTeamAdmin(){
    $idt=(int)getget('idt');
    $idu=(int)getget('idu');
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac FROM teams as t,users as u WHERE t.id_leadera=u.uid AND t.idt="'.$idt.'"');   
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);}
    if($idu>0&&$team->id_leadera==$this->kernel->user->uid){
      $this->kernel->models->DBusers->Mquery('DELETE FROM teams_users WHERE id_tymu="'.$idt.'" AND id_uzivatele="'.$idu.'";');
      $this->redirect(array('action'=>'team-detail','message'=>'delete-player-request-succes','id'=>$idt),false); 
      }            
    $this->redirect(array('action'=>'team-detail','id'=>$idt),false);   
    } 
  private function AddPlayerRequestIntoTeamAdmin(){
    $idt=(int)getget('idt');
    $idu=(int)getget('idu');
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac,g.nazev as nazev_hry FROM teams as t,users as u, games as g WHERE t.id_leadera=u.uid AND g.idg=t.id_hry AND t.idt="'.$idt.'"');    
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);}
    if($idu>0&&$team->id_leadera==$this->kernel->user->uid){
      $this->kernel->models->DBusers->Mquery('UPDATE teams_users SET potvrdil_leader=1 WHERE id_tymu="'.$idt.'" AND id_uzivatele="'.$idu.'";');      
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$idu.'","'.time().'","0","'.$this->Anchor(array('action'=>'team-detail','id'=>$idt),false).'","notifikace_typ_zadost_o_clenstvi_v_tymu_potvrzena","'.$team->nazev.'","'.$team->nazev_hry.'");');       
      $this->redirect(array('action'=>'team-detail','message'=>'add-player-request-succes-2','id'=>$idt),false); 
      }            
    $this->redirect(array('action'=>'team-detail','id'=>$idt),false);   
    } 
  private function AdminSaveTeam(){
    $idt=(int)getget('idt');
    $team=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.*,u.osloveni as hrac FROM teams as t,users as u WHERE t.id_leadera=u.uid AND t.idt="'.$idt.'"');   
    if($team->idt<1){$this->redirect(array('message'=>'team-not-found'),false);}
    if($team->id_leadera==$this->kernel->user->uid||$this->kernel->user->data->prava>0){
      $nazev=prepare_get_data_safely(getpost('nazev',''));
      $popis=prepare_get_data_safely(getpost('popis',''));
      $prijima_hrace=(int)prepare_get_data_safely(getpost('prijima_hrace',''));  
      $this->kernel->models->DBusers->Mquery('UPDATE teams SET nazev="'.$nazev.'",popis="'.$popis.'",prijima_hrace="'.$prijima_hrace.'" WHERE idt="'.$idt.'";');
      $this->redirect(array('action'=>'team-detail','message'=>'saved-succes','id'=>$idt),false);       
      }
    $this->redirect(array('action'=>'team-detail','id'=>$idt),false);   
    }  
  }