<?php
class FPages extends Module{
  public function Main(){
    $this->parent_module='Frontend';     
    $idp=(int)getget('idp',0);
    if($idp<1){      
      header('HTTP/1.0 404 Not Found', true, 404);
      header('Location: /sitemap/');
      die();
      $this->Redirect(array('module'=>'FSitemap'),false);
      } 
    $page=$this->kernel->models->DBpages->getLine('*','WHERE idp="'.$idp.'" limit 1');
    if($page->idp<1||$page->zobrazovat==0){      
      header('HTTP/1.0 404 Not Found', true, 404);
      header('Location: /sitemap/');
      die();
      $this->Redirect(array('module'=>'FSitemap'),false);
      }      
    $page->seo_title=trim($page->seo_title);
    $this->seo_title=$page->seo_title==''?$page->nazev:$page->seo_title;        
    $this->seo_keywords=$page->seo_keywords;        
    $this->seo_description=$page->seo_description;     
    if($page->zobrazovat_navigaci==1){
      $breadcrumb=$this->kernel->models->DBpages->returnBreadcrumb($page->idp);   
      foreach($breadcrumb as $kb=>$vb){
          if($vb->type==0){
            $breadcrumb[$kb]->link=$this->Anchor($vb->urlPieces);
          }elseif($vb->type==(-1)){
            $breadcrumb[$kb]->link='/';
          }
        }        
    }else{
      $breadcrumb=array();
    }   
    $page->obsah=$this->kernel->modules->FBoxes->replaceTextContent($page->obsah);
    $boxes=$this->kernel->boxesByParent;
    $submenu='';
    if(isset($boxes['menu_podclanku'])&&count($boxes['menu_podclanku'])>0){
      foreach($boxes['menu_podclanku'] as $b){
        $submenu.=$this->kernel->modules->FBoxes->getContent($b);
        }
      }
    $tpl=new Templater();
    $tpl->add('page',$page);
    $tpl->add('submenu',$submenu);        
    $tpl->add('breadcrumb',$breadcrumb);    
    $this->content=$tpl->fetch('frontend/page.tpl');  
    $this->execute();  
    }    
  }
?>