<?php
class BSuperAdmin extends Module{
  public function __construct(){
    $this->need_user=1;
    $this->user_rights=2;
    $this->parent_module='Backend';
    }
  public function Main(){
    $this->seo_title='Super Admin'; 
    $action=getget('action','base-settings');
      if($action=='base-settings'){$this->BaseSettings();}            
      elseif($action=='base-settings-save-order'){$this->BaseSettingsSaveOrder();}   
      elseif($action=='base-settings-new-post'){$this->BaseSettingsNewPost();}   
      elseif($action=='base-settings-delete'){$this->BaseSettingsDeletePost();}   
      elseif($action=='base-settings-edit'){$this->BaseSettingsEdit();}   
      elseif($action=='base-settings-edit-post'){$this->BaseSettingsEditPost();}         
      elseif($action=='boxes-categories'){$this->BoxesCategories();}   
      elseif($action=='boxes-categories-save-order'){$this->BoxesCategoriesSaveOrder();}  
      elseif($action=='boxes-categories-new-post'){$this->BoxesCategoriesNewPost();}               
      elseif($action=='boxes-categories-delete'){$this->BoxesCategoriesDeletePost();}   
      elseif($action=='boxes-categories-edit'){$this->BoxesCategoriesEdit();}   
      elseif($action=='boxes-categories-edit-post'){$this->BoxesCategoriesEditPost();}    
      elseif($action=='images-sizes'){$this->ImagesSizes();}   
      elseif($action=='images-sizes-new-post'){$this->ImagesSizesNewPost();}               
      elseif($action=='images-sizes-delete'){$this->ImagesSizesDeletePost();}   
      elseif($action=='templates'){$this->Templates();}
      elseif($action=='templates-new-post'){$this->TemplatesNewPost();}   
      elseif($action=='templates-edit-post'){$this->TemplatesEditPost();} 
      elseif($action=='templates-delete'){$this->TemplatesDeletePost();}         
      else{$this->Redirect();}      
    }
  private function SetLeftMenu(){
    $menu=array(
      $this->Anchor(array('action'=>'base-settings'))=>'<span class="icon"><i class="fa fa-cog"></i></span> Základní nastavení',                  
      $this->Anchor(array('action'=>'boxes-categories'))=>'<span class="icon"><i class="fa fa-cube"></i></span> Kategorie prvků',
      $this->Anchor(array('action'=>'images-sizes'))=>'<span class="icon"><i class="fa fa-expand"></i></span> Velikosti obrázků',
      $this->Anchor(array('action'=>'templates'))=>'<span class="icon"><i class="fa fa-file-text-o"></i></span> Šablony textů pro editor',
      );
    $active='base-settings';    
    if(getget('action','')=='boxes-categories'){$active='boxes-categories';}          
    if(getget('action','')=='boxes-categories-edit'){$active='boxes-categories';}          
    if(getget('action','')=='images-sizes'){$active='images-sizes';}          
    if(getget('action','')=='templates'){$active='templates';}          
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/superadmin/leftMenu.tpl');    
    }  
  /*
  * Základní nastavení:
  */  
  private function BaseSettings(){
    $this->seo_title='Základní nastavení - Super Admin'; 
    $tpl=new Templater();
    $tree=$this->kernel->models->DBsettings->getLines('ids,nazev,klic,zobrazovat','ORDER BY poradi');
    foreach($tree as $tk=>$tv){  
      $tree[$tk]->aedit=$this->Anchor(array('action'=>'base-settings-edit','ids'=>$tv->ids),false);    
      $tree[$tk]->adel=$this->Anchor(array('action'=>'base-settings-delete','ids'=>$tv->ids),false);
      }          
    $tpl->add('tree',$tree);
    $tpl->add('anew',$this->Anchor(array('action'=>'base-settings-new-post'),false));     
    $tpl->add('asaveorder',$this->Anchor(array('action'=>'base-settings-save-order'),false));           
    $this->content=$tpl->fetch('backend/superadmin/baseSettings.tpl');
    $this->SetLeftMenu();
    $this->execute();     
    }
  private function BaseSettingsSaveOrder(){    
    $order=trim(prepare_get_data_safely(getpost('order','')));
    if($order==''){
      $this->Redirect(array('action'=>'base-settings','message'=>'order-is-same'),false);  
      }    
    $td=$this->kernel->models->DBsettings->getLines('ids,poradi');
    $data=array();
    foreach($td as $vtd){
      $data[$vtd->ids]=$vtd;    
      }
    $orders=explode(',',$order);
    $poradi=1;
    foreach($orders as $o){
      if(trim($o)==''){
        continue;
        }      
      $ids=(int)$o;
      if($ids>0){
        if($data[$ids]->poradi!=$poradi){
          $this->kernel->models->DBsettings->updateId($ids,array('poradi'=>$poradi));                   
          }        
        $poradi++;
        }                  
      }        
    $this->Redirect(array('action'=>'base-settings','message'=>'order-saved'),false);      
    }
  private function BaseSettingsNewPost(){    
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    $postdata['poradi']=((int)$this->kernel->models->DBsettings->getOne('poradi','order by poradi desc limit 1'))+1;  
    $ids=$this->kernel->models->DBsettings->store(0,$postdata);         
    $this->kernel->models->DBsettings->RegenerateCacheFile(); 
    $this->Redirect(array('action'=>'base-settings','message'=>'created','ids'=>$ids),false);   
    }  
  private function BaseSettingsDeletePost(){
    $ids=(int)getget('ids','');
    if($ids>0){
      $this->kernel->models->DBsettings->deleteId($ids);
      $this->kernel->models->DBsettings->RegenerateCacheFile(); 
      $this->Redirect(array('action'=>'base-settings','message'=>'deleted','ids'=>$ids),false);   
      }
    $this->Redirect(array('action'=>'base-settings'),false);   
    }
  private function BaseSettingsEdit(){
    $this->seo_title='Základní nastavení - Super Admin'; 
    $ids=(int)getget('ids','');
    if($ids<1){
      $this->Redirect(array('action'=>'base-settings','message'=>'setting-not-found','ids'=>$ids),false);
      }
    $data=$this->kernel->models->DBsettings->getLine('*','WHERE ids="'.$ids.'"');
    if($data->ids<1){
      $this->Redirect(array('action'=>'base-settings','message'=>'setting-not-found','ids'=>$ids),false);    
      }
    $tpl=new Templater();
    $tpl->add('setting',$data);
    $tpl->add('message',getget('message',''));    
    $tpl->add('editor',$this->kernel->GetEditor('hodnota',$data->hodnota));
    $tpl->add('asave',$this->Anchor(array('action'=>'base-settings-edit-post','ids'=>$data->ids),false));
    $tpl->add('aback',$this->Anchor(array('action'=>'base-settings'),false));
    $this->content=$tpl->fetch('backend/superadmin/baseSettingsEdit.tpl');
    $this->SetLeftMenu();
    $this->execute();  
    }  
  private function BaseSettingsEditPost(){
    $ids=(int)getget('ids','');
    if($ids<1){
      $this->Redirect(array('action'=>'base-settings','message'=>'setting-not-found','ids'=>$ids),false);
      }
    $data=$this->kernel->models->DBsettings->getLine('*','WHERE ids="'.$ids.'"');
    if($data->ids<1){
      $this->Redirect(array('action'=>'base-settings','message'=>'setting-not-found','ids'=>$ids),false);    
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){
        if($k=='hodnota'){
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
          $postdata[prepare_get_data_safely($k)]=$hodnota;   
        }else{
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        }
      }           
    $this->kernel->models->DBsettings->updateId($ids,$postdata);     
    $this->kernel->models->DBsettings->RegenerateCacheFile();  
    $this->Redirect(array('action'=>'base-settings-edit','message'=>'saved','ids'=>$ids),false);   
    }
  /**********************************/    
  private function BoxesCategories(){
    $this->seo_title='Kategorie prvků - Super Admin'; 
    $tpl=new Templater();
    $tree=$this->kernel->models->DBboxesCategories->getLines('idbc,nazev,interni_nazev,zobrazovat_admin','ORDER BY poradi');
    foreach($tree as $tk=>$tv){ 
      $tree[$tk]->boxesCount=$this->kernel->models->DBboxes->getOne('count(idb)','WHERE id_bc="'.$tv->idbc.'"');
      $tree[$tk]->aedit=$this->Anchor(array('action'=>'boxes-categories-edit','idbc'=>$tv->idbc),false);    
      $tree[$tk]->adel=$this->Anchor(array('action'=>'boxes-categories-delete','idbc'=>$tv->idbc),false);
      }          
    $tpl->add('tree',$tree);
    $tpl->add('anew',$this->Anchor(array('action'=>'boxes-categories-new-post'),false));     
    $tpl->add('asaveorder',$this->Anchor(array('action'=>'boxes-categories-save-order'),false));        
    $this->content=$tpl->fetch('backend/superadmin/boxesCategories.tpl');
    $this->SetLeftMenu();
    $this->execute();      
    }
  private function BoxesCategoriesSaveOrder(){    
    $order=trim(prepare_get_data_safely(getpost('order','')));
    if($order==''){
      $this->Redirect(array('action'=>'boxes-categories','message'=>'order-is-same'),false);  
      }    
    $td=$this->kernel->models->DBboxesCategories->getLines('idbc,poradi');
    $data=array();
    foreach($td as $vtd){
      $data[$vtd->idbc]=$vtd;    
      }
    $orders=explode(',',$order);
    $poradi=1;
    foreach($orders as $o){
      if(trim($o)==''){
        continue;
        }      
      $ids=(int)$o;
      if($ids>0){
        if($data[$ids]->poradi!=$poradi){
          $this->kernel->models->DBboxesCategories->updateId($ids,array('poradi'=>$poradi));                   
          }        
        $poradi++;
        }                  
      }     
    $this->Redirect(array('action'=>'boxes-categories','message'=>'order-saved'),false);      
    }
  private function BoxesCategoriesNewPost(){    
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    $postdata['poradi']=((int)$this->kernel->models->DBboxesCategories->getOne('poradi','order by poradi desc limit 1'))+1;  
    $ids=$this->kernel->models->DBboxesCategories->store(0,$postdata);           
    $this->Redirect(array('action'=>'boxes-categories','message'=>'created','idbc'=>$ids),false);   
    }  
  private function BoxesCategoriesDeletePost(){
    $ids=(int)getget('idbc','');
    if($ids>0){
      $this->kernel->models->DBboxesCategories->deleteId($ids);  
      $this->Redirect(array('action'=>'boxes-categories','message'=>'deleted','idbc'=>$ids),false);   
      }
    $this->Redirect(array('action'=>'boxes-categories'),false);   
    }
  private function BoxesCategoriesEdit(){
    $this->seo_title='Kategorie prvků - Super Admin'; 
    $ids=(int)getget('idbc','');
    if($ids<1){
      $this->Redirect(array('action'=>'boxes-categories','message'=>'category-not-found','idbc'=>$ids),false);
      }
    $data=$this->kernel->models->DBboxesCategories->getLine('*','WHERE idbc="'.$ids.'"');
    if($data->idbc<1){
      $this->Redirect(array('action'=>'boxes-categories','message'=>'category-not-found','idbc'=>$ids),false);    
      }
    $tpl=new Templater();
    $tpl->add('setting',$data);
    $tpl->add('message',getget('message',''));        
    $tpl->add('asave',$this->Anchor(array('action'=>'boxes-categories-edit-post','idbc'=>$data->idbc),false));
    $tpl->add('aback',$this->Anchor(array('action'=>'boxes-categories'),false));
    $this->content=$tpl->fetch('backend/superadmin/boxesCategoriesEdit.tpl');
    $this->SetLeftMenu();
    $this->execute();  
    }  
  private function BoxesCategoriesEditPost(){
    $ids=(int)getget('idbc','');
    if($ids<1){
      $this->Redirect(array('action'=>'boxes-categories','message'=>'category-not-found','idbc'=>$ids),false);
      }
    $data=$this->kernel->models->DBboxesCategories->getLine('*','WHERE idbc="'.$ids.'"');
    if($data->idbc<1){
      $this->Redirect(array('action'=>'boxes-categories','message'=>'category-not-found','idbc'=>$ids),false);    
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){        
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);        
      }           
    $this->kernel->models->DBboxesCategories->updateId($ids,$postdata);      
    $this->Redirect(array('action'=>'boxes-categories-edit','message'=>'saved','idbc'=>$ids),false);   
    }
  /**********************************/    
  private function ImagesSizes(){
    $this->seo_title='Velikosti obrázků - Super Admin'; 
    $tpl=new Templater();
    $tree=$this->kernel->models->DBimagesSizes->getLines('idis,x,y','ORDER BY x,y');
    foreach($tree as $tk=>$tv){  
      $tree[$tk]->adel=$this->Anchor(array('action'=>'images-sizes-delete','idis'=>$tv->idis),false);
      }          
    $tpl->add('tree',$tree);
    $tpl->add('anew',$this->Anchor(array('action'=>'images-sizes-new-post'),false));     
    $this->content=$tpl->fetch('backend/superadmin/imagesSizes.tpl');
    $this->SetLeftMenu();
    $this->execute();     
    }
  private function ImagesSizesNewPost(){
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=(int)prepare_get_data_safely($v);
      }   
    if($postdata['x']<1||$postdata['x']>9999){
      $postdata['x']=1;
      }                
    if($postdata['y']<1||$postdata['y']>9999){
      $postdata['y']=1;
      } 
    $ids=$this->kernel->models->DBimagesSizes->store(0,$postdata);
    $this->kernel->models->DBimagesSizes->RegenerateCacheFile();           
    $this->Redirect(array('action'=>'images-sizes','message'=>'created','idis'=>$ids),false); 
    }
  private function ImagesSizesDeletePost(){
    $ids=(int)getget('idis','');
    if($ids>0){
      $this->kernel->models->DBimagesSizes->deleteId($ids);  
      $this->kernel->models->DBimagesSizes->RegenerateCacheFile();
      $this->Redirect(array('action'=>'images-sizes','message'=>'deleted','idis'=>$ids),false);   
      }
    $this->kernel->models->DBimagesSizes->RegenerateCacheFile();
    $this->Redirect(array('action'=>'images-sizes'),false);      
    }
  /**********************************/      
  private function Templates(){
    $this->seo_title='Šablony textů pro editor - Super Admin'; 
    $tpl=new Templater();
    $templates=$this->kernel->models->DBtemplates->getLines('*','ORDER BY nazev');
    foreach($templates as $tk=>$tv){  
      $templates[$tk]->adel=$this->Anchor(array('action'=>'templates-delete','idt'=>$tv->idt),false);
      $templates[$tk]->aedit=$this->Anchor(array('action'=>'templates-edit-post','idt'=>$tv->idt),false);
      $templates[$tk]->editor=$this->kernel->GetEditor('html_'.$tv->idt,$tv->html);
      }          
    $tpl->add('templates',$templates);
    $tpl->add('anew',$this->Anchor(array('action'=>'templates-new-post'),false));     
    $tpl->add('aneweditor',$this->kernel->GetEditor('html',''));     
    $this->content=$tpl->fetch('backend/superadmin/templates.tpl');
    $this->SetLeftMenu();
    $this->execute();       
    }
  private function TemplatesNewPost(){
    $postdata=array();        
    $postdata['nazev']=prepare_get_data_safely(getpost('nazev',''));
    $postdata['html']=prepare_get_data_safely_editor(getpost('html',''));  
    $idt=$this->kernel->models->DBtemplates->store(0,$postdata);      
    $this->Redirect(array('action'=>'templates','message'=>'created','idt'=>$idt),false);  
    }
  private function TemplatesEditPost(){
    $idt=(int)getget('idt','0');
    if($idt<1){
      $this->Redirect(array('action'=>'templates','message'=>'template-not-found','idt'=>$idt),false);  
      }
    $template=$this->kernel->models->DBtemplates->getOne('idt','WHERE idt="'.$idt.'"');   
    if($template<1){
      $this->Redirect(array('action'=>'templates','message'=>'template-not-found','idt'=>$idt),false);  
      }
    $postdata=array();        
    $postdata['nazev']=prepare_get_data_safely(getpost('nazev',''));
    $postdata['html']=prepare_get_data_safely_editor(getpost('html_'.$idt,''));  
    $idt=$this->kernel->models->DBtemplates->store($idt,$postdata);            
    $this->Redirect(array('action'=>'templates','message'=>'edited','idt'=>$idt),false);     
    }
  private function TemplatesDeletePost(){
    $idt=(int)getget('idt','0');
    if($idt<1){
      $this->Redirect(array('action'=>'templates','message'=>'template-not-found','idt'=>$idt),false);  
      }
    $template=$this->kernel->models->DBtemplates->getOne('idt','WHERE idt="'.$idt.'"');   
    if($template<1){
      $this->Redirect(array('action'=>'templates','message'=>'template-not-found','idt'=>$idt),false);  
      }    
    $this->kernel->models->DBtemplates->deleteId($idt); 
    $this->Redirect(array('action'=>'templates','message'=>'deleted','idt'=>$idt),false);
    }    
  }
?>