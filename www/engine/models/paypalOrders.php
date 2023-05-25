<?php

class DBpaypalOrders extends Model {
    public function __construct() {
        $this->setTable('paypal_orders');
        $this->setPrimaryKey('idpo');
    }
}
