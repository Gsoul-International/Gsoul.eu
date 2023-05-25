<?php

class DBusers extends Model {
    public function __construct() {
        $this->setTable('users');
        $this->setPrimaryKey('uid');
    }
}

?>