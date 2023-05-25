<?php

class BGameCups extends Module {
    public function __construct() {
        $this->parent_module = 'BGame';
    }

    public function Main() {}

    public function PageMain() {
        $return = new stdClass();
        $filter = $this->getCupsFilter();
        $page = (int)getget('page', '0');
        $counter = 10;
        $andGroup = array();
        $andWhere = '';

        if ($filter->f_hra > 0) {
            $andGroup[] = ' id_hry="' . ((int)$filter->f_hra) . '" ';
        }

        if ($filter->f_zobrazeni == 1) {
            $andGroup[] = ' skryty=0 ';
        }

        if ($filter->f_zobrazeni == 2) {
            $andGroup[] = ' skryty=1 ';
        }

        if ($filter->f_zahajeno == 1) {
            $andGroup[] = ' zahajeno=1 ';
        }

        if ($filter->f_zahajeno == 2) {
            $andGroup[] = ' zahajeno=0 ';
        }

        if ($filter->f_dohrano == 1) {
            $andGroup[] = ' dohrano=1 ';
        }

        if ($filter->f_dohrano == 2) {
            $andGroup[] = ' dohrano=0 ';
        }

        if (count($andGroup) > 0) {
            $andWhere = ' WHERE ' . implode(' AND ', $andGroup) . ' ';
        }

        $list = $this->kernel->models->DBgamesCups->getLines('*', $andWhere . 'order by datum_cas_startu DESC LIMIT ' . ($page * $counter) . ', ' . $counter);
        $list_count = $this->kernel->models->DBgamesCups->getOne('count(idc)', $andWhere);
        $paginnator = $this->Paginnator($page, $list_count, $counter);
        $gaxx = array('0');
        $tgxx = array('0');

        foreach ($list as $lk => $lv) {
            $gaxx[] = $lv->id_hry;
            $tgxx[] = $lv->id_typu_hry;
            $list[$lk]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups-edit', 'idc' => $lv->idc));
        }

        $gaxy = $this->kernel->models->DBgames->getLines('*', 'WHERE idg in (' . implode(',', $gaxx) . ')');
        $tgxy = $this->kernel->models->DBgamesTypes->getLines('*', 'WHERE idgt in (' . implode(',', $tgxx) . ')');
        $moxy = $this->kernel->models->DBgamesModules->getLines('*','order by nazev ASC ');

        $games2 = array('0' => ' - Nezadáno - ');
        $types2 = array('0' => ' - Nezadáno - ');
        $modules2 = array('0' => ' - Nezadáno - ');
        $games3 = array('0' => ' - Nezadáno - ');

        foreach ($gaxy as $gx) {
            $games2[$gx->idg] = $gx->nazev;
        }

        foreach ($tgxy as $tx) {
            $types2[$tx->idgt] = $tx->nazev;
        }

        foreach ($moxy as $mx) {
            $modules2[$mx->idm] = $mx->nazev;
        }

        $gaxy3 = $this->kernel->models->DBgames->getLines('idg,nazev', 'ORDER BY nazev');

        foreach ($gaxy3 as $gx3) {
            $games3[$gx3->idg] = $gx3->nazev;
        }

        $tpl = new Templater();
        $tpl->add('list', $list);
        $tpl->add('paginnator', $paginnator);
        $tpl->add('games2', $games2);
        $tpl->add('types2', $types2);
        $tpl->add('modules2', $modules2);
        $tpl->add('games3', $games3);
        $tpl->add('f_zobrazeni', $filter->f_zobrazeni);
        $tpl->add('f_zahajeno', $filter->f_zahajeno);
        $tpl->add('f_dohrano', $filter->f_dohrano);
        $tpl->add('f_hra', $filter->f_hra);
        $tpl->add('afilter', $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups-set-filter')));
        $return->seo_title = 'Turnaje ';
        $return->content = $tpl->fetch('backend/games/cups.tpl');
        return $return;
    }

    public function PageEdit() {
        $idc = (int)getget('idc', '');
        $data = $this->kernel->models->DBgamesCups->getLine('*', ' WHERE idc="' . $idc . '"');

        if (!isset($data->idc) || $data->idc < 1) {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'cups', 'message' => 'not-found'), false);
        }

        $data->adetailcreator = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $data->id_uzivatele), false);
        $game = $this->kernel->models->DBgames->getLine('*', 'WHERE idg="' . $data->id_hry . '"');
        $type = $this->kernel->models->DBgamesTypes->getLine('*', 'WHERE idgt="' . $data->id_typu_hry . '"');
        $module = $this->kernel->models->DBgamesModules->getLine('*', 'WHERE idm="' . $data->id_modulu . '"');
        $moduleGames = $this->kernel->models->DBgamesModulesVsGames->getLine('*', 'WHERE idmod="' . $data->id_modulu . '" AND idgam="' . $data->id_hry . '"');
        $players = $this->kernel->models->DBgamesCupsPlayers->getLines('*', 'WHERE id_cupu="' . $data->idc . '" ORDER BY skore DESC');
        $playersArr = array('0', $data->id_uzivatele);

        foreach ($players as $kpl => $pl) {
            $playersArr[] = $pl->id_hrace;
            $playersArr2[] = $pl->id_hrace;
            $players[$kpl]->adetail = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $pl->id_hrace), false);
        }

        $users = $this->kernel->models->DBusers->getLines('uid,osloveni', 'WHERE uid in (' . implode(',', $playersArr) . ')');
        $users2 = array();

        foreach ($users as $us) {
            $users2[$us->uid] = $us;
        }

        $paramsIds = array('0');
        $params = $this->kernel->models->DBgamesParameters->getLines('*', ' WHERE idg="' . $data->id_hry . '" ORDER BY nazev');

        foreach ($params as $pk => $pv) {
            $paramsIds[$pv->idp] = $pv->idp;
        }

        $subParams = array();
        $subParamsq = $this->kernel->models->DBgamesParametersValues->getLines('*', ' WHERE idp in (' . implode(',', $paramsIds) . ') ORDER BY nazev');

        foreach ($subParamsq as $pk => $pv) {
            $subParams[$pv->idp][$pv->idpv] = $pv;
        }

        unset($subParamsq);
        $setParams = array();
        $subParamsq = $this->kernel->models->DBgamesCupsParameters->getLines('*', ' WHERE id_cupu = "' . $idc . '" ');

        foreach ($subParamsq as $sPq) {
            $setParams[] = $sPq->id_hodnoty_parametru;
        }

        unset($subParamsq);

        if ($data->hraji_tymy == 1) {
            $loggedTeams = $this->kernel->models->DBusers->MqueryGetLines('SELECT t.* FROM teams as t, games_cups_teams as g  WHERE g.id_cupu="' . $data->idc . '" AND g.id_tymu=t.idt AND t.id_hry="' . $data->id_hry . '" ORDER BY t.nazev ASC');
            $loggedTeams2 = array();

            foreach ($loggedTeams as $lTx) {
                $loggedTeams2[$lTx->idt] = $lTx;
            }
        } else {
            $loggedTeams = array();
            $loggedTeams2 = array();
        }

        ///
        $alters = $this->kernel->models->DBgamesCupsAlternatesPlayers->getLines('*', 'WHERE id_turnaje="' . $data->idc . '" ORDER BY id_tymu DESC');
        $altersArr = array('0');
        $altersArr2 = array();

        foreach ($alters as $kat => $at) {
            $altersArr[] = $at->id_hrace;
            $altersArr2[] = $at->id_hrace;
            $alters[$kat]->adetail = $this->Anchor(array('module' => 'BUsers', 'action' => 'detail', 'uid' => $at->id_hrace), false);
        }

        $users3 = $this->kernel->models->DBusers->getLines('uid,osloveni', 'WHERE uid in (' . implode(',', $altersArr) . ')');
        $users4 = array();

        foreach ($users3 as $us) {
            $users4[$us->uid] = $us;
        }

    ///
    $return = new stdClass();
$tpl = new Templater();

$tpl->add('data', $data);
$tpl->add('game', $game);
$tpl->add('type', $type);
$tpl->add('module', $module);
$tpl->add('alters', $alters);
$tpl->add('moduleGames', $moduleGames);
$tpl->add('users', $users2);
$tpl->add('usersA', $users4);
$tpl->add('players', $players);
$tpl->add('playersArr', $playersArr2);
$tpl->add('aback', $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups'), false));
$tpl->add('aview', $this->Anchor(array('module' => 'FCups', 'action' => 'cup-view', 'idc' => $data->idc), false));
$tpl->add('achangeView', $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups-change-view', 'idc' => $data->idc), false));
$tpl->add('achangeBanner', $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups-change-banner', 'idc' => $data->idc), false));
$tpl->add('banner', $this->kernel->GetEditor('banner', $data->banner));
$tpl->add('params', $params);
$tpl->add('subParams', $subParams);
$tpl->add('setParams', $setParams);
$tpl->add('loggedTeams', $loggedTeams);
$tpl->add('loggedTeams2', $loggedTeams2);

$return->seo_title = 'Detail turnaje';
$return->content = $tpl->fetch('backend/games/cupsEdit.tpl');

return $return;
}

private function Paginnator($page = 0, $count = 0, $counter = 0)
{
    $pages = array();
    $maxpage = 0;

    for ($i = 0; $i < ceil($count / $counter); $i++) {
        $pages[($i + 1)] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups', 'page' => $i), false);
        $maxpage = $i;
    }

    if (($page - 1) < 0) {
        $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups', 'page' => '0'), false);
    } else {
        $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups', 'page' => ($page - 1)), false);
    }

    if (($page + 1) > $maxpage) {
        $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups', 'page' => $maxpage), false);
    } else {
        $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'cups', 'page' => ($page + 1)), false);
    }

    return $pages;
}

public function PageCupsChangeBanner()
{
    $idc = (int)getget('idc', '');
    $banner = prepare_get_data_safely_editor(getpost('banner', ''));

    if ($idc < 1) {
        $this->redirect(array('module' => $this->parent_module, 'action' => 'cups-edit', 'idc' => $idc), false);
    }

    $this->kernel->models->DBgamesCups->store($idc, array('banner' => $banner));
    $this->redirect(array('module' => $this->parent_module, 'action' => 'cups-edit', 'message' => 'viewing-saved', 'idc' => $idc), false);
}

public function PageCupsChangeView()
{
    $idc = (int)getget('idc', '');
    $skryty = (int)getpost('skryty', '');

    if ($idc < 1) {
        $this->redirect(array('module' => $this->parent_module, 'action' => 'cups-edit', 'idc' => $idc), false);
    }

    $this->kernel->models->DBgamesCups->store($idc, array('skryty' => $skryty));
    $this->redirect(array('module' => $this->parent_module, 'action' => 'cups-edit', 'message' => 'viewing-saved', 'idc' => $idc), false);
}

public function PageCupsSetFilter()
{
    $obj = new stdClass();
    $obj->f_zobrazeni = (int)getpost('f_zobrazeni');
    $obj->f_zahajeno = (int)getpost('f_zahajeno');
    $obj->f_dohrano = (int)getpost('f_dohrano');
    $obj->f_hra = (int)getpost('f_hra');
    $_SESSION['tournaments-backend-filter'] = $obj;
    $this->redirect(array('module' => $this->parent_module, 'action' => 'cups'), false);
}

private function getCupsFilter()
{
    if (isset($_SESSION['tournaments-backend-filter'])) {
        return $_SESSION['tournaments-backend-filter'];
    }

    $obj = new stdClass();
    $obj->f_zobrazeni = 0;
    $obj->f_zahajeno = 0;
    $obj->f_dohrano = 0;
    $obj->f_hra = 0;
    $_SESSION['tournaments-backend-filter'] = $obj;
    return $obj;
}
