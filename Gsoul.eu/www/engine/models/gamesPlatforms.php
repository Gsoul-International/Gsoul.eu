<?php

class DBgamesPlatforms extends Model {
    public function __construct() {
        $this->setTable('games_platforms');
        $this->setPrimaryKey('idgp');
    }
}
