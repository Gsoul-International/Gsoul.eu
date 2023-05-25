<?php

class DBgamesTournamentsChat extends Model {
    public function __construct() {
        $this->setTable('games_tournaments_chat');
        $this->setPrimaryKey('idtc');
    }
}
