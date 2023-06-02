<?php

class DBgamesCupsParameters extends Model {
    public function __construct() {
        $this->setTable('games_cups_parameters');
        $this->setPrimaryKey('idgcp');
    }
}
