<?php

class DBfilesCategories extends Model{
  public function __construct(){ 
    $this->setTable('files_categories');
    $this->setPrimaryKey('idfc');
    }
  }
?>