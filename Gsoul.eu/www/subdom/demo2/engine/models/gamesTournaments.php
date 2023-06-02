<?php
class DBgamesTournaments extends Model{
  public function __construct(){
    $this->setTable('games_tournaments');
    $this->setPrimaryKey('idt');
    }
  public function generateHash($idt='',$id_modulu='',$id_hry='',$id_serveru='',$id_typu_hry='',$id_mapy='',$id_uzivatele='',$id_vyplaty='',$datum_vytvoreni='',$datum_cas_startu='',$datum_cas_konce='',$pocet_kol=''){
    $x='a'.time().'b'.$idt.'c'.$id_modulu.'d'.$id_hry.'e'.$id_serveru.'f'.$id_typu_hry.'g'.$id_mapy.'h'.$id_uzivatele.'i'.$id_vyplaty.'j'.$datum_vytvoreni.'k'.$datum_cas_startu.'l'.$datum_cas_konce.'m'.$pocet_kol.'n'.rand(100000,999999).'o';
    $y=substr(md5($x),3,10);
    $exist=$this->getOne('`idt`',' WHERE `hash`="'.$y.'" ');
    if($exist>0){
      return $this->generateHash($idt,$id_modulu,$id_hry,$id_serveru,$id_typu_hry,$id_mapy,$id_uzivatele,$id_vyplaty,$datum_vytvoreni,$datum_cas_startu,$datum_cas_konce,$pocet_kol); 
      }
    return $y; 
    }
  }