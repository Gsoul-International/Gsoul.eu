<?php

class DBnewsletterLogouts extends Model {
    public function __construct() {
        $this->setTable('newsletter_logouts');
        $this->setPrimaryKey('idnl');
    }
}
