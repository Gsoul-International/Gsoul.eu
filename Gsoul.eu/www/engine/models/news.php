<?php

class DBnews extends Model {
    public function __construct() {
        $this->setTable('news');
        $this->setPrimaryKey('idn');
    }

    public function returnBreadcrumb($idn = 0, $last = true, $systemTranslator) {
        $idn = (int)$idn;
        $breadcrumbArray = array();
        $breadcrumb = new stdClass();
        $breadcrumb2 = new stdClass();
        $dataMainPage = $this->kernel->models->DBmainPages->getLine('nazev,zobrazovat_v_navigaci');
        $breadcrumb2->lastPage = 0;
        $breadcrumb2->idp = 0;
        $breadcrumb2->type = (-1);
        $breadcrumb2->show = $dataMainPage->zobrazovat_v_navigaci;
        $breadcrumb2->name = $dataMainPage->nazev;
        $breadcrumb2->urlPieces = array();
        $breadcrumbArray[] = $breadcrumb2;
        $breadcrumb3 = new stdClass();
        $breadcrumb3->lastPage = 0;
        $breadcrumb3->idn = 0;
        $breadcrumb3->type = 0;
        $breadcrumb3->show = 1;
        $breadcrumb3->name = $systemTranslator['obecne_novinky'];
        $breadcrumb3->urlPieces = array('module' => 'FNews');
        $breadcrumbArray[] = $breadcrumb3;
        $data = $this->getLine('idn,zobrazovat,zobrazovat_v_navigaci,nazev', 'WHERE idn="'.$idn.'"');
        if (@$data->idn > 0) {
            if ($data->zobrazovat == 1 && $data->zobrazovat_v_navigaci == 1) {
                $breadcrumb->show = 1;
            } else {
                $breadcrumb->show = 0;
            }
            $breadcrumb->lastPage = 1;
            $breadcrumb->idn = $data->idn;
            $breadcrumb->new_window = 0;
            $breadcrumb->name = $data->nazev;
            $breadcrumb->type = 0;
            $breadcrumb->link = '';
            $breadcrumb->urlPieces = array('module' => 'FNews', 'idn' => $idn);
            $breadcrumbArray[] = $breadcrumb;
        }
        return $breadcrumbArray;
    }
}
class DBnews extends Model {
    public function __construct() {
        $this->setTable('news');
        $this->setPrimaryKey('idn');
    }

    public function returnBreadcrumb($idn = 0, $last = true, $systemTranslator) {
        $idn = (int)$idn;
        $breadcrumbArray = array();
        $breadcrumb = new stdClass();
        $breadcrumb2 = new stdClass();
        $dataMainPage = $this->kernel->models->DBmainPages->getLine('nazev,zobrazovat_v_navigaci');
        $breadcrumb2->lastPage = 0;
        $breadcrumb2->idp = 0;
        $breadcrumb2->type = (-1);
        $breadcrumb2->show = $dataMainPage->zobrazovat_v_navigaci;
        $breadcrumb2->name = $dataMainPage->nazev;
        $breadcrumb2->urlPieces = array();
        $breadcrumbArray[] = $breadcrumb2;
        $breadcrumb3 = new stdClass();
        $breadcrumb3->lastPage = 0;
        $breadcrumb3->idn = 0;
        $breadcrumb3->type = 0;
        $breadcrumb3->show = 1;
        $breadcrumb3->name = $systemTranslator['obecne_novinky'];
        $breadcrumb3->urlPieces = array('module' => 'FNews');
        $breadcrumbArray[] = $breadcrumb3;
        $data = $this->getLine('idn,zobrazovat,zobrazovat_v_navigaci,nazev', 'WHERE idn="'.$idn.'"');
        if (@$data->idn > 0) {
            if ($data->zobrazovat == 1 && $data->zobrazovat_v_navigaci == 1) {
                $breadcrumb->show = 1;
            } else {
                $breadcrumb->show = 0;
            }
            $breadcrumb->lastPage = 1;
            $breadcrumb->idn = $data->idn;
            $breadcrumb->new_window = 0;
            $breadcrumb->name = $data->nazev;
            $breadcrumb->type = 0;
            $breadcrumb->link = '';
            $breadcrumb->urlPieces = array('module' => 'FNews', 'idn' => $idn);
            $breadcrumbArray[] = $breadcrumb;
        }
        return $breadcrumbArray;
    }
}
