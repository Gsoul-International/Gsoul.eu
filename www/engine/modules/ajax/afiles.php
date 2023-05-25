<?php

/*
* Modul na vracení šablon do editoru.
*/ 
class AFiles extends Module {
    public function __construct() {
        $this->parent_module = 'Ajax';
    }
    
    public function Main() {
        if ($this->kernel->user->uid == 0) {
            return '';
        } else {
            if ($this->kernel->user->data->prava < 1) {
                return '';
            }
        }
        
        $action = getget('action', 'list');
        
        if ($action == 'list') {
            $this->PageList();
        }
        
        return '';
    }
    
    public function PageList() {
        $submenu = $this->kernel->models->DBfilesCategories->getLines('idfc,nazev', 'order by nazev');
        $idfc = (int)getget('idfc', '0');
        $getOrder = getget('order', 'name');
        
        foreach ($submenu as $sk => $sv) {
            $submenu[$sk]->aview = $this->kernel->config->domain_http . $this->Anchor(array('action' => 'list', 'order' => $getOrder, 'idfc' => $sv->idfc), false);
            $submenu[$sk]->active = ($idfc == $sv->idfc);
        }
        
        $files = array();
        
        if ($idfc > 0) {
            $order = 'nazev asc';
            
            if ($getOrder == 'name') {
                $order = 'nazev asc';
            }
            
            if ($getOrder == 'namedesc') {
                $order = 'nazev desc';
            }
            
            if ($getOrder == 'time') {
                $order = 'vytvoreni_timestamp asc';
            }
            
            if ($getOrder == 'timedesc') {
                $order = 'vytvoreni_timestamp desc';
            }
            
            $files = $this->kernel->models->DBfiles->getLines('*', 'WHERE id_fc="' . $idfc . '" ORDER BY ' . $order);
            
            foreach ($files as $fk => $fv) {
                $files[$fk]->areturn = '/' . $fv->cesta;
            }
        }
        
        $tpl = new Templater();
        $tpl->add('submenu', $submenu);
        $tpl->add('getOrder', $getOrder);
        $tpl->add('aorders', array(
            'name' => $this->kernel->config->domain_http . $this->Anchor(array('action' => 'list', 'order' => 'name', 'idfc' => $idfc), false),
            'namedesc' => $this->kernel->config->domain_http . $this->Anchor(array('action' => 'list', 'order' => 'namedesc', 'idfc' => $idfc), false),
            'time' => $this->kernel->config->domain_http . $this->Anchor(array('action' => 'list', 'order' => 'time', 'idfc' => $idfc), false),
            'timedesc' => $this->kernel->config->domain_http . $this->Anchor(array('action' => 'list', 'order' => 'timedesc', 'idfc' => $idfc), false)
        ));
        
        $tpl->add('files', $files);
        echo $tpl->fetch('ajax/files/list.tpl');
    }
}
?>
