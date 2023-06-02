<?php

class DBgamesTournamentsPrescores extends Model {
    public function __construct() {
        $this->setTable('games_tournaments_prescores');
        $this->setPrimaryKey('idgtps');
    }
}
