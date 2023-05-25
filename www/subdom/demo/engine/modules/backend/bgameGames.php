<?php
class BGameGames extends Module{
  public function __construct(){$this->parent_module='BGame';}
  public function Main(){}  
  public function PageMain(){    
    $return=new stdClass();        
    $page=(int)getget('page','0');
    $counter=10; 
    $list=$this->kernel->models->DBgames->getLines('idg,nazev,aktivni','order by nazev ASC LIMIT '.($page*$counter).', '.$counter);
    $list_count=$this->kernel->models->DBgames->getOne('count(idg)');    
    $paginnator=$this->Paginnator($page,$list_count,$counter);
    foreach($list as $lk=>$lv){
      $list[$lk]->aedit=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit','idg'=>$lv->idg,'bp'=>$page));
      $list[$lk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-delete-post','idg'=>$lv->idg));
      }
    $tpl=new Templater();
    $tpl->add('list',$list);
    $tpl->add('paginnator',$paginnator);  
    $tpl->add('anew',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-add-post'),false));         
    $return->seo_title='Hry';        
    $return->content=$tpl->fetch('backend/games/games.tpl');    
    return $return;
    }   
  private function Paginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>($page+1)),false);
    }    
    return $pages;          
    }
  public function PageEdit(){
    $idg=(int)getget('idg','');
    $bp=(int)getget('bp','');
    $data=$this->kernel->models->DBgames->getLine('*',' WHERE idg="'.$idg.'"');
    if(!isset($data->idg)||$data->idg<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games','message'=>'not-found'),false);}
    $servers=$this->kernel->models->DBgamesServers->getLines('*',' WHERE idg="'.$idg.'" ORDER BY nazev');
    foreach($servers as $sk=>$sv){
      $servers[$sk]->aeditserver=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit-server-post','idg'=>$idg,'page'=>$bp,'idgs'=>$sv->idgs),false);
      $servers[$sk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-del-server-post','idg'=>$idg,'page'=>$bp,'idgs'=>$sv->idgs),false);
      }
    $types=$this->kernel->models->DBgamesTypes->getLines('*',' WHERE idg="'.$idg.'" ORDER BY nazev');
    foreach($types as $sk=>$sv){
      $types[$sk]->aedittype=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit-type-post','idg'=>$idg,'page'=>$bp,'idgs'=>$sv->idgt),false);
      $types[$sk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-del-type-post','idg'=>$idg,'page'=>$bp,'idgs'=>$sv->idgt),false);
      }
    $maps=$this->kernel->models->DBgamesMaps->getLines('*',' WHERE idgam="'.$idg.'" ORDER BY nazev');
    foreach($maps as $mk=>$mv){
      $maps[$mk]->aeditmap=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit-map-post','idg'=>$idg,'page'=>$bp,'idgm'=>$mv->idgm),false);
      $maps[$mk]->adel=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-del-map-post','idg'=>$idg,'page'=>$bp,'idgm'=>$mv->idgm),false);
      }
    $winners=$this->kernel->models->DBgamesWinnerTypes->getLines('*',' WHERE idg="'.$idg.'" ORDER BY nazev');
    foreach($winners as $wk=>$wv){$winners[$wk]->aeditwin=$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit-winner-post','idg'=>$idg,'page'=>$bp,'idgwt'=>$wv->idgwt),false);}
    $return=new stdClass();     
    $tpl=new Templater();
    $tpl->add('data',$data);
    $tpl->add('servers',$servers);      
    $tpl->add('types',$types);
    $tpl->add('maps',$maps);
    $tpl->add('winners',$winners);
    $tpl->add('aedit',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-edit-post','idg'=>$idg),false));  
    $tpl->add('aback',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games','page'=>$bp),false));
    $tpl->add('aaddserver',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-add-server-post','idg'=>$idg,'page'=>$bp),false));
    $tpl->add('aaddtype',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-add-type-post','idg'=>$idg,'page'=>$bp),false));
    $tpl->add('aaddmap',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-add-map-post','idg'=>$idg,'page'=>$bp),false));
    $tpl->add('aaddwinner',$this->Anchor(array('module'=>$this->parent_module,'action'=>'games-add-winner-post','idg'=>$idg,'page'=>$bp),false));                 
    $return->seo_title='Editace hry';        
    $return->content=$tpl->fetch('backend/games/gamesEdit.tpl');    
    return $return;
    }
  public function PageAddPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''){$this->redirect(array('module'=>$this->parent_module,'action'=>'games','message'=>'not-created'),false);}
    $this->kernel->models->DBgames->store(0,array('nazev'=>$nazev,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games','message'=>'created'),false);
    }  
  public function PageEditPost(){
    $idg=(int)getget('idg','');
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $mail_ukonceni_zapasu=prepare_get_data_safely(getpost('mail_ukonceni_zapasu',''));
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'not-saved','idg'=>$idg),false);}
    $this->kernel->models->DBgames->store($idg,array('nazev'=>$nazev,'mail_ukonceni_zapasu'=>$mail_ukonceni_zapasu,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'saved','idg'=>$idg),false);
    }
  public function PageDeletePost(){
    $idg=(int)getget('idg','');
    if($idg<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games','message'=>'not-found'),false);}
    $this->kernel->models->DBgames->deleteId($idg);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games','message'=>'deleted'),false);
    }    
  public function PageAddServerPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'server-not-added','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesServers->store(0,array('nazev'=>$nazev,'idg'=>$idg,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'server-added','idg'=>$idg,'bp'=>$page),false);
    }
  public function PageEditServerPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $idgs=(int)getget('idgs','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1||$idgs<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'server-not-saved','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesServers->store($idgs,array('nazev'=>$nazev,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'server-saved','idg'=>$idg,'bp'=>$page),false);  
    } 
  public function PageDelServerPost(){    
    $idg=(int)getget('idg','');
    $idgs=(int)getget('idgs','');
    $page=(int)getget('page','');
    if($idg<1||$idgs<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesServers->deleteId($idgs);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'server-deleted','idg'=>$idg,'bp'=>$page),false);    
    }
  public function PageAddTypePost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'type-not-added','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesTypes->store(0,array('nazev'=>$nazev,'idg'=>$idg,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'type-added','idg'=>$idg,'bp'=>$page),false);
    }
  public function PageEditTypePost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $idgs=(int)getget('idgs','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1||$idgs<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'type-not-saved','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesTypes->store($idgs,array('nazev'=>$nazev,'aktivni'=>$aktivni));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'type-saved','idg'=>$idg,'bp'=>$page),false);  
    } 
  public function PageDelTypePost(){    
    $idg=(int)getget('idg','');
    $idgs=(int)getget('idgs','');
    $page=(int)getget('page','');
    if($idg<1||$idgs<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesTypes->deleteId($idgs);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'type-deleted','idg'=>$idg,'bp'=>$page),false);    
    }  
  public function PageAddMapPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'map-not-added','idg'=>$idg,'bp'=>$page),false);}
    $mapid=$this->kernel->models->DBgamesMaps->store(0,array('nazev'=>$nazev,'idgam'=>$idg,'aktivni'=>$aktivni));
    $file=$_FILES["soubor"];
    if($file["error"]<=0&&$mapid>0){
      $pripony=explode(',',$this->kernel->settings['povolene_pripony_obrazku']);
      $original_name=explode('.',$file["name"]);
      $suffix=strtolower(end($original_name));
      if(in_array($suffix,$pripony)){
        if(filesize($file["tmp_name"])<=(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){
          $suffix='png';   
          if(move_uploaded_file($file["tmp_name"],'img/userfiles/maps/'.$mapid.'_.'.$suffix)){
            $phpThumb=new MHMthumb();
            $is=$phpThumb->thumb('img/userfiles/maps/'.$mapid.'_.'.$suffix,'img/userfiles/maps/'.$mapid.'.'.$suffix,356,200,false,25,25,25);                 
            }
          }  
        }     
      }   
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'map-added','idg'=>$idg,'bp'=>$page),false);
    }
  public function PageEditMapPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $idgm=(int)getget('idgm','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    if($nazev==''||$idg<1||$idgm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'map-not-saved','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesMaps->store($idgm,array('nazev'=>$nazev,'aktivni'=>$aktivni));     
    $file=$_FILES["soubor"];
    if($file["error"]<=0&&$idgm>0){
      $mapid=$idgm;
      $pripony=explode(',',$this->kernel->settings['povolene_pripony_obrazku']);
      $original_name=explode('.',$file["name"]);
      $suffix=strtolower(end($original_name));
      if(in_array($suffix,$pripony)){
        if(filesize($file["tmp_name"])<=(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){
          $suffix='png';   
          if(move_uploaded_file($file["tmp_name"],'img/userfiles/maps/'.$mapid.'_.'.$suffix)){
            $phpThumb=new MHMthumb();
            $is=$phpThumb->thumb('img/userfiles/maps/'.$mapid.'_.'.$suffix,'img/userfiles/maps/'.$mapid.'.'.$suffix,356,200,false,25,25,25);                 
            }
          }  
        }     
      }    
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'map-saved','idg'=>$idg,'bp'=>$page),false);    
    }
  public function PageDelMapPost(){
    $idg=(int)getget('idg','');
    $idgm=(int)getget('idgm','');
    $page=(int)getget('page','');
    if($idg<1||$idgm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesMaps->deleteId($idgm);      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'map-deleted','idg'=>$idg,'bp'=>$page),false);      
    }
  public function PageAddWinnerPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    $winners_count=(int)getpost('winners_count','');
    if($nazev==''||$idg<1||$winners_count<1||$winners_count>10){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'winner-not-added','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesWinnerTypes->store(0,array('nazev'=>$nazev,'idg'=>$idg,'aktivni'=>$aktivni,'winners_count'=>$winners_count));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'winner-added','idg'=>$idg,'bp'=>$page),false);
    }
  public function PageEditWinnerPost(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $idg=(int)getget('idg','');
    $idgm=(int)getget('idgwt','');
    $page=(int)getget('page','');
    $aktivni=(int)getpost('aktivni','');
    $misto_1=(int)getpost('misto_1','');
    $misto_2=(int)getpost('misto_2','');
    $misto_3=(int)getpost('misto_3','');
    $misto_4=(int)getpost('misto_4','');
    $misto_5=(int)getpost('misto_5','');
    $misto_6=(int)getpost('misto_6','');
    $misto_7=(int)getpost('misto_7','');
    $misto_8=(int)getpost('misto_8','');
    $misto_9=(int)getpost('misto_9','');
    $misto_10=(int)getpost('misto_10','');
    if($nazev==''||$idg<1||$idgm<1){$this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'winner-not-saved','idg'=>$idg,'bp'=>$page),false);}
    $this->kernel->models->DBgamesWinnerTypes->store($idgm,array('nazev'=>$nazev,'aktivni'=>$aktivni,'misto_1'=>$misto_1,'misto_2'=>$misto_2,'misto_3'=>$misto_3,'misto_4'=>$misto_4,'misto_5'=>$misto_5,'misto_6'=>$misto_6,'misto_7'=>$misto_7,'misto_8'=>$misto_8,'misto_9'=>$misto_9,'misto_10'=>$misto_10));      
    $this->redirect(array('module'=>$this->parent_module,'action'=>'games-edit','message'=>'winner-saved','idg'=>$idg,'bp'=>$page),false);    
    }
  }
  