<?php
class DBlanguages extends Model{
  public function __construct(){ 
    $this->setTable('languages');
    $this->setPrimaryKey('idl');
    }
  public function RegenerateCacheFile(){          
    $data=$this->getLines('*',' WHERE aktivni=1 ORDER BY nazev');
    $cache=fopen('engine/cache/languages.php','w');
    fwrite($cache,"<?php\n");
    fwrite($cache,'global $systemLanguages;'."\n");
    foreach($data as $d){
      fwrite($cache,'$systemLanguages["'.$d->idl.'"]=new stdClass();');
      fwrite($cache,'$systemLanguages["'.$d->idl.'"]->nazev="'.str_replace('"','\"',$d->nazev).'";');
      fwrite($cache,'$systemLanguages["'.$d->idl.'"]->vychozi="'.$d->vychozi.'";');
      }    
    fclose($cache);       
    }  
  }
