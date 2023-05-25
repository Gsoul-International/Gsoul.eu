<?php
class DBmainPages extends Model{
  public function __construct(){ 
    $this->setTable('main_pages');
    $this->setPrimaryKey('idmp');
    }  
  }
?>