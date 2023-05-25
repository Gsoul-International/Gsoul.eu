<?php
if($_SERVER['SERVER_NAME']!='www.gsoul.cz'&&$_SERVER['SERVER_NAME']!='demo.gsoul.cz'){
  Header('Location: http://www.gsoul.cz/');
  exit();
  }
require __DIR__ . '/PayPal-PHP-SDK/autoload.php';
if(isset($_GET['mhm-error'])){if($_GET['mhm-error']=='1'){ ini_set('display_errors','1');error_reporting(E_ALL);}else{ini_set('display_errors','0');error_reporting(0);}}else{ ini_set('display_errors','0');error_reporting(0);}
require_once('engine/kernel.php');
$kernel=new Kernel('Frontend');