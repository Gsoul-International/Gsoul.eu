<?php
class FNewsletterLogout extends Module{
  public function Main(){
    if(getget('action','')=='logout'){$this->Logout();}
    $this->parent_module='Frontend';               
    $this->seo_title=$this->kernel->systemTranslator['nwslgout_odhlaseni_odberu_newsletteru'];   
    $this->seo_keywords=$this->kernel->systemTranslator['nwslgout_odhlaseni_odberu_newsletteru'];  
    $this->seo_description=$this->kernel->systemTranslator['nwslgout_odhlaseni_odberu_newsletteru'];          
    $tpl=new Templater();          
    $tpl->add('alogout',$this->Anchor(array('action'=>'logout')));
    $tpl->add('message',trim(strip_tags(getget('message',''))));                
    $tpl->add('email',trim(strip_tags(urldecode(getget('email','')))));
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $this->content=$tpl->fetch('frontend/newsletterLogout/main.tpl');            
    $this->execute();  
    }
  private function Logout(){
    $mail=str_replace(array('"',"'"),array('',''),trim(strip_tags(getpost('email',''))));
    $x_mail_parts_first=explode('@',$mail); 
    if(count($x_mail_parts_first)!=2){$this->Redirect(array('message'=>'email-incorrect','email'=>$mail));}           
    $x_mail_parts_second=explode('.',$x_mail_parts_first[1]);
    if(count($x_mail_parts_second)!=2){$this->Redirect(array('message'=>'email-incorrect','email'=>$mail));}
    $exist=$this->kernel->models->DBnewsletterLogouts->getLine('*','WHERE `email`="'.$mail.'"');    
    if($exist->idnl>0){$this->Redirect(array('message'=>'email-used','email'=>$mail));}
    $this->kernel->models->DBnewsletterLogouts->store(0,array('email'=>$mail,'ts'=>time(),'hotovo'=>'0'));
    $this->Redirect(array('message'=>'logout-done','email'=>$mail));
    }
  }