<?php
class BGameTournaments extends Module{
  public function __construct(){$this->parent_module='BGame';}
  public function Main(){}  
  public function PageMain(){    
    $return=new stdClass();   
    $page=(int)getget('page','0');
    $counter=10;          
    $list=$this->kernel->models->DBgamesTournaments->getLines('*','order by datum_cas_startu DESC LIMIT '.($page*$counter).', '.$counter);
    $list_count=$this->kernel->models->DBgamesTournaments->getOne('count(idt)');    
    $paginnator=$this->Paginnator($page,$list_count,$counter);
    $gaxx=array('0');$sexx=array('0');$tgxx=array('0');$maxx=array('0');       
    foreach($list as $lk=>$lv){
      $gaxx[]=$lv->id_hry;
      $sexx[]=$lv->id_serveru;
      $tgxx[]=$lv->id_typu_hry;
      $maxx[]=$lv->id_mapy;   
      $list[$lk]->aedit=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$lv->idt));      
      }     
    $gaxy=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gaxx).')');
    $sexy=$this->kernel->models->DBgamesServers->getLines('*','WHERE idgs in ('.implode(',',$sexx).')');   
    $tgxy=$this->kernel->models->DBgamesTypes->getLines('*','WHERE idgt in ('.implode(',',$tgxx).')');   
    $maxy=$this->kernel->models->DBgamesMaps->getLines('*','WHERE idgm in ('.implode(',',$maxx).')');
    $moxy=$this->kernel->models->DBgamesModules->getLines('*','order by nazev ASC ');    
    $games2=array('0'=>' - Nezadáno - ');$servers2=array('0'=>' - Nezadáno - ');$types2=array('0'=>' - Nezadáno - ');$maps2=array('0'=>' - Nezadáno - ');$modules2=array('0'=>' - Nezadáno - '); 
    foreach($gaxy as $gx){$games2[$gx->idg]=$gx->nazev;}      
    foreach($sexy as $sx){$servers2[$sx->idgs]=$sx->nazev;}
    foreach($tgxy as $tx){$types2[$tx->idgt]=$tx->nazev;}
    foreach($maxy as $mx){$maps2[$mx->idgm]=$mx->nazev;}
    foreach($moxy as $mx){$modules2[$mx->idm]=$mx->nazev;}
    $tpl=new Templater();
    $tpl->add('list',$list);  
    $tpl->add('paginnator',$paginnator);       
    $tpl->add('games2',$games2);
    $tpl->add('servers2',$servers2);
    $tpl->add('types2',$types2);
    $tpl->add('maps2',$maps2);
    $tpl->add('modules2',$modules2);  
    $return->seo_title='Turnaje ';        
    $return->content=$tpl->fetch('backend/games/tournaments.tpl');    
    return $return;
    }   
  public function PageEdit(){
    $idt=(int)getget('idt','');    
    $data=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE idt="'.$idt.'"');
    if(!isset($data->idt)||$data->idt<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments','message'=>'not-found'),false);}
    $data->adetailcreator=$this->Anchor(array('module'=>'BUsers','action'=>'detail','uid'=>$data->id_uzivatele),false); 
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$data->id_hry.'"');       
    $server=$this->kernel->models->DBgamesServers->getLine('*','WHERE idgs="'.$data->id_serveru.'"');   
    $type=$this->kernel->models->DBgamesTypes->getLine('*','WHERE idgt="'.$data->id_typu_hry.'"');   
    $map=$this->kernel->models->DBgamesMaps->getLine('*','WHERE idgm="'.$data->id_mapy.'"');      
    $module=$this->kernel->models->DBgamesModules->getLine('*','WHERE idm="'.$data->id_modulu.'"');
    $moduleGames=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$data->id_modulu.'" AND idgam="'.$data->id_hry.'"');
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$data->idt.'" ORDER BY skore DESC');
    $playersArr=array('0',$data->id_uzivatele);
    foreach($players as $kpl=>$pl){
      $playersArr[]=$pl->id_hrace;
      $playersArr2[]=$pl->id_hrace;
      $players[$kpl]->adetail=$this->Anchor(array('module'=>'BUsers','action'=>'detail','uid'=>$pl->id_hrace),false);    
      }      
    $users=$this->kernel->models->DBusers->getLines('uid,osloveni','WHERE uid in ('.implode(',',$playersArr).')');
    $users2=array();
    foreach($users as $us){$users2[$us->uid]=$us;}    
    $chatData=$this->kernel->models->DBgamesTournamentsChat->getLines('*','WHERE id_turnaje="'.$idt.'" ORDER BY idtc ASC');
    foreach($chatData as $cDk=>$cDv){$chatData[$cDk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments-del-chat','idt'=>$idt,'idtc'=>$cDv->idtc));}
    $winners_count=$this->kernel->models->DBgamesWinnerTypes->getOne('winners_count','WHERE idgwt="'.$data->id_vyplaty.'"');
    $winnersData=$this->kernel->models->DBgamesTournamentsWinners->getLine('*','WHERE id_turnaje="'.$data->idt.'"');
    $return=new stdClass();     
    $tpl=new Templater();
    $tpl->add('data',$data);
    $tpl->add('game',$game);  
    $tpl->add('server',$server);  
    $tpl->add('type',$type);  
    $tpl->add('map',$map);
    $tpl->add('module',$module);
    $tpl->add('moduleGames',$moduleGames);
    $tpl->add('users',$users2);
    $tpl->add('players',$players);
    $tpl->add('playersArr',$playersArr2); 
    $tpl->add('chatData',$chatData);
    $tpl->add('winners_count',$winners_count);    
    $tpl->add('winnersData',$winnersData);                   
    $tpl->add('aback',$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments'),false));    
    $tpl->add('aaddwinners',$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments-add-winners','idt'=>$idt),false));  
    $return->seo_title='Detail turnaje ';        
    $return->content=$tpl->fetch('backend/games/tournamentsEdit.tpl');    
    return $return;
    }
  private function Paginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments','page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments','page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments','page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments','page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'tournaments','page'=>($page+1)),false);
    }    
    return $pages;          
    }
  public function PageDelChatPost(){
    $idt=(int)getget('idt','');
    $idtc=(int)getget('idtc','');  
    if($idt<1||$idtc<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournamentsChat->deleteId($idtc);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'chat-deleted','idt'=>$idt),false);        
    }
  public function PageAddWinnersPost(){
    $idt=(int)getget('idt','');
    $data=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE idt="'.$idt.'"');
    $dataGame=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$data->id_hry.'"');
    $dataMap=$this->kernel->models->DBgamesMaps->getLine('*','WHERE idgm="'.$data->id_mapy.'"');
    $dataServer=$this->kernel->models->DBgamesServers->getLine('*','WHERE idgs="'.$data->id_serveru.'"');  
    if(!isset($data->idt)||$data->idt<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments','message'=>'not-found'),false);}
    $usersX=array();
    $winnersTypes=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$data->id_vyplaty.'"');    
    for($i=1;$i<=$winnersTypes->winners_count;$i++){
      if($_POST['position_'.$i]<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-not-added','idt'=>$idt),false);}
      if(isset($usersX[$_POST['position_'.$i]])&&$usersX[$_POST['position_'.$i]]==1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-not-added','idt'=>$idt),false);}
      $usersX[$_POST['position_'.$i]]=1;
      }
    $usersCnt=$this->kernel->models->DBgamesTournamentsPlayers->getOne('count(idgtp)','WHERE id_turnaje="'.$idt.'"');    
    $onePercent=($data->cena * $usersCnt) / 100;
    $moduleGames=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$data->id_modulu.'" AND idgam="'.$data->id_hry.'"');
    $storeArr=array('id_turnaje'=>$idt,'id_typu_vyhry'=>$data->id_vyplaty,'odmena_zakladateli'=>str_replace(',','.',round($onePercent*$moduleGames->procenta_pro_zakladatele,2)) );
    
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.(int)$data->id_uzivatele.'"');
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$data->id_uzivatele,'datum_cas'=>time(),'coins'=>str_replace(',','.',round($onePercent*$moduleGames->procenta_pro_zakladatele,2)),'duvod'=>'Odměna za turnaj '.$dataGame->nazev.' '.$dataServer->nazev.' '.$dataMap->nazev));
    $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+str_replace(',','.',round($onePercent*$moduleGames->procenta_pro_zakladatele,2)))));
    
    for($i=1;$i<=$winnersTypes->winners_count;$i++){    
      $storeArr['idu_misto_'.$i]=(int)$_POST['position_'.$i];    
      $ln='misto_'.$i;
      $coins=str_replace(',','.',round($onePercent*$winnersTypes->$ln,2));
      $storeArr['coins_'.$i]=$coins;
      $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.(int)$_POST['position_'.$i].'"');
      $this->kernel->models->DBusersCoins->store(0,array('idu'=>(int)$_POST['position_'.$i],'datum_cas'=>time(),'coins'=>$coins,'duvod'=>'Výhra v turnaji '.$dataGame->nazev.' '.$dataServer->nazev.' '.$dataMap->nazev));
      $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$coins)));
      }
    $this->kernel->models->DBgamesTournamentsWinners->store(0,$storeArr);  
    $this->kernel->models->DBgamesTournaments->store($idt,array('prerozdelene_vyhry'=>'1'));    
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-added','idt'=>$idt),false);    
    }
  }  