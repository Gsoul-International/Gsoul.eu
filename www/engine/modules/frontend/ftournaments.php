<?php
class FTournaments extends Module{
  private $xmodule,$xmoduleData;
  public function Main(){
    $this->parent_module='Frontend';
    $this->xmodule='tournaments';    
    $this->xmoduleData=$this->kernel->models->DBgamesModules->getLine('*',' WHERE interni_nazev="'.$this->xmodule.'"');            
    $action=getget('action','main');     
    if(isset($_POST['action'])&&$_POST['action']=='setFilter'){$action='set-filter';}
    if($action=='main'){$this->MainPage();} 
    elseif($action=='new-tournament-post'){$this->NewTournamentPost();}
    elseif($action=='new-tournament'){$this->NewTournament();}                                     
    elseif($action=='new-tournament-post-2'){$this->NewTournamentPost2();}
    elseif($action=='tournament-view'){$this->TournamentView();}
    elseif($action=='get-into-tournament'){$this->GetIntoTournament();}
    elseif($action=='get-alter-into-tournament'){$this->GetAlterIntoTournament();}
    elseif($action=='get-team-into-tournament'){$this->GetTeamIntoTournament();}
    elseif($action=='end-tournament'){$this->EndTournament();}
    elseif($action=='upload-screenshot'){$this->UploadScreenshot();}
    elseif($action=='extend-tournament'){$this->ExtendTournament();}
    elseif($action=='new-chat'){$this->InsertChatIntoTournament();}
    elseif($action=='set-filter'){$this->SetFiter();}
    elseif($action=='unset-filter'){$this->UnSetFiter();}
    elseif($action=='change-filter-view'){$this->changeFilterView();}
    elseif($action=='player-kick'){$this->PlayerKick();}
    elseif($action=='team-kick'){$this->TeamKick();}   
    elseif($action=='add-round'){$this->AddRound();} 
    elseif($action=='edit-round'){$this->EditRound();}    
    elseif($action=='save-tournament-logindata'){$this->SaveTournamentLoginData();}
    elseif($action=='game-selector'){$this->GameSelector();}
    elseif($action=='save-info'){$this->SaveTournamentInfo();}
    else{$this->Redirect();}            
    }    
  private function GameSelector(){
    $this->seo_title=$this->kernel->systemTranslator['cups_cups'];              
    $this->seo_keywords=$this->kernel->systemTranslator['cups_cups'];   
    $this->seo_description=$this->kernel->systemTranslator['cups_cups'];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev'); 
    $moduleGames=$this->kernel->models->DBgamesModulesVsGames->getLines('*','WHERE idmod="'.$this->xmoduleData->idm.'"');
    $modulePlatformsQ=$this->kernel->models->DBgamesPlatforms->getLines('*',' where aktivni=1 order by nazev ');
    $modulePlatforms=array();
    foreach($modulePlatformsQ as $mP){
      $modulePlatforms[$mP->idgp]=$mP->nazev;
      }
    $gamesIds=array('0');   
    foreach($moduleGames as $xMg){$gamesIds[]=$xMg->idgam;}        
    $gamesQ=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gamesIds).') AND aktivni=1 ORDER BY nazev');
    $games=array();
    foreach($gamesQ as $xGk=>$xGv){
      $games[$xGv->idg]=$xGv;      
      $games[$xGv->idg]->platforms=array();
      $games[$xGv->idg]->alink=$this->Anchor(array('idg'=>$xGv->idg));
      }
    $platformsGamesQ=$this->kernel->models->DBgamesPlatformsGames->getLines('*','WHERE idg in ('.implode(',',$gamesIds).')');    
    foreach($platformsGamesQ as $pfGq){
      if(isset($games[$pfGq->idg])){
        $games[$pfGq->idg]->platforms[]=$modulePlatforms[$pfGq->idgp];       
        }    
      }
    $tpl=new Templater();  
    $tpl->add('games',$games);
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('moduleGames',$moduleGames);
    $tpl->add('modulePlatforms',$modulePlatformsQ);     
    $tpl->add('athis',$this->Anchor(array(),false));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);  
    $this->content=$tpl->fetch('frontend/tournaments/mainGames.tpl');  
    $this->execute();       
    }
  private function MainPage(){
    $this->seo_title=$this->kernel->systemTranslator['cups_cups'];              
    $this->seo_keywords=$this->kernel->systemTranslator['cups_cups'];   
    $this->seo_description=$this->kernel->systemTranslator['cups_cups'];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev'); 
    $page=(int)getget('page','0');
    $idg=(int)getget('idg','0');        
    $counter=20;
    $moduleGames=$this->kernel->models->DBgamesModulesVsGames->getLines('*','WHERE idmod="'.$this->xmoduleData->idm.'"');
    $modulePlatforms=$this->kernel->models->DBgamesPlatforms->getLines('*',' where aktivni=1 order by nazev ');
    $gamesIds=array('0');    
    foreach($moduleGames as $xMg){$gamesIds[]=$xMg->idgam;}    
    $games=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gamesIds).') AND aktivni=1 ORDER BY nazev');    
    foreach($games as $xGk=>$xGv){
      $games[$xGk]->alink=$this->Anchor(array('idg'=>$xGv->idg));
      }
    $modulePlatformsNames=array();
    foreach($modulePlatforms as $xGk=>$xGv){
      $modulePlatforms[$xGk]->alink=$this->Anchor(array('idgp'=>$xGv->idgp));
      $modulePlatformsNames[$xGv->idgp]=$xGv->nazev;
      }    
    if($idg<1){ 
      if($this->kernel->user->uid>0&&$this->kernel->user->data->last_entered_game>0){
        $idg=(int)$this->kernel->user->data->last_entered_game;
        if($idg>0){
          $this->redirect(array('idg'=>$idg));
          }
        }
      $idg=(int)$_SESSION['last_entered_game'];
      if($idg>0){
        $this->redirect(array('idg'=>$idg));
        }     
      $this->redirect(array('action'=>'game-selector'));
      return true;
    }else{
      $currGame=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$idg.'" AND aktivni=1');
      if($currGame->idg<1){$this->Redirect();}
      $_SESSION['last_entered_game']=$currGame->idg;
      if($this->kernel->user->uid>0){
        if($this->kernel->user->data->last_entered_game!=$currGame->idg){
          $this->kernel->models->DBusers->store($this->kernel->user->uid,array('last_entered_game'=>$currGame->idg));
          }
        }      
    } 
    $currGameModules=$this->kernel->models->DBgamesPlatformsGames->getLines('*',' WHERE idg="'.$idg.'"');
    $currGameplatforms=array();
    foreach($currGameModules as $mPs){
      $currGameplatforms[$mPs->idgp]=$modulePlatformsNames[$mPs->idgp];
      }  
    if(!isset($_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule])){$_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule]=array();}   
    $andddGrp=array();
    $andddGrpCup=array();
    if(count($_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule])>0){      
      foreach($_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule] as $sesparm){
        $dataFitersOne=$this->kernel->models->DBgamesTournamentsParameters->getLines('*','WHERE id_hodnoty_parametru="'.$sesparm.'" ');
        $dataFitersTwo=array('0');
        foreach($dataFitersOne as $dFo){$dataFitersTwo[$dFo->id_turnaje]=$dFo->id_turnaje;}        
        $andddGrp[]=' idt IN ('.implode(',',$dataFitersTwo).') ';
        //
        $dataFitersThree=$this->kernel->models->DBgamesCupsParameters->getLines('*','WHERE id_hodnoty_parametru="'.$sesparm.'" ');
        $dataFitersFour=array('0');
        foreach($dataFitersThree as $dFT){$dataFitersTwo[$dFT->id_cupu]=$dFT->id_cupu;}        
        $andddGrpCup[]=' idc IN ('.implode(',',$dataFitersTwo).') ';
        }                  
      }  
    if(!isset($_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule])){$_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule]=array();}   
    if(count($_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule])>0){      
      foreach($_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule] as $sesparm){
        $sesparmX=explode('_',trim($sesparm));
        $sesparamId=(int)$sesparmX[0];
        $sesparamText=str_replace($sesparamId.'_','',trim($sesparm));
        $dataFitersOne=$this->kernel->models->DBgamesTournamentsParameters->getLines('*',' WHERE id_parametru="'.$sesparamId.'" AND textova_hodnota="'.$sesparamText.'" ');
        $dataFitersTwo=array('0');
        foreach($dataFitersOne as $dFo){$dataFitersTwo[$dFo->id_turnaje]=$dFo->id_turnaje;}        
        $andddGrp[]=' idt IN ('.implode(',',$dataFitersTwo).') ';
        //
        $dataFitersThree=$this->kernel->models->DBgamesCupsParameters->getLines('*',' WHERE id_parametru="'.$sesparamId.'" AND textova_hodnota="'.$sesparamText.'" ');
        $dataFitersFour=array('0');
        foreach($dataFitersThree as $dFT){$dataFitersTwo[$dFT->id_cupu]=$dFT->id_cupu;}        
        $andddGrpCup[]=' idc IN ('.implode(',',$dataFitersTwo).') ';
        }
      }     
    if(count($andddGrp)>0){
      $andddGrpx=' AND ('.implode(' AND ',$andddGrp).') ';
    }else{
      $andddGrpx='';
    }
    if(count($andddGrpCup)>0){
      $andddGrpCupx=' AND ('.implode(' AND ',$andddGrpCup).') ';
    }else{
      $andddGrpCupx='';
    }                      
    $tournaments=$this->kernel->models->DBgamesTournaments->MqueryGetLines('
    SELECT idt,type,id_hry,cena,id_typu_hry,datum_cas_startu,dohrano,minimalni_pocet,maximalni_pocet,titulek,pravidla_mala FROM (
      (SELECT idc as idt, "cup" as type, id_hry,cena,id_typu_hry,datum_cas_startu,dohrano,minimalni_pocet_hracutymu as minimalni_pocet,maximalni_pocet_hracutymu as maximalni_pocet,titulek_cupu as titulek,pravidla_mala  FROM games_cups WHERE id_modulu="'.$this->xmoduleData->idm.'" AND id_hry="'.$currGame->idg.'" AND skryty=0 '.$andddGrpCupx.' ORDER BY datum_cas_startu DESC)
      UNION 
      (SELECT idt as idt, "tournament" as type, id_hry,cena,id_typu_hry,datum_cas_startu,dohrano,minimalni_pocet_hracu as minimalni_pocet,maximalni_pocet_hracu as maximalni_pocet,titul_turnaje as titulek,pravidla_turnaje_mala as pravidla_mala FROM games_tournaments WHERE id_modulu="'.$this->xmoduleData->idm.'" AND id_hry="'.$currGame->idg.'" AND skryty=0 AND id_cupu=0 '.$andddGrpx.' ORDER BY datum_cas_startu DESC) 
      )
      results ORDER BY datum_cas_startu DESC LIMIT '.($page*$counter).', '.$counter
    );
    $news_count_tours=$this->kernel->models->DBgamesTournaments->getOne('count(idt)','WHERE id_modulu="'.$this->xmoduleData->idm.'" AND id_hry="'.$currGame->idg.'" AND skryty=0 AND id_cupu=0 '.$andddGrpx.' ');
    $news_count_cups=$this->kernel->models->DBgamesCups->getOne('count(idc)','WHERE id_modulu="'.$this->xmoduleData->idm.'" AND id_hry="'.$currGame->idg.'" AND skryty=0 '.$andddGrpCupx.' '); 
    $news_count=$news_count_tours+$news_count_cups; 
    $paginnator=$this->Paginnator($page,$news_count,$counter,$currGame->idg);
    $gaxx=array('0');$tgxx=array('0');
    foreach($tournaments as $kt=>$vt){
      $gaxx[]=$vt->id_hry;      
      $tgxx[]=$vt->id_typu_hry;                  
      if($vt->type=="tournament"){
        $tournaments[$kt]->aview=$this->Anchor(array('action'=>'tournament-view','idt'=>$vt->idt));
        $xxpparams=$this->kernel->models->DBgamesTournamentsParameters->getLines('*',' WHERE id_turnaje="'.$vt->idt.'" ');
      }else{
        $tournaments[$kt]->aview=$this->Anchor(array('module'=>'FCups','action'=>'cup-view','idt'=>$vt->idt));
        $xxpparams=$this->kernel->models->DBgamesCupsParameters->getLines('*',' WHERE id_cupu="'.$vt->idt.'" ');      
      } 
      $tournaments[$kt]->params=array();
      $tournaments[$kt]->paramsText=array();    
      foreach($xxpparams as $xpp){
        if($xpp->id_parametru==0&&$xpp->id_hodnoty_parametru>0){
          $tournaments[$kt]->params[$xpp->id_hodnoty_parametru]=$xpp->id_hodnoty_parametru;
          }
        if($xpp->id_parametru>0&&$xpp->id_hodnoty_parametru==0){
          $tournaments[$kt]->paramsText[$xpp->id_parametru]=$xpp->textova_hodnota;
          
          }
        }   
      }
    $gaxy=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gaxx).')');       
    $tgxy=$this->kernel->models->DBgamesTypes->getLines('*','WHERE idgt in ('.implode(',',$tgxx).')');       
    $games2=array('0'=>' - Nezadáno - ');$types2=array('0'=>' - Nezadáno - ');
    foreach($gaxy as $gx){$games2[$gx->idg]=$gx->nazev;}          
    foreach($tgxy as $tx){$types2[$tx->idgt]=$tx->nazev;}    
    $paramsIds=array('0');      
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$currGame->idg.'" ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      $paramsIds[$pv->idp]=$pv->idp;     
      }       
    $subParams=array();
    $subParamsq=$this->kernel->models->DBgamesParametersValues->getLines('*',' WHERE idp in ('.implode(',',$paramsIds).') AND aktivni=1 ORDER BY poradi,nazev');  
    foreach($subParamsq as $pk=>$pv){
      $subParams[$pv->idp][$pv->idpv]=$pv;         
      }       
    unset($subParamsq);
    $subParamsText=array(); 
    $subParamsTextq=$this->kernel->models->DBgamesCupsParameters->MqueryGetLines('
    SELECT id_parametru,id_hodnoty_parametru,textova_hodnota FROM (
      (SELECT id_parametru,id_hodnoty_parametru,textova_hodnota FROM games_cups_parameters WHERE id_parametru in ('.implode(',',$paramsIds).') AND id_hodnoty_parametru=0)
      UNION 
      (SELECT id_parametru,id_hodnoty_parametru,textova_hodnota FROM games_tournaments_parameters WHERE id_parametru in ('.implode(',',$paramsIds).') AND id_hodnoty_parametru=0 ORDER BY id_parametru,textova_hodnota)
    )  results ORDER BY id_parametru,textova_hodnota
    ');
    foreach($subParamsTextq as $pPTk=>$pPTv){
      $subParamsText[$pPTv->id_parametru][$pPTv->textova_hodnota]=$pPTv->textova_hodnota;
      }   
    unset($subParamsTextq);    
    $tpl=new Templater();          
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('paginnator',$paginnator);
    $tpl->add('currGameModules',$currGameModules);
    $tpl->add('currGameplatforms',$currGameplatforms);        
    $tpl->add('moduleGames',$moduleGames);
    $tpl->add('games',$games);
    $tpl->add('games2',$games2);
    $tpl->add('currGame',$currGame);
    $tpl->add('modulePlatforms',$modulePlatforms);    
    $tpl->add('types2',$types2);           
    $tpl->add('tournaments',$tournaments);           
    $tpl->add('userID',$this->kernel->user->uid);           
    $tpl->add('anewpost',$this->Anchor(array('action'=>'new-tournament-post'),false));
    $tpl->add('anewcuppost',$this->Anchor(array('module'=>'FCups','action'=>'new-cup-post'),false));
    $tpl->add('athis',$this->Anchor(array(),false));  
    $tpl->add('athisGame',$this->Anchor(array('idg'=>$currGame->idg),false));
    $tpl->add('aunsetFilter',$this->Anchor(array('idg'=>$currGame->idg,'action'=>'unset-filter'),false));
    $tpl->add('achangefilterview',$this->Anchor(array('idg'=>$currGame->idg,'action'=>'change-filter-view'),false));
    $tpl->add('params',$params);
    $tpl->add('subParams',$subParams);
    $tpl->add('subParamsText',$subParamsText);    
    $tpl->add('curIdg',$idg);
    $tpl->add('filterParams',$_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule]);
    $tpl->add('filterParamsText',$_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule]);    
    $tpl->add('user',$this->kernel->user->data);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);   
    $tpl->add('aGoToCups',$this->Anchor(array('module'=>'FCups','idg'=>$idg),false));
    $tpl->add('achangethegame',$this->Anchor(array('action'=>'game-selector'),false));
    $tpl->add('aGoToTournaments',$this->Anchor(array('module'=>'FTournaments','idg'=>$idg),false)); 
    $tpl->add('filterEnabled',(int)$_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule]);    
    $this->content=$tpl->fetch('frontend/tournaments/main.tpl');  
    $this->execute();  
    }
  private function Paginnator($page=0,$count=0,$counter=0,$idg=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('idg'=>$idg,'page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('idg'=>$idg,'page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('idg'=>$idg,'page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('idg'=>$idg,'page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('idg'=>$idg,'page'=>($page+1)),false);
    }    
    return $pages;          
    } 
  private function SetFiter(){
    $idg=(int)getget('idg','0');
    $_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule]=array();
    if(isset($_POST['params'])&&count($_POST['params'])>0){         
      foreach($_POST['params'] as $xpp){
        $xpp=(int)$xpp;
        if($xpp>0){
          $_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule][$xpp]=$xpp;
          }              
        }
      }
    $_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule]=array();
    if(isset($_POST['paramsText'])&&count($_POST['paramsText'])>0){         
      foreach($_POST['paramsText'] as $xpp){
        $xpp=prepare_get_data_safely($xpp);
        if($xpp!=''&&$xpp!='0'){
          $_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule][$xpp]=$xpp;
          }              
        }
      }
    $this->redirect(array('idg'=>$idg),false);   
    }  
  private function UnSetFiter(){
    $idg=(int)getget('idg','0');
    $_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule]=array();
    $_SESSION['filterText_game_'.$idg.'_id_'.$this->xmodule]=array();
    $this->redirect(array('idg'=>$idg),false);
    }    
  private function changeFilterView(){
    $idg=(int)getget('idg','0');
    if(!isset($_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule])){
      $_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule]=false;
      }
    $filter=$_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule];
    if($filter==false){
      $_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule]=true;
      }
    if($filter==true){
      $_SESSION['filter_Activator_game_'.$idg.'_id_'.$this->xmodule]=false;
      }
    $this->redirect(array('idg'=>$idg),false);
    }  
  private function NewTournamentPost(){
    $idg=(int)getpost('idg','0');
    $_SESSION['new_game_id_'.$this->xmodule]=$idg;
    unset($_SESSION['new_game_'.$this->xmodule]);
    $this->redirect(array('action'=>'new-tournament'),false);    
    }
  private function NewTournament(){     
    $this->seo_title=$this->kernel->systemTranslator['turnaje_turnaje2'];     
    $this->seo_keywords=$this->kernel->systemTranslator['turnaje_turnaje2'];         
    $this->seo_description=$this->kernel->systemTranslator['turnaje_turnaje2'];  
    $idg=(int)$_SESSION['new_game_id_'.$this->xmodule];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');   
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$idg.'"');
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$idg.'"');
    if($game->idg<1||$this->kernel->user->data->overen_email==0||($game->zaklada_jen_admin==1&&$this->kernel->user->data->prava==0)){$this->redirect(array('message'=>'choose-game'),false);}   
    $types=$this->kernel->models->DBgamesTypes->getLines('*',' WHERE idg="'.$idg.'" and aktivni=1 ORDER BY nazev');    
    $vyplaty=$this->kernel->models->DBgamesWinnerTypes->getLines('*',' WHERE idg="'.$idg.'" and aktivni=1 ORDER BY nazev');
    $paramsIds=array('0');      
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$idg.'" and typ_v_turnaji_cupu>0 ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      $paramsIds[$pv->idp]=$pv->idp;     
      }       
    $subParams=array();
    $subParamsq=$this->kernel->models->DBgamesParametersValues->getLines('*',' WHERE idp in ('.implode(',',$paramsIds).') AND aktivni=1 ORDER BY poradi,nazev');  
    foreach($subParamsq as $pk=>$pv){
      $subParams[$pv->idp][$pv->idpv]=$pv;         
      }          
    unset($subParamsq);
    if(!isset($_SESSION['new_game_'.$this->xmodule])){
      $_SESSION['new_game_'.$this->xmodule]=new stdClass();      
      $_SESSION['new_game_'.$this->xmodule]->idgt=0;           
      $_SESSION['new_game_'.$this->xmodule]->cena='0,00';
      $_SESSION['new_game_'.$this->xmodule]->datum_cas_startu=strftime('%d.%m.%Y %H:%M',time()+86400);                 
      $_SESSION['new_game_'.$this->xmodule]->poznamka_zakladatele=''; 
      $_SESSION['new_game_'.$this->xmodule]->titul_turnaje=''; 
      $_SESSION['new_game_'.$this->xmodule]->heslo_turnaje='';      
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_mala=$game->pravidla_turnaje;
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_velka=$game->podrobna_pravidla_turnaje;    
      $_SESSION['new_game_'.$this->xmodule]->params=array();  
      $_SESSION['new_game_'.$this->xmodule]->paramsText=array();  
      $this->redirect(array('action'=>'new-tournament'),false);    
      }
    if($_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_mala==''){
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_mala=$game->pravidla_turnaje;
      }
    if($_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_velka==''){
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_velka=$game->podrobna_pravidla_turnaje;
      }
    $currGameModules=$this->kernel->models->DBgamesPlatformsGames->getLines('*',' WHERE idg="'.$game->idg.'"');
    $modulePlatforms=$this->kernel->models->DBgamesPlatforms->getLines('*',' where aktivni=1 order by RAND() ');
    foreach($modulePlatforms as $xGk=>$xGv){$modulePlatforms[$xGk]->alink=$this->Anchor(array('idgp'=>$xGv->idgp));} 
    $tpl=new Templater(); 
    $tpl->add('modulePlatforms',$modulePlatforms);
    $tpl->add('currGameModules',$currGameModules);
    $tpl->add('abackGame',$this->Anchor(array('idg'=>$game->idg),false));           
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('moduleGame',$moduleGame);  
    $tpl->add('game',$game);      
    $tpl->add('types',$types);        
    $tpl->add('vyplaty',$vyplaty);
    $tpl->add('params',$params);
    $tpl->add('subParams',$subParams);
    $tpl->add('ngData',$_SESSION['new_game_'.$this->xmodule]);                   
    $tpl->add('anext',$this->Anchor(array('action'=>'new-tournament-post-2'),false));
    $tpl->add('aback',$this->Anchor(array(),false));      
    $tpl->add('message',getget('message',''));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);      
    $this->content=$tpl->fetch('frontend/tournaments/newTournament.tpl');  
    $this->execute();  
    }  
  private function NewTournamentPost2(){
    $idg=(int)$_SESSION['new_game_id_'.$this->xmodule];
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$idg.'"');
    $storeData=array('id_modulu'=>$this->xmoduleData->idm,'id_hry'=>$idg,'id_uzivatele'=>$this->kernel->user->uid,'datum_vytvoreni'=>time());
    $Data=new stdClass();
    foreach($_POST as $kp=>$vp){
      $kp=prepare_get_data_safely($kp);
      $vp=prepare_get_data_safely($vp);
      if($kp!='params'){
        $_SESSION['new_game_'.$this->xmodule]->$kp=$vp;
        $Data->$kp=$vp;
        }              
      }
    $Data->params=array(); 
    if(isset($_POST['params'])&&count($_POST['params'])>0){
      $_SESSION['new_game_'.$this->xmodule]->params=array();    
      foreach($_POST['params'] as $xpp){
        $xpp=(int)$xpp;
        $_SESSION['new_game_'.$this->xmodule]->params[$xpp]=$xpp;  
        $Data->params[$xpp]=$xpp;          
        }
      }
    //
    $Data->paramsText=array();
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$idg.'" and typ_v_turnaji_cupu=3 ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      if(isset($_POST['paramText_'.$pv->idp])){
        $Data->paramsText[$pv->idp]=prepare_get_data_safely($_POST['paramText_'.$pv->idp]);
        $_SESSION['new_game_'.$this->xmodule]->paramsText[$pv->idp]=prepare_get_data_safely($_POST['paramText_'.$pv->idp]);
        }
      }  
    //       
    $typeOfGame=$this->kernel->models->DBgamesTypes->getLine('*',' WHERE idg="'.$idg.'" AND idgt="'.$Data->idgt.'" and aktivni=1 ORDER BY nazev');         
    if($Data->having_licence<1||$Data->idgt<1||$idg<1||$this->xmoduleData->idm<1||$Data->cena==''||$Data->datum_cas_startu==''||$Data->pravidla_turnaje_mala==''||$Data->pravidla_turnaje_velka==''||$this->kernel->user->data->overen_email==0||$typeOfGame->idgt<1){$this->redirect(array('action'=>'new-tournament','message'=>'all-needed'),false);}            
    $storeData['id_typu_hry']=$Data->idgt;    
    $storeData['id_vyplaty']=$typeOfGame->tournament_id_vyplaty;    
    $storeData['cena']=str_replace(',','.',trim((round((float)str_replace(',','.',$Data->cena),2))));     ;
    $storeData['datum_cas_startu']=DateTimeToTimestamp($Data->datum_cas_startu);    
    $storeData['minimalni_pocet_hracu']=(int)$typeOfGame->tournament_minimalni_pocet_hracu;
    $storeData['maximalni_pocet_hracu']=(int)$typeOfGame->tournament_maximalni_pocet_hracu;
    $storeData['hraji_tymy']=(int)$Data->hraji_tymy;    
    $storeData['minimalni_pocet_tymu']=(int)$typeOfGame->tournament_minimalni_pocet_tymu;
    $storeData['maximalni_pocet_tymu']=(int)$typeOfGame->tournament_maximalni_pocet_tymu;    
    $storeData['poznamka_zakladatele']=$Data->poznamka_zakladatele;    
    $storeData['pravidla_turnaje_mala']=$Data->pravidla_turnaje_mala;
    $storeData['pravidla_turnaje_velka']=$Data->pravidla_turnaje_velka;  
    $storeData['titul_turnaje']=$Data->titul_turnaje;
    $storeData['heslo_turnaje']=$Data->heslo_turnaje;                         
    if($storeData['datum_cas_startu']<=time()+3600){$this->redirect(array('action'=>'new-tournament','message'=>'date-error'),false);}                     
    if($this->kernel->user->data->ucetni_zustatek<$moduleGame->poplatek_za_zalozeni_turnaje){$this->redirect(array('action'=>'new-tournament','message'=>'coins'),false);}    
    $storeData['hash']=$this->kernel->models->DBgamesTournaments->generateHash(rand(0,99999),$this->xmoduleData->idm,$storeData['id_typu_hry'],0,$storeData['id_typu_hry'],1,$this->kernel->user->uid,$storeData['id_vyplaty'],$storeData['datum_vytvoreni'],$storeData['datum_cas_startu'],$storeData['datum_cas_startu']+10,3);   
    $xid=$this->kernel->models->DBgamesTournaments->store(0,$storeData);
    $this->kernel->models->DBrewrites->AddEditRewrite('tournaments/view-tournament/'.$storeData['hash'].'-'.$xid.'/','FTournaments3','idt',$xid);    
    if(count($Data->params)>0){
      foreach($Data->params as $xpam){
        $this->kernel->models->DBgamesTournamentsParameters->store(0,array('id_turnaje'=>$xid,'id_hodnoty_parametru'=>$xpam));    
        }
      }
    if(count($Data->paramsText)>0){
      foreach($Data->paramsText as $xpamk=>$xpamv){
        $this->kernel->models->DBgamesTournamentsParameters->store(0,array('id_turnaje'=>$xid,'id_parametru'=>$xpamk,'textova_hodnota'=>$xpamv));    
        }
      }
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($moduleGame->poplatek_za_zalozeni_turnaje*(-1)),'duvod'=>'Poplatek za založení turnaje #'.$xid.'.'));
    $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$moduleGame->poplatek_za_zalozeni_turnaje)));
    unset($_SESSION['new_game_id_'.$this->xmodule]);
    unset($_SESSION['new_game_'.$this->xmodule]);        
    $this->redirect(array('action'=>'tournament-view','idt'=>$xid),false);      
    }
  private function TournamentView(){
    //if($this->kernel->user->uid<1){$this->redirect();} 
    $this->seo_title=$this->kernel->systemTranslator['turnaje_turnaje2'];     
    $this->seo_keywords=$this->kernel->systemTranslator['turnaje_turnaje2'];      
    $this->seo_description=$this->kernel->systemTranslator['turnaje_turnaje2']; 
    $idt=(int)getget('idt','');
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');  
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'" AND skryty=0');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$tournament->id_hry.'"');       
    $type=$this->kernel->models->DBgamesTypes->getLine('*','WHERE idgt="'.$tournament->id_typu_hry.'"');       
    $vyplata=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$tournament->id_vyplaty.'"');
    $vyplataData=$this->kernel->models->DBgamesTournamentsWinners->getLine('*','WHERE id_turnaje="'.$tournament->idt.'"');
    $currentPlayer=new stdClass();
    $alternatesArr=array('0');
    $alternatesArr2=array();
    $alternatesNicks=array();
    $playersNicks=array();
    $alternates=$this->kernel->models->DBgamesTournamentsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" ORDER BY id_tymu DESC');
    foreach($alternates as $al){
      $alternatesArr[]=$al->id_hrace;$alternatesArr2[]=$al;
      $playersNicks[$al->id_hrace]=$al->nick;$alternatesNicks[$al->id_hrace]=$al->nick;
      if($al->id_hrace==$this->kernel->user->uid){
        $currentPlayer=$al;
        }
      }
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" ORDER BY id_tymu DESC');
    $playersArr=array('0',$tournament->id_uzivatele);        
    foreach($players as $pl){
      $playersArr[]=$pl->id_hrace;$playersArr2[]=$pl->id_hrace;$playersNicks[$pl->id_hrace]=$pl->nick;
      if($pl->id_hrace==$this->kernel->user->uid){
        $currentPlayer=$pl;
        }
      }          
    $users=$this->kernel->models->DBusers->getLines('uid,osloveni,user_picture,fb_picture','WHERE uid in ('.implode(',',$playersArr).')');
    $users2=array();
    foreach($users as $us){$users2[$us->uid]=$us;}    
    $chatData=$this->kernel->models->DBgamesTournamentsChat->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" ORDER BY idtc ASC');
    $currGameModules=$this->kernel->models->DBgamesPlatformsGames->getLines('*',' WHERE idg="'.$tournament->id_hry.'"');
    $modulePlatforms=$this->kernel->models->DBgamesPlatforms->getLines('*',' where aktivni=1 order by RAND() ');
    foreach($modulePlatforms as $xGk=>$xGv){$modulePlatforms[$xGk]->alink=$this->Anchor(array('idgp'=>$xGv->idgp));}  
    if($tournament->hraji_tymy==1){
      $currentUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams WHERE id_hry="'.$tournament->id_hry.'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
      $UserHaveLoggedOwnTeam=0;
      if($currentUserTeam->idt>0){
        $loggedUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM games_tournaments_teams WHERE id_tymu="'.$currentUserTeam->idt.'" AND id_turnaje="'.$tournament->idt.'"');
        if($loggedUserTeam->idgtt>0){
          $UserHaveLoggedOwnTeam=1;
          }
        }
      $loggedTeams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" ORDER BY t.nazev ASC');
      $loggedTeams2=array();
      $currentPlayerTeams=array();
      $loggedTeamsIds=array('0');
      foreach($loggedTeams as $xteam){
        if($xteam->id_leadera==$this->kernel->user->uid){
          $currentPlayerTeams[$xteam->idt]=$xteam;
          } 
        $loggedTeamsIds[$xteam->idt]=$xteam->idt; 
        $loggedTeams2[$xteam->idt]=$xteam;      
        }
      $xu=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM teams_users WHERE id_tymu IN ('.implode(',',$loggedTeamsIds).') AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
      foreach($xu as $xuv){
        $currentPlayerTeams[$xuv->id_tymu]=$loggedTeams2[$xuv->id_tymu];
        }     
    }else{
      $currentUserTeam=new stdClass();
      $UserHaveLoggedOwnTeam=0;
      $loggedTeams=array();
      $loggedTeams2=array(); 
      $currentPlayerTeams=array();    
    }  
    ///
    $postupujici_tymy=array();
    $postupujici_hraci=array();
    if($tournament->pocet_postupujicich>0){      
      if($tournament->hraji_tymy==1){
        $loggedTeamsProgressing=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND g.postupuje=1 ORDER BY t.nazev ASC');
        foreach($loggedTeamsProgressing as $xteam){          
          $postupujici_tymy[]=$xteam->idt;          
          } 
      }else{
        $playersProgressing=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" AND postupuje=1 ORDER BY id_tymu DESC');
        foreach($playersProgressing as $xplayer){          
          $postupujici_hraci[]=$xplayer->id_hrace;          
          } 
      }      
    } 
    if($tournament->id_cupu>0){    
      $cup=$this->kernel->models->DBgamesCups->getLine('*',' WHERE idc="'.$tournament->id_cupu.'"');
    }else{
      $cup=new stdClass();
    }   
    ///
    $screens=$this->kernel->models->DBgamesTournamentsScreenshots->getLines('*',' WHERE idt="'.$tournament->idt.'"  ORDER BY datum_cas DESC');
    $winners_count=$this->kernel->models->DBgamesWinnerTypes->getOne('winners_count','WHERE idgwt="'.$tournament->id_vyplaty.'"');
    ///
    $tournamentRounds=$this->kernel->models->DBgamesTournamentsRounds->getLines('*',' WHERE idt="'.$tournament->idt.'"  ORDER BY datum_cas DESC');    
    if(count($tournamentRounds)>0){
      $tournamentRoundsIds=array('0');    
      foreach($tournamentRounds as $tRound){
        $tRound->idgtr=(int)$tRound->idgtr;
        $tournamentRoundsIds[$tRound->idgtr]=$tRound->idgtr;
        }
      $tournamentRoundsScoresData=$this->kernel->models->DBgamesTournamentsRoundsScore->getLines('*',' WHERE idgtr IN ('.implode(',',$tournamentRoundsIds).') ');
      $tournamentRoundsScores=array();
      $tournamentRoundsScoresTwo=array();
      foreach($tournamentRoundsScoresData as $tsrsd){
        $tournamentRoundsScores[$tsrsd->idgtr][$tsrsd->id_hrace_tymu]=$tsrsd->skore;
        $tournamentRoundsScoresTwo[$tsrsd->idgtr][$tsrsd->id_hrace_tymu]=$tsrsd->id_hrace_tymu;
        $tournamentRoundsScoresThree[$tsrsd->idgtr][$tsrsd->id_hrace_tymu]=$tsrsd->idgtrs;
        }
      $tournamentRoundsTotalScores=$this->kernel->models->DBgamesTournamentsRoundsScore->MqueryGetLines('SELECT id_hrace_tymu, sum(skore) as finalskore FROM `games_tournaments_rounds_score` WHERE idgtr in ('.implode(',',$tournamentRoundsIds).') GROUP BY id_hrace_tymu ORDER BY finalskore DESC');
    }else{
      $tournamentRoundsScores=array();
      $tournamentRoundsScoresTwo=array();
      $tournamentRoundsScoresThree=array();
      $tournamentRoundsTotalScores=array();
    }
    ///
    $paramsIds=array('0');      
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$tournament->id_hry.'" and typ_v_turnaji_cupu>0 ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      $paramsIds[$pv->idp]=$pv->idp;     
      }       
    $subParams=array();
    $subParamsq=$this->kernel->models->DBgamesParametersValues->getLines('*',' WHERE idp in ('.implode(',',$paramsIds).') AND aktivni=1 ORDER BY poradi,nazev');  
    foreach($subParamsq as $pk=>$pv){
      $subParams[$pv->idp][$pv->idpv]=$pv;         
      }          
    unset($subParamsq);
    $paramsUsed=array();
    $paramsUsedText=array();
    $paramsUsedq=$this->kernel->models->DBgamesTournamentsParameters->getLines('*',' WHERE id_turnaje="'.$tournament->idt.'"'); 
    foreach($paramsUsedq as $pUq){
      if($pUq->id_parametru>0&&$pUq->id_hodnoty_parametru==0){
        $paramsUsedText[$pUq->id_parametru]=$pUq->textova_hodnota;
        }
      if($pUq->id_parametru==0&&$pUq->id_hodnoty_parametru>0){
        $paramsUsed[$pUq->id_hodnoty_parametru]=$pUq->id_hodnoty_parametru;
        }
      }    
    unset($paramsUsedq);        
    $tpl=new Templater();        
    $tpl->add('params',$params);
    $tpl->add('subParams',$subParams);
    $tpl->add('paramsUsed',$paramsUsed);
    $tpl->add('paramsUsedText',$paramsUsedText);
    $tpl->add('alternatesArr',$alternatesArr);
    $tpl->add('alternatesArr2',$alternatesArr2);
    $tpl->add('alternatesNicks',$alternatesNicks);    
    $tpl->add('tournamentRounds',$tournamentRounds);
    $tpl->add('tournamentRoundsScores',$tournamentRoundsScores);
    $tpl->add('tournamentRoundsScoresTwo',$tournamentRoundsScoresTwo);
    $tpl->add('tournamentRoundsScoresThree',$tournamentRoundsScoresThree);
    $tpl->add('tournamentRoundsTotalScores',$tournamentRoundsTotalScores);
    $tpl->add('screens',$screens);
    $tpl->add('winners_count',$winners_count);
    $tpl->add('cup',$cup);
    $tpl->add('postupujici_hraci',$postupujici_hraci);
    $tpl->add('postupujici_tymy',$postupujici_tymy);
    $tpl->add('currentUserTeam',$currentUserTeam);
    $tpl->add('UserHaveLoggedOwnTeam',$UserHaveLoggedOwnTeam);
    $tpl->add('loggedTeams',$loggedTeams);
    $tpl->add('loggedTeams2',$loggedTeams2);
    $tpl->add('currentPlayerTeams',$currentPlayerTeams);
    $tpl->add('currentPlayer',$currentPlayer);    
    $tpl->add('agetinTeam',$this->Anchor(array('action'=>'get-team-into-tournament','idt'=>$idt),false));
    $tpl->add('tournament',$tournament);
    $tpl->add('modulePlatforms',$modulePlatforms);
    $tpl->add('currGameModules',$currGameModules);  
    $tpl->add('game',$game);          
    $tpl->add('type',$type);         
    $tpl->add('vyplata',$vyplata);
    $tpl->add('vyplataData',$vyplataData);              
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('users',$users2);    
    $tpl->add('players',$players);
    $tpl->add('playersArr',$playersArr2);
    $tpl->add('chatData',$chatData);
    $tpl->add('currentUserID',$this->kernel->user->uid);
    $tpl->add('zustatek',$this->kernel->user->data->ucetni_zustatek);       
    $tpl->add('aback',$this->Anchor(array(),false));
    $tpl->add('abackGame',$this->Anchor(array('idg'=>$tournament->id_hry),false)); 
    $tpl->add('agetin',$this->Anchor(array('action'=>'get-into-tournament','idt'=>$idt),false));
    $tpl->add('agetinAlter',$this->Anchor(array('action'=>'get-alter-into-tournament','idt'=>$idt),false));
    $tpl->add('acup',$this->Anchor(array('module'=>'FCups','action'=>'cup-view','idt'=>$tournament->id_cupu),false));
    $tpl->add('asaveturnamentlogin',$this->Anchor(array('action'=>'save-tournament-logindata','idt'=>$idt),false));
    $tpl->add('aendtournament',$this->Anchor(array('action'=>'end-tournament','idt'=>$idt),false));
    $tpl->add('atournamentExtend',$this->Anchor(array('action'=>'extend-tournament','idt'=>$idt),false));
    $tpl->add('anewchat',$this->Anchor(array('action'=>'new-chat','idt'=>$idt),false));
    $tpl->add('auploadScreenshot',$this->Anchor(array('action'=>'upload-screenshot','idt'=>$idt),false));
    $tpl->add('atournamentPlayerKick',$this->Anchor(array('action'=>'player-kick','idt'=>$idt),false));
    $tpl->add('atournamentTeamKick',$this->Anchor(array('action'=>'team-kick','idt'=>$idt),false));
    $tpl->add('asaveturnamentinfos',$this->Anchor(array('action'=>'save-info','idt'=>$idt),false));
    $tpl->add('athis',$this->Anchor(array('action'=>'tournament-view','idt'=>$tournament->idt))); 
    $tpl->add('aaddround',$this->Anchor(array('action'=>'add-round','idt'=>$idt),false));
    $tpl->add('aeditround',$this->Anchor(array('action'=>'edit-round','idt'=>$idt),false));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator); 
    $tpl->add('userX',$this->kernel->user);   
    $tpl->add('playersNicks',$playersNicks);      
    $this->content=$tpl->fetch('frontend/tournaments/view.tpl');  
    $this->execute();  
    }
  private function GetIntoTournament(){
    $idt=(int)getget('idt');
    $username=strip_tags(addslashes(getpost('username','')));
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'"');    
    if($tournament->maximalni_pocet_hracu==count($players)){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}
    if($tournament->dohrano==1){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}    
    if($this->kernel->user->data->ucetni_zustatek<$tournament->cena){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}    
    if($tournament->hraji_tymy==1){
      $id_tymu=(int)getpost('id_tymu');
      $playersByTeam=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" AND id_tymu="'.$id_tymu.'"');      
      $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$id_tymu.'" ORDER BY t.nazev ASC'); 
      $isUserInTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams_users WHERE id_tymu="'.$id_tymu.'" AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
      //var_dump($isLoggedThisTeam);
      //var_dump($isUserInTeam);
      if($isLoggedThisTeam->idt>0 && ($isUserInTeam->idtu>0||$isLoggedThisTeam->id_leadera==$this->kernel->user->uid) && count($playersByTeam)<=ceil($tournament->maximalni_pocet_hracu/$tournament->maximalni_pocet_tymu) ){
        $xid=$this->kernel->models->DBgamesTournamentsPlayers->store(0,array('id_turnaje'=>$idt,'id_tymu'=>$id_tymu,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
        $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena*(-1)),'duvod'=>'Zápisné turnaje #'.$idt.'.'));
        $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$tournament->cena)));
        $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$id_tymu.'"'); 
        $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$isLoggedThisTeam->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_hrac_tymu_se_prihlasil","'.$username.'","'.$teamName.'","'.$gameName.'");');
      }else{
        $this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt,'x'=>'a'),false);
      }      
    }else{
      $xid=$this->kernel->models->DBgamesTournamentsPlayers->store(0,array('id_turnaje'=>$idt,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
      $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena*(-1)),'duvod'=>'Zápisné turnaje #'.$idt.'.'));
      $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$tournament->cena)));
    }
    $this->redirect(array('action'=>'tournament-view','message'=>'login-succes','idt'=>$idt),false); 
    }
  private function GetAlterIntoTournament(){
    $idt=(int)getget('idt');
    $username=strip_tags(addslashes(getpost('username','')));
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'"');
    $playersTwo=$this->kernel->models->DBgamesTournamentsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'"');    
    $isLoggedInTournament=0;
    foreach($players as $p){
      if($p->id_hrace==$this->kernel->user->uid){
        $isLoggedInTournament=1;
        break;
        }
      }
    foreach($playersTwo as $pT){
      if($pT->id_hrace==$this->kernel->user->uid){
        $isLoggedInTournament=1;
        break;
        }
      }
    if($tournament->dohrano==1||$isLoggedInTournament==1||$tournament->hraji_tymy==0){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}        
    $id_tymu=(int)getpost('id_tymu');
    $playersByTeam=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" AND id_tymu="'.$id_tymu.'"');      
    $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$id_tymu.'" ORDER BY t.nazev ASC'); 
    $isUserInTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams_users WHERE id_tymu="'.$id_tymu.'" AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
    if($isLoggedThisTeam->idt>0 && ($isUserInTeam->idtu>0||$isLoggedThisTeam->id_leadera==$this->kernel->user->uid)  ){
      $xid=$this->kernel->models->DBgamesTournamentsAlternatesPlayers->store(0,array('id_turnaje'=>$idt,'id_tymu'=>$id_tymu,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
      $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$id_tymu.'"'); 
      $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$isLoggedThisTeam->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_hrac_tymu_se_prihlasil_jako_nahradnik","'.$username.'","'.$teamName.'","'.$gameName.'");');
      $this->redirect(array('action'=>'tournament-view','message'=>'login-succes','idt'=>$idt),false);   
    }else{
      $this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt,'x'=>'a'),false);
      }       
    }
  private function GetTeamIntoTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'" AND skryty=0');
    $currentUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams WHERE id_hry="'.((int)$tournament->id_hry).'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
    if($currentUserTeam->idt>0&&$tournament->hraji_tymy==1){
      $loggedTeams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" ORDER BY t.nazev ASC'); 
      $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_tournaments_teams as g  WHERE g.id_turnaje="'.$tournament->idt.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$currentUserTeam->idt.'" ORDER BY t.nazev ASC'); 
      if(count($loggedTeams)<$tournament->maximalni_pocet_tymu&&$isLoggedThisTeam->idt<1){
        $this->kernel->models->DBusers->Mquery('INSERT INTO games_tournaments_teams (id_turnaje,id_tymu) VALUES ("'.$tournament->idt.'","'.$currentUserTeam->idt.'")');
        $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$currentUserTeam->idt.'"');
        $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
        $teamsPlayers=$this->kernel->models->DBusers->MqueryGetLines('SELECT id_uzivatele FROM `teams_users` WHERE id_tymu="'.$currentUserTeam->idt.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
        foreach($teamsPlayers as $tPu){
          $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$tPu->id_uzivatele.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_pridani_tymu_do_zapasu","'.$teamName.'","'.$gameName.'");');
          }
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$this->kernel->user->uid.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_pridani_tymu_do_zapasu","'.$teamName.'","'.$gameName.'");');
        //
        }
      }
    $this->redirect(array('action'=>'tournament-view','message'=>'login-succes','idt'=>$idt),false);
    }
  private function ExtendTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-extend-failed','idt'=>$idt),false);}
    $datum_cas_startu=DateTimeToTimestamp(getpost('datum_cas_startu',''));
    if($datum_cas_startu<=time()+3600){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-extend-failed','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournaments->store($idt,array('datum_cas_startu'=>$datum_cas_startu ));
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
      $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'"');
      foreach($players as $pl){
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_zmena_casu_startu","'.$gameName.'","'.strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu).'","'.strftime('%d.%m.%Y %H:%M',$datum_cas_startu).'");');    
        } 
      $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_alternates_players WHERE id_turnaje="'.$idt.'"');
      foreach($playersTwo as $plT){
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_zmena_casu_startu","'.$gameName.'","'.strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu).'","'.strftime('%d.%m.%Y %H:%M',$datum_cas_startu).'");');
        }     
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-extend-succes','idt'=>$idt),false); 
    }
  private function EndTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
    $screens=$this->kernel->models->DBgamesTournamentsScreenshots->getLines('*',' WHERE idt="'.$tournament->idt.'"  ORDER BY datum_cas DESC');
    if(count($screens)<1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
    $winnersTypes=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$tournament->id_vyplaty.'"'); 
    $usersX=array();
    $storeArr=array('idt'=>$idt);
    for($i=1;$i<=$winnersTypes->winners_count;$i++){
      if($_POST['position_'.$i]<1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
      if(isset($usersX[$_POST['position_'.$i]])&&$usersX[$_POST['position_'.$i]]==1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
      $usersX[$_POST['position_'.$i]]=1;
      $storeArr['idu_'.$i]=(int)$_POST['position_'.$i];
      }
    $this->kernel->models->DBgamesTournamentsPrescores->store(0,$storeArr); 
    $postdata=array();    
    foreach($_POST as $k=>$v){$postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);}
    $storeData=array('dohrano'=>'1','datum_cas_konce'=>time(),'poznamka_skore'=>$postdata['poznamka_skore']);
    $this->kernel->models->DBgamesTournaments->store($idt,$storeData);            
    $gameDataMail=trim($this->kernel->models->DBgames->getOne('mail_ukonceni_zapasu','WHERE idg="'.$tournament->id_hry.'"'));
    if($gameDataMail!=''){
      $mailer=new PHPMailer();
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='Dokončen turnaj, nahrány výsledky - přerozdělte prosím výhry.';              
      $mailer->MsgHTML('<h1>Dokončen turnaj, nahrány výsledky - přerozdělte prosím výhry.</h1>
      <a href="'.$this->kernel->config->defualt_system.'backend/game/tournaments-edit/?idt='.$idt.'" target="_blank">Přejít do turnaje -></a>');
      $mailer->AddAddress($gameDataMail);
      $mailer->Send();                     
      }
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'"');
    foreach($players as $pl){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_dokonceni_zapasu","'.$gameName.'");');    
      } 
    $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_alternates_players WHERE id_turnaje="'.$idt.'"');
    foreach($playersTwo as $plT){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_dokonceni_zapasu","'.$gameName.'");');
      }        
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-succes','idt'=>$idt),false); 
    }
  private function UploadScreenshot(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'" AND skryty=0');
    if($tournament->idt<1||$tournament->dohrano==1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $playerExist=$this->kernel->models->DBgamesTournamentsPlayers->getLine('*','WHERE id_turnaje="'.$idt.'" AND id_hrace="'.$this->kernel->user->uid.'"');    
    $playerExistA=$this->kernel->models->DBgamesTournamentsAlternatesPlayers->getLine('*','WHERE id_turnaje="'.$idt.'" AND id_hrace="'.$this->kernel->user->uid.'"'); 
    if($this->kernel->user->uid==$tournament->id_uzivatele||$playerExist->idgtp>0||$playerExistA->idgtap>0){
      $fm=new fileManager();    
      $uploaded=$fm->UploadFile('screenshot',$this->kernel->user->uid.'-'.$idt.'-'.time(),'userfiles/tournaments_score/','imageconfig');
      if($uploaded==false){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-screenshot-failed','idt'=>$idt),false);}
      $uploadedEx=end(explode('.',$uploaded));
      if($uploadedEx=='conf'||$uploadedEx=='cfg'){
        $typ=1;
      }else{
        $typ=0;
      }
      $this->kernel->models->DBgamesTournamentsScreenshots->store(0,array('idt'=>$idt,'idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'cesta'=>$uploaded,'typ'=>$typ)); 
      $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
      $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'"');
      foreach($players as $pl){
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_nahrani_screenshotu","'.$gameName.'");');    
        } 
      $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_alternates_players WHERE id_turnaje="'.$idt.'"');
      foreach($playersTwo as $plT){
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_nahrani_screenshotu","'.$gameName.'");');
        }          
      $this->redirect(array('action'=>'tournament-view','message'=>'tournament-screenshot-succes','idt'=>$idt),false);
      }    
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-screenshot-failed','idt'=>$idt),false); 
    }
  private function InsertChatIntoTournament(){
    $idt=(int)getget('idt');
    $obsah=prepare_get_data_safely(getpost('obsah',''));
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');    
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $player=$this->kernel->models->DBgamesTournamentsPlayers->getLine('*','WHERE id_turnaje="'.$idt.'" AND id_hrace="'.$this->kernel->user->uid.'"');    
    $playerA=$this->kernel->models->DBgamesTournamentsAlternatesPlayers->getLine('*','WHERE id_turnaje="'.$idt.'" AND id_hrace="'.$this->kernel->user->uid.'"');      
    if($tournament->dohrano==1||($player->idgtp<1&&$playerA->idgtap<1)||$obsah==''){$this->redirect(array('action'=>'tournament-view','message'=>'chat-failed','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournamentsChat->store(0,array('id_turnaje'=>$idt,'id_hrace'=>$this->kernel->user->uid,'ts'=>time(),'obsah'=>$obsah));
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'"');
    foreach($players as $pl){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_novy_chat","'.$gameName.'");');    
      } 
    $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_alternates_players WHERE id_turnaje="'.$idt.'"');
    foreach($playersTwo as $plT){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_novy_chat","'.$gameName.'");');
      }     
    $this->redirect(array('action'=>'tournament-view','message'=>'chat-succes','idt'=>$idt),false); 
    }
  private function SaveTournamentLoginData(){
    $idt=(int)getget('idt');    
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $postdata=array();    
    foreach($_POST as $k=>$v){$postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);}    
    $storeData=array('titul_turnaje'=>$postdata['titul_turnaje'],'heslo_turnaje'=>$postdata['heslo_turnaje']);    
    $this->kernel->models->DBgamesTournaments->store($idt,$storeData);
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje="'.$idt.'"');
    foreach($players as $pl){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_zmena_logovacich_dat","'.$gameName.'");');    
      } 
    $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_alternates_players WHERE id_turnaje="'.$idt.'"');
    foreach($playersTwo as $plT){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_zmena_logovacich_dat","'.$gameName.'");');
      }     
    $this->redirect(array('action'=>'tournament-view','idt'=>$idt),false); 
    }
  private function PlayerKick(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1||$tournament->dohrano==1||$tournament->id_cupu>0){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $idu=(int)getpost('idu');
    $exist=$this->kernel->models->DBgamesTournamentsPlayers->getLine('*','WHERE id_turnaje="'.$tournament->idt.'" AND id_hrace="'.$idu.'"');  
    if($exist->idgtp<1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-kickuser-failed','idt'=>$idt),false);}   
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$idu.'"');
    if($user->uid<1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-kickuser-failed','idt'=>$idt),false);}
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$idu,'datum_cas'=>time(),'coins'=>($tournament->cena),'duvod'=>'Vyřazení z turnaje #'.$idt.'.'));
    $this->kernel->models->DBusers->store($user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$tournament->cena)));
    $this->kernel->models->DBgamesTournamentsPlayers->deleteId($exist->idgtp); 
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$idu.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_vyrazeni_hrace_ze_zapasu","'.$exist->nick.'","'.$gameName.'");');
    if($exist->id_tymu>0){
      $teamData=$this->kernel->models->DBusers->MqueryGetLine('SELECT nazev,id_leadera FROM teams WHERE idt="'.$exist->id_tymu.'"');
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$teamData->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","notifikace_typ_zapas_vyrazeni_hrace_ze_zapasu_pro_tym","'.$exist->nick.'","'.$gameName.'","'.$teamData->nazev.'");');        
      }             
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-kickuser-succes','idt'=>$idt),false);
    }
  private function TeamKick(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1||$tournament->dohrano==1||$tournament->id_cupu>0){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $idtm=(int)getpost('idu');
    $existt=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM games_tournaments_teams WHERE id_tymu="'.$idtm.'" AND id_turnaje="'.$tournament->idt.'"');
    $teamData=$this->kernel->models->DBusers->MqueryGetLine('SELECT nazev,id_leadera FROM teams WHERE idt="'.$idtm.'"');
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"'); 
    if($existt->idgtt<1){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-kickteam-failed','idt'=>$idt),false);}   
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_tymu="'.$idtm.'" AND id_turnaje="'.$tournament->idt.'"'); 
    if(count($players)>0){
      foreach($players as $pl){
        $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$pl->id_hrace.'"');
        if($user->uid>0){
          $this->kernel->models->DBusersCoins->store(0,array('idu'=>$user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena),'duvod'=>'Vyřazení z turnaje #'.$idt.'.'));
          $this->kernel->models->DBusers->store($user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$tournament->cena)));
          $this->kernel->models->DBgamesTournamentsPlayers->deleteId($pl->idgtp);   
          $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$user->uid.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","nofitikace_typ_zapas_vyrazeni_tymu_ze_zapasu_pro_cleny","'.$gameName.'","'.$teamData->nazev.'");');              
          }
        }        
      }
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$teamData->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'tournament-view','idt'=>$idt),false).'","nofitikace_typ_zapas_vyrazeni_tymu_ze_zapasu_pro_leadera","'.$gameName.'","'.$teamData->nazev.'");'); 
    $this->kernel->models->DBusers->Mquery('DELETE FROM games_tournaments_teams WHERE id_tymu="'.$idtm.'" AND id_turnaje="'.$tournament->idt.'"');        
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-kickteam-succes','idt'=>$idt),false);
    }
  private function AddRound(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $storeDataA=array('idt'=>$idt);
    $storeDataB=array();
    foreach($_POST as $kp=>$vp){
      $kp=prepare_get_data_safely($kp);
      $vp=prepare_get_data_safely($vp);
      if($kp=='players'){
        foreach($_POST['players'] as $pp){
          $ppp=(int)$pp;
          if($ppp>0){
            $storeDataB[$ppp]=$ppp;
            }
          }        
        }  
      if($kp=='nazev'||$kp=='mapa'||$kp=='titulek'||$kp=='heslo'||$kp=='poznamka'){
        $storeDataA[$kp]=$vp;
        } 
      if($kp=='datum_cas'){
        $storeDataA[$kp]=(int)DateTimeToTimestamp($vp);      
        }           
      }
    if(count($storeDataB)==0||$storeDataA['nazev']==''||$storeDataA['titulek']==''||$storeDataA['heslo']==''||$storeDataA['datum_cas']<100000){
      $this->redirect(array('action'=>'tournament-view','message'=>'tournament-round-added-failed','idt'=>$idt),false);
      exit();      
      }
    $idr=$this->kernel->models->DBgamesTournamentsRounds->store(0,$storeDataA);
    if($idr>0){
      foreach($storeDataB as $idht){
        $this->kernel->models->DBgamesTournamentsRoundsScore->store(0,array('idgtr'=>$idr,'id_hrace_tymu'=>$idht,'skore'=>0));
        }
      }
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-round-added-success','idt'=>$idt),false);
    }
  private function EditRound(){
    $idt=(int)getget('idt');
    $idgtr=(int)getpost('idgtr');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1||$idgtr<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid==$tournament->id_uzivatele||$this->kernel->user->prava>0){}else{$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $storeDataA=array('idt'=>$idt);
    $storeDataB=array();
    foreach($_POST as $kp=>$vp){
      $kp=prepare_get_data_safely($kp);
      $vp=prepare_get_data_safely($vp);  
      if($kp=='nazev'||$kp=='mapa'||$kp=='titulek'||$kp=='heslo'||$kp=='poznamka'){
        $storeDataA[$kp]=$vp;
        } 
      if($kp=='datum_cas'){
        $storeDataA[$kp]=(int)DateTimeToTimestamp($vp);      
        }  
      if(strpos($kp,'score_')===false){}else{
        $kxx=explode('_',$kp);
        $kxx[1]=(int)$kxx[1];
        $vp=str_replace(',','.',$vp);
        $this->kernel->models->DBgamesTournamentsRoundsScore->store($kxx[1],array('skore'=>$vp));
        }         
      }
    if($storeDataA['nazev']==''||$storeDataA['titulek']==''||$storeDataA['heslo']==''||$storeDataA['datum_cas']<100000){
      $this->redirect(array('action'=>'tournament-view','message'=>'tournament-round-edit-failed','idt'=>$idt),false);
      exit();      
      }
    $this->kernel->models->DBgamesTournamentsRounds->store($idgtr,$storeDataA);   
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-round-edit-success','idt'=>$idt),false);
    }
  private function SaveTournamentInfo(){
    $idt=(int)getget('idt');    
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$tournament->id_hry.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','idt'=>$idt),false);}   
    $poznamka_zakladatele=strip_tags(str_replace(array('"',"'"),array('',''),getpost('poznamka_zakladatele','')));
    $pravidla_turnaje_mala=strip_tags(str_replace(array('"',"'"),array('',''),getpost('pravidla_turnaje_mala','')));
    $pravidla_turnaje_velka=strip_tags(str_replace(array('"',"'"),array('',''),getpost('pravidla_turnaje_velka','')));     
    $maximalni_pocet_tymu=(int)getpost('maximalni_pocet_tymu','');        
    $maximalni_pocet_hracu=(int)getpost('maximalni_pocet_hracu','');          
    if($maximalni_pocet_hracu>$moduleGame->maximalni_pocet_hracu&&$tournament->hraji_tymy==0){$maximalni_pocet_hracu=$moduleGame->maximalni_pocet_hracu;}
    if($maximalni_pocet_tymu>$moduleGame->maximalni_pocet_tymu&&$tournament->hraji_tymy==1){$maximalni_pocet_tymu=$moduleGame->maximalni_pocet_tymu;}    
    if($maximalni_pocet_hracu<$tournament->minimalni_pocet_hracu){$maximalni_pocet_hracu=$tournament->minimalni_pocet_hracu;}    
    if($maximalni_pocet_tymu<$tournament->minimalni_pocet_tymu){$maximalni_pocet_tymu=$tournament->minimalni_pocet_tymu;}                      
    $storeData=array('poznamka_zakladatele'=>$poznamka_zakladatele,'pravidla_turnaje_mala'=>$pravidla_turnaje_mala,'pravidla_turnaje_velka'=>$pravidla_turnaje_velka);    
    if($tournament->hraji_tymy==0){
      $storeData['maximalni_pocet_hracu']=$maximalni_pocet_hracu; 
    }else{
      $storeData['maximalni_pocet_tymu']=$maximalni_pocet_tymu;
    }
    $this->kernel->models->DBgamesTournaments->store($idt,$storeData);
    //
    $Data=new stdClass();
    $Data->params=array(); 
    if(isset($_POST['params'])&&count($_POST['params'])>0){      
      foreach($_POST['params'] as $xpp){
        $xpp=(int)$xpp;        
        $Data->params[$xpp]=$xpp;          
        }
      }    
    $Data->paramsText=array();
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$tournament->id_hry.'" and typ_v_turnaji_cupu=3 ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      if(isset($_POST['paramText_'.$pv->idp])){
        $Data->paramsText[$pv->idp]=prepare_get_data_safely($_POST['paramText_'.$pv->idp]);        
        }
      }  
    if(count($Data->params)>0||count($Data->paramsText)>0){
      $this->kernel->models->DBgamesTournamentsParameters->deleteWhere('WHERE id_turnaje='.$idt);  
      }
    if(count($Data->params)>0){
      foreach($Data->params as $xpam){
        $this->kernel->models->DBgamesTournamentsParameters->store(0,array('id_turnaje'=>$idt,'id_hodnoty_parametru'=>$xpam));    
        }
      }
    if(count($Data->paramsText)>0){
      foreach($Data->paramsText as $xpamk=>$xpamv){
        $this->kernel->models->DBgamesTournamentsParameters->store(0,array('id_turnaje'=>$idt,'id_parametru'=>$xpamk,'textova_hodnota'=>$xpamv));    
        }
      }              
    $this->redirect(array('action'=>'tournament-view','idt'=>$idt,'message'=>'tournament-saved'),false); 
    }
  }