<?php
class BGame extends Module{
  public function __construct(){$this->parent_module='Backend';}
  public function Main(){
    $this->seo_title='Hraní';
    $this->subTit=' | Hraní';
    $action=getget('action','games');
    if($action=='games'){$this->PageGames();} 
    elseif($action=='games-edit'){$this->PageGamesEdit();}      
    elseif($action=='games-add-post'){$this->PageGamesAddPost();} 
    elseif($action=='games-edit-post'){$this->PageGamesEditPost();}     
    elseif($action=='games-delete-post'){$this->PageGamesDeletePost();}
    elseif($action=='games-add-server-post'){$this->PageGamesAddServerPost();}
    elseif($action=='games-edit-server-post'){$this->PageGamesEditServerPost();}
    elseif($action=='games-del-server-post'){$this->PageGamesDelServerPost();}
    elseif($action=='games-add-type-post'){$this->PageGamesAddTypePost();}
    elseif($action=='games-edit-type-post'){$this->PageGamesEditTypePost();}
    elseif($action=='games-del-type-post'){$this->PageGamesDelTypePost();}   
    elseif($action=='games-add-map-post'){$this->PageGamesAddMapPost();}
    elseif($action=='games-edit-map-post'){$this->PageGamesEditMapPost();}
    elseif($action=='games-del-map-post'){$this->PageGamesDelMapPost();}  
    elseif($action=='games-add-winner-post'){$this->PageGamesAddWinnerPost();}
    elseif($action=='games-edit-winner-post'){$this->PageGamesEditWinnerPost();} 
    elseif($action=='modules'){$this->PageModules();}
    elseif($action=='modules-edit'){$this->PageModulesEdit();}
    elseif($action=='modules-add-game'){$this->PageModulesAddGame();}      
    elseif($action=='modules-edit-game'){$this->PageModulesEditGame();}      
    elseif($action=='modules-del-game'){$this->PageModulesDelGame();}   
    elseif($action=='tournaments'){$this->PageTournaments();}   
    elseif($action=='tournaments-edit'){$this->PageTournamentsEdit();}
    elseif($action=='tournaments-del-chat'){$this->PageTournamentsDelChat();}
    elseif($action=='tournaments-add-winners'){$this->PageTournamentsAddWinners();}                      
    else{$this->Redirect();}        
    }
  private function SetLeftMenu(){    
    $menu=array(
      $this->Anchor(array('action'=>'games'))=>'<span class="icon"><i class="fa fa-gamepad"></i></span> Hry',
      $this->Anchor(array('action'=>'modules'))=>'<span class="icon"><i class="fa fa-database"></i></span> Moduly',
      $this->Anchor(array('action'=>'tournaments'))=>'<span class="icon"><i class="fa fa-trophy"></i></span> Turnaje',             
      );  
    $active='games';             
    if(getget('action','')=='games'){$active='games';}    
    if(getget('action','')=='games-edit'){$active='games';}    
    if(getget('action','')=='modules'){$active='modules';}
    if(getget('action','')=='modules-edit'){$active='modules';}        
    if(getget('action','')=='tournaments'){$active='tournaments';}
    if(getget('action','')=='tournaments-edit'){$active='tournaments';}        
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/games/leftMenu.tpl');        
    }  
  private function PageGames(){$data=$this->kernel->modules->BGameGames->PageMain();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}
  private function PageGamesEdit(){$data=$this->kernel->modules->BGameGames->PageEdit();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}
  private function PageGamesAddPost(){$this->kernel->modules->BGameGames->PageAddPost();}
  private function PageGamesEditPost(){$this->kernel->modules->BGameGames->PageEditPost();}
  private function PageGamesDeletePost(){$this->kernel->modules->BGameGames->PageDeletePost();}
  private function PageGamesAddServerPost(){$this->kernel->modules->BGameGames->PageAddServerPost();}
  private function PageGamesEditServerPost(){$this->kernel->modules->BGameGames->PageEditServerPost();}
  private function PageGamesDelServerPost(){$this->kernel->modules->BGameGames->PageDelServerPost();}   
  private function PageGamesAddTypePost(){$this->kernel->modules->BGameGames->PageAddTypePost();}
  private function PageGamesEditTypePost(){$this->kernel->modules->BGameGames->PageEditTypePost();}
  private function PageGamesDelTypePost(){$this->kernel->modules->BGameGames->PageDelTypePost();}   
  private function PageGamesAddMapPost(){$this->kernel->modules->BGameGames->PageAddMapPost();}
  private function PageGamesEditMapPost(){$this->kernel->modules->BGameGames->PageEditMapPost();}
  private function PageGamesDelMapPost(){$this->kernel->modules->BGameGames->PageDelMapPost();}   
  private function PageModules(){$data=$this->kernel->modules->BGameModules->PageMain();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}     
  private function PageModulesEdit(){$data=$this->kernel->modules->BGameModules->PageEdit();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}     
  private function PageModulesAddGame(){$this->kernel->modules->BGameModules->PageAddGamePost();}
  private function PageModulesEditGame(){$this->kernel->modules->BGameModules->PageEditGamePost();}
  private function PageModulesDelGame(){$this->kernel->modules->BGameModules->PageDeleteGamePost();}
  private function PageTournaments(){$data=$this->kernel->modules->BGameTournaments->PageMain();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}
  private function PageTournamentsEdit(){$data=$this->kernel->modules->BGameTournaments->PageEdit();$this->seo_title=$data->seo_title.$this->subTit;$this->content=$data->content;$this->SetLeftMenu();$this->execute();}
  private function PageTournamentsDelChat(){$this->kernel->modules->BGameTournaments->PageDelChatPost();}
  private function PageTournamentsAddWinners(){$this->kernel->modules->BGameTournaments->PageAddWinnersPost();}
  private function PageGamesAddWinnerPost(){$this->kernel->modules->BGameGames->PageAddWinnerPost();}
  private function PageGamesEditWinnerPost(){$this->kernel->modules->BGameGames->PageEditWinnerPost();}  
  }