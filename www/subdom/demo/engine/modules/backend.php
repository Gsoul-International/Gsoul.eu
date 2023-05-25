<?php
class Backend extends Module{
  public function Main(){
    $action=getget('action','');
    if($action=='login'){$this->LogIn();}
    if($action=='logout'){$this->LogOut();}    
      if($this->kernel->user->uid==0){  
        $this->PageLogin();              
      }else{
        if($this->kernel->user->data->prava<1){
          Redirect();
          }
        $this->MainPage();        
      }          
    }  
  private function MainPage(){
    $content='';
    $leftMenu='';
    $topMenu=array();
    foreach($this->kernel->modules as $km=>$vm){
      if($vm->parent_module=='Backend'){  
        if($this->kernel->user->data->prava>=$vm->user_rights){     
          $obj=new stdClass();
          $obj->module=$km;
          $obj->icon=$this->kernel->config->modulesIcons[$km];
          $obj->name=$this->kernel->config->modulesNames[$km];
          $obj->ahref=$this->Anchor(array('module'=>$km));
          if(getget('module')==$km){
            $obj->active=1;
          }else{
            $obj->active=0;
          }
          $topMenu[]=$obj;      
          }
        }
      }
    if(getget('module')=='Backend'){            
      $this->seo_title='MHMcube Administrace';
      $this->seo_keywords='MHMcube Administrace';
      $this->seo_description='MHMcube Administrace';
      $tplContent=new Templater();          
      $content=$tplContent->fetch('backend/defaultContent.tpl');
      $tplLeftMenu=new Templater();          
      $leftMenu=$tplLeftMenu->fetch('backend/defaultLeftMenu.tpl');
    }else{
      $content=$this->content;
      $leftMenu=$this->content2;
      }
    $tpl=new Templater();
    $tpl->add('abackend',$this->Anchor(array('module'=>'Backend')));
    $tpl->add('alogout',$this->Anchor(array('module'=>'Backend','action'=>'logout')));
    $tpl->add('user',$this->kernel->user->data);
    $tpl->add('topMenu',$topMenu);
    $tpl->add('content',$content);
    $tpl->add('leftMenu',$leftMenu);
    $tpl->add('SEO_title',$this->kernel->config->title['prefix'].$this->seo_title.$this->kernel->config->title['suffix']);
    $tpl->add('SEO_keywords',$this->seo_keywords);
    $tpl->add('SEO_description',$this->seo_description);         
    $tpl->add('time_start',$this->kernel->time_start);  
    echo $tpl->fetch('backend/index.tpl');  
    }
  private function PageLogin(){
    $this->seo_title='MHMSYS Administrace';
    $this->seo_keywords='MHMSYS Administrace';
    $this->seo_description='MHMSYS Administrace';
    $tpl=new Templater();
    $tpl->add('SEO_title',$this->kernel->config->title['prefix'].$this->seo_title.$this->kernel->config->title['suffix']);
    $tpl->add('SEO_keywords',$this->seo_keywords);
    $tpl->add('SEO_description',$this->seo_description); 
    $tpl->add('LoginError',(int)getget('LoginError','0'));
    $tpl->add('alogin',$this->Anchor(array('module'=>'Backend','action'=>'login')/*,$this->kernel->config->domain_https*/));
    echo $tpl->fetch('backend/loginform.tpl');           
    }
  private function LogIn(){
    $email=getpost('email','');
    $pass=getpost('pass','');
    $this->kernel->LoginUser($email,$pass,'Backend');
    }
  private function LogOut(){
    $this->kernel->LogoutUser('Backend');
    }
  }
?>