<?php
class FTournaments extends Module{
  private $xmodule,$xmoduleData;
  public function Main(){
    $this->parent_module='Frontend';
    $this->xmodule='tournaments';    
    $this->xmoduleData=$this->kernel->models->DBgamesModules->getLine('*',' WHERE interni_nazev="'.$this->xmodule.'"');;        
    $action=getget('action','main');     
    if($action=='main'){$this->MainPage();} 
    elseif($action=='new-tournament-post'){$this->NewTournamentPost();}
    elseif($action=='new-tournament'){$this->NewTournament();}                                     
    elseif($action=='new-tournament-post-2'){$this->NewTournamentPost2();}
    elseif($action=='tournament-view'){$this->TournamentView();}
    elseif($action=='get-into-tournament'){$this->GetIntoTournament();}
    elseif($action=='end-tournament'){$this->EndTournament();}
    elseif($action=='extend-tournament'){$this->ExtendTournament();}
    elseif($action=='new-chat'){$this->InsertChatIntoTournament();}
    else{$this->Redirect();}            
    }    
  private function MainPage(){
    $this->seo_title='Tournaments';        
    $this->seo_keywords='Tournaments';        
    $this->seo_description='Tournaments'; 
    $moduleGames=$this->kernel->models->DBgamesModulesVsGames->getLines('*','WHERE idmod="'.$this->xmoduleData->idm.'"');
    $gamesIds=array('0');
    foreach($moduleGames as $xMg){$gamesIds[]=$xMg->idgam;}
    $games=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gamesIds).') AND aktivni=1 ORDER BY nazev');
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');
    $tournaments=$this->kernel->models->DBgamesTournaments->getLines('idt,id_hry,id_serveru,cena,id_typu_hry,id_mapy,datum_cas_startu,maximalni_pocet_hracu,minimalni_pocet_hracu,dohrano',' WHERE id_modulu="'.$this->xmoduleData->idm.'" ORDER BY datum_cas_startu asc');
    $gaxx=array('0');$sexx=array('0');$tgxx=array('0');$maxx=array('0');
    foreach($tournaments as $kt=>$vt){
      $gaxx[]=$vt->id_hry;
      $sexx[]=$vt->id_serveru;
      $tgxx[]=$vt->id_typu_hry;
      $maxx[]=$vt->id_mapy;    
      $tournaments[$kt]->aview=$this->Anchor(array('action'=>'tournament-view','idt'=>$vt->idt));    
      }
    $gaxy=$this->kernel->models->DBgames->getLines('*','WHERE idg in ('.implode(',',$gaxx).')');
    $sexy=$this->kernel->models->DBgamesServers->getLines('*','WHERE idgs in ('.implode(',',$sexx).')');   
    $tgxy=$this->kernel->models->DBgamesTypes->getLines('*','WHERE idgt in ('.implode(',',$tgxx).')');   
    $maxy=$this->kernel->models->DBgamesMaps->getLines('*','WHERE idgm in ('.implode(',',$maxx).')');
    $games2=array('0'=>' - Nezadáno - ');$servers2=array('0'=>' - Nezadáno - ');$types2=array('0'=>' - Nezadáno - ');$maps2=array('0'=>' - Nezadáno - ');
    foreach($gaxy as $gx){$games2[$gx->idg]=$gx->nazev;}      
    foreach($sexy as $sx){$servers2[$sx->idgs]=$sx->nazev;}
    foreach($tgxy as $tx){$types2[$tx->idgt]=$tx->nazev;}
    foreach($maxy as $mx){$maps2[$mx->idgm]=$mx->nazev;}
    $tpl=new Templater();          
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('moduleGames',$moduleGames);
    $tpl->add('games',$games);
    $tpl->add('games2',$games2);
    $tpl->add('servers2',$servers2);
    $tpl->add('types2',$types2);
    $tpl->add('maps2',$maps2);
    $tpl->add('tournaments',$tournaments);
    $tpl->add('userID',$this->kernel->user->uid);           
    $tpl->add('anewpost',$this->Anchor(array('action'=>'new-tournament-post'),false));  
    $this->content=$tpl->fetch('frontend/tournaments/main.tpl');  
    $this->execute();  
    }
  private function NewTournamentPost(){
    $idg=(int)getpost('idg','0');
    $_SESSION['new_game_id_'.$this->xmodule]=$idg;
    $this->redirect(array('action'=>'new-tournament'),false);    
    }
  private function NewTournament(){    
    $this->seo_title='Tournaments';        
    $this->seo_keywords='Tournaments';        
    $this->seo_description='Tournaments'; 
    $idg=(int)$_SESSION['new_game_id_'.$this->xmodule];
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');   
    $moduleGame=$this->kernel->models->DBgamesModulesVsGames->getLine('*','WHERE idmod="'.$this->xmoduleData->idm.'" AND idgam="'.$idg.'"');
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$idg.'"');
    if($moduleGame->idgm<1||$game->idg<1){$this->redirect(array('message'=>'choose-game'),false);}
    $servers=$this->kernel->models->DBgamesServers->getLines('*',' WHERE idg="'.$idg.'" and aktivni=1 ORDER BY nazev');
    $types=$this->kernel->models->DBgamesTypes->getLines('*',' WHERE idg="'.$idg.'" and aktivni=1 ORDER BY nazev');
    $maps=$this->kernel->models->DBgamesMaps->getLines('*',' WHERE idgam="'.$idg.'" and aktivni=1 ORDER BY nazev');
    $vyplaty=$this->kernel->models->DBgamesWinnerTypes->getLines('*',' WHERE idg="'.$idg.'" and aktivni=1 ORDER BY nazev');
    if(!isset($_SESSION['new_game_'.$this->xmodule])){
      $_SESSION['new_game_'.$this->xmodule]=new stdClass();
      $_SESSION['new_game_'.$this->xmodule]->idgs=0;
      $_SESSION['new_game_'.$this->xmodule]->idgt=0;
      $_SESSION['new_game_'.$this->xmodule]->idgm=0;
      $_SESSION['new_game_'.$this->xmodule]->id_vyplaty=0;
      $_SESSION['new_game_'.$this->xmodule]->cena='0,00';
      $_SESSION['new_game_'.$this->xmodule]->datum_cas_startu=strftime('%d.%m.%Y %H:%M',time()+86400);      
      $_SESSION['new_game_'.$this->xmodule]->minimalni_pocet_hracu=1;
      $_SESSION['new_game_'.$this->xmodule]->maximalni_pocet_hracu=$moduleGame->maximalni_pocet_hracu;
      $_SESSION['new_game_'.$this->xmodule]->pocet_kol=$moduleGame->maximalni_pocet_kol;
      $_SESSION['new_game_'.$this->xmodule]->poznamka_zakladatele='';      
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_mala='';
      $_SESSION['new_game_'.$this->xmodule]->pravidla_turnaje_velka='';    
      $this->redirect(array('action'=>'new-tournament'),false);    
      }
    $tpl=new Templater();          
    $tpl->add('mainpagename',$mainpagename);
    $tpl->add('moduleGame',$moduleGame);  
    $tpl->add('game',$game);
    $tpl->add('servers',$servers);     
    $tpl->add('types',$types);     
    $tpl->add('maps',$maps);
    $tpl->add('vyplaty',$vyplaty);
    $tpl->add('ngData',$_SESSION['new_game_'.$this->xmodule]);               
    $tpl->add('anext',$this->Anchor(array('action'=>'new-tournament-post-2'),false));
    $tpl->add('aback',$this->Anchor(array(),false));      
    $tpl->add('message',getget('message',''));      
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
      $_SESSION['new_game_'.$this->xmodule]->$kp=$vp;        
      $Data->$kp=$vp;
      }        
    if($Data->id_vyplaty<1||$Data->idgs<1||$Data->idgt<1||$Data->idgm<1||$idg<1||$this->xmoduleData->idm<1||$Data->cena==''||$Data->datum_cas_startu==''||$Data->minimalni_pocet_hracu==''||$Data->maximalni_pocet_hracu==''||$Data->pocet_kol==''||$Data->poznamka_zakladatele==''||$Data->pravidla_turnaje_mala==''||$Data->pravidla_turnaje_velka==''){$this->redirect(array('action'=>'new-tournament','message'=>'all-needed'),false);}
    $storeData['id_serveru']=$Data->idgs;
    $storeData['id_typu_hry']=$Data->idgt;
    $storeData['id_mapy']=$Data->idgm;
    $storeData['id_vyplaty']=$Data->id_vyplaty;    
    $storeData['cena']=str_replace(',','.',trim((round((float)str_replace(',','.',$Data->cena),2))));     ;
    $storeData['datum_cas_startu']=DateTimeToTimestamp($Data->datum_cas_startu);    
    $storeData['minimalni_pocet_hracu']=(int)$Data->minimalni_pocet_hracu;
    $storeData['maximalni_pocet_hracu']=(int)$Data->maximalni_pocet_hracu;
    $storeData['pocet_kol']=(int)$Data->pocet_kol;
    $storeData['poznamka_zakladatele']=$Data->poznamka_zakladatele;    
    $storeData['pravidla_turnaje_mala']=$Data->pravidla_turnaje_mala;
    $storeData['pravidla_turnaje_velka']=$Data->pravidla_turnaje_velka;    
    if($storeData['datum_cas_startu']<=time()+3600){$this->redirect(array('action'=>'new-tournament','message'=>'date-error'),false);}         
    if($storeData['pocet_kol']<1||$storeData['pocet_kol']>$moduleGame->maximalni_pocet_kol){$this->redirect(array('action'=>'new-tournament','message'=>'rounds'),false);}
    if($storeData['minimalni_pocet_hracu']<1||$storeData['minimalni_pocet_hracu']>$moduleGame->maximalni_pocet_hracu){$this->redirect(array('action'=>'new-tournament','message'=>'count-players'),false);}
    if($storeData['minimalni_pocet_hracu']>$storeData['maximalni_pocet_hracu']||$storeData['maximalni_pocet_hracu']>$moduleGame->maximalni_pocet_hracu){$this->redirect(array('action'=>'new-tournament','message'=>'count-players'),false);}
    if($this->kernel->user->data->ucetni_zustatek<$moduleGame->poplatek_za_zalozeni_turnaje){$this->redirect(array('action'=>'new-tournament','message'=>'coins'),false);}    
    $xid=$this->kernel->models->DBgamesTournaments->store(0,$storeData);
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($moduleGame->poplatek_za_zalozeni_turnaje*(-1)),'duvod'=>'Poplatek za založení turnaje #'.$xid.'.'));
    $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$moduleGame->poplatek_za_zalozeni_turnaje)));
    unset($_SESSION['new_game_id_'.$this->xmodule]);
    unset($_SESSION['new_game_'.$this->xmodule]);        
    $this->redirect(array('action'=>'tournament-view','idt'=>$xid),false);      
    }
  private function TournamentView(){
    $this->seo_title='Tournaments';        
    $this->seo_keywords='Tournaments';        
    $this->seo_description='Tournaments'; 
    $idt=(int)getget('idt','');
    $mainpagename=$this->kernel->models->DBmainPages->getOne('nazev');  
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $game=$this->kernel->models->DBgames->getLine('*','WHERE idg="'.$tournament->id_hry.'"');
    $server=$this->kernel->models->DBgamesServers->getLine('*','WHERE idgs="'.$tournament->id_serveru.'"');   
    $type=$this->kernel->models->DBgamesTypes->getLine('*','WHERE idgt="'.$tournament->id_typu_hry.'"');   
    $map=$this->kernel->models->DBgamesMaps->getLine('*','WHERE idgm="'.$tournament->id_mapy.'"');
    $vyplata=$this->kernel->models->DBgamesWinnerTypes->getLine('*','WHERE idgwt="'.$tournament->id_vyplaty.'"');
    $vyplataData=$this->kernel->models->DBgamesTournamentsWinners->getLine('*','WHERE id_turnaje="'.$tournament->idt.'"');
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" ORDER BY skore DESC');
    $playersArr=array('0',$tournament->id_uzivatele);
    foreach($players as $pl){$playersArr[]=$pl->id_hrace;$playersArr2[]=$pl->id_hrace;}      
    $users=$this->kernel->models->DBusers->getLines('uid,osloveni','WHERE uid in ('.implode(',',$playersArr).')');
    $users2=array();
    foreach($users as $us){$users2[$us->uid]=$us;}    
    $chatData=$this->kernel->models->DBgamesTournamentsChat->getLines('*','WHERE id_turnaje="'.$tournament->idt.'" ORDER BY idtc ASC');
    $tpl=new Templater();        
    $tpl->add('tournament',$tournament);
    $tpl->add('game',$game);     
    $tpl->add('server',$server);     
    $tpl->add('type',$type);     
    $tpl->add('map',$map);
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
    $tpl->add('agetin',$this->Anchor(array('action'=>'get-into-tournament','idt'=>$idt),false));
    $tpl->add('aendtournament',$this->Anchor(array('action'=>'end-tournament','idt'=>$idt),false));
    $tpl->add('atournamentExtend',$this->Anchor(array('action'=>'extend-tournament','idt'=>$idt),false));
    $tpl->add('anewchat',$this->Anchor(array('action'=>'new-chat','idt'=>$idt),false));          
    $this->content=$tpl->fetch('frontend/tournaments/view.tpl');  
    $this->execute();  
    }
  private function GetIntoTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $players=$this->kernel->models->DBgamesTournamentsPlayers->getLines('*','WHERE id_turnaje="'.$tournament->idt.'"');
    if($tournament->maximalni_pocet_hracu==count($players)){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}
    if($tournament->dohrano==1){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}    
    if($this->kernel->user->data->ucetni_zustatek<$tournament->cena){$this->redirect(array('action'=>'tournament-view','message'=>'login-failed','idt'=>$idt),false);}
    $xid=$this->kernel->models->DBgamesTournamentsPlayers->store(0,array('id_turnaje'=>$idt,'id_hrace'=>$this->kernel->user->uid));
    $this->kernel->models->DBusersCoins->store(0,array('idu'=>$this->kernel->user->uid,'datum_cas'=>time(),'coins'=>($tournament->cena*(-1)),'duvod'=>'Zápisné turnaje #'.$idt.'.'));
    $this->kernel->models->DBusers->store($this->kernel->user->uid,array('ucetni_zustatek'=>str_replace(',','.',$this->kernel->user->data->ucetni_zustatek-$tournament->cena)));
    $this->redirect(array('action'=>'tournament-view','message'=>'login-succes','idt'=>$idt),false); 
    }
  private function ExtendTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-extend-failed','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournaments->store($idt,array('datum_cas_startu'=>($tournament->datum_cas_startu+((int)(getpost('extend','')*3600)) )));
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-extend-succes','idt'=>$idt),false); 
    }
  private function EndTournament(){
    $idt=(int)getget('idt');
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    if($this->kernel->user->uid!=$tournament->id_uzivatele){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
    $fm=new fileManager();    
    $uploaded=$fm->UploadFile('obrazek_skore','screen-'.$idt,'userfiles/tournaments_score/','image');
    if($uploaded==false){$this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-failed','idt'=>$idt),false);}
    $postdata=array();    
    foreach($_POST as $k=>$v){$postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);}
    $storeData=array('dohrano'=>'1','datum_cas_konce'=>time(),'poznamka_skore'=>$postdata['poznamka_skore'],'obrazek_skore'=>$uploaded);
    $this->kernel->models->DBgamesTournaments->store($idt,$storeData);
    @$fm->UploadFile('obrazek_skore_a','screen-'.$idt.'-a','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_b','screen-'.$idt.'-b','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_c','screen-'.$idt.'-c','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_d','screen-'.$idt.'-d','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_e','screen-'.$idt.'-e','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_f','screen-'.$idt.'-f','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_g','screen-'.$idt.'-g','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_h','screen-'.$idt.'-h','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_i','screen-'.$idt.'-i','userfiles/tournaments_score/','image');
    @$fm->UploadFile('obrazek_skore_j','screen-'.$idt.'-j','userfiles/tournaments_score/','image');    
    $gameDataMail=trim($this->kernel->models->DBgames->getOne('mail_ukonceni_zapasu','WHERE idg="'.$tournament->id_hry.'"'));
    if($gameDataMail!=''){
      $mailer=new PHPMailer();;
      $mailer->CharSet="UTF-8"; 
      $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
      $mailer->Subject='Dokončen turnaj, nahrány výsledky - přerozdělte prosím výhry.';              
      $mailer->MsgHTML('<h1>Dokončen turnaj, nahrány výsledky - přerozdělte prosím výhry.</h1>
      <a href="http://demo.gsoul.cz/backend/game/tournaments-edit/?idt='.$idt.'" target="_blank">Přejít do turnaje -></a>');
      $mailer->AddAddress($gameDataMail);
      $mailer->Send();                     
      }
    $this->redirect(array('action'=>'tournament-view','message'=>'tournament-end-succes','idt'=>$idt),false); 
    }
  private function InsertChatIntoTournament(){
    $idt=(int)getget('idt');
    $obsah=prepare_get_data_safely(getpost('obsah',''));
    $tournament=$this->kernel->models->DBgamesTournaments->getLine('*',' WHERE id_modulu="'.$this->xmoduleData->idm.'" and idt="'.$idt.'"');    
    if($tournament->idt<1){$this->redirect(array('message'=>'tournament-not-found'),false);} 
    $player=$this->kernel->models->DBgamesTournamentsPlayers->getLine('*','WHERE id_turnaje="'.$idt.'" AND id_hrace="'.$this->kernel->user->uid.'"');
    if($tournament->dohrano==1||$player->idgtp<1||$obsah==''){$this->redirect(array('action'=>'tournament-view','message'=>'chat-failed','idt'=>$idt),false);}
    $this->kernel->models->DBgamesTournamentsChat->store(0,array('id_turnaje'=>$idt,'id_hrace'=>$this->kernel->user->uid,'ts'=>time(),'obsah'=>$obsah));    
    $this->redirect(array('action'=>'tournament-view','message'=>'chat-succes','idt'=>$idt),false); 
    }
  
  }