<?php
class BBoxes extends Module{
  public function __construct(){
    $this->parent_module='Backend';
    $this->rights=new stdClass();
    }
  public function Main(){
    $this->seo_title='Prvky';
    $this->rights=$this->getUserRights();
		if(!in_array('boxes_views',$this->rights->povoleneKody)){
			$this->Redirect(array('module'=>'Backend'),false);
			}
    $action=getget('action','list');
      if($action=='list'){$this->PageList();}      
      elseif($action=='new-post'&&in_array('boxes_changes',$this->rights->povoleneKody)){$this->PageNewPost();}                  
      elseif($action=='delete'&&in_array('boxes_changes',$this->rights->povoleneKody)){$this->PageDeletePost();}                  
      elseif($action=='save-order'&&in_array('boxes_changes',$this->rights->povoleneKody)){$this->PageSaveOrder();}                  
      elseif($action=='edit-save'&&in_array('boxes_changes',$this->rights->povoleneKody)){$this->PageSaveEdit();}                  
      elseif($action=='edit'){$this->PageEdit();}                  
      else{$this->Redirect();}    
    }
  private function SetLeftMenu(){
    $idbc=(int)getget('idbc','0');
    $active=$this->Anchor(array('action'=>'list'),false);
    $menu=array($this->Anchor(array('action'=>'list'),false)=>'<span class="icon"><i class="fa fa-question-circle"></i></span> Nápověda prvků');
    if($this->kernel->user->data->prava==2){
      $boxes_categories=$this->kernel->models->DBboxesCategories->getLines('idbc,nazev','	order by poradi');      
    }else{
      $boxes_categories=$this->kernel->models->DBboxesCategories->getLines('idbc,nazev','WHERE zobrazovat_admin=1	order by poradi');        
    }
    foreach($boxes_categories as $bc){
      $menu[$this->Anchor(array('action'=>'list','idbc'=>$bc->idbc),false)]='<span class="icon"><i class="fa fa-cube"></i></span> '.$bc->nazev;
      if($idbc==$bc->idbc){
        $active=$this->Anchor(array('action'=>'list','idbc'=>$bc->idbc),false);
        }
      }              
    $tpl2=new Templater();
    $tpl2->add('menu',$menu);
    $tpl2->add('active',$active);  
    $this->content2=$tpl2->fetch('backend/boxes/leftMenu.tpl');    
    }  
  private function PageList(){
    $idbc=(int)getget('idbc','0');
    $tpl=new Templater();         
    if($idbc<1){
      $this->content=$tpl->fetch('backend/boxes/nullList.tpl');  
    }else{
      $list=$this->kernel->models->DBboxes->getLines('idb,modul,nazev,zobrazovat','WHERE id_bc="'.$idbc.'" order by poradi');
      foreach($list as $kl=>$vl){
        $list[$kl]->aedit=$this->Anchor(array('action'=>'edit','idbc'=>$idbc,'idb'=>$vl->idb),false);
        $list[$kl]->adel=$this->Anchor(array('action'=>'delete','idbc'=>$idbc,'idb'=>$vl->idb),false);        
        }
      $boxes_category=$this->kernel->models->DBboxesCategories->getLine('idbc,nazev,popis','WHERE idbc="'.$idbc.'"');       
      $modules=$this->kernel->models->DBboxes->ReturnModules();
      $tpl->add('boxesCategory',$boxes_category);           
      $tpl->add('modules',$modules);     
      $tpl->add('list',$list);     
      $tpl->add('anew',$this->Anchor(array('action'=>'new-post','idbc'=>$idbc),false));     
      $tpl->add('asaveorder',$this->Anchor(array('action'=>'save-order','idbc'=>$idbc),false));     
      $this->content=$tpl->fetch('backend/boxes/list.tpl');
    }    
    $this->SetLeftMenu();
    $this->execute();        
    }  
  private function PageNewPost(){
    $idbc=(int)getget('idbc','0');    
    if($idbc<1){
      $this->Redirect(array('action'=>'list'),false);
      }    
    $maxPoradi=$this->kernel->models->DBboxes->getOne('poradi','order by poradi desc limit 1');
    $maxPoradi=$maxPoradi+1;
    $idb=$this->kernel->models->DBboxes->store(0,array(
      'poradi'=>$maxPoradi,
      'nazev'=>prepare_get_data_safely(getpost('nazev','')),      
      'modul'=>prepare_get_data_safely(getpost('module','')),
      'id_bc'=>$idbc
      ));        
    $this->Redirect(array('action'=>'edit','idbc'=>$idbc,'idb'=>$idb),false);              
    }
  private function PageSaveOrder(){
    $idbc=(int)getget('idbc','0');    
    if($idbc<1){
      $this->Redirect(array('action'=>'list'),false);
      }  
    $order=trim(prepare_get_data_safely(getpost('order','')));
    if($order==''){
      $this->Redirect(array('action'=>'list','message'=>'order-is-same','idbc'=>$idbc),false);  
      }    
    $td=$this->kernel->models->DBboxes->getLines('idb,poradi','WHERE id_bc="'.$idbc.'"');
    $data=array();
    foreach($td as $vtd){
      $data[$vtd->idb]=$vtd;    
      }
    $orders=explode(',',$order);
    $poradi=1;
    foreach($orders as $o){
      if(trim($o)==''){
        continue;
        }      
      $idb=(int)$o;
      if($idb>0){
        if($data[$idb]->poradi!=$poradi){
          $this->kernel->models->DBboxes->updateId($idb,array('poradi'=>$poradi));                   
          }        
        $poradi++;
        }                  
      }        
    $this->Redirect(array('action'=>'list','message'=>'order-saved','idbc'=>$idbc),false);      
    }
  private function PageDeletePost(){
    $idbc=(int)getget('idbc','0');    
    if($idbc<1){
      $this->Redirect(array('action'=>'list'),false);
      }  
    $idb=(int)getget('idb','0');    
    if($idb<1){
      $this->Redirect(array('action'=>'list','idbc'=>$idbc),false);
      }  
    $this->kernel->models->DBboxes->deleteWhere('WHERE idb ="'.$idb.'" limit 1');  
    $this->Redirect(array('action'=>'list','message'=>'deleted','idbc'=>$idbc),false);      
    }  
  private function PageEdit(){
    $idbc=(int)getget('idbc','0');    
    if($idbc<1){$this->Redirect(array('action'=>'list'),false);}  
    $idb=(int)getget('idb','0');    
    if($idb<1){$this->Redirect(array('action'=>'list','idbc'=>$idbc),false);}  
    $boxes_category=$this->kernel->models->DBboxesCategories->getLine('idbc,nazev,popis','WHERE idbc="'.$idbc.'"'); 
    $box=$this->kernel->models->DBboxes->getLine('*','WHERE idb="'.$idb.'"'); 
    if($boxes_category->idbc<1){$this->Redirect(array('action'=>'list'),false);}
    if($box->idb<1){$this->Redirect(array('action'=>'list','idbc'=>$idbc),false);}        
    $modules=$this->kernel->models->DBboxes->ReturnModules();
    $tplSub=new Templater();
    $tplSub->add('boxesCategory',$boxes_category);     
    if($box->modul=='gallery'||$box->modul=='files'){if($box->int_2<1){$box->int_2=10;}}   
    $tplSub->add('box',$box);              
    $tplSub->add('modules',$modules);       
    if($box->modul=='text'){
      $tplSub->add('sablony',array('0'=>'Čistý text'));
      $tplSub->add('text_1',$this->kernel->GetEditor('text_1',$box->text_1));     
      }  
    if($box->modul=='pages_menu'){
      $tplSub->add('sablony',array('0'=>'Jednoúrovňové menu v hlavičce','4'=>'Jednoúrovňové menu v patičce','3'=>'Jednoúrovňové obrázkové menu'/*,'1'=>'Víceúrovňové menu - otevřené pouze aktivní stránky','2'=>'Víceúrovňové menu - otevřené všechny stránky'*/));
      $tree=$this->kernel->models->DBpages->returnTree(0,'idp,parent_id,nazev');
      $tplSub->add('tree_select',$this->getTreePagesSelect($tree,$box->int_1));         
      }     
    if($box->modul=='gallery'){
      $fotogalerie=$this->kernel->models->DBimagesCategories->getLines('idic,nazev','order by nazev');
      $tplSub->add('fotogalerie',$fotogalerie);
      $tplSub->add('sablony',array('1'=>'Fotogalerie','0'=>'Banner s odkazy'/*,'1'=>'Partneři - fotogalerie bez odkazů','2'=>'Banner'*/));
      $tplSub->add('poradi',array('nazev asc'=>'Dle názvu (A-Z)','nazev desc'=>'Dle názvu (Z-A)','idi asc'=>'Od nejstarších','idi desc'=>'Od nejnovějších','RAND ()'=>'Náhodné pořadí'));
      }
    if($box->modul=='files'){
      $files=$this->kernel->models->DBfilesCategories->getLines('idfc,nazev','order by nazev');
      $tplSub->add('files',$files);
      $tplSub->add('sablony',array('0'=>'Seznam souborů'));
      $tplSub->add('poradi',array('nazev asc'=>'Dle názvu (A-Z)','nazev desc'=>'Dle názvu (Z-A)','idf asc'=>'Od nejstarších','idf desc'=>'Od nejnovějších','RAND ()'=>'Náhodné pořadí'));
      }
    $tpl=new Templater();   
    $tpl->add('boxesCategory',$boxes_category);     
    $tpl->add('box',$box);              
    $tpl->add('modules',$modules);         
    $tpl->add('asave',$this->Anchor(array('action'=>'edit-save','idbc'=>$idbc,'idb'=>$idb),false));     
    $tpl->add('aback',$this->Anchor(array('action'=>'list','idbc'=>$idbc),false));     
    $tpl->add('tplSub',$tplSub->fetch('backend/boxes/edit-'.$box->modul.'.tpl'));     
    $this->content=$tpl->fetch('backend/boxes/editMain.tpl');       
    $this->SetLeftMenu();
    $this->execute();                    
    }
  private function PageSaveEdit(){
    $idbc=(int)getget('idbc','0');    
    if($idbc<1){
      $this->Redirect(array('action'=>'list'),false);
      }  
    $idb=(int)getget('idb','0');    
    if($idb<1){
      $this->Redirect(array('action'=>'list','idbc'=>$idbc),false);
      }  
    $boxes_category=$this->kernel->models->DBboxesCategories->getLine('idbc,nazev,popis','WHERE idbc="'.$idbc.'"'); 
    $box=$this->kernel->models->DBboxes->getLine('*','WHERE idb="'.$idb.'"'); 
    if($boxes_category->idbc<1){
      $this->Redirect(array('action'=>'list'),false);
      }
    if($box->idb<1){
      $this->Redirect(array('action'=>'list','idbc'=>$idbc),false);
      } 
    $data=array(
      'nazev'=>prepare_get_data_safely(getpost('nazev','')),
      'nadpis'=>prepare_get_data_safely(getpost('nadpis','')),
      'zobrazovat'=>(int)prepare_get_data_safely(getpost('zobrazovat','0')),
      'zobrazovat_nadpis'=>(int)prepare_get_data_safely(getpost('zobrazovat_nadpis','0'))
      );   
    if($box->modul=='text'){
      $data['sablona']=(int)prepare_get_data_safely(getpost('sablona','0'));
      $data['text_1']=prepare_get_data_safely_editor(getpost('text_1','0'));
      }    
    if($box->modul=='pages_menu'){
      $data['sablona']=(int)prepare_get_data_safely(getpost('sablona','0'));
      $data['int_1']=(int)prepare_get_data_safely(getpost('int_1','0'));
      }
    if($box->modul=='gallery'||$box->modul=='files'){
      $data['sablona']=(int)prepare_get_data_safely(getpost('sablona','0'));
      $data['int_1']=(int)prepare_get_data_safely(getpost('int_1','0'));
      $data['int_2']=(int)prepare_get_data_safely(getpost('int_2','10'));
      $data['text_1']=prepare_get_data_safely(getpost('text_1',''));
      }
    if($box->modul=='contact_form'){      
      $data['text_1']=prepare_get_data_safely(getpost('text_1',''));
      }
    $idb=$this->kernel->models->DBboxes->store($idb,$data);        
    $this->Redirect(array('action'=>'edit','message'=>'box-edited','idbc'=>$idbc,'idb'=>$idb),false);                
    }
  private function getTreePagesSelect($tree=array(), $selected=0,$firstloop=1,$level=0){
    if($firstloop==1){
      $content='<option value="0"> - Hlavní stránka - </option>';
    }else{
      $content='';
    }
    $TextPadding='';
    for($i=0;$i<$level;$i++){
      $TextPadding.='-';
      }
    foreach($tree as $t){
      if($t->idp==$selected){
        $content.='<option value="'.$t->idp.'" selected> '.$TextPadding.$t->nazev.' </option>';
      }else{
        $content.='<option value="'.$t->idp.'"> '.$TextPadding.$t->nazev.' </option>';
      }
      if(isset($t->subtree)){
        $content.=$this->getTreePagesSelect($t->subtree, $selected,0,$level+1);
        }
      }
    return $content;
    }
  }
?>
