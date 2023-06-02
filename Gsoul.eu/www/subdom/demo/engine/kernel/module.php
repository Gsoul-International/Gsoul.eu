<?php
class Module{
  public $seo_title;  
  public $seo_keywords;
  public $seo_description;
  public $content;
  public $content2;
  public $need_user;
  public $user_rights;
  public $parent_module;
  public $data;
  public $kernel;
  public $module_name;
  public function __construct(){    
    $this->seo_title='';          //titulek stránky
    $this->seo_keywords='';       //klíčová slova stránky
    $this->seo_description='';    //description stránky
    $this->content='';            //obsah stránky   
    $this->content2='';           //obsah stránky 2   
    $this->need_user=0;           //nutnost přihlášeného uživatele k zobrazení stránky 0/1
    $this->user_rights=0;         //uživatel musí mít danné oprávnění
    $this->parent_module='';      //nadrazeny modul
    $this->kernel=null;           //instance kernelu
    $this->module_name='';
    }
  public function addKernel($xkernel,$xname=''){
      $this->kernel=&$xkernel;  
      $this->module_name=$xname;    
    }
  public function execute(){  
    if($this->need_user==1){
      if($this->kernel->user->uid<1){
        Redirect(array('ModuleError'=>'1'));
        }
      if($this->user_rights>0){
        if($this->kernel->user->uid<1){
          Redirect(array('ModuleError'=>'1'));
          }
        if($this->kernel->user->data->prava<$this->user_rights){
          Redirect(array('ModuleError'=>'2'));
          }    
        } 
      }  
     
    if($this->parent_module!=''){
      $this->SendData();
      }
    }
  public function SendData(){
    $parent_module=$this->parent_module;
    if($parent_module!=''){
      $data=array(
        'parent_module'=>$parent_module,
        'seo_title'=>$this->seo_title,
        'seo_keywords'=>$this->seo_keywords,
        'seo_description'=>$this->seo_description,
        'content'=>$this->content,
        'content2'=>$this->content2,
        'data'=>$this->data,
        );      
      $this->kernel->modules->$parent_module->RecieveData($data);      
      }
    }
  public function RecieveData($data=array()){
    if($data['parent_module']!=''){
      $this->seo_title=$data['seo_title'];
      $this->seo_keywords=$data['seo_keywords'];
      $this->seo_description=$data['seo_description'];
      $this->content=$data['content'];            
      $this->content2=$data['content2'];            
      $this->data=$data['data']; 
      $parent=$data['parent_module'];           
      $this->kernel->modules->$parent->Main();
      }
    }
  public function Main(){
    echo 'KERNEL_ERR_002: Main function undefined!';
    die();
    }
  public function Anchor($url=array(),$useDB=1, $base='/'){
    $return=$this->RewritePrepare($url,$useDB,$base); 
    return Anchor($return->url,$return->base);
    }
  public function Redirect($url=array(),$useDB=1, $base='/'){
    $return=$this->RewritePrepare($url,$useDB,$base);
    Redirect($return->url,$return->base);
    }
  public function RewritePrepare($url=array(),$useDB=1, $base='/'){
    $return=new stdClass();
    $addModule=true;
    foreach($url as $k=>$v){
      if($k=='module'&&$v!=''){
        $addModule=false;
        continue;
        }            
      }
    if($addModule==true){
      $url2=array_merge(array('module'=>$this->module_name),$url);      
    }else{
      $url2=$url;
    }  
    $testbase='/';
    $findbase='/';
    $datatounset=array();
    foreach($url2 as $ua=>$ub){
        if($testbase=='/'){
          $testbase.='?'.$ua.'='.$ub;  
        }else{
          $testbase.='&'.$ua.'='.$ub;
        }
        $datatounset[]=$ua;
        if(isset($this->kernel->config->systemRewrites[$testbase])){
          $data=$this->kernel->config->systemRewrites[$testbase];
        }else{
          if($useDB==1){  
            $data=trim($this->kernel->models->DBrewrites->getOne('nice_url','WHERE system_url="'.addslashes($testbase).'"'));
          }else{
            $data='';
            }      
        }
        if($data!=''){
          $findbase='/'.$data;
          foreach($datatounset as $dtu){
            if(isset($url2[$dtu])){
              unset($url2[$dtu]);            
              }          
            }
          }
      }
    $return->base=$findbase;
    $return->url=$url2;    
    return $return;
    }
  }
?>