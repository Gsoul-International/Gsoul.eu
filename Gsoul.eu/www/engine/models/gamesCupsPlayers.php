<?php

class DBgamesCupsPlayers extends Model {
    public function __construct() {
        $this->setTable('games_cups_players');
        $this->setPrimaryKey('idgcp');
    }
}
