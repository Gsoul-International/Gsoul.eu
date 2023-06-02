<?php
/*
* třídy k ovládání databáze
* třída Database je hlavní třídou
* pracujeme s databází typu mysqli
*/
Class Database{
  private $connector;
  private $log;
  public function __construct(){
    $this->log=array();    
    }
  /*
  * fce k připojení databáze
  */  
  public function Login($loginData=array()){
    $time_start = microtime(true);
    if(count($loginData)<1){
      return 'E_NOLOGINDATAGET';
      }    
    $this->connector=new mysqli($loginData['host'],$loginData['user'],$loginData['password'],$loginData['db']);
    if(empty($this->connector)||mysqli_connect_errno()){
      return mysqli_connect_error();
      }
    $this->connector->set_charset($loginData['charset']);
    $this->connector->query("SET collation_connection = ".$loginData['collation']);
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,'DB LOGIN + DB CHARSET');
    return 'E_SUCCESS';
    }
  /*
  * fce k odpojení databáze
  */  
  public function Logout(){
    $time_start = microtime(true);
    mysqli_close($this->connector);
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,'DB LOGOUT');
    }
  /*
  * fce k vrácení connectoru
  */    
  public function ReturnConnector(){
    return $this->connector;
    }
  /*
  * fce k vrácení logu
  */    
  public function ReturnLog(){
    return $this->log;
    }
  /*
  * fce k přidání dat do logu
  */    
  public function AddLog($time_start,$time_end,$query,$key='-'){
    $x=new stdClass();     
    $x->query=$query;
    $x->time_start=$time_start;
    $x->time_end=$time_end;
    $x->time_final=round((($time_end-$time_start)),6);  
    $x->inserted_key=$key;  
    $this->log[]=$x;
    unset($x);
    }
  /*
  * fce k jednoduchému příkazu
  */  
  public function Mquery($q=''){
    $time_start = microtime(true);
    try{
      $a=$this->connector->query($q);
    }catch (Exception $e) {$a=null;}
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    return $a;
    }
  /*
  * fce k vložení jednoho řádku do tabulky, vrací id záznamu
  */  
  public function MqueryInsert($q=''){
    $time_start = microtime(true);
    try{      
      $this->connector->query($q);    
      $key=$this->connector->insert_id;      
    }catch (Exception $e) {$key=0;}
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,$q,$key);
    return $key;    
    }
  /*
  * fce k vybrání jedné buňky z tabulky
  */  
  public function MqueryGetOne($q=''){
    $time_start = microtime(true);
    try{
      $result=$this->connector->query($q);    
      $object=$result->fetch_array();
      $result->close();
      }catch (Exception $e) {$object=array(0=>null);}
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    return $object[0]; 
    }
  /*
  * fce k vybrání jednoho řádku z tabulky
  * type=='object' vrací data v objektu 
  * type=='cokoli' vrací data v poli    
  */
  public function MqueryGetLine($q='',$type='object'){
    $time_start = microtime(true);
    try{      
      $result=$this->connector->query($q);      
      if($type=='object'){       
        $object=$result->fetch_object();        
      }else{        
        $object=$result->fetch_row();
      }
      $result->close();
    }catch (Exception $e) {$object=null;}
    $time_end = microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    return $object; 
    }
  /*
  * fce k vybrání více řádků z tabulky
  * type=='object' vrací data v poli objektů  
  * type=='cokoli' vrací data v poli polí   
  */
  public function MqueryGetLines($q='',$type='object'){    
    $time_start = microtime(true);
    $array=array();
    try{
      $result=$this->connector->query($q);
      if(isset($result)){
          if($type=='object'){
            while($x=$result->fetch_object()){
              $array[]=$x;
              }      
          }else{ 
            while($x=$result->fetch_row()){
              $array[]=$x;
              }                
          }
        }
      }catch (Exception $e) {}
    $time_end = microtime(true);    
    $this->AddLog($time_start,$time_end,$q);
    return $array;   
    }
  }
Class Model{
  public $kernel;
  private $table;
  public function __construct($table=''){ 
    $this->kernel=null;
    $this->primary_key=null;
    $this->table=$table;
    }
  public function setTable($table=''){
    $this->table=$table;
    }
  public function setPrimaryKey($key='id'){
    $this->primary_key=$key;
    }
  public function getTable(){
    return $this->table;
    } 
  public function getPrimaryKey(){
    return $this->primary_key;
    }    
  public function addKernel($xkernel){
      $this->kernel=&$xkernel;      
    }
  public function Mquery($q=''){
    return $this->kernel->ReturnDatabase()->Mquery($q);
    }
  public function MqueryInsert($q=''){
    return $this->kernel->ReturnDatabase()->MqueryInsert($q);
    }  
  public function MqueryGetOne($q=''){
    return $this->kernel->ReturnDatabase()->MqueryGetOne($q);
    }
  public function MqueryGetLine($q='',$type='object'){
    return $this->kernel->ReturnDatabase()->MqueryGetLine($q);
    }
  public function MqueryGetLines($q='',$type='object'){
    return $this->kernel->ReturnDatabase()->MqueryGetLines($q);
    }
  public function getOne($what='*',$where=''){
    return $this->MqueryGetOne('SELECT '.$what.' FROM '.$this->table.' '.$where);
    }
  public function getLine($what='*',$where='',$type='object'){
    return $this->MqueryGetLine('SELECT '.$what.' FROM '.$this->table.' '.$where,$type);
    }
  public function getLines($what='*',$where='',$type='object'){
    //echo 'SELECT '.$what.' FROM '.$this->table.' '.$where,$type."\n";
    return $this->MqueryGetLines('SELECT '.$what.' FROM '.$this->table.' '.$where,$type);
    }
  public function insert($array=array()){      
    return $this->MqueryInsert('INSERT INTO '.$this->table.' '.$this->Concat2($array));  
    }
  public function updateId($id=0,$array=array()){  
    if($id>0)    
      return $this->Mquery('UPDATE '.$this->table.' SET '.$this->Concat($array).' WHERE '.$this->primary_key.'='.((int)$id));  
    }
  public function updateWhere($where='WHERE 1=1',$array=array()){   
    return $this->Mquery('UPDATE '.$this->table.' SET '.$this->Concat($array).' '.$where);  
    }
  public function deleteId($id=0){
    return $this->mquery('DELETE FROM '.$this->table.' WHERE '.$this->primary_key.'='.((int)$id));        
    }
  public function deleteWhere($where='WHERE 1=1'){
    return $this->mquery('DELETE FROM '.$this->table.' '.$where);        
    }
  public function store($id=0,$array=array()){    
    $id=(int)$id;        
    if($id>0){
        $this->updateId($id,$array);
        $id2=$id;                
    }else{
        $id2=$this->insert($array);
    }
    return $id2;
    }
  public function Concat($arr){
    $s=array();
    foreach($arr as $a=>$b){
      $s[]=$a.'="'.addslashes($b).'"';
      }
    return implode(',',$s);
    }
  public function Concat2($arr){
    $s=array();
    $q=array();
    foreach($arr as $a=>$b){
      $s[]=$a;
      $q[]='"'.addslashes($b).'"';
      }
    return '('.implode(',',$s).') VALUES ('.implode(',',$q).')';
    }
  }
?>