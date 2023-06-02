<?php
class BSettings extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    }
  public function Main(){
    $this->seo_title='Nastavení';
    $action=getget('action','list-main');
      if($action=='list-main'){$this->PageListMain();}
      elseif($action=='edit-main'){$this->PageEditMain();}   
      elseif($action=='edit-main-post'){$this->PageEditMainPost();}               
      elseif($action=='list-caches'){$this->PageListCaches();}   
      elseif($action=='cache-regenerate-rewrites'){$this->PageRegenerateRewriteCaches();}                    
      elseif($action=='cache-regenerate-settings'){$this->PageRegenerateSettingsCaches();}                                        
      elseif($action=='cache-regenerate-images-sizes'){$this->PageRegenerateImagesSizesCaches();}                    
      else{$this->Redirect();}        
    }
  /*
  * Levý sloupec
  */
  private function SetLeftMenu(){
    if($this->kernel->user->data->prava==2){
      $menu=array(
        $this->Anchor(array('action'=>'list-main'))=>'<span class="icon"><i class="fa fa-cog"></i></span> Základní nastavení',        
        $this->Anchor(array('action'=>'list-caches'))=>'<span class="icon"><i class="fa fa-refresh "></i></span> Systémové cache',
        );
    }else{
      $menu=array(
        $this->Anchor(array('action'=>'list-main'))=>'<span class="icon"><i class="fa fa-cog"></i></span> Základní nastavení',        
        );
    }
    $active='list-main';            
    if(getget('action','')=='list-caches'){$active='list-caches';}         
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/settings/leftMenu.tpl');        
    }  
  /*
  * Hlavní položky nastavení / settings
  */    
  private function PageListMain(){
    $this->seo_title='Základní nastavení';    
    $settings=$this->kernel->models->DBsettings->getLines('ids,nazev,klic,popis,hodnota,typ','WHERE zobrazovat=1 order by poradi');
    foreach($settings as $sk=>$sv){
      $settings[$sk]->aedit=$this->Anchor(array('action'=>'edit-main','ids'=>$sv->ids),false);        
      }
    $tpl=new Templater();
    $tpl->add('settings',$settings);    
    $tpl->add('message',getget('message',''));    
    $this->content=$tpl->fetch('backend/settings/listMain.tpl');
    $this->SetLeftMenu();
    $this->execute();   
    }
  private function PageEditMain(){
    $ids=(int)getget('ids','');
    if($ids<1){
      $this->Redirect(array('action'=>'list-main','message'=>'setting-not-found','ids'=>$ids),false);
      }
    $data=$this->kernel->models->DBsettings->getLine('*','WHERE ids="'.$ids.'" AND zobrazovat=1');
    if($data->ids<1){
      $this->Redirect(array('action'=>'list-main','message'=>'setting-not-found','ids'=>$ids),false);    
      }
    $tpl=new Templater();
    $tpl->add('setting',$data);
    $tpl->add('message',getget('message',''));    
    $tpl->add('editor',$this->kernel->GetEditor('hodnota',$data->hodnota));
    $tpl->add('asave',$this->Anchor(array('action'=>'edit-main-post','ids'=>$data->ids),false));
    $tpl->add('aback',$this->Anchor(array('action'=>'list-main'),false));
    $this->content=$tpl->fetch('backend/settings/editMain.tpl');
    $this->SetLeftMenu();
    $this->execute();           
    }
  private function PageEditMainPost(){
    $ids=(int)getget('ids','');
    if($ids<1){
      $this->Redirect(array('action'=>'list-main','message'=>'setting-not-found','ids'=>$ids),false);
      }
    $data=$this->kernel->models->DBsettings->getLine('*','WHERE ids="'.$ids.'" AND zobrazovat=1');
    if($data->ids<1){
      $this->Redirect(array('action'=>'list-main','message'=>'setting-not-found','ids'=>$ids),false);    
      }
    if($data->typ=='editor'||$data->typ=='textarea'){
      $hodnota=prepare_get_data_safely_editor(getpost('hodnota',''));
    }else{
      $hodnota=prepare_get_data_safely(getpost('hodnota',''));
    }
    if($data->typ=='ano_ne'||$data->typ=='int'){
      $hodnota=(int)$hodnota;      
      }
    if($data->typ=='float'){
      $hodnota=(float)str_replace(',','.',$hodnota);      
      }   
    $this->kernel->models->DBsettings->updateId($ids,array('hodnota'=>$hodnota));     
    $this->kernel->models->DBsettings->RegenerateCacheFile();  
    $this->Redirect(array('action'=>'edit-main','message'=>'saved','ids'=>$ids),false);   
    }    
  /*
  * Nastavení cache
  */
  private function PageListCaches(){
    $this->seo_title='Nastavení systémových cache'; 
    $rewrites_time='-';
    if(file_exists('engine/cache/rewrites.php')){
      $rewrites_time=strftime('%d.%m.%Y %H:%M:%S',filemtime('engine/cache/rewrites.php')); 
      }  
    $settings_time='-';
    if(file_exists('engine/cache/settings.php')){
      $settings_time=strftime('%d.%m.%Y %H:%M:%S',filemtime('engine/cache/settings.php')); 
      }      
    $images_sizes_time='-';
    if(file_exists('engine/cache/images_sizes.php')){
      $images_sizes_time=strftime('%d.%m.%Y %H:%M:%S',filemtime('engine/cache/images_sizes.php')); 
      }  
    $tpl=new Templater();   
    $tpl->add('message',prepare_get_data_safely(getget('message','')));    
    $tpl->add('rewritesTime',$rewrites_time);    
    $tpl->add('aRewritesRegenerate',$this->anchor(array('action'=>'cache-regenerate-rewrites'),false));    
    $tpl->add('settingsTime',$settings_time);    
    $tpl->add('aSettingsRegenerate',$this->anchor(array('action'=>'cache-regenerate-settings'),false));            
    $tpl->add('imagesSizesTime',$images_sizes_time);    
    $tpl->add('aImagesSizesRegenerate',$this->anchor(array('action'=>'cache-regenerate-images-sizes'),false));    
    $this->content=$tpl->fetch('backend/settings/listCaches.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageRegenerateRewriteCaches(){
    $this->kernel->models->DBrewrites->RegenerateCacheFile();
    $this->Redirect(array('action'=>'list-caches','message'=>'rewrites-regenerated'),false);
    }
  private function PageRegenerateSettingsCaches(){
    $this->kernel->models->DBsettings->RegenerateCacheFile();
    $this->Redirect(array('action'=>'list-caches','message'=>'settings-regenerated'),false);    
    } 
  private function PageRegenerateImagesSizesCaches(){
    $this->kernel->models->DBimagesSizes->RegenerateCacheFile();
    $this->Redirect(array('action'=>'list-caches','message'=>'images-sizes-regenerated'),false);    
    }
  }
?>