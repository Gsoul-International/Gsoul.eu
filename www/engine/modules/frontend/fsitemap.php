<?php
class FSitemap extends Module{
  public function Main(){   
    if(getget('action','')=='xml-sitemap'){return $this->xmlSitemap();}  
    header('HTTP/1.0 404 Not Found', true, 404);
    $this->parent_module='Frontend';    
    $this->seo_title=$this->kernel->systemTranslator['obecne_stranka_nenalezena'];            
    $tpl=new Templater(); 
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/sitemap/sitemap.tpl');  
    $this->execute();  
    }  
  public function xmlSitemap(){        
    $mainpages=$this->kernel->models->DBmainPages->getLines('sitemap_priorita');
    $pages=$this->kernel->models->DBpages->getLines('idp,sitemap_priorita,typ,zobrazovat');          
    $news=$this->kernel->models->DBnews->getLines('idn,sitemap_priorita,zobrazovat');
    header("Content-Type:text/xml");  
    echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
    foreach($mainpages as $m){      
      echo '<url>';
      echo '<loc>'.$this->kernel->config->domain_http.'/</loc>';
      echo '<priority>'.$m->sitemap_priorita.'</priority>';
      echo '<changefreq>daily</changefreq>';
      echo '</url>'."\n";      
      }
    foreach($pages as $p){
      if($p->typ==0&&$p->zobrazovat==1){
        echo '<url>';
        echo '<loc>'.$this->kernel->config->domain_http.$this->Anchor(array('module'=>'FPages','idp'=>$p->idp),false).'</loc>';
        echo '<priority>'.$p->sitemap_priorita.'</priority>';
        echo '<changefreq>daily</changefreq>';
        echo '</url>'."\n";
        }   
      }
    if(count($news)>0){
      echo '<url>';
      echo '<loc>'.$this->kernel->config->domain_http.$this->Anchor(array('module'=>'FNews'),false).'</loc>';
      echo '<priority>0.7</priority>';
      echo '<changefreq>daily</changefreq>';
      echo '</url>'."\n";      
      foreach($news as $n){
        if($n->zobrazovat==1){
          echo '<url>';
          echo '<loc>'.$this->kernel->config->domain_http.$this->Anchor(array('module'=>'FNews','idn'=>$n->idn),false).'</loc>';
          echo '<priority>'.$n->sitemap_priorita.'</priority>';
          echo '<changefreq>daily</changefreq>';
          echo '</url>'."\n";
          }
        }
      }
    echo '</urlset>';
    }
  }
?>