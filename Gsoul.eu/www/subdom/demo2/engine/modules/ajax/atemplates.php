<?php
/*
* Modul na vracení šablon do editoru.
*/ 
class ATemplates extends Module{
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
    $templates=$this->kernel->models->DBtemplates->getLines('*','ORDER BY nazev');    
    $tpl=new Templater();
    $tpl->add('templates',$templates);    
    echo $tpl->fetch('ajax/templates/list.tpl');
    }   
  }  
?>