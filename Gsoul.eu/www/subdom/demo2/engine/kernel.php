<?php
/*třída s jádrem systému*/
require_once('engine/rewrites.php');
if(file_exists('engine/cache/rewrites.php')){require_once('engine/cache/rewrites.php');}
require_once('engine/settings.php');
if(file_exists('engine/cache/settings.php')){require_once('engine/cache/settings.php');}
require_once('engine/images_sizes.php');
if(file_exists('engine/cache/images_sizes.php')){require_once('engine/cache/images_sizes.php');} 
require_once('engine/languages.php');
if(file_exists('engine/cache/languages.php')){require_once('engine/cache/languages.php');} 
require_once('engine/config.php');
require_once('engine/kernel/database.php');
require_once('engine/kernel/module.php');
require_once('engine/kernel/baseFunctions.php');
class Kernel{
  public  $config;
  public  $settings;
  private $database;  
  public  $time_start;
  public  $user;    
  public  $models;
  public  $modules;
  public  $db; 
  public  $imagesSizes;  
  public  $languages;
  public  $active_language;
  public  $systemTranslator;
  public  $getedURL;  
  public  $boxesByParent=array();  
  public  $boxesByVariable=array();  
  static private $instance=NULL;  
  public function __construct($module=''){
    self::$instance=$this;
    $this->Init($module);
    }  
  public function __destruct(){$this->DeInit();}       
  static function getInstance(){return self::$instance;}
  /*hlavní inicializační funkce*/        
  public function Init($module=''){    
    $this->time_start=microtime(true);
    session_start();     
    $this->SetConfigData();
    @ini_set("memory_limit",$this->config->max_memmory_limit);    
    @set_time_limit($this->config->max_time_limit);
    $this->SetDatabase();       
    $this->getRewriteData();    
    $this->InitUser();
    $this->InitSettings();
    $this->InitLanguages();
    $this->InitImagesSizes();
    foreach($this->config->helpers as $h){require_once $h;}        
    $this->db=new Model();
    $this->db->addKernel($this->getInstance());
    $this->models=new stdClass();      
    foreach($this->config->models as $m_path=>$m_name){
      require_once $m_path;
      $this->models->$m_name=new $m_name();      
      } 
    foreach($this->config->models as $m_name){$this->models->$m_name->addKernel($this->getInstance());}    
    $this->modules=new stdClass();      
    foreach($this->config->modules as $m_path=>$m_name){
      require_once $m_path;
      $this->modules->$m_name=new $m_name();
      }
    foreach($this->config->modules as $m_name){$this->modules->$m_name->addKernel($this->getInstance(),$m_name);}     
    if(!in_array($module,$this->config->modules)){$module='';}       
    if(getget('module','')!=''){
      if(in_array(getget('module',''),$this->config->modules)){$module=getget('module','');}
      }
    if($module==''){$module='Frontend';}        
    $this->InitRedirect();         
    $this->modules->$module->Main();              
    }
  /*hlavní funkce pro inicializaci základních proměnných - settings*/  
  public function InitSettings(){
    global $systemSettings;
    $this->settings=array();
      if(count($systemSettings)>0){
        $this->settings=$systemSettings;
      }else{
      $data=$this->database->MqueryGetLines('SELECT klic,hodnota FROM settings');
      foreach($data as $d){      
        $this->settings[$d->klic]=$d->hodnota;
        }  
      }
    }   
  public function InitLanguages(){
    global $systemLanguages;
    global $systemTranslator;
    $this->active_language=0;
    $this->languages=array();
      if(count($systemLanguages)>0){
        $this->languages=$systemLanguages;
        foreach($systemLanguages as $lk=>$lv){
          if($lv->vychozi=="1"){
            $this->active_language=$lk;
            }
          }
      }else{
        $data=$this->database->MqueryGetLines('SELECT * FROM languages WHERE aktivni=1 ORDER BY nazev');
        foreach($data as $d){      
          $this->languages[$d->idl]=new stdClass();
          $this->languages[$d->idl]->nazev='"'.str_replace('"','\"',$d->nazev).'"';
          $this->languages[$d->idl]->vychozi='"'.$d->vychozi.'"';
          if($d->vychozi==1){
            $this->active_language=$d->idl;
            }
          }  
      }
    if(isset($_SESSION['language'])&&$_SESSION['language']>0&&isset($this->languages[$_SESSION['language']])){
      $this->active_language=$_SESSION['language'];
      }
    if($this->active_language>0){
      if(file_exists('engine/cache/translators_'.$this->active_language.'.php')){
        require_once('engine/cache/translators_'.$this->active_language.'.php');
        }
      }  
    $this->systemTranslator=$systemTranslator;    
    }
  public function InitImagesSizes(){
    global $systemImagesSizes;
    $this->imagesSizes=array();
      if(count($systemImagesSizes)>0){$this->imagesSizes=$systemImagesSizes;}
      if(count($systemImagesSizes)<=2){
        $data=$this->database->MqueryGetLines('SELECT idis,x,y FROM images_sizes order by x,y');
        foreach($data as $d){$this->imagesSizes[$d->idis]=$d->x.'x'.$d->y;}  
        }   
    }
  /*hlavní funkce pro inicializaci uživatele*/
  public function InitUser(){
    $this->user=new stdClass();   
    $data=$this->database->MqueryGetLine('SELECT * FROM users WHERE session="'.session_id().'" AND session!="" limit 1');
      if(isset($data->uid)&&$data->uid>0){
        $this->user->uid=$data->uid;
        unset($data->heslo);
        unset($data->heslo_2);
        $this->user->data=$data;   
        if($data->last_selected_language>0){
          $_SESSION['language']=$data->last_selected_language;
          }     
      }else{
        $this->user->uid=0;
        $this->user->data=array();         
      }
    }
  public function LoginUser($email='',$pass='',$redirectto='Frontend'){    
    $email=prepare_get_data_safely($email);
    $pass=prepare_get_data_safely($pass);
    if($email!=''&&$pass!=''){
      $data=$this->database->MqueryGetLine('SELECT uid,heslo,heslo_2,pocet_prihlaseni,registrace,last_selected_language FROM users WHERE email="'.trim($email).'" limit 1');
      if(isset($data->uid)&&$data->uid>0){
        $login=false;        
        if((saltHashSha($pass,$data->uid,$data->registrace,'SaltOfGSoulEU')==$data->heslo)&&$data->heslo!=''&&$pass!=''){$login=true;}
        if((saltHashSha($pass,$data->uid,$data->registrace,'SaltOfGSoulEU')==$data->heslo_2)&&$data->heslo_2!=''&&$pass!=''){$login=true;}        
          if($login==true){
            $this->database->Mquery('UPDATE users SET session="'.session_id().'",posledni_prihlaseni="'.time().'",posledni_prihlaseni_ip="'.$_SERVER['REMOTE_ADDR'].'",pocet_prihlaseni="'.($data->pocet_prihlaseni+1).'" WHERE uid="'.$data->uid.'"');
            if($data->last_selected_language>0){
              $_SESSION['language']=(int)$data->last_selected_language;
              }
            $this->modules->$redirectto->Redirect(array('module'=>$redirectto,'message'=>'user-success-login'));          
          }else{
            $this->modules->$redirectto->Redirect(array('module'=>$redirectto,'action'=>'userLogIn','LoginError'=>'1'));
          }
        }
      $this->modules->$redirectto->Redirect(array('module'=>$redirectto,'action'=>'userLogIn','LoginError'=>'1'));
      }
    $this->modules->$redirectto->Redirect(array('module'=>$redirectto,'action'=>'userLogIn','LoginError'=>'1'));
    }
  public function LogoutUser($redirectto='Frontend'){
    if($this->user->uid>0){$this->database->Mquery('UPDATE users SET session="" WHERE uid="'.$this->user->uid.'"');}
    $this->modules->$redirectto->Redirect(array('module'=>$redirectto));  
    }
  public function GetEditor($name='undefined',$value=''){return '<textarea rows="10" cols="80" class="editor" name="'.$name.'" id="editor_'.$name.'">'.$value.'</textarea>'."\n";}  
  /*hlavní deinicializační funkce*/
  public function DeInit(){
    $this->database->Logout();    
    $time_end = microtime(true);
    $dblogs=$this->database->ReturnLog();
    $memusage=convert_memory(memory_get_usage());    
    if(getget('mhm-info','')=='1'){      
      $dbTotalTime=0;
      foreach($dblogs as $dbl){$dbTotalTime+=$dbl->time_final;}
      $totaltime=round((($time_end-$this->time_start)),6);
      $this->InfoBox($memusage,$totaltime,$dbTotalTime,$dblogs);      
      }  
    }
  /*funkce na načtení konfiguračních dat*/    
  public function SetConfigData(){
    $conf=new Config();
    $this->config=$conf->GetData(); 
    }
  /*funkce na načtení databázového objektu a přihlášení k databázi*/  
  public function SetDatabase(){
    $this->database=new Database();
    $logged=$this->database->Login($this->config->database);
    $this->config->database=array();
    if($logged!='E_SUCCESS'){die('KERNEL_ERR_001: Unable to connect DB!');}    
    }
  public function ReturnDatabase(){return $this->database;}
  /*funkce, ktera se stara o to, aby pri zadani nepekne url adresy byl web presmerovan na peknou*/    
  public function InitRedirect(){    
    if(isset($_GET)){
      if(count($_GET)>0){
        if(!isset($_GET['mhm-error'])&&!isset($_GET['mhm-info'])&&!isset($_GET['mhm-vars'])){       
          $xget=$_GET;        
          if(isset($xget['module'])){if($xget['module']=='FMainPages'){$xget['module']='';}}         
          $defaultModule=new Module();
          $defaultModule->addKernel($this->getInstance());    
          $url=$defaultModule->Anchor($xget,false);
          if(trim($_SERVER['REQUEST_URI'])!=''&&trim($_SERVER['REQUEST_URI'])!='/'&&strlen($url)>1&&$url!='/?module='){          
            if(trim($url)!=trim($_SERVER['REQUEST_URI'])){                             
              Header("HTTP/1.1 301 Moved Permanently");
              Redirect2($url);
              }
            } 
          if(trim($_SERVER['REQUEST_URI'])!=''&&trim($_SERVER['REQUEST_URI'])!='/'&&strlen($url)>1&&$url=='/?module='){          
            if(trim($url)!=trim($_SERVER['REQUEST_URI'])){                                  
              Header("HTTP/1.1 301 Moved Permanently");
              Redirect2('/?module=FSitemap');
              }
            }
          }               
        }    
      }
    }
  /*funkce, která z přijaté nice url adresy implementuje GET proměnné*/ 
  public function getRewriteData(){
    $geturl=explode('?',trim($_SERVER['REQUEST_URI']));    
    $xget=array();           
    if($geturl[0][0]=='/'){
      $geturl[0][0]=' ';
      $geturl[0]=trim($geturl[0]);
      } 
    $this->getedURL=$geturl[0];     
    if($geturl[0]!=''){   
      if(in_array($geturl[0],$this->config->systemRewrites)){     
        $data=new stdClass();
        $data->rid=1;
        $data->system_url=array_search($geturl[0],$this->config->systemRewrites);
      }else{       
        $data=$this->database->MqueryGetLine('SELECT system_url,rid FROM rewrites WHERE nice_url="'.addslashes($geturl[0]).'" limit 1');
      }
      if(isset($data)){
        if($data->rid>0){
          $data2=explode('#',$data->system_url);    
          $data3=str_replace(array('/?','?'),array('&','&'),$data2[0]);
          $data4=explode('&',$data3);
          foreach($data4 as $d){      
            $data5=explode('=',$d);
            if($data5[0]!=''){
              if(!isset($_GET[$data5[0]])){$xget[$data5[0]]=$data5[1];}
              }
            }
          }      
      }
    }
    $_GET=array_merge($xget,$_GET);   //potrebujeme presne poradi kvuli rewrites  
    if(!isset($_GET['module'])){$_GET['module']='FMainPages';}
    if(trim($geturl[0])!=''&&(!isset($_GET['module'])||!in_array($_GET['module'],$this->config->modules))){$_GET['module']='FSitemap';}  
    }  
  /*funkce na výpis dotazů do databáze a informací ohledně průběhu načítání stránky*/  
  public function InfoBox($memusage='',$totaltime=0,$dbTotalTime=0,$dblogs=array()){
    echo '<div class="kernel_info_box"><table>';
    echo '<tr><th><b>Memory usage:</b></th><td>'.$memusage.'</td></tr>';
    echo '<tr><th><b>Total time load:</b></th><td>'.number_format($totaltime,6,',',' ').' sec</td></tr>';
    echo '<tr><th><b>DB queries time:</b></th><td>'.number_format($dbTotalTime,6,',',' ').' sec</td></tr>';                  
    echo '<tr><th><b>Time load without DB queries:</b></th><td>'.number_format(($totaltime-$dbTotalTime),6,',',' ').' sec</td></tr>';
    echo '<tr><th><b>DB queries count:</b></th><td>'.count($dblogs).'</td></tr>';
    echo '</table></div>';
    echo '<div class="kernel_info_box_2"><b>DB queries:</b><br><table>';
    foreach($dblogs as $dbl){echo '<tr><th>'.number_format($dbl->time_final,6,',',' ').' s.</th><td>'.$dbl->query.'</td></tr>';}
    echo '</table></div>';
    }
  }    
?>