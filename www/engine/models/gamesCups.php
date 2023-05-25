<?php

class DBgamesCups extends Model {
    public function __construct() {
        $this->setTable('games_cups');
        $this->setPrimaryKey('idc');
    }

    public function generateHash($idt = '', $id_modulu = '', $id_hry = '', $id_serveru = '', $id_typu_hry = '', $id_mapy = '', $id_uzivatele = '', $id_vyplaty = '', $datum_vytvoreni = '', $datum_cas_startu = '', $datum_cas_konce = '', $pocet_kol = '') {
        $x = 'z' . time() . 'y' . $idt . 'x' . $id_modulu . 'g' . $id_hry . 's' . $id_serveru . 'o' . $id_typu_hry . 'u' . $id_mapy . 'l' . $id_uzivatele . 'e' . $id_vyplaty . 'u' . $datum_vytvoreni . '2' . $datum_cas_startu . '0' . $datum_cas_konce . '1' . $pocet_kol . '9' . rand(100000, 999999) . 'y';
        $y = substr(md5($x), 3, 10);
        $exist = $this->getOne('`idc`', ' WHERE `hash`="' . $y . '" ');

        if ($exist > 0) {
            return $this->generateHash($idt, $id_modulu, $id_hry, $id_serveru, $id_typu_hry, $id_mapy, $id_uzivatele, $id_vyplaty, $datum_vytvoreni, $datum_cas_startu, $datum_cas_konce, $pocet_kol);
        }

        return $y;
    }
}