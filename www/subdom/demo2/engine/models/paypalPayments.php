<?php
class DBpaypalPayments extends Model{
  public function __construct(){ 
    $this->setTable('paypal_payments');
    $this->setPrimaryKey('id');
    }
  }