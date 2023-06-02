<?php

class DBgamesParametersValues extends Model {
    public function __construct() {
        $this->setTable('games_parameters_values');
        $this->setPrimaryKey('idpv');
    }
}
