<?php
class BFiles extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    $this->rights=new stdClass();
    }
  public function Main(){
    $this->seo_title='Soubory';
    $this->rights=$this->getUserRights();
    if(!in_array('files_categories_view',$this->rights->povoleneKody)&&!in_array('images_categories_view',$this->rights->povoleneKody)){
			$this->Redirect(array('module'=>'Backend'),false);
			}
    $action=getget('action','list-images');
    if(in_array('files_categories_view',$this->rights->povoleneKody)&&!in_array('images_categories_view',$this->rights->povoleneKody)){
    	$action=getget('action','list-files');
    	}
  	if($action=='list-files'&&in_array('files_categories_view',$this->rights->povoleneKody)){$this->PageListFiles();}
  	elseif($action=='file-category-new-post'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->FileCategoryNewPost();}   
  	elseif($action=='delete-file-category'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->FileCategoryDeletePost();}                                                              
  	elseif($action=='edit-file-category'&&in_array('files_categories_view',$this->rights->povoleneKody)){$this->FileCategoryEdit();}
  	elseif($action=='file-save-category-post'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->FileCategoryEditPost();} 
  	elseif($action=='file-new-post'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->FileNewPost();}  
  	elseif($action=='save-file'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->SaveFilePost();}  
  	elseif($action=='delete-file'&&in_array('files_categories_changes',$this->rights->povoleneKody)){$this->DeleteFilePost();}                                                                                           
  	elseif($action=='image-category-new-post'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->ImageCategoryNewPost();}   
  	elseif($action=='delete-image-category'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->ImageCategoryDeletePost();}                                                              
  	elseif($action=='list-images'&&in_array('images_categories_view',$this->rights->povoleneKody)){$this->PageListImages();}      
  	elseif($action=='edit-image-category'&&in_array('images_categories_view',$this->rights->povoleneKody)){$this->ImageCategoryEdit();} 
  	elseif($action=='image-new-post'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->ImageNewPost();}
  	elseif($action=='image-new-post-2'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->ImageNewPost2();}                                                            
  	elseif($action=='delete-image'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->DeleteImagePost();}      
  	elseif($action=='save-image'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->SaveImagePost();}                                                                                     
  	elseif($action=='image-save-category-post'&&in_array('images_categories_changes',$this->rights->povoleneKody)){$this->ImageCategoryEditPost();} 
    else{$this->Redirect();}        
    }
  /*
  * Levý sloupec
  */
  private function SetLeftMenu(){
    $menu=array(
      $this->Anchor(array('action'=>'list-images'))=>'<span class="icon"><i class="fa fa-file-image-o"></i></span> Obrázky a videa',
      $this->Anchor(array('action'=>'list-files'))=>'<span class="icon"><i class="fa fa-file-word-o"></i></span> Soubory',      
      );
    if(!in_array('files_categories_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-files'))]);
			}
		if(!in_array('images_categories_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-images'))]);
			}
    $active='list-images';
    if(in_array('files_categories_view',$this->rights->povoleneKody)&&!in_array('images_categories_view',$this->rights->povoleneKody)){
    	$active='list-files';
    	}
    if(getget('action','')=='list-files'){$active='list-files';}         
    if(getget('action','')=='edit-file-category'){$active='list-files';}         
    if(getget('action','')=='list-images'){$active='list-images';}            
    if(getget('action','')=='edit-image-category'){$active='list-images';}         
    if($active=='list-files'){
      $submenu=$this->kernel->models->DBfilesCategories->getLines('idfc,nazev','order by nazev');
      $idfc=(int)getget('idfc','0');
      foreach($submenu as $sk=>$sv){
        $submenu[$sk]->aedit=$this->Anchor(array('action'=>'edit-file-category','idfc'=>$sv->idfc),false);
        $submenu[$sk]->active=($idfc==$sv->idfc);        
        }      
      }
    if($active=='list-images'){
      $submenu=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
      $idic=(int)getget('idic','0');
      foreach($submenu as $sk=>$sv){
        $submenu[$sk]->aedit=$this->Anchor(array('action'=>'edit-image-category','idic'=>$sv->idic),false);
        $submenu[$sk]->active=($idic==$sv->idic);        
        }      
      }
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);    
    $tpl2->add('menu2',$submenu);    
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/files/leftMenu.tpl');        
    }     
  /*
  * Soubory
  */     
  private function PageListFiles(){
    $this->seo_title='Soubory';    
    $filescat=$this->kernel->models->DBfilesCategories->getLines('idfc,nazev','order by nazev');
    foreach($filescat as $fk=>$fv){
      $filescat[$fk]->filesCount=$this->kernel->models->DBfiles->getOne('count(idf)','WHERE id_fc="'.$fv->idfc.'"');      
      $filescat[$fk]->aedit=$this->Anchor(array('action'=>'edit-file-category','idfc'=>$fv->idfc),false);        
      $filescat[$fk]->adel=$this->Anchor(array('action'=>'delete-file-category','idfc'=>$fv->idfc),false);        
      }
    $tpl=new Templater();
    $tpl->add('filescat',$filescat);    
    $tpl->add('message',getget('message',''));
    $tpl->add('anew',$this->Anchor(array('action'=>'file-category-new-post'),false));         
    $this->content=$tpl->fetch('backend/files/listFiles.tpl');
    $this->SetLeftMenu();
    $this->execute();   
    }  
  private function FileCategoryNewPost(){
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'list-files','message'=>'not-created'),false);   
      }
    $ids=$this->kernel->models->DBfilesCategories->store(0,$postdata);  
    if($ids>0){   
      @mkdir('userfiles/files/'.$ids,0777);
      }      
    $this->Redirect(array('action'=>'list-files','message'=>'created','idfc'=>$ids),false);   
    }  
  private function FileCategoryDeletePost(){
    $ids=(int)getget('idfc','');
    if($ids>0){
      $files = array_diff(scandir('userfiles/files/'.$ids), array('.','..'));
      foreach ($files as $file) {
        if(is_dir('userfiles/files/'.$ids.'/'.$file)==false){
          @unlink('userfiles/files/'.$ids.'/'.$file);
          } 
        }
      @rmdir('userfiles/files/'.$ids);
      $this->kernel->models->DBfiles->deleteWhere('WHERE id_fc='.$ids);  
      $this->kernel->models->DBfilesCategories->deleteId($ids);  
      $this->Redirect(array('action'=>'list-files','message'=>'deleted','idfc'=>$ids),false);   
      }
    $this->Redirect(array('action'=>'list-files'),false);  
    }
  private function FileCategoryEditPost(){
    $idfc=(int)getget('idfc','');
    if($idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'category-not-saved','idfc'=>$idfc),false);   
      }
    $ids=$this->kernel->models->DBfilesCategories->store($idfc,$postdata);  
    $this->Redirect(array('action'=>'edit-file-category','message'=>'category-saved','idfc'=>$idfc),false);     
    }
  private function SaveFilePost(){
    $idfc=(int)getget('idfc','');
    $idf=(int)getget('idf','');
    if($idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    if($idf<1){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-not-found','idfc'=>$idfc),false);      
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-not-saved','idfc'=>$idfc),false);   
      }
    $ids=$this->kernel->models->DBfiles->store($idf,$postdata);  
    $this->Redirect(array('action'=>'edit-file-category','message'=>'file-saved','idfc'=>$idfc),false);
    }
  private function DeleteFilePost(){
    $idfc=(int)getget('idfc','');
    $idf=(int)getget('idf','');
    if($idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    if($idf<1){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-not-found','idfc'=>$idfc),false);      
      }
    $path=$this->kernel->models->DBfiles->getOne('cesta','WHERE idf="'.$idf.'"');
    @unlink($path);
    $ids=$this->kernel->models->DBfiles->deleteId($idf);  
    $this->Redirect(array('action'=>'edit-file-category','message'=>'file-deleted','idfc'=>$idfc),false);
    }
  private function FileCategoryEdit(){
    $this->seo_title='Soubory';        
    $idfc=(int)getget('idfc','');
    if($idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    $filescat=$this->kernel->models->DBfilesCategories->getLine('idfc,nazev','WHERE idfc="'.$idfc.'"');
    if($filescat->idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    $order='nazev asc';
    $getOrder=getget('order','name');
    if($getOrder=='name'){$order='nazev asc';}
    if($getOrder=='namedesc'){$order='nazev desc';}
    if($getOrder=='time'){$order='vytvoreni_timestamp	asc';}
    if($getOrder=='timedesc'){$order='vytvoreni_timestamp	desc';}    
    $files=$this->kernel->models->DBfiles->getLines('*','WHERE id_fc="'.$filescat->idfc.'" ORDER BY '.$order);
    foreach($files as $fk=>$fv){
      $files[$fk]->adel=$this->Anchor(array('action'=>'delete-file','idf'=>$fv->idf,'idfc'=>$filescat->idfc),false);
      $files[$fk]->asave=$this->Anchor(array('action'=>'save-file','idf'=>$fv->idf,'idfc'=>$filescat->idfc),false);    
      }
    $tpl=new Templater();
    $tpl->add('filescat',$filescat);    
    $tpl->add('files',$files);    
    $tpl->add('settings',$this->kernel->settings);    
    $tpl->add('getOrder',$getOrder);    
    $tpl->add('message',getget('message',''));
    $tpl->add('aorders',array(
      'name'=>$this->Anchor(array('action'=>'edit-file-category','order'=>'name','idfc'=>$filescat->idfc),false),
      'namedesc'=>$this->Anchor(array('action'=>'edit-file-category','order'=>'namedesc','idfc'=>$filescat->idfc),false),
      'time'=>$this->Anchor(array('action'=>'edit-file-category','order'=>'time','idfc'=>$filescat->idfc),false),
      'timedesc'=>$this->Anchor(array('action'=>'edit-file-category','order'=>'timedesc','idfc'=>$filescat->idfc),false)
      ));
    $tpl->add('anew',$this->Anchor(array('action'=>'file-new-post','idfc'=>$filescat->idfc),false));         
    $tpl->add('asavecat',$this->Anchor(array('action'=>'file-save-category-post','idfc'=>$filescat->idfc),false));         
    $tpl->add('aback',$this->Anchor(array('action'=>'list-files'),false));         
    $this->content=$tpl->fetch('backend/files/listFilesDetail.tpl');
    $this->SetLeftMenu();
    $this->execute();   
    }
  private function FileNewPost(){
    $idfc=(int)getget('idfc','');
    if($idfc<1){
      $this->Redirect(array('action'=>'list-files','message'=>'category-not-found'),false);      
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-not-created','idfc'=>$idfc),false);   
      }
    $fileManager=new fileManager(); 
    $uploaded=$fileManager->UploadFile('soubor',time(),'userfiles/files/'.$idfc.'/','by_array',explode(',',$this->kernel->settings['povolene_pripony_souboru']));
    if($uploaded==false){
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-not-created','idfc'=>$idfc),false);   
    }else{
      $postdata['vytvoreni_timestamp']=time(); 
      $postdata['id_fc']=$idfc;  
      $postdata['cesta']=$uploaded;  
      $idf=$this->kernel->models->DBfiles->store(0,$postdata);
      $this->Redirect(array('action'=>'edit-file-category','message'=>'file-created','idfc'=>$idfc,'idf'=>$idf),false);  
    }
    $this->Redirect(array('action'=>'edit-file-category','message'=>'file-created','idfc'=>$idfc),false);  
    }  
  /*
  * Obrázky
  */    
  private function PageListImages(){
    $this->seo_title='Obrázky';    
    $filescat=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    foreach($filescat as $fk=>$fv){
      $filescat[$fk]->filesCount=$this->kernel->models->DBimages->getOne('count(idi)','WHERE id_ic="'.$fv->idic.'"');      
      $filescat[$fk]->aedit=$this->Anchor(array('action'=>'edit-image-category','idic'=>$fv->idic),false);        
      $filescat[$fk]->adel=$this->Anchor(array('action'=>'delete-image-category','idic'=>$fv->idic),false);        
      }
    $tpl=new Templater();
    $tpl->add('filescat',$filescat);    
    $tpl->add('message',getget('message',''));
    $tpl->add('anew',$this->Anchor(array('action'=>'image-category-new-post'),false));         
    $this->content=$tpl->fetch('backend/files/listImages.tpl');
    $this->SetLeftMenu();
    $this->execute();   
    } 
  private function ImageCategoryNewPost(){
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'list-images','message'=>'not-created'),false);   
      }
    $ids=$this->kernel->models->DBimagesCategories->store(0,$postdata);  
    if($ids>0){   
      @mkdir('userfiles/images/'.$ids,0777);
    }else{     
      $this->Redirect(array('action'=>'list-images','message'=>'not-created'),false);   
    }      
    $this->Redirect(array('action'=>'list-images','message'=>'created','idic'=>$ids),false);   
    }  
  private function ImageCategoryDeletePost(){
    $ids=(int)getget('idic','');
    if($ids>0){
      $images = $this->kernel->models->DBimages->getLines('idi','WHERE id_ic="'.$ids.'"');
      foreach ($images as $i) {
        $this->kernel->models->DBimages->DeleteImage($i->idi);
        }
      @rmdir('userfiles/images/'.$ids);
      $this->kernel->models->DBimages->deleteWhere('WHERE id_ic='.$ids);  
      $this->kernel->models->DBimagesCategories->deleteId($ids);  
      $this->Redirect(array('action'=>'list-images','message'=>'deleted','idic'=>$ids),false);   
      }
    $this->Redirect(array('action'=>'list-images'),false);  
    }
  private function ImageCategoryEdit(){
    $this->seo_title='Obrázky';    
    $idic=(int)getget('idic','0');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);   
      }
    $imagescat=$this->kernel->models->DBimagesCategories->getLine('idic,nazev','WHERE idic="'.$idic.'"');
    if($imagescat->idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }
    $order='nazev asc';
    $getOrder=getget('order','name');
    if($getOrder=='name'){$order='nazev asc';}
    if($getOrder=='namedesc'){$order='nazev desc';}
    if($getOrder=='time'){$order='vytvoreni_timestamp	asc';}
    if($getOrder=='timedesc'){$order='vytvoreni_timestamp	desc';}    
    $images=$this->kernel->models->DBimages->getLines('*','WHERE id_ic="'.$imagescat->idic.'" ORDER BY '.$order);
    foreach($images as $fk=>$fv){
      $images[$fk]->sizes=$this->kernel->models->DBimages->AddSizes($fv->cesta);
      $images[$fk]->adel=$this->Anchor(array('action'=>'delete-image','idi'=>$fv->idi,'idic'=>$imagescat->idic),false);
      $images[$fk]->asave=$this->Anchor(array('action'=>'save-image','idi'=>$fv->idi,'idic'=>$imagescat->idic),false);    
      }
    $tpl=new Templater();
    $tpl->add('imagescat',$imagescat);    
    $tpl->add('images',$images);    
    $tpl->add('settings',$this->kernel->settings);    
    $tpl->add('getOrder',$getOrder);    
    $tpl->add('message',getget('message',''));
    $tpl->add('aorders',array(
      'name'=>$this->Anchor(array('action'=>'edit-image-category','order'=>'name','idic'=>$imagescat->idic),false),
      'namedesc'=>$this->Anchor(array('action'=>'edit-image-category','order'=>'namedesc','idic'=>$imagescat->idic),false),
      'time'=>$this->Anchor(array('action'=>'edit-image-category','order'=>'time','idic'=>$imagescat->idic),false),
      'timedesc'=>$this->Anchor(array('action'=>'edit-image-category','order'=>'timedesc','idic'=>$imagescat->idic),false)
      ));
    $tpl->add('anew',$this->Anchor(array('action'=>'image-new-post','idic'=>$imagescat->idic),false));         
    $tpl->add('anew2',$this->Anchor(array('action'=>'image-new-post-2','idic'=>$imagescat->idic),false));         
    $tpl->add('asavecat',$this->Anchor(array('action'=>'image-save-category-post','idic'=>$imagescat->idic),false));         
    $tpl->add('aback',$this->Anchor(array('action'=>'list-images'),false));         
    $this->content=$tpl->fetch('backend/files/listImagesDetail.tpl');    
    $this->SetLeftMenu();
    $this->execute();   
    }
  private function ImageNewPost(){
    $idic=(int)getget('idic','0');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);   
      }
    $imagescat=$this->kernel->models->DBimagesCategories->getLine('idic,nazev','WHERE idic="'.$idic.'"');
    if($imagescat->idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }    
    if($this->kernel->models->DBimages->CreateImage($_FILES["soubor"],$imagescat->idic,getpost('nazev',''),getpost('popis',''))==false){   
      $this->Redirect(array('action'=>'edit-image-category','message'=>'image-not-created','idic'=>$imagescat->idic),false);      
    }else{
      $this->Redirect(array('action'=>'edit-image-category','message'=>'image-created','idic'=>$imagescat->idic),false);
    }                  
    }
  private function ImageNewPost2(){
    $idic=(int)getget('idic','0');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);   
      }
    $imagescat=$this->kernel->models->DBimagesCategories->getLine('idic,nazev','WHERE idic="'.$idic.'"');
    if($imagescat->idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }    
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $popis=prepare_get_data_safely(getpost('popis',''));
    $youtube_adresa=prepare_get_data_safely(getpost('youtube_adresa',''));    
    $this->kernel->models->DBimages->store(0,array(      
      'nazev'=>$nazev,
      'popis'=>$popis,
      'youtube_adresa'=>$youtube_adresa,
      'je_youtube'=>'1',
      'id_ic'=>$imagescat->idic,
      'vytvoreni_timestamp'=>time()                       
      ));   
    $this->Redirect(array('action'=>'edit-image-category','message'=>'image-created','idic'=>$imagescat->idic),false);                                
    }
  private function DeleteImagePost(){
    $idic=(int)getget('idic','0');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);   
      }
    $imagescat=$this->kernel->models->DBimagesCategories->getLine('idic,nazev','WHERE idic="'.$idic.'"');
    if($imagescat->idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }
    $this->kernel->models->DBimages->DeleteImage(getget('idi',''));
    $this->Redirect(array('action'=>'edit-image-category','message'=>'image-deleted','idic'=>$imagescat->idic),false);           
    }
  private function SaveImagePost(){
    $idic=(int)getget('idic','0');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);   
      }
    $imagescat=$this->kernel->models->DBimagesCategories->getLine('idic,nazev','WHERE idic="'.$idic.'"');
    if($imagescat->idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }
    $idi=(int)getget('idi','0');
    if($idi<1){
      $this->Redirect(array('action'=>'edit-image-category','message'=>'image-not-found','idic'=>$imagescat->idic),false);
      } 
    $image=$this->kernel->models->DBimages->getLine('idi','WHERE idi="'.$idi.'"');
    if($image->idi<1){
      $this->Redirect(array('action'=>'edit-image-category','message'=>'image-not-found','idic'=>$imagescat->idic),false);      
      }           
    $nazev=prepare_get_data_safely(getpost('nazev',''));
    $popis=prepare_get_data_safely(getpost('popis',''));    
    $this->kernel->models->DBimages->store($idi,array(      
      'nazev'=>$nazev,
      'popis'=>$popis        
      ));
    $this->Redirect(array('action'=>'edit-image-category','message'=>'image-saved','idic'=>$imagescat->idic),false);      
    }
  private function ImageCategoryEditPost(){
    $idic=(int)getget('idic','');
    if($idic<1){
      $this->Redirect(array('action'=>'list-images','message'=>'category-not-found'),false);      
      }
    $postdata=array();    
    foreach($_POST as $k=>$v){
      $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
      }
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit-image-category','message'=>'category-not-saved','idic'=>$idic),false);   
      }
    $ids=$this->kernel->models->DBimagesCategories->store($idic,$postdata);  
    $this->Redirect(array('action'=>'edit-image-category','message'=>'category-saved','idic'=>$idic),false);     
    }
  }
?>
