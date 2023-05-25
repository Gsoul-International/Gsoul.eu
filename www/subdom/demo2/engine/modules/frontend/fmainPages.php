<?php
class FMainPages extends Module{
  public function Main(){        
    $this->parent_module='Frontend';       
    $page=$this->kernel->models->DBmainPages->getLine('*','limit 1'); 
    $page->obsah=$this->kernel->modules->FBoxes->replaceTextContent($page->obsah);      
    $page->seo_title=trim($page->seo_title);
    $this->seo_title=$page->seo_title==''?$page->nazev:$page->seo_title;        
    $this->seo_keywords=$page->seo_keywords;        
    $this->seo_description=$page->seo_description;        
    $tpl=new Templater();
    $tpl->add('page',$page);    
    $this->content=$tpl->fetch('frontend/mainPage.tpl');  
    $this->execute();  
    }   
  }
?>