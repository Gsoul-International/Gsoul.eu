<?php
class BPages extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    $this->rights=new stdClass();
    }
  public function Main(){
    $this->seo_title='Stránky';
    $this->rights=$this->getUserRights();
    if(!in_array('homepage_views',$this->rights->povoleneKody)&&!in_array('pages_views',$this->rights->povoleneKody)&&!in_array('news_view',$this->rights->povoleneKody)){
			$this->Redirect(array('module'=>'Backend'),false);
			}		
    $action=getget('action','main-pages');
    if(!in_array('homepage_views',$this->rights->povoleneKody)){
    	$action=getget('action','list');
    	}
    if(!in_array('homepage_views',$this->rights->povoleneKody)&&!in_array('pages_views',$this->rights->povoleneKody)){
    	$action=getget('action','list-news');
    	}
    if($action=='list'&&in_array('pages_views',$this->rights->povoleneKody)){$this->PageList();}      
    elseif($action=='new'&&in_array('pages_views',$this->rights->povoleneKody)){$this->PageNew();}      
    elseif($action=='new-post'&&in_array('pages_changes',$this->rights->povoleneKody)){$this->PageNewPost();} 
    elseif($action=='delete'&&in_array('pages_changes',$this->rights->povoleneKody)){$this->PageDelete();}
    elseif($action=='edit'&&in_array('pages_views',$this->rights->povoleneKody)){$this->PageEdit();}
    elseif($action=='save'&&in_array('pages_changes',$this->rights->povoleneKody)){$this->PageSave();}
    elseif($action=='save-order'&&in_array('pages_changes',$this->rights->povoleneKody)){$this->PageSaveOrder();}      
    elseif($action=='main-pages'&&in_array('homepage_views',$this->rights->povoleneKody)){$this->PageMainPages();}   
    elseif($action=='save-main-page'&&in_array('homepage_changes',$this->rights->povoleneKody)){$this->SaveMainPage();}                
    elseif($action=='list-news'&&in_array('news_view',$this->rights->povoleneKody)){$this->PageListNews();}
    elseif($action=='new-news'&&in_array('news_view',$this->rights->povoleneKody)){$this->PageNewNews();}
    elseif($action=='new-news-post'&&in_array('news_changes',$this->rights->povoleneKody)){$this->PageNewsNewPost();}
    elseif($action=='edit-news'&&in_array('news_view',$this->rights->povoleneKody)){$this->PageEditNews();}
    elseif($action=='save-news'&&in_array('news_changes',$this->rights->povoleneKody)){$this->PageSaveNews();}  
    elseif($action=='delete-news'&&in_array('news_changes',$this->rights->povoleneKody)){$this->PageDeleteNews();}          
    else{$this->Redirect();}    
    }
  private function SetLeftMenu(){
    $menu=array(
      $this->Anchor(array('action'=>'main-pages'))=>'<span class="icon"><i class="fa fa-home"></i></span> Úvodní stránka',
      $this->Anchor(array('action'=>'list'))=>'<span class="icon"><i class="fa fa-bars"></i></span> Správa stránek',
      $this->Anchor(array('action'=>'new'))=>'<span class="icon"><i class="fa fa-plus"></i></span> Přidat stránku',
      $this->Anchor(array('action'=>'list-news'))=>'<span class="icon"><i class="fa fa-bars"></i></span> Správa novinek',
      $this->Anchor(array('action'=>'new-news'))=>'<span class="icon"><i class="fa fa-plus"></i></span> Přidat novinku',                                  
      );
    if(!in_array('homepage_views',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'main-pages'))]);
			}
		if(!in_array('pages_views',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list'))]);
			unset($menu[$this->Anchor(array('action'=>'new'))]);
			}
		if(!in_array('news_view',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'list-news'))]);
			unset($menu[$this->Anchor(array('action'=>'new-news'))]);
			}	
		if(in_array('pages_views',$this->rights->povoleneKody)&&!in_array('pages_changes',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'new'))]);
			}
		if(in_array('news_view',$this->rights->povoleneKody)&&!in_array('news_changes',$this->rights->povoleneKody)){
			unset($menu[$this->Anchor(array('action'=>'new-news'))]);
			}
    $active='main-pages';
    if(!in_array('homepage_views',$this->rights->povoleneKody)){
    	$active='list';
    	}
    if(!in_array('homepage_views',$this->rights->povoleneKody)&&!in_array('pages_views',$this->rights->povoleneKody)){
    	$active='list-news';
    	}
    if(getget('action','')=='new'){$active='new';}          
    if(getget('action','')=='main-pages'){$active='main-pages';}
    if(getget('action','')=='list'){$active='list';}
    if(getget('action','')=='edit'){$active='list';}     
    if(getget('action','')=='list-news'){$active='list-news';}
    if(getget('action','')=='edit-news'){$active='list-news';}                     
    if(getget('action','')=='new-news'){$active='new-news';}
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$this->Anchor(array('action'=>$active)));  
    $this->content2=$tpl2->fetch('backend/pages/leftMenu.tpl');    
    }
  private function PageSaveNews(){
    $idn=(int)getget('idn','0');
    if($idn<1){
      $this->Redirect(array('action'=>'list-news','message'=>'page-not-found','idp'=>$idn),false);
      }
    $page=$this->kernel->models->DBnews->getLine('*','WHERE idn="'.$idn.'"');
    if(!isset($page)||$page->idn<1){
      $this->Redirect(array('action'=>'list-news','message'=>'page-not-found','idp'=>$idn),false);  
      }
    $postdata=array();   
    foreach($_POST as $k=>$v){
        if($k=='obsah'){
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely_editor($v);               
        }elseif($k=='datum'){
          $v=DateToTimestamp($v);
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
          $a=prepare_get_data_safely($k);
          $sesdata->$a=prepare_get_data_safely($v);
        }else{        
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        }    
      } 
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit-news','message'=>'name-required','idn'=>$idn),false);
      } 
    $this->kernel->models->DBnews->updateId($idn,$postdata);        
    $this->kernel->models->DBrewrites->AddEditRewrite('novinky/'.$postdata['nazev'].'-'.$idn.'/','FNews','idn',$idn);     
    $this->Redirect(array('action'=>'edit-news','message'=>'page-edited','idn'=>$idn),false);    
    }
  private function PageEditNews(){
    $this->seo_title='Editace novinky';
    $idn=(int)getget('idn','0');
    if($idn<1){
      $this->Redirect(array('action'=>'list-news','message'=>'page-not-found','idn'=>$idn),false);
      }
    $page=$this->kernel->models->DBnews->getLine('*','WHERE idn="'.$idn.'"');
    if(!isset($page)||$page->idn<1){
      $this->Redirect(array('action'=>'list-news','message'=>'page-not-found','idn'=>$idn),false);  
      }   
    $filescat=array();
    $filescat2=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    foreach($filescat2 as $fc){
      $filescat[$fc->idic]=$fc->nazev;
      }
    $images=$this->kernel->models->DBimages->getLines('*','ORDER BY id_ic,nazev');  
    $breadcrumb=$this->kernel->models->DBnews->returnBreadcrumb($page->idn,true,$this->kernel->systemTranslator);   
    foreach($breadcrumb as $kb=>$vb){
        if($vb->type==0){
          $breadcrumb[$kb]->link=$this->Anchor($vb->urlPieces);
        }elseif($vb->type==(-1)){
          $breadcrumb[$kb]->link='/';
        }
      } 
    $tpl=new Templater();
    $tpl->add('boxes',$this->kernel->models->DBboxes->ReturnVariableBoxes());    
    $tpl->add('page',$page);
    $tpl->add('filescat',$filescat);
    $tpl->add('pageBreadcrumb',$breadcrumb);
    $tpl->add('images',$images);                         
    $tpl->add('aback',$this->Anchor(array('action'=>'list-news')));          
    $tpl->add('aedit',$this->Anchor(array('action'=>'save-news','idn'=>$idn),false)); 
    $tpl->add('afrontend',$this->Anchor(array('module'=>'FNews','idn'=>(int)getget('idn','0'))));
    $tpl->add('afrontend2','/?module=FNews&idp='.(int)getget('idn','0'));    
    $tpl->add('obsah',$this->kernel->GetEditor('obsah',$page->obsah));
    $tpl->add('languages',$this->kernel->languages);     
    $this->content=$tpl->fetch('backend/pages/editNews.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function Paginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('action'=>'list-news','page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('action'=>'list-news','page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('action'=>'list-news','page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('action'=>'list-news','page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('action'=>'list-news','page'=>($page+1)),false);
    }    
    return $pages;          
    }
  private function PageListNews(){
    $this->seo_title='Novinky';
    $tpl=new Templater();    
    $tpl->add('anew',$this->Anchor(array('action'=>'new-news'),false)); 
    $page=(int)getget('page','0');
    $counter=10;              
    $news=$this->kernel->models->DBnews->getLines('idn,zobrazovat,nazev,datum,zobrazovat_datum,id_jazyka','order by datum desc LIMIT '.($page*$counter).', '.$counter);
    $news_count=$this->kernel->models->DBnews->getOne('count(idn)');
    $paginnator=$this->Paginnator($page,$news_count,$counter);
    foreach($news as $kn=>$vn){
      $news[$kn]->aedit=$this->Anchor(array('action'=>'edit-news','idn'=>$vn->idn));
      $news[$kn]->adel=$this->Anchor(array('action'=>'delete-news','idn'=>$vn->idn));
      }
    $tpl->add('news',$news);
    $tpl->add('paginnator',$paginnator);
    $tpl->add('languages',$this->kernel->languages);           
    $this->content=$tpl->fetch('backend/pages/listNews.tpl');
    $this->SetLeftMenu();
    $this->execute();        
    }
  private function PageNewNews(){
    $this->seo_title='Přidání novinky';
    $page=new stdClass();
    $page->obsah='';
    $page->predtext='';
    $page->datum=strftime('%d.%m.%Y',time());
    $page->parent_id=0;
    $page->sitemap_priorita=0.7;
    $page->zobrazovat=1;
    $page->zobrazovat_v_navigaci=1;
    $page->zobrazovat_navigaci=1;
    $page->zobrazovat_nadpis=1;    
    $page->zobrazovat_datum=1;
    if(isset($_SESSION['backend-new-news'])){
      $page=$_SESSION['backend-new-news'];
      }    
    $filescat=array();
    $filescat2=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    foreach($filescat2 as $fc){
      $filescat[$fc->idic]=$fc->nazev;
      }
    $images=$this->kernel->models->DBimages->getLines('*','ORDER BY id_ic,nazev'); 
    $tpl=new Templater();
    $tpl->add('filescat',$filescat);
    $tpl->add('images',$images);
    $tpl->add('boxes',$this->kernel->models->DBboxes->ReturnVariableBoxes());    
    $tpl->add('page',$page);    
    $tpl->add('aback',$this->Anchor(array('action'=>'list-news', false)));          
    $tpl->add('anew',$this->Anchor(array('action'=>'new-news-post'),false)); 
    $tpl->add('obsah',$this->kernel->GetEditor('obsah',$page->obsah));
    $tpl->add('anewpage',$this->Anchor(array('action'=>'edit-news','idn'=>(int)getget('idn','0')),false));   
    $tpl->add('languages',$this->kernel->languages);    
    $this->content=$tpl->fetch('backend/pages/newNews.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageNewsNewPost(){    
    $postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){
        if($k=='obsah'){
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely_editor($v);
          $a=prepare_get_data_safely_editor($k);
          $sesdata->$a=prepare_get_data_safely_editor($v);
        }elseif($k=='datum'){
          $v=DateToTimestamp($v);
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
          $a=prepare_get_data_safely($k);
          $sesdata->$a=prepare_get_data_safely($v);
        }else{        
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
          $a=prepare_get_data_safely($k);
          $sesdata->$a=prepare_get_data_safely($v);
        }    
      } 
    $_SESSION['backend-new-news']=$sesdata;
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'new-news','message'=>'name-required'),false);
      }
    unset($_SESSION['backend-new-news']);
    $idn=$this->kernel->models->DBnews->store(0,$postdata);         
    $this->kernel->models->DBrewrites->AddEditRewrite('novinky/'.$postdata['nazev'].'-'.$idn.'/','FNews','idn',$idn); 
    $this->Redirect(array('action'=>'new-news','message'=>'page-created','idn'=>$idn),false);  
    }
  private function PageList(){
    $tpl=new Templater();    
    $tpl->add('anew',$this->Anchor(array('action'=>'new'),false));     
    $tpl->add('asaveorder',$this->Anchor(array('action'=>'save-order'),false));     
    $tree=$this->kernel->models->DBpages->returnTree(0,'idp,parent_id,nazev,zobrazovat,id_jazyka');
    $tpl->add('tree',$this->getTreeOlList($tree,0));         
    $this->content=$tpl->fetch('backend/pages/list.tpl');
    $this->SetLeftMenu();
    $this->execute();        
    }
  private function getTreeOlList($tree,$parentID=0){
    $data=array();       
    foreach($tree as $kt=>$vt){     
        $d=$vt;
        $parent=$d->parent_id;
        if(isset($d->subtree)){
          $d->subtree=$this->getTreeOlList($d->subtree,$d->idp);          
        }else{
          $d->subtree='';
        }
        $d->aedit=$this->Anchor(array('action'=>'edit','idp'=>$d->idp),false);    
        $d->adel=$this->Anchor(array('action'=>'delete','idp'=>$d->idp),false);  
        $d->afrontend=$this->Anchor(array('module'=>'FPages','idp'=>$d->idp));                  
        $data[]=$d;        
      }
    $tpl=new Templater();
    $tpl->add('data',$data);     
    $tpl->add('parentID',$parentID); 
    return $tpl->fetch('backend/pages/treeOlList.tpl');
    }
  private function PageNew(){
    $this->seo_title='Přidání stránky';
    $page=new stdClass();
    $page->obsah='';
    $page->parent_id=0;
    $page->sitemap_priorita=0.7;
    $page->zobrazovat=1;
    $page->zobrazovat_v_navigaci=1;
    $page->zobrazovat_navigaci=1;
    $page->zobrazovat_nadpis=1;    
    if(isset($_SESSION['backend-new-page'])){
      $page=$_SESSION['backend-new-page'];
      }
    $tree=$this->kernel->models->DBpages->returnTree(0,'idp,parent_id,nazev');
    $treeSelect=$this->getTreeSelect($tree,(int)$page->parent_id,1);
    $filescat=array();
    $filescat2=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    foreach($filescat2 as $fc){
      $filescat[$fc->idic]=$fc->nazev;
      }
    $images=$this->kernel->models->DBimages->getLines('*','ORDER BY id_ic,nazev'); 
    $tpl=new Templater();
    $tpl->add('filescat',$filescat);
    $tpl->add('images',$images);
    $tpl->add('boxes',$this->kernel->models->DBboxes->ReturnVariableBoxes());    
    $tpl->add('page',$page);
    $tpl->add('parentsTree',$treeSelect);   
    $tpl->add('aback',$this->Anchor(array('action'=>'list', false)));          
    $tpl->add('anew',$this->Anchor(array('action'=>'new-post'),false)); 
    $tpl->add('obsah',$this->kernel->GetEditor('obsah',$page->obsah));
    $tpl->add('anewpage',$this->Anchor(array('action'=>'edit','idp'=>(int)getget('idp','0')),false)); 
    $tpl->add('languages',$this->kernel->languages);      
    $this->content=$tpl->fetch('backend/pages/new.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function getTreeSelect($tree=array(), $selected=0,$firstloop=1,$level=0){
    if($firstloop==1){
      $content='<option value="0"> - Tato stránka nebude podstránkou - </option>';
    }else{
      $content='';
    }
    $TextPadding='';
    for($i=0;$i<$level;$i++){
      $TextPadding.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      }
    foreach($tree as $t){
      if($t->idp==$selected){
        $content.='<option value="'.$t->idp.'" selected> '.$TextPadding.$t->nazev.' </option>';
      }else{
        $content.='<option value="'.$t->idp.'"> '.$TextPadding.$t->nazev.' </option>';
      }
      if(isset($t->subtree)){
        $content.=$this->getTreeSelect($t->subtree, $selected,0,$level+1);
        }
      }
    return $content;
    }
  private function PageNewPost(){    
    $postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){
        if($k=='obsah'){
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely_editor($v);
          $a=prepare_get_data_safely_editor($k);
          $sesdata->$a=prepare_get_data_safely_editor($v);
        }else{        
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
          $a=prepare_get_data_safely($k);
          $sesdata->$a=prepare_get_data_safely($v);
        }    
      } 
    $_SESSION['backend-new-page']=$sesdata;
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'new','message'=>'name-required'),false);
      }
    $_SESSION['backend-new-page']=new stdClass();
    $_SESSION['backend-new-page']->sitemap_priorita=0.7;
    $_SESSION['backend-new-page']->obsah='';
    $_SESSION['backend-new-page']->parent_id=0;  
    $_SESSION['backend-new-page']->zobrazovat=1;
    $_SESSION['backend-new-page']->zobrazovat_v_navigaci=1;
    $_SESSION['backend-new-page']->zobrazovat_navigaci=1;
    $_SESSION['backend-new-page']->zobrazovat_nadpis=1;   
    $postdata['poradi']=$this->kernel->models->DBpages->getOne('max(poradi)')+1;
    $idp=$this->kernel->models->DBpages->store(0,$postdata);         
    $this->kernel->models->DBrewrites->AddEditRewrite(''.$postdata['nazev'].'-'.$idp.'/','FPages','idp',$idp); 
    $this->Redirect(array('action'=>'new','message'=>'page-created','idp'=>$idp),false);  
    }
  private function PageDelete(){
    $idp=(int)getget('idp','0');
    if($idp<1){
      $this->Redirect(array('action'=>'list','message'=>'page-not-found','idp'=>$idp),false);
      }
    $idpToDelete=array();
    $treeIds=$this->kernel->models->DBpages->returnTreeSubData($idp,'idp,parent_id');      
    foreach($treeIds as $ti){
      $idpToDelete[]=$ti->idp;
      }
    if(count($idpToDelete)>0){      
      $this->kernel->models->DBpages->deleteWhere('WHERE idp in ('.implode(',',$idpToDelete).')');      
      $this->kernel->models->DBrewrites->DeleteRewrites('FPages',$idpToDelete);    
      }
    $this->Redirect(array('action'=>'list','message'=>'page-deleted','idp'=>$idp),false);
    }
  private function PageDeleteNews(){
    $idn=(int)getget('idn','0');
    if($idn<1){
      $this->Redirect(array('action'=>'list-news','message'=>'page-not-found','idn'=>$idn),false);
      }
    $idpToDelete=array($idn);    
    if(count($idpToDelete)>0){      
      $this->kernel->models->DBnews->deleteWhere('WHERE idn in ('.implode(',',$idpToDelete).')');      
      $this->kernel->models->DBrewrites->DeleteRewrites('Fnews',$idpToDelete);    
      }
    $this->Redirect(array('action'=>'list-news','message'=>'page-deleted','idn'=>$idn),false);
    }
  private function PageEdit(){
    $this->seo_title='Editace stránky';
    $idp=(int)getget('idp','0');
    if($idp<1){
      $this->Redirect(array('action'=>'list','message'=>'page-not-found','idp'=>$idp),false);
      }
    $page=$this->kernel->models->DBpages->getLine('*','WHERE idp="'.$idp.'"');
    if(!isset($page)||$page->idp<1){
      $this->Redirect(array('action'=>'list','message'=>'page-not-found','idp'=>$idp),false);  
      }
    $tree=$this->kernel->models->DBpages->returnTree(0,'idp,parent_id,nazev');
    $treeSelect=$this->getTreeSelect($tree,(int)$page->parent_id,1);
    $breadcrumb=$this->kernel->models->DBpages->returnBreadcrumb($page->idp);   
    foreach($breadcrumb as $kb=>$vb){
        if($vb->type==0){
          $breadcrumb[$kb]->link=$this->Anchor($vb->urlPieces);
        }elseif($vb->type==(-1)){
          $breadcrumb[$kb]->link='/';
        }
      } 
    $filescat=array();
    $filescat2=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    foreach($filescat2 as $fc){
      $filescat[$fc->idic]=$fc->nazev;
      }
    $images=$this->kernel->models->DBimages->getLines('*','ORDER BY id_ic,nazev');  
    $tpl=new Templater();
    $tpl->add('boxes',$this->kernel->models->DBboxes->ReturnVariableBoxes());    
    $tpl->add('page',$page);
    $tpl->add('filescat',$filescat);
    $tpl->add('images',$images);
    $tpl->add('parentsTree',$treeSelect);       
    $tpl->add('pageBreadcrumb',$breadcrumb);                          
    $tpl->add('aback',$this->Anchor(array('action'=>'list')));          
    $tpl->add('aedit',$this->Anchor(array('action'=>'save','idp'=>$idp),false)); 
    $tpl->add('afrontend',$this->Anchor(array('module'=>'FPages','idp'=>(int)getget('idp','0'))));
    $tpl->add('afrontend2','/?module=FPages&idp='.(int)getget('idp','0'));    
    $tpl->add('obsah',$this->kernel->GetEditor('obsah',$page->obsah));
    $tpl->add('languages',$this->kernel->languages);       
    $this->content=$tpl->fetch('backend/pages/edit.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function PageSave(){
    $idp=(int)getget('idp','0');
    if($idp<1){
      $this->Redirect(array('action'=>'list','message'=>'page-not-found','idp'=>$idp),false);
      }
    $page=$this->kernel->models->DBpages->getLine('*','WHERE idp="'.$idp.'"');
    if(!isset($page)||$page->idp<1){
      $this->Redirect(array('action'=>'list','message'=>'page-not-found','idp'=>$idp),false);  
      }
    $postdata=array();   
    foreach($_POST as $k=>$v){
        if($k=='obsah'){
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely_editor($v);               
        }else{        
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        }    
      } 
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'edit','message'=>'name-required','idp'=>$idp),false);
      } 
    $this->kernel->models->DBpages->updateId($idp,$postdata);        
    $this->kernel->models->DBrewrites->AddEditRewrite(''.$postdata['nazev'].'-'.$idp.'/','FPages','idp',$idp);     
    $this->Redirect(array('action'=>'edit','message'=>'page-edited','idp'=>$idp),false);    
    }
  private function PageSaveOrder(){
    $order=trim(prepare_get_data_safely(getpost('order','')));
    if($order==''){
      $this->Redirect(array('action'=>'list','message'=>'order-is-same'),false);  
      }
    $td=$this->kernel->models->DBpages->getLines('idp,parent_id,poradi');
    $data=array();
    foreach($td as $ktd=>$vtd){
      $data[$vtd->idp]=$vtd;    
      }
    $orders=explode(',',$order);
    $poradi=1;
    foreach($orders as $o){
      if(trim($o)==''){
        continue;
        }
      $ox=explode('=',$o);
      $parent=(int)$ox[0];
      $idp=(int)$ox[1];
      if($idp>0){
        if($data[$idp]->parent_id!=$parent||$data[$idp]->poradi!=$poradi){
          $this->kernel->models->DBpages->updateId($idp,array('parent_id'=>$parent,'poradi'=>$poradi));                   
          }        
        $poradi++;
        }                  
      }        
    $this->Redirect(array('action'=>'list','message'=>'order-saved'),false);      
    }
  private function PageMainPages(){
    $this->seo_title='Úvodní stránky';
    $mainpages=array();    
    $page=new stdClass();
    $page->idmp=0;        
    $page->nazev='Úvodní stránka';
    $page->obsah='';
    $page->sitemap_priorita=0.7;
    $page->zobrazovat_v_navigaci=1;
    $page->zobrazovat_navigaci=1;
    $page->zobrazovat_nadpis=1;  
    $dbPage=$this->kernel->models->DBmainPages->getLine('*','limit 1');
    if($dbPage->idmp>0){$page=$dbPage;}
    $page->editor=$this->kernel->GetEditor('obsah',$page->obsah);    
    $mainpages[]=$page;          
    $tpl=new Templater();
    $tpl->add('mainpages',$mainpages);
    $tpl->add('boxes',$this->kernel->models->DBboxes->ReturnVariableBoxes());
    $tpl->add('asave',$this->Anchor(array('action'=>'save-main-page'),false));
    $this->content=$tpl->fetch('backend/pages/mainPages.tpl');
    $this->SetLeftMenu();
    $this->execute();    
    }
  private function SaveMainPage(){
    $postdata=array();   
    foreach($_POST as $k=>$v){
        if($k=='obsah'){
          $postdata['obsah']=prepare_get_data_safely_editor($v);               
        }else{        
          $postdata[prepare_get_data_safely($k)]=prepare_get_data_safely($v);
        }    
      } 
    if($postdata['nazev']==''){
      $this->Redirect(array('action'=>'main-pages','message'=>'name-required','idp'=>$idp),false);
      } 
    $idmp=$postdata['idmp'];
    unset($postdata['idmp']);
    $dbPage=$this->kernel->models->DBmainPages->store($idmp,$postdata);
    $this->kernel->models->DBrewrites->AddEditRewrite(''.$postdata['nazev'].'-'.$idmp.'/','FMainPages','idmp',$idmp,true);     
    $this->Redirect(array('action'=>'main-pages','message'=>'page-saved'),false); 
    }
  }
?>
