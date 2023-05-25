<?php

class DBgamesTournamentsAlternatesPlayers extends Model {
    public function __construct() {
        $this->setTable('games_tournaments_alternates_players');
        $this->setPrimaryKey('idgtap');
    }
}
