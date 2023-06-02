<?php
class FCups extends Module{
  private $xmodule,$xmoduleData;
  public function Main(){
    $this->parent_module='Frontend';
    $this->xmodule='tournaments';    
    $this->xmoduleData=$this->kernel->models->DBgamesModules->getLine('*',' WHERE interni_nazev="'.$this->xmodule.'"');            
    $action=getget('action','main');     
    if(isset($_POST['action'])&&$_POST['action']=='setFilter'){$action='set-filter';}
    if($action=='main'){$this->MainPage();} 
    elseif($action=='new-cup-post'){$this->NewCupPost();}
    elseif($action=='new-cup'){$this->NewCup();}                                     
    elseif($action=='new-cup-post-2'){$this->NewCupPost2();}
    elseif($action=='cup-view'){$this->CupView();}
    elseif($action=='get-into-cup'){$this->GetIntoCup();}
    elseif($action=='save-cup-settings'){$this->SaveCupSettings();}
    elseif($action=='generate-new-round'){$this->GenerateNewRound();}
    elseif($action=='get-team-into-cup'){$this->GetTeamIntoCup();}    
    elseif($action=='extend-cup'){$this->ExtendCup();}    
    elseif($action=='set-filter'){$this->SetFiter();}
    elseif($action=='player-kick'){$this->PlayerKick();}
    elseif($action=='team-kick'){$this->TeamKick();}
    elseif($action=='get-alter-into-cup'){$this->GetAlterIntoCup();}     
    else{$this->Redirect();}            
    }    
  private function MainPage(){
    $this->Redirect(array('module'=>'FTournaments'),false);    
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
        $_SESSION['filter_game_'.$idg.'_id_'.$this->xmodule][$xpp]=$xpp;              
        }
      }
    $this->redirect(array('idg'=>$idg),false);   
    }        
  private function NewCupPost(){
    $idg=(int)getpost('idg','0');
    $_SESSION['new_cup_id_'.$this->xmodule]=$idg;
    unset($_SESSION['new_cup_'.$this->xmodule]);
    $this->redirect(array('action'=>'new-cup'),false);    
    }
  private function NewCup(){     
    $this->seo_title=$this->kernel->systemTranslator['cups_cups'];     
    $this->seo_keywords=$this->kernel->systemTranslator['cups_cups'];         
    $this->seo_description=$this->kernel->systemTranslator['cups_cups'];  
    $idg=(int)$_SESSION['new_cup_id_'.$this->xmodule];
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
    if(!isset($_SESSION['new_cup_'.$this->xmodule])){
      $_SESSION['new_cup_'.$this->xmodule]=new stdClass();      
      $_SESSION['new_cup_'.$this->xmodule]->idgt=0;      
      $_SESSION['new_cup_'.$this->xmodule]->cena='0,00';
      $_SESSION['new_cup_'.$this->xmodule]->datum_cas_startu=strftime('%d.%m.%Y %H:%M',time()+86400);      
      $_SESSION['new_cup_'.$this->xmodule]->titulek_cupu='';      
      $_SESSION['new_cup_'.$this->xmodule]->hraji_tymy='1';      
      $_SESSION['new_cup_'.$this->xmodule]->poznamka_zakladatele='';      
      $_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_mala=$game->pravidla_turnaje;
      $_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_velka=$game->podrobna_pravidla_turnaje;    
      $_SESSION['new_cup_'.$this->xmodule]->params=array();
      $_SESSION['new_cup_'.$this->xmodule]->paramsText=array();                      
      $this->redirect(array('action'=>'new-cup'),false);    
      }
    if($_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_mala==''){
      $_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_mala=$game->pravidla_turnaje;
      }
    if($_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_velka==''){
      $_SESSION['new_cup_'.$this->xmodule]->pravidla_turnaje_velka=$game->podrobna_pravidla_turnaje;
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
    $tpl->add('ngData',$_SESSION['new_cup_'.$this->xmodule]);                   
    $tpl->add('anext',$this->Anchor(array('action'=>'new-cup-post-2'),false));
    $tpl->add('aback',$this->Anchor(array(),false));      
    $tpl->add('message',getget('message',''));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);      
    $this->content=$tpl->fetch('frontend/cups/newCup.tpl');  
    $this->execute();  
    }  
  private function NewCupPost2(){
    $idg=(int)$_SESSION['new_cup_id_'.$this->xmodule];
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$idg.'"');
    $storeData=array('id_modulu'=>$this->xmoduleData->idm,'id_hry'=>$idg,'id_uzivatele'=>$this->kernel->user->uid,'datum_vytvoreni'=>time());
    $Data=new stdClass();
    foreach($_POST as $kp=>$vp){
      $kp=prepare_get_data_safely($kp);
      $vp=prepare_get_data_safely($vp);
      if($kp!='params'){
        $_SESSION['new_cup_'.$this->xmodule]->$kp=$vp;
        $Data->$kp=$vp;
        }              
      }
    $Data->params=array(); 
    if(isset($_POST['params'])&&count($_POST['params'])>0){
      $_SESSION['new_cup_'.$this->xmodule]->params=array();    
      foreach($_POST['params'] as $xpp){
        $xpp=(int)$xpp;
        $_SESSION['new_cup_'.$this->xmodule]->params[$xpp]=$xpp;  
        $Data->params[$xpp]=$xpp;          
        }
      }
    //
    $Data->paramsText=array();
    $params=$this->kernel->models->DBgamesParameters->getLines('*',' WHERE idg="'.$idg.'" and typ_v_turnaji_cupu=3 ORDER BY poradi,nazev');     
    foreach($params as $pk=>$pv){
      if(isset($_POST['paramText_'.$pv->idp])){
        $Data->paramsText[$pv->idp]=prepare_get_data_safely($_POST['paramText_'.$pv->idp]);
        $_SESSION['new_cup_'.$this->xmodule]->paramsText[$pv->idp]=prepare_get_data_safely($_POST['paramText_'.$pv->idp]);
        }
      }  
    //   
    $typeOfGame=$this->kernel->models->DBgamesTypes->getLine('*',' WHERE idg="'.$idg.'" AND idgt="'.$Data->idgt.'" and aktivni=1 ORDER BY nazev');          
    if($Data->having_licence<1||$Data->idgt<1||$idg<1||$this->xmoduleData->idm<1||$Data->cena==''||$Data->datum_cas_startu==''||$Data->pravidla_turnaje_mala==''||$Data->pravidla_turnaje_velka==''||$this->kernel->user->data->overen_email==0||$typeOfGame->idgt<1){$this->redirect(array('action'=>'new-cup','message'=>'all-needed'),false);}    
    $storeData['id_typu_hry']=(int)$Data->idgt;    
    $storeData['cena']=str_replace(',','.',trim((round((float)str_replace(',','.',$Data->cena),2))));     ;
    $storeData['datum_cas_startu']=DateTimeToTimestamp($Data->datum_cas_startu);  
    $storeData['titulek_cupu']=str_replace(array('"',"'"),array('',''),strip_tags($Data->titulek_cupu));   
    $storeData['hraji_tymy']=(int)$Data->hraji_tymy;        
    $storeData['minimalni_pocet_hracutymu']=(int)$typeOfGame->cup_minimalni_pocet_hracutymu;
    $storeData['maximalni_pocet_hracutymu']=(int)$typeOfGame->cup_maximalni_pocet_hracutymu;
    $storeData['idealni_pocet_hracutymu_na_turnaj']=(int)$typeOfGame->cup_idealni_pocet_hracutymu_na_turnaj;
    $storeData['pocet_postupujicich_hracutymu']=(int)$typeOfGame->cup_pocet_postupujicich_hracutymu;
    $storeData['idealni_pocet_hracu_v_tymu']=(int)$typeOfGame->cup_idealni_pocet_hracu_v_tymu;                
    $storeData['id_vyplaty']=$typeOfGame->cup_id_vyplaty;                 
    $storeData['poznamka_zakladatele']=str_replace(array('"',"'"),array('',''),strip_tags($Data->poznamka_zakladatele));    
    $storeData['pravidla_mala']=str_replace(array('"',"'"),array('',''),strip_tags($Data->pravidla_turnaje_mala));
    $storeData['pravidla_velka']=str_replace(array('"',"'"),array('',''),strip_tags($Data->pravidla_turnaje_velka));                                                                        
    if($storeData['datum_cas_startu']<=time()+3600){$this->redirect(array('action'=>'new-cup','message'=>'date-error'),false);}             
    if($this->kernel->user->data->ucetni_zustatek<$moduleGame->poplatek_za_zalozeni_turnaje){$this->redirect(array('action'=>'new-cup','message'=>'coins'),false);}    
    $storeData['hash']=$this->kernel->models->DBgamesCups->generateHash(rand(0,99999),$this->xmoduleData->idm,$storeData['id_typu_hry'],0,$storeData['id_typu_hry'],1,$this->kernel->user->uid,$storeData['id_vyplaty'],$storeData['datum_vytvoreni'],$storeData['datum_cas_startu'],$storeData['datum_cas_startu']+10,3);   
    $xid=$this->kernel->models->DBgamesCups->store(0,$storeData);
    $this->kernel->models->DBrewrites->AddEditRewrite('cups/view-cup/'.$storeData['hash'].'-'.$xid.'/','FCups3','idt',$xid);    
    if(count($Data->params)>0){
      foreach($Data->params as $xpam){
        $this->kernel->models->DBgamesCupsParameters->store(0,array('id_cupu'=>$xid,'id_hodnoty_parametru'=>$xpam));    
        }
      }
    if(count($Data->paramsText)>0){
      foreach($Data->paramsText as $xpamk=>$xpamv){
        $this->kernel->models->DBgamesCupsParameters->store(0,array('id_cupu'=>$xid,'id_parametru'=>$xpamk,'textova_hodnota'=>$xpamv));    
        }
      }
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($moduleGame->poplatek_za_zalozeni_turnaje*(-1)),'duvod'=>'Poplatek za založení cupu #'.$xid.'.'));
    $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$moduleGame->poplatek_za_zalozeni_turnaje)));
    unset($_SESSION['new_cup_id_'.$this->xmodule]);
    unset($_SESSION['new_cup_'.$this->xmodule]);        
    $this->redirect(array('action'=>'cup-view','idt'=>$xid),false);      
    }
  private function CupView(){
    //if($this->kernel->user->uid<1){$this->redirect();} 
    $this->seo_title=$this->kernel->systemTranslator['cups_cups'];     
    $this->seo_keywords=$this->kernel->systemTranslator['cups_cups'];      
    $this->seo_description=$this->kernel->systemTranslator['cups_cups']; 
    $idt=(int)getget('idt','');
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');  
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'" AND skryty=0');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$tournament->id_hry.'"');       
    $type=$this->kernel->models->DBgamesTypes->getLine('*','WHERE idgt="'.$tournament->id_typu_hry.'"');       
    $vyplata=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$tournament->id_vyplaty.'"');    
    $currentPlayer=new stdClass();
    $alternatesArr=array('0');
    $alternatesArr2=array();
    $alternatesNicks=array();
    $playersNicks=array();
    $alternates=$this->kernel->models->DBgamesCupsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idc.'" ORDER BY id_tymu DESC');
    foreach($alternates as $al){
      $alternatesArr[]=$al->id_hrace;$alternatesArr2[]=$al;
      $playersNicks[$al->id_hrace]=$al->nick;$alternatesNicks[$al->id_hrace]=$al->nick;
      if($al->id_hrace==$this->kernel->user->uid){
        $currentPlayer=$al;
        }
      }
    $players=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'" ORDER BY id_tymu DESC');
    $playersArr=array('0',$tournament->id_uzivatele);    
    $pocetHracuTymu=0; 
    foreach($players as $pl){
      $pocetHracuTymu++;
      $playersArr[]=$pl->id_hrace;$playersArr2[]=$pl->id_hrace;$playersNicks[$pl->id_hrace]=$pl->nick;
      if($pl->id_hrace==$this->kernel->user->uid){
        $currentPlayer=$pl;
      }
    }          
    $users=$this->kernel->models->DBusers->getLines('uid,osloveni,user_picture,fb_picture','WHERE uid in ('.implode(',',$playersArr).')');
    $users2=array();
    foreach($users as $us){$users2[$us->uid]=$us;}        
    $currGameModules=$this->kernel->models->DBgamesPlatformsGames->getLines('*',' WHERE idg="'.$tournament->id_hry.'"');
    $modulePlatforms=$this->kernel->models->DBgamesPlatforms->getLines('*',' where aktivni=1 order by RAND() ');
    foreach($modulePlatforms as $xGk=>$xGv){$modulePlatforms[$xGk]->alink=$this->Anchor(array('idgp'=>$xGv->idgp));}  
    if($tournament->hraji_tymy==1){
      $pocetHracuTymu=0;
      $currentUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams WHERE id_hry="'.$tournament->id_hry.'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
      $UserHaveLoggedOwnTeam=0;
      if($currentUserTeam->idt>0){
        $loggedUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM games_cups_teams WHERE id_tymu="'.$currentUserTeam->idt.'" AND id_cupu="'.$tournament->idc.'"');
        if($loggedUserTeam->idgct>0){
          $UserHaveLoggedOwnTeam=1;
          }
        }
      $loggedTeams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" ORDER BY t.nazev ASC');
      $loggedTeams2=array();
      $currentPlayerTeams=array();
      $loggedTeamsIds=array('0');
      foreach($loggedTeams as $xteam){
        $pocetHracuTymu++;
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
    $spider=$this->generateSpider($pocetHracuTymu,$tournament->idealni_pocet_hracutymu_na_turnaj,$tournament->pocet_postupujicich_hracutymu,$vyplata->winners_count);
    $tournaments=array();
    $tsData=$this->kernel->models->DBgamesTournaments->MqueryGetLines('SELECT * FROM games_tournaments WHERE id_cupu="'.$idt.'"');    
    $tsIds=array('0');   
    $tournamentsHraciTymy=array(); 
    $roundToGenerate=0;
    foreach($tsData as $tsD){
      $tsIds[]=$tsD->idt;
      $tsD->hraji_v_turnaji=0;
      $prihlasenHrac=$this->kernel->models->DBgamesTournaments->MqueryGetOne('SELECT idgtp FROM games_tournaments_players WHERE id_turnaje="'.$tsD->idt.'" AND id_hrace="'.$this->kernel->user->uid.'"');
      if($prihlasenHrac>0){$tsD->hraji_v_turnaji=1;}
      $tsD->aLink=$this->Anchor(array('module'=>'FTournaments','action'=>'tournament-view','idt'=>$tsD->idt));                  
      $tournaments[$tsD->id_kola_cupu][$tsD->id_zapasu_cupu]=$tsD;
      if($roundToGenerate<=$tsD->id_kola_cupu){
        $roundToGenerate=$tsD->id_kola_cupu+1;
        }
      }
    if($tournament->hraji_tymy==1){
      $tournamentsHraciTymyData=$this->kernel->models->DBgamesTournaments->MqueryGetLines('SELECT * FROM games_tournaments_teams WHERE id_turnaje IN ('.implode(',',$tsIds).')');
      foreach($tournamentsHraciTymyData as $tHTDval){$tournamentsHraciTymy[$tHTDval->id_turnaje][$tHTDval->id_tymu]=$tHTDval->postupuje;}
    }else{
      $tournamentsHraciTymyData=$this->kernel->models->DBgamesTournaments->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje IN ('.implode(',',$tsIds).')');
      foreach($tournamentsHraciTymyData as $tHTDval){$tournamentsHraciTymy[$tHTDval->id_turnaje][$tHTDval->id_hrace]=$tHTDval->postupuje;}
    }
    $lastRoundDone=1;
    if($roundToGenerate>1){
      foreach($tournaments[$roundToGenerate-1] as $tsX){
        if($tsX->dohrano==0||$tsX->prerozdelene_vyhry==0){
          $lastRoundDone=0;
          break;
          }
        }
      }
    $lastSpiderRound=0;
    foreach($spider as $ddS){
      if($ddS->kolo>$lastSpiderRound){$lastSpiderRound=$ddS->kolo;}
      }
    if(isset($tournaments[$lastSpiderRound][1])&&$tournaments[$lastSpiderRound][1]->idt>0){
      $vyplataData=$this->kernel->models->DBgamesTournamentsWinners->getLine('*','WHERE id_turnaje="'.$tournaments[$lastSpiderRound][1]->idt.'"');
    }else{
      $vyplataData=new stdClass();
    }    
    //print_r($spider);
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
    $paramsUsedq=$this->kernel->models->DBgamesCupsParameters->getLines('*',' WHERE id_cupu="'.$tournament->idc.'"'); 
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
    $tpl->add('tournamentsHraciTymy',$tournamentsHraciTymy); 
    $tpl->add('vyplataData',$vyplataData);
    $tpl->add('spider',$spider);
    $tpl->add('tournaments',$tournaments);
    $tpl->add('roundToGenerate',$roundToGenerate);
    $tpl->add('lastRoundDone',$lastRoundDone);
    $tpl->add('lastSpiderRound',$lastSpiderRound);
    $tpl->add('currentUserTeam',$currentUserTeam);
    $tpl->add('UserHaveLoggedOwnTeam',$UserHaveLoggedOwnTeam);
    $tpl->add('loggedTeams',$loggedTeams);
    $tpl->add('loggedTeams2',$loggedTeams2);
    $tpl->add('currentPlayerTeams',$currentPlayerTeams);
    $tpl->add('currentPlayer',$currentPlayer);    
    $tpl->add('agetinTeam',$this->Anchor(array('action'=>'get-team-into-cup','idt'=>$idt),false));
    $tpl->add('tournament',$tournament);
    $tpl->add('modulePlatforms',$modulePlatforms);
    $tpl->add('currGameModules',$currGameModules);  
    $tpl->add('game',$game);              
    $tpl->add('type',$type);         
    $tpl->add('vyplata',$vyplata);                  
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('users',$users2);    
    $tpl->add('players',$players);
    $tpl->add('playersArr',$playersArr2);    
    $tpl->add('currentUserID',$this->kernel->user->uid);
    $tpl->add('zustatek',$this->kernel->user->data->ucetni_zustatek);       
    $tpl->add('aback',$this->Anchor(array(),false));
    $tpl->add('abackGame',$this->Anchor(array('idg'=>$tournament->id_hry),false)); 
    $tpl->add('agetinAlter',$this->Anchor(array('action'=>'get-alter-into-cup','idt'=>$idt),false));
    $tpl->add('agetin',$this->Anchor(array('action'=>'get-into-cup','idt'=>$idt),false));      
    $tpl->add('asaveCupSettings',$this->Anchor(array('action'=>'save-cup-settings','idt'=>$idt),false)); 
    $tpl->add('atournamentRoundGenerate',$this->Anchor(array('action'=>'generate-new-round','idt'=>$idt),false));
    $tpl->add('atournamentExtend',$this->Anchor(array('action'=>'extend-cup','idt'=>$idt),false));
    $tpl->add('atournamentPlayerKick',$this->Anchor(array('action'=>'player-kick','idt'=>$idt),false));
    $tpl->add('atournamentTeamKick',$this->Anchor(array('action'=>'team-kick','idt'=>$idt),false));    
    $tpl->add('athis',$this->Anchor(array('action'=>'cup-view','idt'=>$tournament->idc))); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator); 
    $tpl->add('userX',$this->kernel->user);   
    $tpl->add('playersNicks',$playersNicks);      
    $this->content=$tpl->fetch('frontend/cups/view.tpl');  
    $this->execute();  
    }
  private function GetIntoCup(){
    $idt=(int)getget('idt');
    $username=strip_tags(addslashes(getpost('username','')));
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    $players=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'"');    
    if($tournament->maximalni_pocet_hracutymu==count($players)){$this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt),false);}
    if($tournament->dohrano==1){$this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt),false);}    
    if($this->kernel->user->data->ucetni_zustatek<$tournament->cena){$this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt),false);}    
    if($tournament->hraji_tymy==1){
      $id_tymu=(int)getpost('id_tymu');
      $playersByTeam=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'" AND id_tymu="'.$id_tymu.'"');      
      $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$id_tymu.'" ORDER BY t.nazev ASC'); 
      $isUserInTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams_users WHERE id_tymu="'.$id_tymu.'" AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
      //var_dump($isLoggedThisTeam);
      //var_dump($isUserInTeam);
      if($isLoggedThisTeam->idt>0 && ($isUserInTeam->idtu>0||$isLoggedThisTeam->id_leadera==$this->kernel->user->uid) && count($playersByTeam)<=($tournament->idealni_pocet_hracu_v_tymu+2) ){
        $xid=$this->kernel->models->DBgamesCupsPlayers->store(0,array('id_cupu'=>$idt,'id_tymu'=>$id_tymu,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
        $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena*(-1)),'duvod'=>'Zápisné turnaje #'.$idt.'.'));
        $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$tournament->cena)));        
        $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$id_tymu.'"'); 
        $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$isLoggedThisTeam->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_hrac_tymu_se_prihlasil","'.$username.'","'.$teamName.'","'.$gameName.'");');
      }else{
        $this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt,'x'=>'a'),false);
      }      
    }else{
      $xid=$this->kernel->models->DBgamesCupsPlayers->store(0,array('id_cupu'=>$idt,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
      $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena*(-1)),'duvod'=>'Zápisné turnaje #'.$idt.'.'));
      $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$tournament->cena)));
    }
    $this->redirect(array('action'=>'cup-view','message'=>'login-succes','idt'=>$idt),false); 
    }
  private function GetTeamIntoCup(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'" AND skryty=0');
    $currentUserTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams WHERE id_hry="'.((int)$tournament->id_hry).'" AND id_leadera="'.$this->kernel->user->uid.'"'); 
    if($currentUserTeam->idt>0&&$tournament->hraji_tymy==1){
      $loggedTeams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" ORDER BY t.nazev ASC'); 
      $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$currentUserTeam->idt.'" ORDER BY t.nazev ASC'); 
      if(count($loggedTeams)<$tournament->maximalni_pocet_hracutymu&&$isLoggedThisTeam->idt<1){
        $this->kernel->models->DBusers->Mquery('INSERT INTO games_cups_teams (id_cupu,id_tymu) VALUES ("'.$tournament->idc.'","'.$currentUserTeam->idt.'")');
        $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$currentUserTeam->idt.'"');
        $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
        $teamsPlayers=$this->kernel->models->DBusers->MqueryGetLines('SELECT id_uzivatele FROM `teams_users` WHERE id_tymu="'.$currentUserTeam->idt.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
        foreach($teamsPlayers as $tPu){
          $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$tPu->id_uzivatele.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_pridani_tymu_do_turnaje","'.$teamName.'","'.$gameName.'");');
          }
        $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$this->kernel->user->uid.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_pridani_tymu_do_turnaje","'.$teamName.'","'.$gameName.'");');
        //die('c2');
        }
      //die('c1');
      }
    $this->redirect(array('action'=>'cup-view','message'=>'login-succes','idt'=>$idt),false);
    }
  private function GetAlterIntoCup(){
    $idt=(int)getget('idt');
    $username=strip_tags(addslashes(getpost('username','')));
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    $players=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'"');
    $playersTwo=$this->kernel->models->DBgamesCupsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idc.'"');    
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
    if($tournament->dohrano==1||$isLoggedInTournament==1||$tournament->hraji_tymy==0){$this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt),false);}        
    $id_tymu=(int)getpost('id_tymu');
    $playersByTeam=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'" AND id_tymu="'.$id_tymu.'"');      
    $isLoggedThisTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" AND t.idt="'.$id_tymu.'" ORDER BY t.nazev ASC'); 
    $isUserInTeam=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM teams_users WHERE id_tymu="'.$id_tymu.'" AND id_uzivatele="'.$this->kernel->user->uid.'" AND potvrdil_leader=1 AND potvvrdil_uzivatel=1');
    if($isLoggedThisTeam->idt>0 && ($isUserInTeam->idtu>0||$isLoggedThisTeam->id_leadera==$this->kernel->user->uid)  ){
      $xid=$this->kernel->models->DBgamesCupsAlternatesPlayers->store(0,array('id_turnaje'=>$idt,'id_tymu'=>$id_tymu,'id_hrace'=>$this->kernel->user->uid,'nick'=>$username));
      $teamName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM teams WHERE idt="'.$id_tymu.'"'); 
      $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$isLoggedThisTeam->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_hrac_tymu_se_prihlasil_jako_nahradnik","'.$username.'","'.$teamName.'","'.$gameName.'");');
      $this->redirect(array('action'=>'cup-view','message'=>'login-succes','idt'=>$idt),false);   
    }else{
      $this->redirect(array('action'=>'cup-view','message'=>'login-failed','idt'=>$idt,'x'=>'a'),false);
      }       
    }
  private function ExtendCup(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'cup-view','message'=>'cup-extend-failed','idt'=>$idt),false);}
    $datum_cas_startu=DateTimeToTimestamp(getpost('datum_cas_startu',''));
    if($datum_cas_startu<=time()+3600){$this->redirect(array('action'=>'cup-view','message'=>'cup-extend-failed','idt'=>$idt),false);}
    $this->kernel->models->DBgamesCups->store($idt,array('datum_cas_startu'=>$datum_cas_startu ));
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_cups_players WHERE id_cupu="'.$tournament->idc.'"');
    foreach($players as $pl){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$pl->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_zmena_casu_startu_turnaje","'.$gameName.'","'.strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu).'","'.strftime('%d.%m.%Y %H:%M',$datum_cas_startu).'");');    
      } 
    $playersTwo=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_cups_alternates_players WHERE id_turnaje="'.$tournament->idc.'"');
    foreach($playersTwo as $plT){
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$plT->id_hrace.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_zmena_casu_startu_turnaje","'.$gameName.'","'.strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu).'","'.strftime('%d.%m.%Y %H:%M',$datum_cas_startu).'");');    
      } 
    $this->redirect(array('action'=>'cup-view','message'=>'cup-extend-succes','idt'=>$idt),false); 
    } 
  private function SaveCupSettings(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$tournament->id_hry.'"');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'cup-view','message'=>'cup-not-saved','idt'=>$idt),false);}
    $titulek_cupu=strip_tags(str_replace(array('"',"'"),array('',''),getpost('titulek_cupu','')));
    $poznamka_zakladatele=strip_tags(str_replace(array('"',"'"),array('',''),getpost('poznamka_zakladatele','')));
    $pravidla_mala=strip_tags(str_replace(array('"',"'"),array('',''),getpost('pravidla_mala','')));
    $pravidla_velka=strip_tags(str_replace(array('"',"'"),array('',''),getpost('pravidla_velka','')));    
    $idealni_pocet_hracutymu_na_turnaj=(int)getpost('idealni_pocet_hracutymu_na_turnaj','');
    $pocet_postupujicich_hracutymu=(int)getpost('pocet_postupujicich_hracutymu','');
    $maximalni_pocet_hracutymu=(int)getpost('maximalni_pocet_hracutymu','');    
    if($idealni_pocet_hracutymu_na_turnaj<2){$idealni_pocet_hracutymu_na_turnaj=2;}
    if($pocet_postupujicich_hracutymu>=$idealni_pocet_hracutymu_na_turnaj){$pocet_postupujicich_hracutymu=($idealni_pocet_hracutymu_na_turnaj-1);}
    if($maximalni_pocet_hracutymu>$moduleGame->maximalni_pocet_hracu&&$tournament->hraji_tymy==0){$maximalni_pocet_hracutymu=$moduleGame->maximalni_pocet_hracu;}
    if($maximalni_pocet_hracutymu>$moduleGame->maximalni_pocet_tymu&&$tournament->hraji_tymy==1){$maximalni_pocet_hracutymu=$moduleGame->maximalni_pocet_tymu;}
    if($maximalni_pocet_hracutymu<$tournament->minimalni_pocet_hracutymu){$maximalni_pocet_hracutymu=$tournament->minimalni_pocet_hracutymu;}    
    $this->kernel->models->DBgamesCups->store($idt,array('titulek_cupu'=>$titulek_cupu,'idealni_pocet_hracutymu_na_turnaj'=>$idealni_pocet_hracutymu_na_turnaj,'pocet_postupujicich_hracutymu'=>$pocet_postupujicich_hracutymu,'poznamka_zakladatele'=>$poznamka_zakladatele,'pravidla_mala'=>$pravidla_mala,'pravidla_velka'=>$pravidla_velka,'maximalni_pocet_hracutymu'=>$maximalni_pocet_hracutymu));  
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
      $this->kernel->models->DBgamesCupsParameters->deleteWhere('WHERE id_cupu='.$idt);  
      }
    if(count($Data->params)>0){
      foreach($Data->params as $xpam){
        $this->kernel->models->DBgamesCupsParameters->store(0,array('id_cupu'=>$idt,'id_hodnoty_parametru'=>$xpam));    
        }
      }
    if(count($Data->paramsText)>0){
      foreach($Data->paramsText as $xpamk=>$xpamv){
        $this->kernel->models->DBgamesCupsParameters->store(0,array('id_cupu'=>$idt,'id_parametru'=>$xpamk,'textova_hodnota'=>$xpamv));    
        }
      }              
    $this->redirect(array('action'=>'cup-view','message'=>'cup-saved','idt'=>$idt),false); 
    }
  private function GenerateNewRound(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    if($tournament->idc<1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'cup-view','message'=>'cup-round-not-generated','idt'=>$idt),false);}   
    if($tournament->dohrano==1){$this->redirect(array('action'=>'cup-view','message'=>'cup-round-not-generated','idt'=>$idt),false);}
    $vyplata=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$tournament->id_vyplaty.'"');       
    $pocetHracuTymu=0;  
    $pocetHracuX=0;
    $players=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'" ORDER BY id_tymu DESC');
    foreach($players as $pl){$pocetHracuX++;}
    if($tournament->hraji_tymy==1){          
      $loggedTeams=$this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="'.$tournament->idc.'" AND g.id_tymu=t.idt AND t.id_hry="'.$tournament->id_hry.'" ORDER BY t.nazev ASC');      
      foreach($loggedTeams as $xteam){$pocetHracuTymu++;}       
    }else{
      $pocetHracuTymu=$pocetHracuX;                
    }        
    $spider=$this->generateSpider($pocetHracuTymu,$tournament->idealni_pocet_hracutymu_na_turnaj,$tournament->pocet_postupujicich_hracutymu,$vyplata->winners_count);
    if(count($spider)<1){$this->redirect(array('action'=>'cup-view','message'=>'cup-round-not-generated','idt'=>$idt),false);}
    $tournaments=array();
    $tsData=$this->kernel->models->DBgamesTournaments->MqueryGetLines('SELECT * FROM games_tournaments WHERE id_cupu="'.$idt.'"');
    $roundToGenerate=1;
    foreach($tsData as $tsD){                      
      $tournaments[$tsD->id_kola_cupu][$tsD->id_zapasu_cupu]=$tsD;
      if($roundToGenerate<=$tsD->id_kola_cupu){
        $roundToGenerate=$tsD->id_kola_cupu+1;
        }
      }
    $lastRoundDone=1;
    $prewRoundToursIds=array('0');
    if($roundToGenerate>1){
      foreach($tournaments[$roundToGenerate-1] as $tsX){
        $prewRoundToursIds[]=$tsX->idt;
        if($tsX->dohrano==0||$tsX->prerozdelene_vyhry==0){
          $lastRoundDone=0;
          break;
          }
        }
      }
    $lastSpiderRound=0;
    foreach($spider as $ddS){
      if($ddS->kolo>$lastSpiderRound){$lastSpiderRound=$ddS->kolo;}
      }    
    if($lastRoundDone==1&&$roundToGenerate>0&&$roundToGenerate<=$lastSpiderRound&&isset($spider[$roundToGenerate])){
      if($roundToGenerate==1){
        $postupujici_hraci=$this->kernel->models->DBgamesCupsPlayers->getLines('*','WHERE id_cupu="'.$tournament->idc.'" ORDER BY RAND()');
        $postupujici_tymy=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_cups_teams WHERE id_cupu="'.$tournament->idc.'" ORDER BY RAND()');
        $this->kernel->models->DBgamesCups->store($tournament->idc,array('zahajeno'=>'1','datum_cas_startu'=>time()));
      }else{
        $postupujici_hraci=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_players WHERE id_turnaje IN ('.implode(',',$prewRoundToursIds).') AND postupuje=1 ORDER BY RAND()');
        $postupujici_tymy=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_tournaments_teams WHERE id_turnaje IN ('.implode(',',$prewRoundToursIds).') AND postupuje=1 ORDER BY RAND()');        
      }      
      $cenaNaFinalnihoHrace=(($pocetHracuX*$tournament->cena)/$spider[$roundToGenerate]->PocetHracuPosledniKolo);
      for($i=0;$i<$spider[$roundToGenerate]->celkovyPocetTurnaju;$i++){
        /////
        $pocetFinalnichHracuProCenu=0;
        $storeData=array('id_modulu'=>$tournament->id_modulu,'id_hry'=>$tournament->id_hry,'id_uzivatele'=>$this->kernel->user->uid,'datum_vytvoreni'=>time());        
        $storeData['id_typu_hry']=$tournament->id_typu_hry;       
        $storeData['id_vyplaty']=$tournament->id_vyplaty;    
        $storeData['cena']=''.($roundToGenerate==$lastSpiderRound?$cenaNaFinalnihoHrace:'0');;
        $storeData['datum_cas_startu']=time();    
        $storeData['hraji_tymy']=(int)$tournament->hraji_tymy;  
        if(($i+1)<$spider[$roundToGenerate]->celkovyPocetTurnaju){  
          $storeData['minimalni_pocet_tymu']=(int)$spider[$roundToGenerate]->PocetHracuPlnyZapas;
          $storeData['maximalni_pocet_tymu']=(int)$spider[$roundToGenerate]->PocetHracuPlnyZapas;
          $storeData['minimalni_pocet_hracu']=(int)$spider[$roundToGenerate]->PocetHracuPlnyZapas;
          $storeData['maximalni_pocet_hracu']=(int)$spider[$roundToGenerate]->PocetHracuPlnyZapas;
        }else{
          $storeData['minimalni_pocet_tymu']=(int)$spider[$roundToGenerate]->PocetHracuPosledniKolo;
          $storeData['maximalni_pocet_tymu']=(int)$spider[$roundToGenerate]->PocetHracuPosledniKolo;
          $storeData['minimalni_pocet_hracu']=(int)$spider[$roundToGenerate]->PocetHracuPosledniKolo;
          $storeData['maximalni_pocet_hracu']=(int)$spider[$roundToGenerate]->PocetHracuPosledniKolo;
        }        
        $storeData['poznamka_zakladatele']=$tournament->poznamka_zakladatele;    
        $storeData['pravidla_turnaje_mala']=$tournament->pravidla_mala;
        $storeData['pravidla_turnaje_velka']=$tournament->pravidla_velka;        
        $storeData['banner']=$tournament->banner;
        $storeData['id_cupu']=$tournament->idc;
        $storeData['id_kola_cupu']=$roundToGenerate;
        $storeData['id_zapasu_cupu']=$i+1;
        $storeData['pocet_postupujicich']=$tournament->pocet_postupujicich_hracutymu;
        $storeData['neni_odmenovan']=''.($roundToGenerate==$lastSpiderRound?'0':'1');                  
        $storeData['hash']=$this->kernel->models->DBgamesTournaments->generateHash(rand(0,99999),$this->xmoduleData->idm,$tournament->idc,0,$roundToGenerate,1,$this->kernel->user->uid,$storeData['id_vyplaty'],$storeData['datum_vytvoreni'],$storeData['datum_cas_startu'],$i,3);   
        $xid=$this->kernel->models->DBgamesTournaments->store(0,$storeData);
        $this->kernel->models->DBrewrites->AddEditRewrite('tournaments/view-tournament/'.$storeData['hash'].'-'.$xid.'/','FTournaments3','idt',$xid); 
        $linkToTheMatch=$this->Anchor(array('module'=>'FTournaments','action'=>'tournament-view','idt'=>$xid));            
        if(($i+1)<$spider[$roundToGenerate]->celkovyPocetTurnaju){
          $postupujici=$spider[$roundToGenerate]->PocetHracuPlnyZapas;
        }else{
          $postupujici=$spider[$roundToGenerate]->PocetHracuPosledniKolo;
        }        
        for($j=0;$j<$postupujici;$j++){
          if($tournament->hraji_tymy==0){
            foreach($postupujici_hraci as $phk=>$phv){
              $this->kernel->models->DBusers->Mquery('INSERT INTO games_tournaments_players (`id_turnaje`,`id_hrace`,`nick`) VALUES ("'.$xid.'","'.$phv->id_hrace.'","'.$phv->nick.'")');
              $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$phv->id_hrace.'","'.time().'","0","'.$linkToTheMatch.'","notifikace_typ_turnaj_start_noveho_kola","'.$gameName.'","'.$roundToGenerate.'","'.($i+1).'");');    
              $pocetFinalnichHracuProCenu++;
              unset($postupujici_hraci[$phk]);
              break;
              }         
          }else{
            foreach($postupujici_tymy as $phk=>$phv){
              $this->kernel->models->DBusers->Mquery('INSERT INTO games_tournaments_teams (`id_turnaje`,`id_tymu`) VALUES ("'.$xid.'","'.$phv->id_tymu.'")');
              foreach($postupujici_hraci as $phx){
                if($phx->id_tymu==$phv->id_tymu){
                  $this->kernel->models->DBusers->Mquery('INSERT INTO games_tournaments_players (`id_turnaje`,`id_hrace`,`nick`,`id_tymu`) VALUES ("'.$xid.'","'.$phx->id_hrace.'","'.$phx->nick.'","'.$phx->id_tymu.'")');
                  $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$phx->id_hrace.'","'.time().'","0","'.$linkToTheMatch.'","notifikace_typ_turnaj_start_noveho_kola","'.$gameName.'","'.$roundToGenerate.'","'.($i+1).'");'); 
                  $pocetFinalnichHracuProCenu++;
                  }
                }
              $alternates=$this->kernel->models->DBgamesCupsAlternatesPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idc.'" AND id_tymu="'.$phv->id_tymu.'"');
              foreach($alternates as $al){
                $this->kernel->models->DBusers->Mquery('INSERT INTO games_tournaments_alternates_players (`id_turnaje`,`id_hrace`,`nick`,`id_tymu`) VALUES ("'.$xid.'","'.$al->id_hrace.'","'.$al->nick.'","'.$al->id_tymu.'")');
                $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$al->id_hrace.'","'.time().'","0","'.$linkToTheMatch.'","notifikace_typ_turnaj_start_noveho_kola","'.$gameName.'","'.$roundToGenerate.'","'.($i+1).'");');                   
                }
              unset($alternates);
              unset($postupujici_tymy[$phk]);
              break;
              }   
          }
        }  
        /////
        if($roundToGenerate==$lastSpiderRound){
          $cenaNaFinalnihoHrace=(($pocetHracuX*$tournament->cena)/$pocetFinalnichHracuProCenu);
          $this->kernel->models->DBgamesTournaments->store($xid,array('cena'=>$cenaNaFinalnihoHrace));
          }              
        /////
        }        
    }else{
      $this->redirect(array('action'=>'cup-view','message'=>'cup-round-not-generated','idt'=>$idt),false);
    }  
    ////
    $this->redirect(array('action'=>'cup-view','message'=>'cup-round-generated','idt'=>$idt),false);
    } 
  private function generateSpider($pocetHracuTymu,$idealniPocetNaTurnaj,$pocetPostupujicich,$pocetVyher){    
    $pocetHracuTymuAktualniKolo=$pocetHracuTymu;
    $spider=array();    
    $a=0;
    $xB=0;
    for($kolo=1;;$kolo++){
      $xB=$kolo;
      if($pocetHracuTymuAktualniKolo<=($pocetVyher)||$pocetHracuTymuAktualniKolo==$idealniPocetNaTurnaj){
        $spider[$kolo]=new stdClass();
        $spider[$kolo]->kolo=$kolo;
        $spider[$kolo]->celkovyPocetHracuTymu=$pocetHracuTymuAktualniKolo;
        $spider[$kolo]->celkovyPocetTurnaju=1;
        $spider[$kolo]->pocetPlneObsazenychTurnaju=1;
        $spider[$kolo]->pocetPostupujicich=0;
        $spider[$kolo]->pocetHracuTymuPosledniKolo=$pocetHracuTymuAktualniKolo;
        $spider[$kolo]->PocetHracuPlnyZapas=$pocetPostupujicich;
        $spider[$kolo]->PocetPostupujicichPlnyZapas=0;
        $spider[$kolo]->PocetHracuPosledniKolo=$pocetHracuTymuAktualniKolo;
        $spider[$kolo]->PocetPostupujicichPosledniKolo=0;        
        break;
        }
      $spider[$kolo]=new stdClass();
      $spider[$kolo]->kolo=$kolo;
      $spider[$kolo]->celkovyPocetHracuTymu=$pocetHracuTymuAktualniKolo;
      $spider[$kolo]->celkovyPocetTurnaju=ceil($pocetHracuTymuAktualniKolo/$idealniPocetNaTurnaj);
      $spider[$kolo]->pocetPlneObsazenychTurnaju=floor($pocetHracuTymuAktualniKolo/$idealniPocetNaTurnaj);
      $spider[$kolo]->pocetPostupujicich=$spider[$kolo]->pocetPlneObsazenychTurnaju*$pocetPostupujicich;
      $spider[$kolo]->pocetHracuTymuPosledniKolo=$pocetHracuTymuAktualniKolo-($spider[$kolo]->pocetPlneObsazenychTurnaju*$idealniPocetNaTurnaj);
      
      $spider[$kolo]->PocetHracuPlnyZapas=$idealniPocetNaTurnaj;
      $spider[$kolo]->PocetPostupujicichPlnyZapas=$pocetPostupujicich;
      $spider[$kolo]->PocetHracuPosledniKolo=$idealniPocetNaTurnaj;   
      $spider[$kolo]->PocetPostupujicichPosledniKolo=$pocetPostupujicich;
      
      if($spider[$kolo]->pocetPlneObsazenychTurnaju<$spider[$kolo]->celkovyPocetTurnaju){
        if($spider[$kolo]->pocetHracuTymuPosledniKolo>=$pocetPostupujicich){
          $spider[$kolo]->pocetPostupujicich+=$pocetPostupujicich;
          $spider[$kolo]->PocetPostupujicichPosledniKolo=$pocetPostupujicich;
          $spider[$kolo]->PocetHracuPosledniKolo=$spider[$kolo]->pocetHracuTymuPosledniKolo;
        }else{
          $spider[$kolo]->pocetPostupujicich+=$spider[$kolo]->pocetHracuTymuPosledniKolo;
          $spider[$kolo]->PocetPostupujicichPosledniKolo=$spider[$kolo]->pocetHracuTymuPosledniKolo;
          $spider[$kolo]->PocetHracuPosledniKolo=$spider[$kolo]->pocetHracuTymuPosledniKolo;
        }
           
      }  
      $pocetHracuTymuAktualniKolo=$spider[$kolo]->pocetPostupujicich;            
      if($spider[$kolo]->celkovyPocetTurnaju==1){
        $spider[$kolo]->PocetPostupujicichPosledniKolo=0;
        break;        
        }        
      }
    $spider[$xB]->PocetPostupujicichPosledniKolo=0;
    return $spider;
    }
  private function PlayerKick(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    if($tournament->idc<1||$tournament->dohrano==1||$tournament->zahajeno==1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'cup-view','idt'=>$idt),false);}   
    $idu=(int)getpost('idu');
    $exist=$this->kernel->models->DBgamesCupsPlayers->getLine('*','WHERE id_cupu="'.$tournament->idc.'" AND id_hrace="'.$idu.'"');  
    if($exist->idgcp<1){$this->redirect(array('action'=>'cup-view','message'=>'cup-kickuser-failed','idt'=>$idt),false);}   
    $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$idu.'"');
    if($user->uid<1){$this->redirect(array('action'=>'cup-view','message'=>'cup-kickuser-failed','idt'=>$idt),false);}
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$idu,'datum_cas'=>time(),'coins'=>($tournament->cena),'duvod'=>'Vyřazení z turnaje #'.$idt.'.'));
    $this->kernel->models->DBusers->store($user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$tournament->cena)));
    $this->kernel->models->DBgamesCupsPlayers->deleteId($exist->idgcp);       
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"');
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$idu.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_vyrazeni_hrace_ze_zapasu","'.$exist->nick.'","'.$gameName.'");');
    if($exist->id_tymu>0){
      $teamData=$this->kernel->models->DBusers->MqueryGetLine('SELECT nazev,id_leadera FROM teams WHERE idt="'.$exist->id_tymu.'"');
      $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b,data_c) VALUES ("'.$teamData->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","notifikace_typ_turnaj_vyrazeni_hrace_ze_zapasu_pro_tym","'.$exist->nick.'","'.$gameName.'","'.$teamData->nazev.'");');        
      }         
    $this->redirect(array('action'=>'cup-view','message'=>'cup-kickuser-succes','idt'=>$idt),false);
    }
  private function TeamKick(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesCups->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idc="'.$idt.'"');
    if($tournament->idc<1||$tournament->dohrano==1||$tournament->zahajeno==1){$this->redirect(array('module'=>'FTournaments','message'=>'cup-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'cup-view','idt'=>$idt),false);}   
    $idtm=(int)getpost('idu');
    $existt=$this->kernel->models->DBusers->MqueryGetLine('SELECT * FROM games_cups_teams WHERE id_tymu="'.$idtm.'" AND id_cupu="'.$tournament->idc.'"');
    $teamData=$this->kernel->models->DBusers->MqueryGetLine('SELECT nazev,id_leadera FROM teams WHERE idt="'.$idtm.'"');
    $gameName=$this->kernel->models->DBusers->MqueryGetOne('SELECT nazev FROM games WHERE idg="'.((int)$tournament->id_hry).'"'); 
    if($existt->idgct<1){$this->redirect(array('action'=>'cup-view','message'=>'cup-kickteam-failed','idt'=>$idt),false);}   
    $players=$this->kernel->models->DBusers->MqueryGetLines('SELECT * FROM games_cups_players WHERE id_tymu="'.$idtm.'" AND id_cupu="'.$tournament->idc.'"'); 
    if(count($players)>0){
      foreach($players as $pl){
        $user=$this->kernel->models->DBusers->getLine('*','WHERE uid="'.$pl->id_hrace.'"');
        if($user->uid>0){
          $this->kernel->models->DBusersCoins->store(0,array('idu'=>$user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena),'duvod'=>'Vyřazení z turnaje #'.$idt.'.'));
          $this->kernel->models->DBusers->store($user->uid,array('ucetni_zustatek'=>str_replace(',','.',$user->ucetni_zustatek+$tournament->cena)));
          $this->kernel->models->DBgamesCupsPlayers->deleteId($pl->idgcp);    
          $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$user->uid.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","nofitikace_typ_turnaj_vyrazeni_tymu_ze_zapasu_pro_cleny","'.$gameName.'","'.$teamData->nazev.'");');           
          }
        }        
      }
    $this->kernel->models->DBusers->Mquery('INSERT INTO notifications (idu,ts,precteno,link,typ,data_a,data_b) VALUES ("'.$teamData->id_leadera.'","'.time().'","0","'.$this->Anchor(array('action'=>'cup-view','idt'=>$idt),false).'","nofitikace_typ_turnaj_vyrazeni_tymu_ze_zapasu_pro_leadera","'.$gameName.'","'.$teamData->nazev.'");'); 
    $this->kernel->models->DBusers->Mquery('DELETE FROM games_cups_teams WHERE id_tymu="'.$idtm.'" AND id_cupu="'.$tournament->idc.'"');        
    $this->redirect(array('action'=>'cup-view','message'=>'cup-kickteam-succes','idt'=>$idt),false);
    }
  }