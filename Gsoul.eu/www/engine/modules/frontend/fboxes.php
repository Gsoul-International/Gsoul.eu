<?php
class FBoxes extends Module{
  private $boxesContents;
  private $boxes;
  /* s pridanim kernelu se rovnou vygeneruji sablony prvku: */
  public function addKernel($xkernel,$xname=''){
    parent::addKernel($xkernel,$xname);  
    $this->boxesContents=array(); 
    $this->boxes=array(); 
    $this->parent_module='Frontend';   
    $activeModule='Frontend';
    if(isset($_GET['module'])){$activeModule=$_GET['module'];}
    if(!in_array($_GET['module'],$this->kernel->config->ignorateBoxes)){ 
      $boxes=$this->kernel->models->DBboxes->getLines('*','WHERE zobrazovat=1 ORDER BY id_bc,poradi');
      $boxesCategories=array();
      $bcx=$this->kernel->models->DBboxesCategories->getLines('idbc,interni_nazev');
      foreach($bcx as $b){$boxesCategories[$b->idbc]=$b->interni_nazev;}
      unset($bcx);
      foreach($boxes as $bk=>$bv){           
        $boxes[$bk]->content='';
        $this->boxesContents[$bv->idb]='';
        $this->kernel->boxesByVariable['box_'.$bv->id_bc.'_'.$bv->idb]=$boxes[$bk];  
        $this->boxes['box_'.$bv->id_bc.'_'.$bv->idb]=$boxes[$bk];
        $this->kernel->boxesByParent[$boxesCategories[$bv->id_bc]][]=$boxes[$bk];  
        } 
      }      
    }
  public function getContent($box){
    if($this->boxesContents[$box->idb]==''){
      $boxmodule='box_'.$box->modul;
      if(method_exists($this,$boxmodule)){$this->boxesContents[$box->idb]=$this->$boxmodule($box);}       
      }  
    return $this->boxesContents[$box->idb];         
    }
  public function replaceTextContent($text){
    $idbs=array();
    @$text_a=explode('{box_',$text);
    foreach($text_a as $ta){
      @$text_b=explode('}',$ta);
      @$text_c=explode('_',$text_b[0]);
      @$idb=(int)$text_c[1];
      if($idb>0){$idbs['box_'.$text_b[0]]=$idb;}                
      }
    if(count($idbs)>0){
      $what=array();
      $replace=array();
      foreach($idbs as $kb=>$kv){
        $what[]='{'.$kb.'}';
        $replace[]=$this->getContent($this->boxes[$kb]);
        }
      $text=str_replace($what,$replace,$text);
      }      
    return $text;
    }
  /* funkce jednotlivych podmodulu: */
  /* Modul novinek */
  public function box_news($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $news=$this->kernel->models->DBnews->getLines('idn,zobrazovat,nazev,datum,predtext,id_obrazku,zobrazovat_datum','WHERE zobrazovat=1 AND id_jazyka="'.((int)$this->kernel->active_language).'" order by datum desc LIMIT 3');
    $images=array();
    $images2=array();                   
    foreach($news as $kn=>$vn){
      $news[$kn]->alink=$this->Anchor(array('module'=>'FNews','idn'=>$vn->idn));
      $images2[]=$vn->id_obrazku;        
      }  
    if(count($images2)>0){
      $images3=$this->kernel->models->DBimages->getLines('*','WHERE idi in ('.implode(',',$images2).') ORDER BY id_ic,nazev');  
      unset($images2);
      foreach($images3 as $i3){$images[$i3->idi]=$i3;}
      unset($images3);          
      } 
    $tpl->add('news',$news);
    $tpl->add('images',$images);
    $tpl->add('linkArchive',$this->Anchor(array('module'=>'FNews'))); 
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');    
    }
  /* Modul pro odběr novinek */
  public function box_news_sign($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    $message='';$mail='';$antispam='';
    if(getpost('box_action','')=='news_sign_add'&&getpost('box_identificator','')==$box->idb){      
        $mail=prepare_get_data_safely(getpost('news_sign_mail',''));
        $antispam=prepare_get_data_safely(getpost('news_sign_antispam',''));        
        if($antispam!='13'){
          $message='antispam';
        }else{
          $mailex=explode('@',$mail);
          if(isset($mailex[1])&&strlen($mailex[1])>0){
            $exist_mail=(int)$this->kernel->models->DBusers->getOne('uid','WHERE email="'.$mail.'"');
            if($exist_mail>0){
              $message='emailexist';  
            }else{
              $this->kernel->models->DBusers->store(0,array('prava'=>0,'heslo'=>md5('noreg'.rand(1000000,9999999)),'registrace'=>time(),'posledni_prihlaseni_ip'=>$_SERVER['REMOTE_ADDR'],'odber_novinek'=>1,'email'=>$mail));
              $message='done'; 
              $mail='';
              $antispam='';
            }
          }else{
            $message='email';
          }
        }                
      }
    $tpl->add('message',$message);
    $tpl->add('mail',$mail);
    $tpl->add('antispam',$antispam);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }
  /* Modul pro kontaktní formulář */
  public function box_contact_form($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    $message_return='';
    $mail='';$antispam='';$phone='';$name_surname='';$message='';
    if(getpost('box_action','')=='contact_form_send'&&getpost('box_identificator','')==$box->idb){      
        $mail=prepare_get_data_safely(getpost('contact_form_mail',''));
        $antispam=prepare_get_data_safely(getpost('contact_form_antispam',''));        
        $phone=prepare_get_data_safely(getpost('contact_form_phone',''));        
        $name_surname=prepare_get_data_safely(getpost('contact_form_name_surname',''));        
        $message=prepare_get_data_safely(getpost('contact_form_message',''));        
        if($antispam!='9'){
          $message_return='antispam';
        }elseif($message==''||$name_surname==''||$mail==''||$antispam==''){
          $message_return='data';            
        }else{
          $mailex=explode('@',$mail);
          if(isset($mailex[1])&&strlen($mailex[1])>0){
              $mailer=new PHPMailer();;
              $mailer->CharSet="UTF-8"; 
              $mailer->SetFrom($this->kernel->config->default_email['from_email'],$this->kernel->config->default_email['from_name']);
              $mailer->Subject='Zpráva z kontaktního formuláře';              
              $mailer->MsgHTML('<h1>Zpráva z kontaktního formuláře</h1><table>
              <tr><th align="left">Jméno a příjmení:</th><td>'.$name_surname.'</td></tr>
              <tr><th align="left">E-mail:</th><td><a href="mailto:'.$mail.'">'.$mail.'</a></td></tr>
              <tr><th align="left">Telefon:</th><td>'.$phone.'</td></tr>
              <tr><th align="left" valign="top">Zpráva:</th><td>'.$message.'</td></tr>              
              </table>');
              $mailer->AddAddress($box->text_1);
              $mailer->Send();                       
              $message_return='ok'; 
              $mail='';$antispam='';$phone='';$name_surname='';$message='';            
          }else{
            $message_return='data';
          }
        }                
      }
    $tpl->add('message_return',$message_return);
    $tpl->add('mail',$mail);$tpl->add('antispam',$antispam);$tpl->add('name_surname',$name_surname);$tpl->add('phone',$phone);$tpl->add('message',$message);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }
  /* Modul pro text */
  public function box_text($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }
  /* Modul pro text */
  public function box_howtoplay($box){
    $tpl=new Templater();
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $tpl->add('box',$box);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }
  /* Modul pro fotogalerie a bannery */
  public function box_gallery($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    if(trim($box->text_1)==''){
      $box->text_1='nazev asc';
      }
    if($box->int_2<1){
        $box->int_2=10;
        }
    $images=$this->kernel->models->DBimages->getLines('*','WHERE id_ic="'.$box->int_1.'" ORDER BY '.$box->text_1.' LIMIT '.$box->int_2);
    foreach($images as $fk=>$fv){
      $images[$fk]->sizes=$this->kernel->models->DBimages->AddSizes($fv->cesta);
      }
    $tpl->add('images',$images);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }
  /* Modul pro výpis souborů ke stažení */
  public function box_files($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    if(trim($box->text_1)==''){
      $box->text_1='nazev asc';
      }
    if($box->int_2<1){
        $box->int_2=10;
        }
    $files=$this->kernel->models->DBfiles->getLines('*','WHERE id_fc="'.$box->int_1.'" ORDER BY '.$box->text_1.' LIMIT '.$box->int_2);    
    $tpl->add('files',$files);
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    }  
  /* Modul pro menu článků */
  public function box_pages_menu($box){
    $tpl=new Templater();
    $tpl->add('box',$box);
    $tpl->add('systemTranslator',$this->kernel->systemTranslator);
    $idp=0;
    if(isset($_GET['idp'])){$idp=(int)$_GET['idp'];}
    if($box->sablona==0||$box->sablona==1||$box->sablona==2||$box->sablona==3||$box->sablona==4){
      if($box->sablona==3){
        $tree=$this->kernel->models->DBpages->getlines('idp,typ,odkaz,nazev,nove_okno,id_obrazku','WHERE parent_id="'.$idp.'" AND id_jazyka="'.((int)$this->kernel->active_language).'" AND zobrazovat=1 order by poradi');
      }else{
        $tree=$this->kernel->models->DBpages->getlines('idp,typ,odkaz,nazev,nove_okno,id_obrazku','WHERE parent_id="'.$box->int_1.'" AND id_jazyka="'.((int)$this->kernel->active_language).'" AND zobrazovat=1 order by poradi');
      } 
      if($box->sablona==3){
        $images=array();
        $images2=array();
        foreach($tree as $vt){$images2[]=$vt->id_obrazku;}
        if(count($images2)>0){
          $images3=$this->kernel->models->DBimages->getLines('*','WHERE idi in ('.implode(',',$images2).') ORDER BY id_ic,nazev');  
          unset($images2);
          foreach($images3 as $i3){
            $images[$i3->idi]=$i3;
            }
          unset($images3);          
          }
        $tpl->add('images',$images);
        $pagexx=$this->kernel->models->DBpages->getLine('*','WHERE idp="'.$idp.'"');
        $tpl->add('page',$pagexx);
        }     
      foreach($tree as $kt=>$vt){
        if($vt->typ==1){
          $tree[$kt]->link=trim($vt->odkaz);
        }else{
          $tree[$kt]->link=$this->Anchor(array('module'=>'FPages','idp'=>$vt->idp));
        }
        $tree[$kt]->active=$idp==$vt->idp?1:0;
        }
      $tpl->add('tree',$tree);
      }  
    if($box->sablona==1||$box->sablona==2){
      $actives=array($idp);
      if($idp>0){
        $breadcrumb=$this->kernel->models->DBpages->returnBreadcrumb($idp);   
        foreach($breadcrumb as $vb){
            $actives[]=$vb->idp;
          }        
        }      
      $tree=$this->kernel->models->DBpages->returnTree($box->int_1,'idp,parent_id,nazev,zobrazovat,typ,odkaz,nove_okno','WHERE zobrazovat=1 AND id_jazyka="'.((int)$this->kernel->active_language).'" ');      
      $xtree=$this->box_pages_menu_tree($tree,$actives,1,($box->sablona==1?1:0));              
      $tpl->add('tree',$xtree);
      }        
    if($box->sablona==0){
      $actives=array($idp);
      if($idp>0){
        $breadcrumb=$this->kernel->models->DBpages->returnBreadcrumb($idp);   
        foreach($breadcrumb as $vb){
            $actives[]=$vb->idp;
          }        
        }                         
      $tpl->add('actives',$actives);
      }
      $tpl->add('userID',$this->kernel->user->uid);     
      $tpl->add('atournaments',$this->anchor(array('module'=>'FTournaments')));    
      $tpl->add('acups',$this->anchor(array('module'=>'FCups')));
      $tpl->add('ateams',$this->anchor(array('module'=>'FTeams')));
      $tpl->add('areg',$this->anchor(array('module'=>'FUsers','action'=>'userRegistration'))); 
    return $tpl->fetch('frontend/boxes/'.$box->modul.'_'.$box->sablona.'.tpl');
    } 
  private function box_pages_menu_tree($tree=array(), $selected=array(),$firstloop=1,$only_active=0){
    $data=array();       
    foreach($tree as $kt=>$vt){     
        $d=$vt;
        $d->active=in_array($d->idp,$selected)?1:0;             
        $parent=$d->parent_id;
        if(isset($d->subtree)){        
          if($only_active==0){
            $d->subtree=$this->box_pages_menu_tree($d->subtree,$selected,0,$only_active);
          }else{
            if($d->active==1){
              $d->subtree=$this->box_pages_menu_tree($d->subtree,$selected,0,$only_active);
            }else{
              $d->subtree='';
            }
          }          
        }else{
          $d->subtree='';
        } 
        if($vt->typ==1){
          $d->link=trim($vt->odkaz);
        }else{      
          $d->link=$this->Anchor(array('module'=>'FPages','idp'=>$d->idp));  
        }   
        $data[]=$d;        
      }
    $tpl=new Templater();
    $tpl->add('data',$data);   
    return $tpl->fetch('frontend/boxes/pages_menu_tree.tpl');
    }   
  }
?>