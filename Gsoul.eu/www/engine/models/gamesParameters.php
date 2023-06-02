<?php

class DBgamesParameters extends Model {
    public function __construct() {
        $this->setTable('games_parameters');
        $this->setPrimaryKey('idp');
    }
}
