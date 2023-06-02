<?php

class DBgamesTournamentsParameters extends Model {
    public function __construct() {
        $this->setTable('games_tournaments_parameters');
        $this->setPrimaryKey('idgtp');
    }
}
