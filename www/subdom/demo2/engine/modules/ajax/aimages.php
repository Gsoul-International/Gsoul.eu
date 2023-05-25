<?php
/*
* Modul na vracení šablon do editoru.
*/ 
class AImages extends Module{
  public function __construct(){
    $this->parent_module='Ajax';
    }
  public function Main(){
    if($this->kernel->user->uid==0){  
      return '';         
    }else{
      if($this->kernel->user->data->prava<1){
        return '';  
        }              
    }
    $action=getget('action','list');
    if($action=='list'){$this->PageList();}      
    return '';           
    }
  public function PageList(){
    $submenu=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
    $idic=(int)getget('idic','0');
    $getOrder=getget('order','name');
    foreach($submenu as $sk=>$sv){
      $submenu[$sk]->aview=$this->kernel->config->domain_http.$this->Anchor(array('action'=>'list','order'=>$getOrder,'idic'=>$sv->idic),false);
      $submenu[$sk]->active=($idic==$sv->idic);        
      }  
    $files=array();      
    if($idic>0){
      $order='nazev asc';      
      if($getOrder=='name'){$order='nazev asc';}
      if($getOrder=='namedesc'){$order='nazev desc';}
      if($getOrder=='time'){$order='vytvoreni_timestamp	asc';}
      if($getOrder=='timedesc'){$order='vytvoreni_timestamp	desc';} 
      $files=$this->kernel->models->DBimages->getLines('*','WHERE id_ic="'.$idic.'" AND je_youtube!=1 ORDER BY '.$order);
      foreach($files as $fk=>$fv){        
        $files[$fk]->areturn=$this->kernel->config->domain_http.$this->Anchor(array('action'=>'return','idi'=>$fv->idi),false);    
        $files[$fk]->sizes=$this->kernel->models->DBimages->AddSizes($fv->cesta); 
        $files[$fk]->sizes2=array();
        foreach($files[$fk]->sizes as $sk=>$sv){
          $obj=new stdClass();
          $obj->src=$sv;
          $obj->key=$sk;
          $obj->key2=$this->kernel->imagesSizes[$sk];
          $obj->areturn=$this->Anchor(array('action'=>'return','idi'=>$fv->idi,'size'=>$sk),false);    
          $obj->areturn='/'.$sv;    
          $files[$fk]->sizes2[]=$obj;
          }   
        }  
      }      
    $tpl=new Templater();
    $tpl->add('submenu',$submenu);
    $tpl->add('getOrder',$getOrder);    
    $tpl->add('aorders',array(
      'name'=>$this->kernel->config->domain_http.$this->Anchor(array('action'=>'list','order'=>'name','idic'=>$idic),false),
      'namedesc'=>$this->kernel->config->domain_http.$this->Anchor(array('action'=>'list','order'=>'namedesc','idic'=>$idic),false),
      'time'=>$this->kernel->config->domain_http.$this->Anchor(array('action'=>'list','order'=>'time','idic'=>$idic),false),
      'timedesc'=>$this->kernel->config->domain_http.$this->Anchor(array('action'=>'list','order'=>'timedesc','idic'=>$idic),false)
      ));    
    $tpl->add('files',$files);    
    echo $tpl->fetch('ajax/images/list.tpl');
    }     
  }  
?>