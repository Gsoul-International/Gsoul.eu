<?php
class DBsettings extends Model{
  public function __construct(){ 
    $this->setTable('settings');
    $this->setPrimaryKey('ids');
    }
  public function RegenerateCacheFile(){        
    $data=$this->getLines('klic,hodnota');
    $cache=fopen('engine/cache/settings.php','w');
    fwrite($cache,"<?php\n");
    fwrite($cache,'global $systemSettings;'."\n");
    foreach($data as $d){
      fwrite($cache,'$systemSettings["'.$d->klic.'"]="'.str_replace('"','\"',$d->hodnota).'";');
      }
    fwrite($cache,"\n"."?".">");
    fclose($cache);    
    }  
  }
?>