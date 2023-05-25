<?php
class Frontend extends Module{
  public function Main(){
    $module=prepare_get_data_safely(getget('module',''));
    $action=prepare_get_data_safely(getget('action',''));
    $message=prepare_get_data_safely(getget('message',''));
    $tpl=new Templater();
    $tpl->add('SEO_title',trim(trim($this->kernel->settings['text_pred_titulkem_stranky'].' '.$this->seo_title).' '.$this->kernel->settings['AFTER_TITLE']));
    $tpl->add('SEO_keywords',$this->seo_keywords);
    $tpl->add('SEO_description',$this->seo_description);    
    $tpl->add('page',$this->content);    
    $tpl->add('settings',$this->kernel->settings);      
    $tpl->add('boxes',$this->kernel->boxesByParent);      
    $tpl->add('kernel',$this->kernel);
    $tpl->add('module',$module);
    $tpl->add('action',$action);
    $tpl->add('message',$message);
    $tpl->add('auseraccount',$this->anchor(array('module'=>'FUsers')));
    $tpl->add('ausercalendary',$this->anchor(array('module'=>'FUsers','action'=>'userCalendar')));                      
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
    $tpl->add('ateams',$this->anchor(array('module'=>'FTeams')));
    $tpl->add('anotifications',$this->anchor(array('module'=>'FNotifications')));
    $tpl->add('notificationsCounts',((int)$this->kernel->models->DBusers->MqueryGetOne('SELECT count(idn) as cnt FROM notifications WHERE idu="'.$this->kernel->user->uid.'" AND idu>0 AND precteno=0 ') ));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);                                    
    echo $tpl->fetch('frontend/index.tpl');
    }
  }
?>