<?php

class BGamePlatforms extends Module {
    public function __construct() {
        $this->parent_module = 'BGame';
    }

    public function Main() {}

    public function PageMain() {
        $return = new stdClass();
        $page = (int)getget('page', '0');
        $counter = 10;
        $list = $this->kernel->models->DBgamesPlatforms->getLines('*', 'order by nazev ASC LIMIT ' . ($page * $counter) . ', ' . $counter);
        $list_count = $this->kernel->models->DBgamesPlatforms->getOne('count(idgp)');
        $paginnator = $this->Paginnator($page, $list_count, $counter);

        foreach ($list as $lk => $lv) {
            $list[$lk]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platform-edit', 'idgp' => $lv->idgp, 'bp' => $page));
            $list[$lk]->adel = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platform-delete-post', 'idgp' => $lv->idgp));
        }

        $tpl = new Templater();
        $tpl->add('list', $list);
        $tpl->add('paginnator', $paginnator);
        $tpl->add('anew', $this->Anchor(array('module' => $this->parent_module, 'action' => 'platform-add-post'), false));

        $return->seo_title = 'Platformy';
        $return->content = $tpl->fetch('backend/games/platforms.tpl');

        return $return;
    }

    private function Paginnator($page = 0, $count = 0, $counter = 0) {
        $pages = array();
        $maxpage = 0;

        for ($i = 0; $i < ceil($count / $counter); $i++) {
            $pages[($i + 1)] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => $i), false);
            $maxpage = $i;
        }

        if (($page - 1) < 0) {
            $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => '0'), false);
        } else {
            $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => ($page - 1)), false);
        }

        if (($page + 1) > $maxpage) {
            $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => $maxpage), false);
        } else {
            $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => ($page + 1)), false);
        }

        return $pages;
    }

    public function PageEdit() {
        $idgp = (int)getget('idgp', '');
        $bp = (int)getget('bp', '');
        $data = $this->kernel->models->DBgamesPlatforms->getLine('*', ' WHERE idgp="' . $idgp . '"');
        $dataGames = $this->kernel->models->DBgames->getLines('idg,nazev', ' order by nazev asc');
        $dataGamesPlatforms = array();
        $dataGamesPlatforms2 = $this->kernel->models->DBgamesPlatformsGames->getLines('*', ' WHERE idgp="' . $idgp . '"');

        foreach ($dataGamesPlatforms2 as $dp2) {
            $dataGamesPlatforms[] = $dp2->idg;
        }

        if (!isset($data->idgp) || $data->idgp < 1) {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'platforms', 'message' => 'not-found'), false);
        }

        $return = new stdClass();
        $tpl = new Templater();
        $tpl->add('data', $data);
        $tpl->add('dataGames', $dataGames);
        $tpl->add('dataGamesPlatforms', $dataGamesPlatforms);
        $tpl->add('aedit', $this->Anchor(array('module' => $this->parent_module, 'action' => 'platform-edit-post', 'idgp' => $idgp), false));
        $tpl->add('aback', $this->Anchor(array('module' => $this->parent_module, 'action' => 'platforms', 'page' => $bp), false));
        $return->seo_title = 'Editace platformy';
        $return->content = $tpl->fetch('backend/games/platformsEdit.tpl');

        return $return;
    }

    public function PageAddPost() {
        $nazev = prepare_get_data_safely(getpost('nazev', ''));
        $aktivni = (int)getpost('aktivni', '');

        if ($nazev == '') {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'platforms', 'message' => 'not-created'), false);
        }

        $this->kernel->models->DBgamesPlatforms->store(0, array('nazev' => $nazev, 'aktivni' => $aktivni));
        $this->redirect(array('module' => $this->parent_module, 'action' => 'platforms', 'message' => 'created'), false);
    }

    public function PageEditPost() {
        $idgp = (int)getget('idgp', '');
        $nazev = prepare_get_data_safely(getpost('nazev', ''));
        $aktivni = (int)getpost('aktivni', '');

        if ($nazev == '') {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'platform-edit', 'message' => 'not-saved', 'idgp' => $idgp), false);
        }

        $this->kernel->models->DBgamesPlatforms->store($idgp, array('nazev' => $nazev, 'aktivni' => $aktivni));
        $this->kernel->models->DBrewrites->AddEditRewrite('tournaments/' . $nazev . '-p-' . $idgp . '/', 'FTournaments2', 'idgp', $idgp);
        $this->kernel->models->DBrewrites->AddEditRewrite('cups/' . $nazev . '-p-' . $idgp . '/', 'FCups2', 'idgp', $idgp);

        $this->kernel->models->DBgamesPlatformsGames->deleteWhere('WHERE idgp ="' . $idgp . '" ');

        if (isset($_POST['hry']) && count($_POST['hry']) > 0) {
            foreach ($_POST['hry'] as $xhp) {
                $this->kernel->models->DBgamesPlatformsGames->store(0, array('idgp' => $idgp, 'idg' => $xhp));
            }
        }

        $file = $_FILES["soubor"];

        if ($file["error"] <= 0 && $idgp > 0) {
            $pripony = explode(',', $this->kernel->settings['povolene_pripony_obrazku']);
            $original_name = explode('.', $file["name"]);
            $suffix = strtolower(end($original_name));

            if (in_array($suffix, $pripony)) {
                if (filesize($file["tmp_name"]) <= (str_replace('B', '', ini_get('upload_max_filesize')) * 1048576)) {
                    $suffix = 'png';

                    if (move_uploaded_file($file["tmp_name"], 'img/userfiles/platforms/' . $idgp . '_.' . $suffix)) {
                        $phpThumb = new MHMthumb();
                        $is = $phpThumb->thumb('img/userfiles/platforms/' . $idgp . '_.' . $suffix, 'img/userfiles/platforms/' . $idgp . '.' . $suffix, 473, 266, false, 25, 25, 25);
                    }
                }
            }
        }

        $this->redirect(array('module' => $this->parent_module, 'action' => 'platform-edit', 'message' => 'saved', 'idgp' => $idgp), false);
    }

    public function PageDeletePost() {
        $idgp = (int)getget('idgp', '');

        if ($idgp < 1) {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'platforms', 'message' => 'not-found'), false);
        }

        $this->kernel->models->DBgamesPlatforms->deleteId($idgp);
        $this->redirect(array('module' => $this->parent_module, 'action' => 'platforms', 'message' => 'deleted'), false);
    }
}
