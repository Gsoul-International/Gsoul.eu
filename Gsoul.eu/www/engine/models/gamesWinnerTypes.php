<?php

class DBgamesWinnerTypes extends Model {
    public function __construct() {
        $this->setTable('games_winner_types');
        $this->setPrimaryKey('idgwt');
    }
}
