<?php
class BSettings extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    $this->rights=new stdClass();
    }
  public function Main(){
    $this->seo_title='Nastavení';
    $this->rights=$this->getUserRights();
    if(!in_array('base_settings_view',$this->rights->povoleneKody)&&!in_array('settings_caches_view',$this->rights->povoleneKody)&&!in_array('languages_view',$this->rights->povoleneKody)){
			$this->Redirect(array('module'=>'Backend'),false);
			}		
    $action=getget('action','list-main');
    if(!in_array('base_settings_view',$this->rights->povoleneKody)){
    	$action=getget('action','list-caches');
    	}
    if(!in_array('base_settings_view',$this->rights->povoleneKody)&&!in_array('settings_caches_view',$this->rights->povoleneKody)){
    	$action=getget('action','list-languages');
    	}
    if($action=='list-main'&&in_array('base_settings_view',$this->rights->povoleneKody)){$this->PageListMain();}
    elseif($action=='edit-main'&&in_array('base_settings_view',$this->rights->povoleneKody)){$this->PageEditMain();}   
    elseif($action=='edit-main-post'&&in_array('base_settings_changes',$this->rights->povoleneKody)){$this->PageEditMainPost();}               
    elseif($action=='list-caches'&&in_array('settings_caches_view',$this->rights->povoleneKody)){$this->PageListCaches();}   
    elseif($action=='cache-regenerate-rewrites'&&in_array('settings_caches_changes',$this->rights->povoleneKody)){$this->PageRegenerateRewriteCaches();}                    
    elseif($action=='cache-regenerate-settings'&&in_array('settings_caches_changes',$this->rights->povoleneKody)){$this->PageRegenerateSettingsCaches();}                                        
    elseif($action=='cache-regenerate-images-sizes'&&in_array('settings_caches_changes',$this->rights->povoleneKody)){$this->PageRegenerateImagesSizesCaches();}       
    elseif($action=='cache-regenerate-languages'&&in_array('settings_caches_changes',$this->rights->povoleneKody)){$this->PageRegenerateLanguagesCaches();}  
    elseif($action=='list-languages'&&in_array('languages_view',$this->rights->povoleneKody)){$this->PageListLanguages();}     
    elseif($action=='add-language'&&in_array('languages_changes',$this->rights->povoleneKody)){$this->PageAddLanguage();}      
    elseif($action=='edit-language'&&in_array('languages_view',$this->rights->povoleneKody)){$this->PageEditLanguage();}
    elseif($action=='edit-language-post'&&in_array('languages_changes',$this->rights->povoleneKody)){$this->PageEditLanguagePost();} 
    elseif($action=='edit-language-values-post'&&in_array('languages_changes',$this->rights->povoleneKody)){$this->PageEditLanguageValuesPost();}     
    else{$this->Redirect();}        
    }
  /*
  * Levý sloupec
  */
  private function SetLeftMenu(){    
    $menu=array(
      $this->Anchor(array('action'=>'list-main'))=>'<span class="icon"><i class="fa fa-cog"></i></span> Základní nastavení',        
      $this->Anchor(array('action'=>'list-caches'))=>'<span class="icon"><i class="fa fa-refresh "></i></span> Systémové cache',
      $this->Anchor(array('action'=>'list-languages'))=>'<span class="icon"><i class="fa fa-language "></i></span> Jazyky',
      );
    if(!in_array('base_settings_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-main'))]);
			}
		if(!in_array('settings_caches_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-caches'))]);
			}
		if(!in_array('languages_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-languages'))]);
			}	
    $active='list-main';
    if(in_array('settings_caches_view',$this->rights->povoleneKody)&&!in_array('base_settings_view',$this->rights->povoleneKody)){
    	$active='list-caches';
    	}
    if(in_array('languages_view',$this->rights->povoleneKody)&&!in_array('settings_caches_view',$this->rights->povoleneKody)&&!in_array('base_settings_view',$this->rights->povoleneKody)){
    	$active='list-languages';
    	}            
    if(getget('action','')=='list-caches'){$active='list-caches';}
    if(getget('action','')=='list-languages'){$active='list-languages';}              
    if(getget('action','')=='edit-language'){$active='list-languages';}
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
    $languages_time='-';
    if(file_exists('engine/cache/languages.php')){
      $languages_time=strftime('%d.%m.%Y %H:%M:%S',filemtime('engine/cache/languages.php')); 
      }  
    $tpl=new Templater();   
    $tpl->add('message',prepare_get_data_safely(getget('message','')));    
    $tpl->add('rewritesTime',$rewrites_time);    
    $tpl->add('aRewritesRegenerate',$this->anchor(array('action'=>'cache-regenerate-rewrites'),false));    
    $tpl->add('settingsTime',$settings_time);    
    $tpl->add('aSettingsRegenerate',$this->anchor(array('action'=>'cache-regenerate-settings'),false));            
    $tpl->add('imagesSizesTime',$images_sizes_time);    
    $tpl->add('aImagesSizesRegenerate',$this->anchor(array('action'=>'cache-regenerate-images-sizes'),false));    
    $tpl->add('languagesTime',$languages_time);    
    $tpl->add('aLanguagesRegenerate',$this->anchor(array('action'=>'cache-regenerate-languages'),false));    
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
  private function PageRegenerateLanguagesCaches(){
    $this->kernel->models->DBlanguages->RegenerateCacheFile();
    $this->Redirect(array('action'=>'list-caches','message'=>'languages-regenerated'),false);    
    }
  private function PageListLanguages(){
    $this->seo_title='Jazyky';    
    $langs=$this->kernel->models->DBlanguages->getLines('*','order by nazev');
    foreach($langs as $sk=>$sv){
      $langs[$sk]->aedit=$this->Anchor(array('action'=>'edit-language','idl'=>$sv->idl),false);        
      }
    $tpl=new Templater();
    $tpl->add('langs',$langs);
    $tpl->add('aaddlang',$this->Anchor(array('action'=>'add-language'),false));        
    $tpl->add('message',getget('message',''));    
    $this->content=$tpl->fetch('backend/settings/listLanguages.tpl');
    $this->SetLeftMenu();
    $this->execute();       
    }
  private function PageAddLanguage(){
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $aktivni=(int)getpost('aktivni','');
    $vychozi=(int)getpost('vychozi','');
    if($vychozi==1){$this->kernel->models->DBlanguages->updateWhere(' WHERE 1=1 ',array('vychozi'=>0));}
    $idl=$this->kernel->models->DBlanguages->store(0,array('nazev'=>$nazev,'aktivni'=>$aktivni,'vychozi'=>$vychozi));  
    $file=$_FILES["image"];
    if($idl>0&&$file["error"]<=0){
      $pripony=explode(',',$this->kernel->settings['povolene_pripony_obrazku']);
      $original_name=explode('.',$file["name"]);
      $suffix=strtolower(end($original_name));
      if(in_array($suffix,$pripony)){
        if(filesize($file["tmp_name"])<=(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){
          $suffix='png';   
          if(move_uploaded_file($file["tmp_name"],'userfiles/langs/'.$idl.'_.'.$suffix)){
            $phpThumb=new MHMthumb();
            $is=$phpThumb->thumb('userfiles/langs/'.$idl.'_.'.$suffix,'userfiles/langs/'.$idl.'.'.$suffix,22,16,false,25,25,25);                 
            }
          }  
        }           
      }    
    $this->kernel->models->DBlanguages->RegenerateCacheFile(); 
    $this->Redirect(array('action'=>'list-languages','message'=>'created'),false);   
    }
  private function PageEditLanguage(){
    $idl=(int)getget('idl','');
    $idp=(int)getget('idp','');
    if($idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);
      }
    $data=$this->kernel->models->DBlanguages->getLine('*','WHERE idl="'.$idl.'"');
    if($data->idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);    
      }
    $nextLanguages=array();
    $nextLanguages['0']=new stdClass();
    $nextLanguages['0']->nazev='Nezobrazovat';
    $nextLanguages['0']->link=$this->Anchor(array('action'=>'edit-language','idl'=>$idl,'idp'=>0),false);
    $nextLanguages['0']->active=($idp==0?'1':'0');     
    $nextLanguagesx=$this->kernel->models->DBlanguages->getLines('*','order by nazev');       
    foreach($nextLanguagesx as $sk=>$sv){
      $nextLanguages[$sv->idl]=$sv;
      $nextLanguages[$sv->idl]->active=($idp==$sv->idl?'1':'0');
      $nextLanguages[$sv->idl]->link=$this->Anchor(array('action'=>'edit-language','idl'=>$idl,'idp'=>$sv->idl),false);        
      }
    $keys=$this->kernel->models->DBlanguagesKeywords->getLines('*',' ORDER BY klic');
    $vals=$this->kernel->models->DBlanguagesValues->getLines('*',' WHERE idl="'.$idl.'"');
    $vals2=array();
    if(count($vals)>0){
      foreach($vals as $v){
        $vals2[$v->idk]=$v;
        }    
      }
    $druhePreklady=new stdClass();
    $druhePreklady->idp=$idp;
    if($idp>0){
      $druhePreklady->nazev=$this->kernel->models->DBlanguages->getOne('nazev','WHERE idl="'.$idp.'"');
      $valsX=$this->kernel->models->DBlanguagesValues->getLines('*',' WHERE idl="'.$idp.'"');
      $druhePreklady->vals=array();
      if(count($valsX)>0){
        foreach($valsX as $v){
          $druhePreklady->vals[$v->idk]=$v;
          }    
        }
    }else{
      $druhePreklady->nazev='';
      $druhePreklady->vals=array();
    }
    $tpl=new Templater();
    $tpl->add('nextLanguages',$nextLanguages);
    $tpl->add('l',$data);
    $tpl->add('druhePreklady',$druhePreklady);
    $tpl->add('keys',$keys);
    $tpl->add('vals',$vals2);
    $tpl->add('aeditlang',$this->Anchor(array('action'=>'edit-language-post','idl'=>$idl),false)); 
    $tpl->add('aeditlangvalues',$this->Anchor(array('action'=>'edit-language-values-post','idl'=>$idl),false));   
    $tpl->add('aback',$this->Anchor(array('action'=>'list-languages'),false));        
    $tpl->add('message',getget('message',''));    
    $this->content=$tpl->fetch('backend/settings/editLanguage.tpl');
    $this->SetLeftMenu();
    $this->execute();               
    }
  private function PageEditLanguagePost(){
    $idl=(int)getget('idl','');
    if($idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);
      }
    $data=$this->kernel->models->DBlanguages->getLine('*','WHERE idl="'.$idl.'"');
    if($data->idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);    
      }
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $aktivni=(int)getpost('aktivni','');
    $vychozi=(int)getpost('vychozi','');
    if($vychozi==1){$this->kernel->models->DBlanguages->updateWhere(' WHERE 1=1 ',array('vychozi'=>0));}
    $idl=$this->kernel->models->DBlanguages->store($idl,array('nazev'=>$nazev,'aktivni'=>$aktivni,'vychozi'=>$vychozi)); 
    $file=$_FILES["image"];
    if($idl>0&&$file["error"]<=0){
      $pripony=explode(',',$this->kernel->settings['povolene_pripony_obrazku']);
      $original_name=explode('.',$file["name"]);
      $suffix=strtolower(end($original_name));
      if(in_array($suffix,$pripony)){
        if(filesize($file["tmp_name"])<=(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){
          $suffix='png';   
          if(move_uploaded_file($file["tmp_name"],'userfiles/langs/'.$idl.'_.'.$suffix)){
            $phpThumb=new MHMthumb();
            $is=$phpThumb->thumb('userfiles/langs/'.$idl.'_.'.$suffix,'userfiles/langs/'.$idl.'.'.$suffix,22,16,false,25,25,25);                 
            }
          }  
        }           
      }    
    $this->kernel->models->DBlanguages->RegenerateCacheFile(); 
    $this->Redirect(array('action'=>'edit-language','message'=>'saved','idl'=>$idl),false);   
    }
  private function PageEditLanguageValuesPost(){
    $idl=(int)getget('idl','');
    if($idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);
      }
    $data=$this->kernel->models->DBlanguages->getLine('*','WHERE idl="'.$idl.'"');
    if($data->idl<1){
      $this->Redirect(array('action'=>'list-languages','message'=>'language-not-found','idl'=>$idl),false);    
      }
    if(count($_POST['exist'])>0){
      foreach($_POST['exist'] as $kk=>$vv){
        if($kk>0){          
          $this->kernel->models->DBlanguagesValues->store($kk,array('hodnota'=>str_replace('"','\"',$vv[0])));
          }
        }    
      }
    if(count($_POST['not_exist'])>0){
      foreach($_POST['not_exist'] as $kk=>$vv){
        if($kk>0){
          $this->kernel->models->DBlanguagesValues->store(0,array('idl'=>$idl,'idk'=>$kk,'hodnota'=>str_replace('"','\"',$vv[0])));
          }
        }    
      }
    $this->kernel->models->DBlanguagesValues->RegenerateCacheFile($idl); 
    $this->Redirect(array('action'=>'edit-language','message'=>'saved','idl'=>$idl),false);   
    }
  }
