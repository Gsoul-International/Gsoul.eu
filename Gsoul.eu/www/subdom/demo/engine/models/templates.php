<?php
class DBtemplates extends Model{
  public function __construct(){ 
    $this->setTable('templates');
    $this->setPrimaryKey('idt');
    }  
  }
?>