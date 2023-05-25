<?php

class DBfiles extends Model{
  public function __construct(){ 
    $this->setTable('files');
    $this->setPrimaryKey('idf');
    }
  }
?>