<?php
class DBlanguagesValues extends Model{
  public function __construct(){ 
    $this->setTable('languages_values');
    $this->setPrimaryKey('idlv');
    }
  public function RegenerateCacheFile($id){
    $id=(int)$id;
    if($id>0){
      $cache=fopen('engine/cache/translators_'.$id.'.php','w');
      fwrite($cache,"<?php\n");
      fwrite($cache,'global $systemTranslator;'."\n");
      $keys=$this->MqueryGetLines('SELECT idlk,klic FROM languages_keywords');
      $vals=$this->MqueryGetLines('SELECT idk,hodnota FROM languages_values WHERE idl="'.$id.'"');
      $vals2=array();
      foreach($vals as $v){
        $vals2[$v->idk]=$v->hodnota;      
        }
      foreach($keys as $k){
        $vx=isset($vals2[$k->idlk])?$vals2[$k->idlk]:'';
        fwrite($cache,'$systemTranslator["'.$k->klic.'"]="'.str_replace(array('"','\\'),array('\"',''),$vx).'";');    
        }
      fclose($cache);   
      }
    }
  }
