<?php

class DBlanguagesKeywords extends Model {
    public function __construct() {
        $this->setTable('languages_keywords');
        $this->setPrimaryKey('idlk');
    }
}
