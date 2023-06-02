<?php

class DBgamesCupsAlternatesPlayers extends Model {
    public function __construct() {
        $this->setTable('games_cups_alternates_players');
        $this->setPrimaryKey('idgcap');
    }
}