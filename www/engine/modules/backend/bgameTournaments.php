<?php
class BGameTournaments extends Module {
    public function __construct() {
        $this->parent_module = 'BGame';
    }

    public function Main() {}

    public function PageMain() {
        $return = new stdClass();
        $filter = $this->getTournamentsFilter();
        $page = (int)getget('page', '0');
        $counter = 10;
        $andGroup = array();
        $andWhere = '';

        if ($filter->f_hra > 0) {
            $andGroup[] = ' id_hry="' . ((int)$filter->f_hra) . '" ';
        }

        if ($filter->f_zobrazeni == 1) {
            $andGroup[] = ' skryty=0 ';
        }

        if ($filter->f_zobrazeni == 2) {
            $andGroup[] = ' skryty=1 ';
        }

        if ($filter->f_odmeneno == 1) {
            $andGroup[] = ' prerozdelene_vyhry=1 ';
        }

        if ($filter->f_odmeneno == 2) {
            $andGroup[] = ' prerozdelene_vyhry=0 ';
        }

        if ($filter->f_dohrano == 1) {
            $andGroup[] = ' dohrano=1 ';
        }

        if ($filter->f_dohrano == 2) {
            $andGroup[] = ' dohrano=0 ';
        }

        if (count($andGroup) > 0) {
            $andWhere = ' WHERE ' . implode(' AND ', $andGroup) . ' ';
        }

        $list = $this->kernel->models->DBgamesTournaments->getLines('*', $andWhere . 'order by datum_cas_startu DESC LIMIT ' . ($page * $counter) . ', ' . $counter);
        $list_count = $this->kernel->models->DBgamesTournaments->getOne('count(idt)', $andWhere);
        $paginnator = $this->Paginnator($page, $list_count, $counter);

        $gaxx = array('0');
        $tgxx = array('0');

        foreach ($list as $lk => $lv) {
            $gaxx[] = $lv->id_hry;
            $tgxx[] = $lv->id_typu_hry;
            $list[$lk]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-edit', 'idt' => $lv->idt));
        }

        $gaxy = $this->kernel->models->DBgames->getLines('*', 'WHERE idg in (' . implode(',', $gaxx) . ')');
        $tgxy = $this->kernel->models->DBgamesTypes->getLines('*', 'WHERE idgt in (' . implode(',', $tgxx) . ')');
        $moxy = $this->kernel->models->DBgamesModules->getLines('*', 'order by nazev ASC ');

        $games2 = array('0' => ' - Nezadáno - ');
        $types2 = array('0' => ' - Nezadáno - ');
        $modules2 = array('0' => ' - Nezadáno - ');
        $games3 = array('0' => ' - Nezadáno - ');

        foreach ($gaxy as $gx) {
            $games2[$gx->idg] = $gx->nazev;
        }

        foreach ($tgxy as $tx) {
            $types2[$tx->idgt] = $tx->nazev;
        }

        foreach ($moxy as $mx) {
            $modules2[$mx->idm] = $mx->nazev;
        }

        $gaxy3 = $this->kernel->models->DBgames->getLines('idg,nazev', 'ORDER BY nazev');

        foreach ($gaxy3 as $gx3) {
            $games3[$gx3->idg] = $gx3->nazev;
        }

        $tpl = new Templater();
        $tpl->add('list', $list);
        $tpl->add('paginnator', $paginnator);
        $tpl->add('games2', $games2);
        $tpl->add('types2', $types2);
        $tpl->add('modules2', $modules2);
        $tpl->add('games3', $games3);
        $tpl->add('f_zobrazeni', $filter->f_zobrazeni);
        $tpl->add('f_odmeneno', $filter->f_odmeneno);
        $tpl->add('f_dohrano', $filter->f_dohrano);
        $tpl->add('f_hra', $filter->f_hra);
        $tpl->add('afilter', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-set-filter')));

        $return->seo_title = 'Zápasy ';
        $return->content = $tpl->fetch('backend/games/tournaments.tpl');

        return $return;
    }

  
  public function PageEdit() {

    $idt = (int) getget('idt', '');
    $data = $this->kernel->models->DBgamesTournaments->getLine('*', ' WHERE idt="' . $idt . '"');

    if (!isset($data->idt) || $data->idt < 1) {
        $this->redirect(array('module' => $this->parent_module, 'action' => 'tournaments', 'message' => 'not-found'), false);
    }

    $data->adetailcreator = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $data->id_uzivatele), false);

    $game = $this->kernel->models->DBgames->getLine('*', 'WHERE idg="' . $data->id_hry . '"');
    $type = $this->kernel->models->DBgamesTypes->getLine('*', 'WHERE idgt="' . $data->id_typu_hry . '"');
    $module = $this->kernel->models->DBgamesModules->getLine('*', 'WHERE idm="' . $data->id_modulu . '"');
    $moduleGames = $this->kernel->models->DBgamesModulesVsGames->getLine('*', 'WHERE idmod="' . $data->id_modulu . '" AND idgam="' . $data->id_hry . '"');

    $players = $this->kernel->models->DBgamesTournamentsPlayers->getLines('*', 'WHERE id_turnaje="' . $data->idt . '" ORDER BY skore DESC');
    $playersArr = array('0', $data->id_uzivatele);

    foreach ($players as $kpl => $pl) {
        $playersArr[] = $pl->id_hrace;
        $playersArr2[] = $pl->id_hrace;
        $players[$kpl]->adetail = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $pl->id_hrace), false);
    }

    $users = $this->kernel->models->DBusers->getLines('uid,osloveni', 'WHERE uid in (' . implode(',', $playersArr) . ')');
    $users2 = array();

    foreach ($users as $us) {
        $users2[$us->uid] = $us;
    }

    $chatData = $this->kernel->models->DBgamesTournamentsChat->getLines('*', 'WHERE id_turnaje="' . $idt . '" ORDER BY idtc ASC');

    foreach ($chatData as $cDk => $cDv) {
        $chatData[$cDk]->adel = $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-del-chat', 'idt' => $idt, 'idtc' => $cDv->idtc));
    }

    $winners_count = $this->kernel->models->DBgamesWinnerTypes->getOne('winners_count', 'WHERE idgwt="' . $data->id_vyplaty . '"');
    $winnersData = $this->kernel->models->DBgamesTournamentsWinners->getLine('*', 'WHERE id_turnaje="' . $data->idt . '"');

    $paramsIds = array('0');
    $params = $this->kernel->models->DBgamesParameters->getLines('*', ' WHERE idg="' . $data->id_hry . '" ORDER BY nazev');

    foreach ($params as $pk => $pv) {
        $paramsIds[$pv->idp] = $pv->idp;
    }

    $subParams = array();
    $subParamsq = $this->kernel->models->DBgamesParametersValues->getLines('*', ' WHERE idp in (' . implode(',', $paramsIds) . ') ORDER BY nazev');

    foreach ($subParamsq as $pk => $pv) {
        $subParams[$pv->idp][$pv->idpv] = $pv;
    }

    unset($subParamsq);

    $setParams = array();
    $subParamsq = $this->kernel->models->DBgamesTournamentsParameters->getLines('*', ' WHERE id_turnaje = "' . $idt . '" ');

    foreach ($subParamsq as $sPq) {
        $setParams[] = $sPq->id_hodnoty_parametru;
    }

    unset($subParamsq);

    if ($data->hraji_tymy == 1) {
        $loggedTeams = $this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="' . $data->idt . '" AND g.id_tymu=t.idt AND t.id_hry="' . $data->id_hry . '" ORDER BY t.nazev ASC');
        $loggedTeams2 = array();

        foreach ($loggedTeams as $lTx) {
            $loggedTeams2[$lTx->idt] = $lTx;
        }
    } else {
        $loggedTeams = array();
        $loggedTeams2 = array();
    }


    ///
    $postupujici_tymy = array();
$postupujici_hraci = array();

if ($data->pocet_postupujicich > 0) {
    if ($data->hraji_tymy == 1) {
        $loggedTeamsProgressing = $this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="' . $data->idt . '" AND g.id_tymu=t.idt AND t.id_hry="' . $data->id_hry . '" AND g.postupuje=1 ORDER BY t.nazev ASC');
        
        foreach ($loggedTeamsProgressing as $xteam) {
            $postupujici_tymy[] = $xteam->idt;
        }
    } else {
        $playersProgressing = $this->kernel->models->DBgamesTournamentsPlayers->getLines('*', 'WHERE id_turnaje="' . $data->idt . '" AND postupuje=1 ORDER BY id_tymu DESC');
        
        foreach ($playersProgressing as $xplayer) {
            $postupujici_hraci[] = $xplayer->id_hrace;
        }
    }
}

if ($data->id_cupu > 0) {
    $cup = $this->kernel->models->DBgamesCups->getLine('*', ' WHERE idc="' . $data->id_cupu . '"');
} else {
    $cup = new stdClass();
}

$prescores = $this->kernel->models->DBgamesTournamentsPrescores->getLine('*','WHERE idt="'.$data->idt.'"');
$screens = $this->kernel->models->DBgamesTournamentsScreenshots->getLines('*',' WHERE idt="'.$data->idt.'"  ORDER BY datum_cas DESC');

foreach ($screens as $cDk => $cDv) {
    $screens[$cDk]->adel = $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-del-screen', 'idt' => $idt, 'idgts' => $cDv->idgts));
}

$alters = $this->kernel->models->DBgamesTournamentsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$data->idt.'" ORDER BY id_tymu DESC');
$altersArr = array('0');
$altersArr2 = array();

foreach ($alters as $kat => $at) {
    $altersArr[] = $at->id_hrace;
    $altersArr2[] = $at->id_hrace;
    $alters[$kat]->adetail = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $at->id_hrace), false);
}

$users3 = $this->kernel->models->DBusers->getLines('uid,osloveni','WHERE uid in (' . implode(',', $altersArr) . ')');
$users4 = array();

foreach ($users3 as $us) {
    $users4[$us->uid] = $us;
}

  
    ///
    $return = new stdClass();
    $tpl = new Templater();
    $tpl->add('prescores', $prescores);
    $tpl->add('screens', $screens);
    $tpl->add('postupujici_hraci', $postupujici_hraci);
    $tpl->add('postupujici_tymy', $postupujici_tymy);
    $tpl->add('alters', $alters);
    $tpl->add('cup', $cup);
    $tpl->add('data', $data);
    $tpl->add('game', $game);
    $tpl->add('type', $type);
    $tpl->add('module', $module);
    $tpl->add('moduleGames', $moduleGames);
    $tpl->add('users', $users2);
    $tpl->add('usersA', $users4);
    $tpl->add('players', $players);
    $tpl->add('playersArr', $playersArr2);
    $tpl->add('chatData', $chatData);
    $tpl->add('winners_count', $winners_count);
    $tpl->add('winnersData', $winnersData);

    $tpl->add('aback', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments'), false));

    $tpl->add('aview', $this->Anchor(array('module' => 'FTournaments', 'action' => 'tournament-view', 'idt' => $idt), false));

    $tpl->add('aviewCup', $this->Anchor(array('module' => 'FCups', 'action' => 'cup-view', 'idt' => $data->id_cupu), false));

    $tpl->add('aaddwinners', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-add-winners', 'idt' => $idt), false));

    $tpl->add('aaddwinners2', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-add-winners-2', 'idt' => $idt), false));

    $tpl->add('achangeView', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-change-view', 'idt' => $idt), false));

    $tpl->add('achangeBanner', $this->Anchor(array('module' => $this->parent_module, 'action' => 'tournaments-change-banner', 'idt' => $idt), false));
    
    $tpl->add('banner', $this->kernel->GetEditor('banner', $data->banner));
    $tpl->add('params', $params);
    $tpl->add('subParams', $subParams);
    $tpl->add('setParams', $setParams);
    $tpl->add('loggedTeams', $loggedTeams);
    $tpl->add('loggedTeams2', $loggedTeams2);
    $return->seo_title = 'Detail zápasu ';
    $return->content = $tpl->fetch('backend/games/tournamentsEdit.tpl');
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
  public function PageDelScreenPost(){
    $idt=(int)getget('idt','');
    $idgts=(int)getget('idgts','');  
    if($idt<1||$idgts<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournamentsScreenshots->deleteId($idgts);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'screen-deleted','idt'=>$idt),false);        
    }
  public function PageDelChatPost(){
    $idt=(int)getget('idt','');
    $idtc=(int)getget('idtc','');  
    if($idt<1||$idtc<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournamentsChat->deleteId($idtc);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'chat-deleted','idt'=>$idt),false);        
    }
  public function PageTournamentsChangeBanner(){
    $idt=(int)getget('idt','');
    $banner=prepare_get_data_safely_editor(getpost('banner',''));
    if($idt<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournaments->store($idt,array('banner'=>$banner));  
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'viewing-saved','idt'=>$idt),false);  
    }
  public function PageTournamentsChangeView(){
    $idt=(int)getget('idt','');
    $skryty=(int)getpost('skryty','');
    if($idt<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournaments->store($idt,array('skryty'=>$skryty));  
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'viewing-saved','idt'=>$idt),false);  
    }
  public function PageAddWinnersPost(){
    $idt=(int)getget('idt','');
    $data=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE idt="'.$idt.'"');
    $dataGame=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$data->id_hry.'"');    
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
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$data->id_uzivatele,'datum_cas'=>time(),'coins'=>str_replace(',','.',round($onePercent*$moduleGames->procenta_pro_zakladatele,2)),'duvod'=>'Odměna za turnaj '.$dataGame->nazev));
    $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+str_replace(',','.',round($onePercent*$moduleGames->procenta_pro_zakladatele,2)))));        
    if($data->hraji_tymy==1){
      for($i=1;$i<=$winnersTypes->winners_count;$i++){    
        $storeArr['idu_misto_'.$i]=(int)$_POST['position_'.$i];    
        $ln='misto_'.$i;
        $coins=str_replace(',','.',($onePercent*$winnersTypes->$ln));
        $storeArr['coins_'.$i]=$coins;
        $usersY=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'" AND id_tymu="'.(int)$_POST['position_'.$i].'"');
        $usersYCnt=count($usersY);
        $coinsXZ=$coins/$usersYCnt;
        foreach($usersY as $userYY){
          $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$userYY->id_hrace.'"');
          $this->kernel->models->DBusersCoins->store(0,array('idu'=>$user->uid,'datum_cas'=>time(),'coins'=>$coinsXZ,'duvod'=>'Výhra v turnaji '.$dataGame->nazev));
          $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$coinsXZ)));          
          }
        }    
    }else{            
      for($i=1;$i<=$winnersTypes->winners_count;$i++){    
        $storeArr['idu_misto_'.$i]=(int)$_POST['position_'.$i];    
        $ln='misto_'.$i;
        $coins=str_replace(',','.',($onePercent*$winnersTypes->$ln));
        $storeArr['coins_'.$i]=$coins;
        $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.(int)$_POST['position_'.$i].'"');
        $this->kernel->models->DBusersCoins->store(0,array('idu'=>(int)$_POST['position_'.$i],'datum_cas'=>time(),'coins'=>$coins,'duvod'=>'Výhra v turnaji '.$dataGame->nazev));
        $this->kernel->models->DBusers->store($uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$coins)));
        }      
    } 
    $this->kernel->models->DBgamesTournamentsWinners->store(0,$storeArr);  
    $this->kernel->models->DBgamesTournaments->store($idt,array('prerozdelene_vyhry'=>'1'));   
    if($data->id_cupu>0&&$data->neni_odmenovan==0){$this->kernel->models->DBgamesCups->store($data->id_cupu,array('dohrano'=>'1'));}
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-added','idt'=>$idt),false);    
    }
  public function PageAddWinnersTwoPost(){
    $idt=(int)getget('idt','');
    $data=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE idt="'.$idt.'"');
    $dataGame=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$data->id_hry.'"');       
    if(!isset($data->idt)||$data->idt<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments','message'=>'not-found'),false);}
    $usersX=array();
    for($i=1;$i<=$data->pocet_postupujicich;$i++){
      if($_POST['position_'.$i]<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-not-added','idt'=>$idt),false);}
      if(isset($usersX[$_POST['position_'.$i]])&&$usersX[$_POST['position_'.$i]]==1){$this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'winners-not-added','idt'=>$idt),false);}
      $usersX[$_POST['position_'.$i]]=1;
      }
    if($data->hraji_tymy==1){
      for($i=1;$i<=$data->pocet_postupujicich;$i++){    
        $misto=(int)$_POST['position_'.$i];     
        if($misto>0){  
          $this->kernel->models->DBusers->Mquery('UPDATE `games_tournaments_teams` SET postupuje=1 WHERE id_tymu="'.$misto.'" AND id_turnaje="'.$idt.'" ');
          $this->kernel->models->DBusers->Mquery('UPDATE `games_tournaments_players` SET postupuje=1 WHERE id_tymu="'.$misto.'" AND id_turnaje="'.$idt.'" ');
          }
        }    
    }else{            
      for($i=1;$i<=$data->pocet_postupujicich;$i++){    
        $misto=(int)$_POST['position_'.$i];  
        if($misto>0){  
          $this->kernel->models->DBusers->Mquery('UPDATE `games_tournaments_players` SET postupuje=1 WHERE id_hrace="'.$misto.'" AND id_turnaje="'.$idt.'" ');
          }        
        }      
    } 
    $this->kernel->models->DBgamesTournaments->store($idt,array('prerozdelene_vyhry'=>'1'));   
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments-edit','message'=>'progress-added','idt'=>$idt),false);
    }
  public function PageTournamentsSetFilter(){
    $obj=new stdClass();
    $obj->f_zobrazeni=(int)getpost('f_zobrazeni');
    $obj->f_odmeneno=(int)getpost('f_odmeneno');
    $obj->f_dohrano=(int)getpost('f_dohrano');
    $obj->f_hra=(int)getpost('f_hra');
    $_SESSION['tournaments-backend-filter']=$obj;
    $this->redirect(array('module'=>$this->parent_module,'action'=>'tournaments'),false);    
    }
  private function getTournamentsFilter(){
    if(isset($_SESSION['tournaments-backend-filter'])){
      return $_SESSION['tournaments-backend-filter'];
      }
    $obj=new stdClass();
    $obj->f_zobrazeni=0;
    $obj->f_odmeneno=0;
    $obj->f_dohrano=0;
    $obj->f_hra=0;
    $_SESSION['tournaments-backend-filter']=$obj;
    return $obj; 
    }
  }  