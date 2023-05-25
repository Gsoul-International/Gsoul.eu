<?php

class DBboxesCategories extends Model{
  public function __construct(){ 
    $this->setTable('boxes_categories');
    $this->setPrimaryKey('idbc');
    }
  }
?>