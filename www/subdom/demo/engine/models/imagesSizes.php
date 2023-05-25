<?php
class DBimagesSizes extends Model{
  public function __construct(){ 
    $this->setTable('images_sizes');
    $this->setPrimaryKey('idis');
    }
  public function RegenerateCacheFile(){        
    $data=$this->getLines('*','ORDER BY x,y');
    $cache=fopen('engine/cache/images_sizes.php','w');
    fwrite($cache,"<?php\n");
    fwrite($cache,'global $systemImagesSizes;'."\n");
    foreach($data as $d){
      fwrite($cache,'$systemImagesSizes["'.$d->x.'x'.$d->y.'"]="'.$d->x.'x'.$d->y.'";');      
      }
    fwrite($cache,"?".">");
    fclose($cache);    
    }  
  }
?>