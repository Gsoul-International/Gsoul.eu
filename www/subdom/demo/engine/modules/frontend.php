<?php
class Frontend extends Module{
  public function Main(){
    $tpl=new Templater();
    $tpl->add('SEO_title',trim(trim($this->kernel->settings['text_pred_titulkem_stranky'].' '.$this->seo_title).' '.$this->kernel->settings['AFTER_TITLE']));
    $tpl->add('SEO_keywords',$this->seo_keywords);
    $tpl->add('SEO_description',$this->seo_description);    
    $tpl->add('page',$this->content);    
    $tpl->add('settings',$this->kernel->settings);      
    $tpl->add('boxes',$this->kernel->boxesByParent);      
    $tpl->add('kernel',$this->kernel);
    $tpl->add('auseraccount',$this->anchor(array('module'=>'FUsers')));           
    $tpl->add('ausersettings',$this->anchor(array('module'=>'FUsers','action'=>'userSettings'))); 
    $tpl->add('auserpaypal',$this->anchor(array('module'=>'FPaypal')));                    
    $tpl->add('ausergsc',$this->anchor(array('module'=>'FUsers','action'=>'userGsc')));           
    $tpl->add('auserlogout',$this->anchor(array('module'=>'FUsers','action'=>'userLogOut')));           
    $tpl->add('auserlogin',$this->anchor(array('module'=>'FUsers','action'=>'userLogIn')));           
    $tpl->add('auserpassword',$this->anchor(array('module'=>'FUsers','action'=>'userPassword')));           
    $tpl->add('auserregistration',$this->anchor(array('module'=>'FUsers','action'=>'userRegistration'))); 
    $tpl->add('aadmin',$this->anchor(array('module'=>'Backend')));      
    $tpl->add('atournaments',$this->anchor(array('module'=>'FTournaments')));
    $tpl->add('afreemode',$this->anchor(array('module'=>'FFreemode')));             
    $tpl->add('achallangers',$this->anchor(array('module'=>'FChallangers')));             
    $tpl->add('amatches',$this->anchor(array('module'=>'FMatches')));                                    
    echo $tpl->fetch('frontend/index.tpl');
    }
  }
?>