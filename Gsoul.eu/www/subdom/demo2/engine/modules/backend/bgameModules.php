<?php
class BGameModules extends Module{
  public function __construct(){$this->parent_module='BGame';}
  public function Main(){}  
  public function PageMain(){    
    $return=new stdClass();            
    $list=$this->kernel->models->DBgamesModules->getLines('*','order by nazev ASC ');       
    foreach($list as $lk=>$lv){
      $list[$lk]->aedit=$this->Anchor(array('module'=>$this->parent_module,'action'=>'modules-edit','idm'=>$lv->idm));
      $list[$lk]->xcnt=$this->kernel->models->DBgamesModules->MqueryGetOne('SELECT count(idgm) as xcnt FROM games_modules_vs_games WHERE idmod="'.$lv->idm.'"');
      }
    $tpl=new Templater();
    $tpl->add('list',$list);        
    $return->seo_title='Moduly ';        
    $return->content=$tpl->fetch('backend/games/modules.tpl');    
    return $return;
    }   
  public function PageEdit(){
    $idm=(int)getget('idm','');    
    $data=$this->kernel->models->DBgamesModules->getLine('*',' WHERE idm="'.$idm.'"');
    if(!isset($data->idm)||$data->idm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'modules','message'=>'not-found'),false);}    
    $games=$this->kernel->models->DBgames->getLines('*','ORDER BY nazev');
    $games2=array();
    foreach($games as $gs){$games2[$gs->idg]=$gs;}
    $usedGames=array();
    $spojeni=$this->kernel->models->DBgamesModulesVsGames->getLines('*',' WHERE idmod="'.$idm.'"');
    foreach($spojeni as $sk=>$sv){
      $spojeni[$sk]->aedit=$this->Anchor(array('module'=>$this->parent_module,'action'=>'modules-edit-game','idgm'=>$sv->idgm,'idm'=>$idm));
      $spojeni[$sk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'modules-del-game','idgm'=>$sv->idgm,'idm'=>$idm));
      $usedGames[$sv->idgam]=$sv->idgam;
      }        
    $return=new stdClass();     
    $tpl=new Templater();
    $tpl->add('data',$data);    
    $tpl->add('games',$games);
    $tpl->add('games2',$games2);
    $tpl->add('usedGames',$usedGames);
    $tpl->add('spojeni',$spojeni);    
    $tpl->add('aback',$this->Anchor(array('module'=>$this->parent_module,'action'=>'modules'),false));
    $tpl->add('aaddgame',$this->Anchor(array('module'=>$this->parent_module,'action'=>'modules-add-game','idm'=>$idm),false));         
    $return->seo_title='Editace modulu ';        
    $return->content=$tpl->fetch('backend/games/modulesEdit.tpl');    
    return $return;
    }
  public function PageAddGamePost(){
    $idm=(int)getget('idm','');  
    $idg=(int)getpost('idgam','');    
    $maximalni_pocet_tymu=(int)getpost('maximalni_pocet_tymu','');
    $maximalni_pocet_hracu=(int)getpost('maximalni_pocet_hracu','');
    $poplatek_za_zalozeni_turnaje=str_replace(',','.',trim((round((float)str_replace(',','.',getpost('poplatek_za_zalozeni_turnaje','')),2))));    
    $procenta_pro_zakladatele=str_replace(',','.',trim((round((float)str_replace(',','.',getpost('procenta_pro_zakladatele','')),2))));        
    if($procenta_pro_zakladatele>9){$procenta_pro_zakladatele=9;}  
    $this->kernel->models->DBgamesModulesVsGames->store(0,array('idmod'=>$idm,'idgam'=>$idg,'maximalni_pocet_tymu'=>$maximalni_pocet_tymu,'maximalni_pocet_hracu'=>$maximalni_pocet_hracu,'poplatek_za_zalozeni_turnaje'=>$poplatek_za_zalozeni_turnaje,'procenta_pro_zakladatele'=>$procenta_pro_zakladatele));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'modules-edit','message'=>'created','idm'=>$idm),false);
    }  
  public function PageEditGamePost(){
    $idm=(int)getget('idm','');
    $idgm=(int)getget('idgm','');    
    $maximalni_pocet_tymu=(int)getpost('maximalni_pocet_tymu','');
    $maximalni_pocet_hracu=(int)getpost('maximalni_pocet_hracu','');
    $poplatek_za_zalozeni_turnaje=str_replace(',','.',trim((round((float)str_replace(',','.',getpost('poplatek_za_zalozeni_turnaje','')),2))));
    $procenta_pro_zakladatele=str_replace(',','.',trim((round((float)str_replace(',','.',getpost('procenta_pro_zakladatele','')),2))));
    if($procenta_pro_zakladatele>9){$procenta_pro_zakladatele=9;}         
    if($idgm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'modules-edit','message'=>'not-found','idm'=>$idm),false);}      
    $this->kernel->models->DBgamesModulesVsGames->store($idgm,array('maximalni_pocet_tymu'=>$maximalni_pocet_tymu,'maximalni_pocet_hracu'=>$maximalni_pocet_hracu,'poplatek_za_zalozeni_turnaje'=>$poplatek_za_zalozeni_turnaje,'procenta_pro_zakladatele'=>$procenta_pro_zakladatele));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'modules-edit','message'=>'saved','idm'=>$idm),false);
    }
  public function PageDeleteGamePost(){
    $idm=(int)getget('idm','');
    $idgm=(int)getget('idgm','');
    if($idgm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'modules-edit','message'=>'not-found','idm'=>$idm),false);}
    $this->kernel->models->DBgamesModulesVsGames->deleteId($idgm);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'modules-edit','message'=>'deleted','idm'=>$idm),false);
    }    
  }  