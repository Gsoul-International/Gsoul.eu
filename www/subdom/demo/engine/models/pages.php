<?php
class DBpages extends Model{
  public function __construct(){ 
    $this->setTable('pages');
    $this->setPrimaryKey('idp');
    }
  public function returnTree($parentID=0,$what='*',$where=''){
    $data=$this->getLines($what,$where.' order by parent_id,poradi');
    return $this->treeByParent($data,$parentID);    
    }
  public function returnTreeSubData($parentID=0,$what='*'){
    $data=$this->getLines($what,'order by parent_id,poradi');
    return $this->treeSubData($data,$parentID);    
    }
  public function treeByParent($data,$parentID=0){
    $newdata=array();
    foreach($data as $dk=>$dv){
      if($dv->parent_id==$parentID){
        unset($data[$dk]);        
        $dv->subtree=$this->treeByParent($data,$dv->idp);
        $newdata[]=$dv;                
        }
      }
    return $newdata;
    }
  public function treeSubData($data,$parentID=0){
    $newdata=array();
    foreach($data as $dk=>$dv){
      if($dv->idp==$parentID){
        $newdata[$dv->idp]=$dv;
        }
      if($dv->parent_id==$parentID){
        $newdata[$dv->idp]=$dv;    
        unset($data[$dk]);        
        $subIds=$this->treeSubData($data,$dv->idp);
        foreach($subIds as $si){
          $newdata[$si->idp]=$si;
          }                    
        }
      }    
    return $newdata;
    }  
  public function returnBreadcrumb($idp,$last=true){
    $idp=(int)$idp;  
    $breadcrumbArray=array();       
    $data=$this->getLine('idp,parent_id,zobrazovat,zobrazovat_v_navigaci,nove_okno,typ,odkaz,nazev','WHERE idp="'.$idp.'"');
    if($data->idp>0){      
      $breadcrumb=new stdClass();            
      if($data->zobrazovat==1&&$data->zobrazovat_v_navigaci==1){
        $breadcrumb->show=1;
      }else{
        $breadcrumb->show=0;
      }
      if($last==true){
        $breadcrumb->lastPage=1;
      }else{
        $breadcrumb->lastPage=0;
      }      
      $breadcrumb->idp=$data->idp;
      $breadcrumb->new_window=$data->nove_okno;
      $breadcrumb->name=$data->nazev;
      $breadcrumb->type=$data->typ;
      $breadcrumb->link=$data->odkaz;
      $breadcrumb->urlPieces=array('module'=>'FPages','idp'=>$idp);       
      if($data->parent_id>0){
        $breadcrumbArray2=$this->returnBreadcrumb($data->parent_id,false);
        foreach($breadcrumbArray2 as $ba){
          $breadcrumbArray[]=$ba;
          }
        }
      if($data->parent_id==0){
        $breadcrumb2=new stdClass();  
        $dataMainPage=$this->kernel->models->DBmainPages->getLine('nazev,zobrazovat_v_navigaci');
        $breadcrumb2->lastPage=0;
        $breadcrumb2->idp=0;
        $breadcrumb2->type=(-1);
        $breadcrumb2->show=$dataMainPage->zobrazovat_v_navigaci;
        $breadcrumb2->name=$dataMainPage->nazev;
        $breadcrumb2->urlPieces=array(); 
        $breadcrumbArray[]=$breadcrumb2; 
        }        
      $breadcrumbArray[]=$breadcrumb;
      }            
    return $breadcrumbArray;
    }
  }
?>