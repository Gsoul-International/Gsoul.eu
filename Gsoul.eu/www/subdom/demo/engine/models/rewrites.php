<?php
class DBrewrites extends Model{
  public function __construct(){ 
    $this->setTable('rewrites');
    $this->setPrimaryKey('rid');    
    }
  public function AddEditRewrite($nice_url='',$module='',$item_id_name='',$item_id=0,$mainPage=false){ 
    if($mainPage==false){ 
      $nice_url=trim($nice_url);
      $module=trim($module);
      $item_id_name=trim($item_id_name);
      $item_id=(int)$item_id;     
      if($nice_url==''||$nice_url=='/'||$item_id<1||$item_id_name==''||$module==''){
        return false;
        }    
      $nice_url=GenerateNiceUrl($nice_url);
      $system_url='/?module='.$module.'&'.$item_id_name.'='.$item_id;       
      $rid=(int)$this->getOne('rid','WHERE module="'.$module.'" AND item_id="'.$item_id.'"');
      $this->store($rid,array('system_url'=>$system_url,'nice_url'=>$nice_url,'module'=>$module,'item_id'=>$item_id));    
    }else{ //pro hlavní stránku musíme použít výjímky:
      $nice_url='/';
      $system_url='/?module=FMainPages';
      $rid=(int)$this->getOne('rid','WHERE module="FMainPages" AND item_id="1"');
      $this->store($rid,array('system_url'=>$system_url,'nice_url'=>$nice_url,'module'=>$module,'item_id'=>'1'));    
    }               
    $this->RegenerateCacheFile();   
    return true;      
    }
  public function DeleteRewrite($module='',$item_id='',$regenerate_cash=true){
    $module=trim($module);
    $item_id=(int)$item_id;
    if($module==''||$item_id==0){
      return false;
      }
    $this->deleteWhere('WHERE module="'.$module.'" AND item_id="'.$item_id.'"');
    if($regenerate_cash==true){
      $this->RegenerateCacheFile(); 
      }
    return true;
    }
  public function DeleteRewrites($module='',$item_ids=array()){
    $module=trim($module);
    if(count($item_ids)==0||$module==''){
      return false;
      }  
    foreach($item_ids as $item_id){
      $this->DeleteRewrite($module,(int)$item_id,false);
      }
    $this->RegenerateCacheFile(); 
    return true;
    }
  public function RegenerateCacheFile(){        
    $data=$this->getLines('system_url,nice_url');
    $cache=fopen('engine/cache/rewrites.php','w');
    fwrite($cache,"<?php\n");
    fwrite($cache,'global $systemRewrites;'."\n");
    foreach($data as $d){
      fwrite($cache,'$systemRewrites["'.$d->system_url.'"]="'.$d->nice_url.'";');
      }
    fwrite($cache,"\n"."?".">");
    fclose($cache);    
    }  
  }
?>