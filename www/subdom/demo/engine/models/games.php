<?php
class DBgames extends Model{
  public function __construct(){ 
    $this->setTable('games');
    $this->setPrimaryKey('idg');
    }
  }