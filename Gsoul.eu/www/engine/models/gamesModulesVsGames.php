<?php

class DBgamesModulesVsGames extends Model {
    public function __construct() {
        $this->setTable('games_modules_vs_games');
        $this->setPrimaryKey('idgm');
    }
}
