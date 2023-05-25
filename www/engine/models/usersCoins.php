<?php

class DBusersCoins extends Model {
    public function __construct() {
        $this->setTable('users_coins');
        $this->setPrimaryKey('iduc');
    }
}
