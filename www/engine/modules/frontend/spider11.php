<php


    private function generateSpider($pocetHracuTymu,$idealniPocetNaTurnaj,$pocetPostupujicich,$pocetVyher){  

    $pocetHracuTymuAktualniKolo=$pocetHracuTymu;

    $spider=array(); 

    $a=0;

    $xB=0;

    for ($kolo = 1;; $kolo++) {
    $xB = $kolo;

    if ($pocetHracuTymuAktualniKolo <= $pocetVyher || $pocetHracuTymuAktualniKolo == $idealniPocetNaTurnaj) {
        $spider[$kolo] = new stdClass();

        $spider[$kolo]->kolo = $kolo;
        $spider[$kolo]->celkovyPocetHracuTymu = $pocetHracuTymuAktualniKolo;
        $spider[$kolo]->celkovyPocetTurnaju = 1;
        $spider[$kolo]->pocetPlneObsazenychTurnaju = 1;
        $spider[$kolo]->pocetPostupujicich = 0;
        $spider[$kolo]->pocetHracuTymuPosledniKolo = $pocetHracuTymuAktualniKolo;
        $spider[$kolo]->PocetHracuPlnyZapas = $pocetPostupujicich;
        $spider[$kolo]->PocetPostupujicichPlnyZapas = 0;
        $spider[$kolo]->PocetHracuPosledniKolo = $pocetHracuTymuAktualniKolo;
        $spider[$kolo]->PocetPostupujicichPosledniKolo = 0;
        break;
    }
    
<php    
    $spider[$kolo] = new stdClass();
    $spider[$kolo]->kolo = $kolo;
    $spider[$kolo]->celkovyPocetHracuTymu = $pocetHracuTymuAktualniKolo;
    $spider[$kolo]->celkovyPocetTurnaju = ceil($pocetHracuTymuAktualniKolo / $idealniPocetNaTurnaj);
    $spider[$kolo]->pocetPlneObsazenychTurnaju = floor($pocetHracuTymuAktualniKolo / $idealniPocetNaTurnaj);
    $spider[$kolo]->pocetPostupujicich = $spider[$kolo]->pocetPlneObsazenychTurnaju * $pocetPostupujicich;
    $spider[$kolo]->pocetHracuTymuPosledniKolo = $pocetHracuTymuAktualniKolo - ($spider[$kolo]->pocetPlneObsazenychTurnaju * $idealniPocetNaTurnaj);

    $spider[$kolo]->PocetHracuPlnyZapas = $idealniPocetNaTurnaj;
    $spider[$kolo]->PocetPostupujicichPlnyZapas = $pocetPostupujicich;
    $spider[$kolo]->PocetHracuPosledniKolo = $idealniPocetNaTurnaj;
    $spider[$kolo]->PocetPostupujicichPosledniKolo = $pocetPostupujicich;

    if ($spider[$kolo]->pocetPlneObsazenychTurnaju < $spider[$kolo]->celkovyPocetTurnaju) {
        if ($spider[$kolo]->pocetHracuTymuPosledniKolo >= $pocetPostupujicich) {
            $spider[$kolo]->pocetPostupujicich += $pocetPostupujicich;
            $spider[$kolo]->PocetPostupujicichPosledniKolo = $pocetPostupujicich;
            $spider[$kolo]->PocetHracuPosledniKolo = $spider[$kolo]->pocetHracuTymuPosledniKolo;
        } else {
            $spider[$kolo]->pocetPostupujicich += $spider[$kolo]->pocetHracuTymuPosledniKolo;
            $spider[$kolo]->PocetPostupujicichPosledniKolo = $spider[$kolo]->pocetHracuTymuPosledniKolo;
            $spider[$kolo]->PocetHracuPosledniKolo = $spider[$kolo]->pocetHracuTymuPosledniKolo;
        }
    }

    $pocetHracuTymuAktualniKolo = $spider[$kolo]->pocetPostupujicich;
    if ($spider[$kolo]->celkovyPocetTurnaju == 1) {
        $spider[$kolo]->PocetPostupujicichPosledniKolo = 0;
        break;
    }
}

$spider[$xB]->PocetPostupujicichPosledniKolo = 0;
return $spider;