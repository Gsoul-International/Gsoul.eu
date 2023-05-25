<?php
class FNews extends Module{
  public function Main(){
    $this->parent_module='Frontend';     
    $idn=(int)getget('idn',0);     
    if($idn>0){
      $page=$this->kernel->models->DBnews->getLine('*','WHERE idn="'.$idn.'" limit 1');
      if($page->idn<1||$page->zobrazovat==0){      
        header('HTTP/1.0 404 Not Found',true,404);
        header('Location: /sitemap/');
        die();
        $this->Redirect(array('module'=>'FSitemap'),false);
        }      
      $page->seo_title=trim($page->seo_title);
      $this->seo_title=$page->seo_title==''?$page->nazev:$page->seo_title;        
      $this->seo_keywords=$page->seo_keywords;        
      $this->seo_description=$page->seo_description;     
      if($page->zobrazovat_navigaci==1){
        $breadcrumb=$this->kernel->models->DBnews->returnBreadcrumb($page->idn);   
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
      $image=$this->kernel->models->DBimages->getLine('*','WHERE idi='.((int)$page->id_obrazku));    
      $tpl=new Templater();
      $tpl->add('page',$page);
      $tpl->add('image',$image);
      $tpl->add('breadcrumb',$breadcrumb);   
      $tpl->add('aback',$this->Anchor(array())); 
      $this->content=$tpl->fetch('frontend/news/detail.tpl');  
    }else{
      $this->seo_title='Novinky';
      $this->seo_keywords='Novinky';
      $this->seo_description='Novinky';
      $breadcrumb=$this->kernel->models->DBnews->returnBreadcrumb(0);   
      foreach($breadcrumb as $kb=>$vb){
          if($vb->type==0){
            $breadcrumb[$kb]->link=$this->Anchor($vb->urlPieces);
          }elseif($vb->type==(-1)){
            $breadcrumb[$kb]->link='/';
          }
        } 
      $page=(int)getget('page','0');
      $counter=9;
      $news=$this->kernel->models->DBnews->getLines('idn,zobrazovat,nazev,datum,predtext,id_obrazku,zobrazovat_datum','WHERE zobrazovat=1 order by datum desc LIMIT '.($page*$counter).', '.$counter);         
      $images=array();
      $images2=array();               
      $news_count=$this->kernel->models->DBnews->getOne('count(idn)','WHERE zobrazovat=1'); 
      $paginnator=$this->Paginnator($page,$news_count,$counter);
      foreach($news as $kn=>$vn){
        $news[$kn]->alink=$this->Anchor(array('idn'=>$vn->idn));
        $images2[]=$vn->id_obrazku;        
        }  
      if(count($images2)>0){
        $images3=$this->kernel->models->DBimages->getLines('*','WHERE idi in ('.implode(',',$images2).') ORDER BY id_ic,nazev');  
        unset($images2);
        foreach($images3 as $i3){
          $images[$i3->idi]=$i3;
          }
        unset($images3);          
        }     
      $tpl=new Templater();      
      $tpl->add('breadcrumb',$breadcrumb);
      $tpl->add('news',$news);
      $tpl->add('paginnator',$paginnator);
      $tpl->add('images',$images);     
      $this->content=$tpl->fetch('frontend/news/list.tpl');   
    }        
    $this->execute();  
    }
  private function Paginnator($page=0,$count=0,$counter=0){        
    $pages=array();
    $maxpage=0;
    for($i=0;$i<ceil($count/$counter);$i++){
      $pages[($i+1)]=$this->Anchor(array('page'=>$i),false);
      $maxpage=$i;
      }
    if(($page-1)<0){
      $pages['prew']=$this->Anchor(array('page'=>'0'),false);
    }else{
      $pages['prew']=$this->Anchor(array('page'=>($page-1)),false);
    }
    if(($page+1)>$maxpage){
      $pages['next']=$this->Anchor(array('page'=>$maxpage),false);
    }else{
      $pages['next']=$this->Anchor(array('page'=>($page+1)),false);
    }    
    return $pages;          
    }    
  }
?>