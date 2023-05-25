<?php
/*
 * Tento soubor obsahuje nejčastější a nejpoužívanější funkce ohledně formátování a převodů času, znakových sad apod.
*/
/*Práce s časem:*/
function TimestampToDate($time=0){
    if($time==0)
        return strftime("%d. %m. %Y",time());
    else
        return strftime("%d. %m. %Y",$time);
    }
function TimestampToDateTime($time=0){
    if($time==0)
        return strftime("%d. %m. %Y %H:%M",time());
    else
        return strftime("%d. %m. %Y %H:%M",$time);
    }
function TimestampToTime($time=0){
    if($time==0)
        return strftime("%H:%M",time());
    else
        return strftime("%H:%M",$time);
    } 
function TimestampToTimeSec($time=0){
    if($time==0)
        return strftime("%H:%M:%S",time());
    else
        return strftime("%H:%M:%S",$time);
    }     
function DateToTimestamp($date){ //na vstupu formát dd. mm. yyyy - vraci zacatek dne
    $date=str_replace(' ','',$date);
    $xdate=explode('.',$date);        
    return mktime(0,0,0,$xdate[1],$xdate[0],$xdate[2]);
    }     
function DateToTimestamp2($date){ //na vstupu formát dd. mm. yyyy -vraci konec dne
    $date=str_replace(' ','',$date);
    $xdate=explode('.',$date);        
    return mktime(23,59,59,$xdate[1],$xdate[0],$xdate[2]);
    }    
function DateTimeToTimestamp($date){
    $date=trim(str_replace('. ','.',$date));
    $xxdate=explode(' ',$date);
    $xdate=explode('.',$xxdate[0]);
    $ydate=explode(':',$xxdate[1]);  
    return mktime($ydate[0],$ydate[1],0,$xdate[1],$xdate[0],$xdate[2]);
    } 
/*práce s get a post:*/ 
function getget($name, $default=''){
    if(isset($_GET[$name])){
        return $_GET[$name]; 
    }else{
        //$_GET[$name]=$default;
        return $default;        
    }
    }   
function getpost($name, $default=''){
    if(isset($_POST[$name])){
        return $_POST[$name]; 
    }else{
        //$_POST[$name]=$default;
        return $default;        
    }
    } 
/* tato fce data trimne, odstraní z nich HTML tagy a uvozovky */
function prepare_get_data_safely($data){
    $data=trim($data);
    $data=strip_tags($data);
    $data=stripslashes($data);
    $data=str_replace(array('"',"'"),array('',''),$data);
    $data=stripphp($data);
    return $data;     
    }   
/* tato fce data trimne, odstraní z nich php*/
function prepare_get_data_safely_editor($data){
    $data=trim($data);       
    $data=stripphp($data);
    return $data;     
    }     
/* tato fce je pro lepší vyjádření memory usage*/
function convert_memory($size){
    $unit=array('B','KB','MB','GB','TB','PB');
    return number_format(@round($size/pow(1024,($i=floor(log($size,1024)))),3),3,',',' ').' '.$unit[$i];
    }
/* tato fce vrací aktuální microtime ve formátu float */    
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
    }  
function checkmail($mail){
    $xmail=explode('@',$mail);
    if(!isset($xmail[1])){
        return false;
        }
    $xxmail=explode('.',$xmail[1]);
    if(!isset($xxmail[1])){
        return false;
        }
    return true;
    }
/* tato fce vrací string jí předaný bez diakritiky */ 
function stripdiacritics($string){
    $search1=array('á','č','ď','í','ň','ó','ř','š','ť','ý','ž','é','ě','ú','ů');
    $search2=array('Á','Č','Ď','Í','Ň','Ó','Ř','Š','Ť','Ý','Ž','É','Ě','Ú','Ů');
    $replace=array('a','c','d','i','n','o','r','s','t','y','z','e','e','u','u');       
    $string=str_replace($search1,$replace,$string);
    $string=str_replace($search2,$replace,$string);
    return $string;
    }
/* tato fce vrací string jí předaný bez speciálních znaků bez '/' */
function stripspecialchars($string){
    $search=array('{','}','[',']','(',')','<','>','?','!','*','\\','+','@','#','$','%','=','&','^','~',';','|','.',':');
    $replace=array('_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_');
    $string=str_replace($search,$replace,$string);
    return $string;
    }
/* tato fce vrací string jí předaný bez php entit (< ?  ? >  < ? php da do pryc) */
function stripphp($string){
    $search=array('<'.'?','<'.'?php','?'.'>');
    $replace=array('PHPSTART','PHPSTART','PHPEND');
    $string=str_replace($search,$replace,$string);    
    return $string;
    } 
/* tato fce vrací string jí předaný bez uvozovek */
function stripAllSlashes($string){
    $search=array('"',"'");
    $replace=array('','');
    $string=str_replace($search,$replace,$string);    
    return $string;
    } 
function GenerateNiceUrl($string){
    $string=strip_tags(trim($string));
    $string=stripspecialchars($string);
    $string=stripdiacritics($string);
    $string=stripAllSlashes($string);
    $string=strtolower($string); 
    $string=str_replace(
      array('sw / hw',',',' ','_','-----','----','---','--','---','----','-----'),
      array('sw-hw','-','-','-','-','-','-','-','-','-'),
      $string);     
    return $string;
    }
/* tato fce na zaklade prijate boolean hodnoty vypise ANO, nebo NE a to bud tucne, nebo normalne dle parametru */
function AnoNe($boolean=0, $boldAno=0, $boldNe=0){
    if($boolean==1){
        if($boldAno==1) echo '<b>Ano</b>';
        else echo 'Ano';
    }else{
        if($boldNe==1) echo '<b>Ne</b>';
        else echo 'Ne';
    }
    }
/* tato fce tvoří odkazy dle zadaných parametrů v poli: [$promenna]=$hodnota */
function Anchor($url=array(), $base='/'){
  $i=0;
  foreach($url as $k=>$v){
    if($i==0){
      $base.='?'.unhtmlentities($k).'='.unhtmlentities($v);
    }else{
      $base.='&'.unhtmlentities($k).'='.unhtmlentities($v);
    }    
    $i++;
    }
  return $base;
  }
/* tato fce přesměrovává dle zadaných parametrů v poli: [$promenna]=$hodnota */
function Redirect($url=array(),$base='/'){
  $url2=Anchor($url,$base);  
  header ("Location: ".$url2);
  die();
  }
/* tato fce přesměrovává natvrdo */
function Redirect2($url='/',$base='/'){   
  header ("Location: ".$url);
  die();
  }
function unhtmlentities($str){
  $trans = get_html_translation_table(HTML_ENTITIES);
  $trans = array_flip($trans);
  return strtr($str, $trans);
  }
function printcost($cost=0,$precison=2){
  return number_format(round($cost,$precison),$precison,',',' ');
  }
function saltHashSha($password,$id=0,$registrationTimestamp='',$salt=''){  
  $stringToConvert='rjy8fCrBQ5'.$password.'GDF4mU8krS'.$id.'tvNwhUvvnT'.$registrationTimestamp.'BEc7J3wLVF'.$salt.'Fp7mdGhDFy';    
  $result='';
  $subStrings=str_split($stringToConvert,6);  
  $i=0;
  foreach($subStrings as $sS){
    $i++;
    if($i%3==0){$subHash='RtyY9Bw4ak'.$id.'9vrzeLNzAJ'.$sS.'2k7e6fUrEs'.$registrationTimestamp.'fZtxtkMzhv';}
    if($i%3==1){$subHash='RdGKSQvkfj'.$registrationTimestamp.'qmCh3gY9Bq'.$sS.'JcKkPKfGDn'.$id.'tuyDvPsSde';}
    if($i%3==2){$subHash='mFvcn9qbdd'.$sS.'TE4NtSVQqR'.$registrationTimestamp.'VKBg4sPghH'.$id.'7mfpTHfEaG';}    
    $result.=hash('sha512',$subHash);               
    }  
  $resultHashed=hash('sha512',$salt.'kskpn3pS3J'.$result.'K7eqdUn2SQ'.($registrationTimestamp%99));   
  return $resultHashed; 
  }
function frontendMessage($type='normal',$text){
  $xtype='normal';
  if($type=='red'){$xtype='red';}
  if($type=='green'){$xtype='green';}  
  echo '<div class="message-box message-box-'.$type.'">';
    echo '<div class="wrap">';
      if($type=='green'){
        echo '<i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp; ';
      }elseif($type=='red'){
        echo '<i class="fa fa-times-circle" aria-hidden="true"></i> &nbsp; ';
      }else{
        echo '<i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp; ';
      }
      echo $text;
    echo '</div>';
  echo '</div>';    
  }