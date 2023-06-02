<?php

class DBboxes extends Model {
    public function __construct() {
        $this->setTable('boxes');
        $this->setPrimaryKey('idb');
    }

    public function ReturnModules() {
        $modules = array(
            'text' => 'Textový prvek',
            'gallery' => 'Fotogalerie',
            'news' => 'Novinky',
            'howtoplay' => 'How to play',
            //'contact_form'=>'Kontaktní formulář',
            //'files'=>'Soubory ke stažení',            
            'pages_menu' => 'Menu stránek',
            //'news_sign'=>'Přihlášení k odběru novinek'      
        );
        return $modules;
    }

    public function ReturnVariableBoxes() {
        $boxes = $this->kernel->models->DBboxes->getLines('idb,id_bc,nazev,modul,zobrazovat', ' ORDER BY id_bc,poradi');
        $boxesCategories = array();
        $bcx = $this->kernel->models->DBboxesCategories->getLines('idbc,nazev', 'WHERE zobrazovat_admin=1 ORDER BY poradi');

        foreach ($bcx as $b) {
            $boxesCategories[$b->idbc] = $b->nazev;
        }

        unset($bcx);
        $modules = $this->ReturnModules();

        foreach ($boxes as $bk => $bv) {
            if (!isset($boxesCategories[$bv->id_bc])) {
                unset($boxes[$bk]);
                continue;
            }

            $boxes[$bk]->_promenna = '{box_' . $bv->id_bc . '_' . $bv->idb . '}';
            $boxes[$bk]->_kategorie = $boxesCategories[$bv->id_bc];
            $boxes[$bk]->_modul = $modules[$bv->modul];
        }

        return $boxes;
    }
}

?>