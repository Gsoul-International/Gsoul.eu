<?php

class BGameGames extends Module {
    public function __construct()
    {
        $this->parent_module = 'BGame';
    }

    public function Main()
    {
    }

    public function PageMain()
    {
        $return = new stdClass();
        $page = (int)getget('page', '0');
        $counter = 10;
        $list = $this->kernel->models->DBgames->getLines('idg,nazev,aktivni', 'order by nazev ASC LIMIT ' . ($page * $counter) . ', ' . $counter);
        $list_count = $this->kernel->models->DBgames->getOne('count(idg)');
        $paginnator = $this->Paginnator($page, $list_count, $counter);

        foreach ($list as $lk => $lv) {
            $list[$lk]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit', 'idg' => $lv->idg, 'bp' => $page));
        }

        $tpl = new Templater();
        $tpl->add('list', $list);
        $tpl->add('paginnator', $paginnator);
        $tpl->add('anew', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-add-post'), false));

        $return->seo_title = 'Hry';
        $return->content = $tpl->fetch('backend/games/games.tpl');

        return $return;
    }

    private function Paginnator($page = 0, $count = 0, $counter = 0)
    {
        $pages = array();
        $maxpage = 0;

        for ($i = 0; $i < ceil($count / $counter); $i++) {
            $pages[($i + 1)] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => $i), false);
            $maxpage = $i;
        }

        if (($page - 1) < 0) {
            $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => '0'), false);
        } else {
            $pages['prew'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => ($page - 1)), false);
        }

        if (($page + 1) > $maxpage) {
            $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => $maxpage), false);
        } else {
            $pages['next'] = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => ($page + 1)), false);
        }

        return $pages;
    }

    public function PageEdit()
    {
        $idg = (int)getget('idg', '');
        $bp = (int)getget('bp', '');

        $moduleGame = $this->kernel->models->DBgamesModulesVsGames->getLine('*', 'WHERE idmod="1" AND idgam="' . $idg . '"');
        $moduleGame->maxHracuTymu = ($moduleGame->maximalni_pocet_hracu > $moduleGame->maximalni_pocet_tymu ? $moduleGame->maximalni_pocet_hracu : $moduleGame->maximalni_pocet_tymu);

        $data = $this->kernel->models->DBgames->getLine('*', ' WHERE idg="' . $idg . '"');

        if (!isset($data->idg) || $data->idg < 1) {
            $this->redirect(array('module' => $this->parent_module, 'action' => 'games', 'message' => 'not-found'), false);
        }

        $types = $this->kernel->models->DBgamesTypes->getLines('*', ' WHERE idg="' . $idg . '" ORDER BY nazev');

        foreach ($types as $sk => $sv) {
            $types[$sk]->aedittype = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-type-post', 'idg' => $idg, 'page' => $bp, 'idgs' => $sv->idgt), false);
        }

        $winners = $this->kernel->models->DBgamesWinnerTypes->getLines('*', ' WHERE idg="' . $idg . '" ORDER BY nazev');

        foreach ($winners as $wk => $wv) {
            $winners[$wk]->aeditwin = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-winner-post', 'idg' => $idg, 'page' => $bp, 'idgwt' => $wv->idgwt), false);
        }

        $paramsIds = array('0');
        $params = $this->kernel->models->DBgamesParameters->getLines('*', ' WHERE idg="' . $idg . '" ORDER BY poradi,nazev');

        foreach ($params as $pk => $pv) {
            $paramsIds[$pv->idp] = $pv->idp;
            $params[$pk]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-param-post', 'idg' => $idg, 'page' => $bp, 'idp' => $pv->idp), false);
            $params[$pk]->aadd = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-add-subparam-post', 'idg' => $idg, 'page' => $bp, 'idp' => $pv->idp), false);
        }

        $subParams = array();
        $subParamsq = $this->kernel->models->DBgamesParametersValues->getLines('*', ' WHERE idp in (' . implode(',', $paramsIds) . ') ORDER BY poradi,nazev');

        foreach ($subParamsq as $pk => $pv) {
            $subParams[$pv->idp][$pv->idpv] = $pv;
            $subParams[$pv->idp][$pv->idpv]->aedit = $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-subparam-post', 'idg' => $idg, 'page' => $bp, 'idpv' => $pv->idpv), false);
        }

        unset($subParamsq);

        $return = new stdClass();
        $tpl = new Templater();
        $tpl->add('data', $data);
        $tpl->add('types', $types);
        $tpl->add('moduleGame', $moduleGame);
        $tpl->add('winners', $winners);
        $tpl->add('params', $params);
        $tpl->add('subParams', $subParams);
        $tpl->add('aedit', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-post', 'idg' => $idg), false));
        $tpl->add('aback', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games', 'page' => $bp), false));
        $tpl->add('aaddtype', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-add-type-post', 'idg' => $idg, 'page' => $bp), false));
        $tpl->add('aaddwinner', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-add-winner-post', 'idg' => $idg, 'page' => $bp), false));
        $tpl->add('aeditparams', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-edit-parameters', 'idg' => $idg, 'page' => $bp), false));
        $tpl->add('anew', $this->Anchor(array('module' => $this->parent_module, 'action' => 'games-add-param-post', 'idg' => $idg, 'bp' => $bp), false));
        $return->seo_title = 'Editace hry';
        $return->content = $tpl->fetch('backend/games/gamesEdit.tpl');
        return $return;
    }


  public function PageGamesAddParameterPost()
{
    $idg = (int)getget('idg', '');
    $bp = (int)getget('bp', '');
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $typ_v_turnaji_cupu = (int)getpost('typ_v_turnaji_cupu', '');
    $typ_v_tabulce = (int)getpost('typ_v_tabulce', '');
    $zobrazovat_v_tabulce = (int)getpost('zobrazovat_v_tabulce', '');
    $poradi = (int)getpost('poradi', '');

    $this->kernel->models->DBgamesParameters->store(0, array(
        'nazev' => $nazev,
        'idg' => $idg,
        'poradi' => $poradi,
        'typ_v_turnaji_cupu' => $typ_v_turnaji_cupu,
        'typ_v_tabulce' => $typ_v_tabulce,
        'zobrazovat_v_tabulce' => $zobrazovat_v_tabulce
    ));

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'created',
        'idg' => $idg,
        'bp' => $bp
    ), false);
}

public function PageGamesEditParameterPost()
{
    $idg = (int)getget('idg', '');
    $idp = (int)getget('idp', '');
    $bp = (int)getget('bp', '');
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $typ_v_turnaji_cupu = (int)getpost('typ_v_turnaji_cupu', '');
    $typ_v_tabulce = (int)getpost('typ_v_tabulce', '');
    $zobrazovat_v_tabulce = (int)getpost('zobrazovat_v_tabulce', '');
    $poradi = (int)getpost('poradi', '');

    if ($idp > 0) {
        $this->kernel->models->DBgamesParameters->store($idp, array(
            'nazev' => $nazev,
            'typ_v_turnaji_cupu' => $typ_v_turnaji_cupu,
            'typ_v_tabulce' => $typ_v_tabulce,
            'zobrazovat_v_tabulce' => $zobrazovat_v_tabulce,
            'poradi' => $poradi
        ));
    }

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'edited',
        'idg' => $idg,
        'bp' => $bp
    ), false);
}

public function PageGamesAddSubParameterPost()
{
    $idg = (int)getget('idg', '');
    $bp = (int)getget('bp', '');
    $idp = (int)getget('idp', '');
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $aktivni = (int)getpost('aktivni', '');
    $poradi = (int)getpost('poradi', '');

    if ($idp > 0) {
        $this->kernel->models->DBgamesParametersValues->store(0, array(
            'nazev' => $nazev,
            'idp' => $idp,
            'poradi' => $poradi,
            'aktivni' => $aktivni
        ));
    }

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'sub-created',
        'idg' => $idg,
        'bp' => $bp
    ), false);
}

public function PageGamesEditSubParameterPost()
{
    $idg = (int)getget('idg', '');
    $bp = (int)getget('bp', '');
    $idpv = (int)getget('idpv', '');
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $aktivni = (int)getpost('aktivni', '');
    $poradi = (int)getpost('poradi', '');

    if ($idpv > 0) {
        $this->kernel->models->DBgamesParametersValues->store($idpv, array(
            'nazev' => $nazev,
            'poradi' => $poradi,
            'aktivni' => $aktivni
        ));
    }

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'sub-edited',
        'idg' => $idg,
        'bp' => $bp
    ), false);
}


  public function PageAddPost()
{
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $aktivni = (int)getpost('aktivni', '');

    if ($nazev == '') {
        $this->redirect(array(
            'module' => $this->parent_module,
            'action' => 'games',
            'message' => 'not-created'
        ), false);
    }

    $this->kernel->models->DBgames->store(0, array(
        'nazev' => $nazev,
        'aktivni' => $aktivni
    ));

    $this->kernel->models->DBrewrites->AddEditRewrite(
        'tournaments/' . $nazev . '-' . $idg . '/',
        'FTournaments',
        'idg',
        $idg
    );

    $this->kernel->models->DBrewrites->AddEditRewrite(
        'cups/' . $nazev . '-' . $idg . '/',
        'FCups',
        'idg',
        $idg
    );

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games',
        'message' => 'created'
    ), false);
}

public function PageEditPost()
{
    $idg = (int)getget('idg', '');
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $mail_ukonceni_zapasu = prepare_get_data_safely(getpost('mail_ukonceni_zapasu', ''));
    $pravidla_turnaje = prepare_get_data_safely(getpost('pravidla_turnaje', ''));
    $podrobna_pravidla_turnaje = prepare_get_data_safely(getpost('podrobna_pravidla_turnaje', ''));
    $zobraz_buyin = (int)getpost('zobraz_buyin', '');
    $zobraz_typhry = (int)getpost('zobraz_typhry', '');
    $zobraz_pocethracu = (int)getpost('zobraz_pocethracu', '');
    $zobraz_datumzahajeni = (int)getpost('zobraz_datumzahajeni', '');
    $zobraz_pravidla = (int)getpost('zobraz_pravidla', '');
    $zobrazit_dohrano = (int)getpost('zobrazit_dohrano', '');
    $zobraz_login = (int)getpost('zobraz_login', '');
    $aktivni = (int)getpost('aktivni', '');
    $zaklada_jen_admin = (int)getpost('zaklada_jen_admin', '');

    if ($nazev == '') {
        $this->redirect(array(
            'module' => $this->parent_module,
            'action' => 'games-edit',
            'message' => 'not-saved',
            'idg' => $idg
        ), false);
    }

    $this->kernel->models->DBgames->store($idg, array(
        'nazev' => $nazev,
        'mail_ukonceni_zapasu' => $mail_ukonceni_zapasu,
        'aktivni' => $aktivni,
        'pravidla_turnaje' => $pravidla_turnaje,
        'podrobna_pravidla_turnaje' => $podrobna_pravidla_turnaje,
        'zobraz_buyin' => $zobraz_buyin,
        'zobraz_typhry' => $zobraz_typhry,
        'zobraz_pocethracu' => $zobraz_pocethracu,
        'zobraz_datumzahajeni' => $zobraz_datumzahajeni,
        'zobraz_pravidla' => $zobraz_pravidla,
        'zobrazit_dohrano' => $zobrazit_dohrano,
        'zobraz_login' => $zobraz_login,
        'zaklada_jen_admin' => $zaklada_jen_admin
    ));

    $this->kernel->models->DBrewrites->AddEditRewrite(
        'tournaments/' . $nazev . '-' . $idg . '/',
        'FTournaments',
        'idg',
        $idg
    );

    $this->kernel->models->DBrewrites->AddEditRewrite(
        'cups/' . $nazev . '-' . $idg . '/',
        'FCups',
        'idg',
        $idg
    );

    $file = $_FILES["soubor"];
    if ($file["error"] <= 0 && $idg > 0) {
        $pripony = explode(',', $this->kernel->settings['povolene_pripony_obrazku']);
        $original_name = explode('.', $file["name"]);
        $suffix = strtolower(end($original_name));

        if (in_array($suffix, $pripony)) {
            if (filesize($file["tmp_name"]) <= (str_replace('B', '', ini_get('upload_max_filesize')) * 1048576)) {
                $suffix = 'png';

                if (move_uploaded_file($file["tmp_name"], 'img/userfiles/games/' . $idg . '_.' . $suffix)) {
                    $phpThumb = new MHMthumb();
                    $is = $phpThumb->thumb('img/userfiles/games/' . $idg . '_.' . $suffix, 'img/userfiles/games/' . $idg . '.' . $suffix, 473, 266, false, 25, 25, 25);
                }
            }
        }
    }

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'saved',
        'idg' => $idg
    ), false);
}


public function PageAddTypePost()
{
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $idg = (int)getget('idg', '');
    $page = (int)getget('page', '');
    $aktivni = (int)getpost('aktivni', '');
    $tournament_id_vyplaty = (int)getpost('tournament_id_vyplaty', '');
    $tournament_minimalni_pocet_hracu = (int)getpost('tournament_minimalni_pocet_hracu', '');
    $tournament_maximalni_pocet_hracu = (int)getpost('tournament_maximalni_pocet_hracu', '');
    $tournament_minimalni_pocet_tymu = (int)getpost('tournament_minimalni_pocet_tymu', '');
    $tournament_maximalni_pocet_tymu = (int)getpost('tournament_maximalni_pocet_tymu', '');
    $cup_id_vyplaty = (int)getpost('cup_id_vyplaty', '');
    $cup_minimalni_pocet_hracutymu = (int)getpost('cup_minimalni_pocet_hracutymu', '');
    $cup_maximalni_pocet_hracutymu = (int)getpost('cup_maximalni_pocet_hracutymu', '');
    $cup_pocet_postupujicich_hracutymu = (int)getpost('cup_pocet_postupujicich_hracutymu', '');
    $cup_idealni_pocet_hracu_v_tymu = (int)getpost('cup_idealni_pocet_hracu_v_tymu', '');
    $cup_idealni_pocet_hracutymu_na_turnaj = (int)getpost('cup_idealni_pocet_hracutymu_na_turnaj', '');

    if ($nazev == '' || $idg < 1) {
        $this->redirect(array('module' => $this->parent_module, 'action' => 'games-edit', 'message' => 'type-not-added', 'idg' => $idg, 'bp' => $page), false);
    }

    $this->kernel->models->DBgamesTypes->store(0, array(
        'nazev' => $nazev,
        'idg' => $idg,
        'aktivni' => $aktivni,
        'tournament_id_vyplaty' => $tournament_id_vyplaty,
        'tournament_minimalni_pocet_hracu' => $tournament_minimalni_pocet_hracu,
        'tournament_maximalni_pocet_hracu' => $tournament_maximalni_pocet_hracu,
        'tournament_minimalni_pocet_tymu' => $tournament_minimalni_pocet_tymu,
        'tournament_maximalni_pocet_tymu' => $tournament_maximalni_pocet_tymu,
        'cup_id_vyplaty' => $cup_id_vyplaty,
        'cup_minimalni_pocet_hracutymu' => $cup_minimalni_pocet_hracutymu,
        'cup_maximalni_pocet_hracutymu' => $cup_maximalni_pocet_hracutymu,
        'cup_pocet_postupujicich_hracutymu' => $cup_pocet_postupujicich_hracutymu,
        'cup_idealni_pocet_hracu_v_tymu' => $cup_idealni_pocet_hracu_v_tymu,
        'cup_idealni_pocet_hracutymu_na_turnaj' => $cup_idealni_pocet_hracutymu_na_turnaj
    ));

    $this->redirect(array('module' => $this->parent_module, 'action' => 'games-edit', 'message' => 'type-added', 'idg' => $idg, 'bp' => $page), false);
}

public function PageEditTypePost()
{
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $idg = (int)getget('idg', '');
    $idgs = (int)getget('idgs', '');
    $page = (int)getget('page', '');
    $aktivni = (int)getpost('aktivni', '');
    $tournament_id_vyplaty = (int)getpost('tournament_id_vyplaty', '');
    $tournament_minimalni_pocet_hracu = (int)getpost('tournament_minimalni_pocet_hracu', '');
    $tournament_maximalni_pocet_hracu = (int)getpost('tournament_maximalni_pocet_hracu', '');
    $tournament_minimalni_pocet_tymu = (int)getpost('tournament_minimalni_pocet_tymu', '');
    $tournament_maximalni_pocet_tymu = (int)getpost('tournament_maximalni_pocet_tymu', '');
    $cup_id_vyplaty = (int)getpost('cup_id_vyplaty', '');
    $cup_minimalni_pocet_hracutymu = (int)getpost('cup_minimalni_pocet_hracutymu', '');
    $cup_maximalni_pocet_hracutymu = (int)getpost('cup_maximalni_pocet_hracutymu', '');
    $cup_pocet_postupujicich_hracutymu = (int)getpost('cup_pocet_postupujicich_hracutymu', '');
    $cup_idealni_pocet_hracu_v_tymu = (int)getpost('cup_idealni_pocet_hracu_v_tymu', '');
    $cup_idealni_pocet_hracutymu_na_turnaj = (int)getpost('cup_idealni_pocet_hracutymu_na_turnaj', '');

    if ($nazev == '' || $idg < 1 || $idgs < 1) {
        $this->redirect(array('module' => $this->parent_module, 'action' => 'games-edit', 'message' => 'type-not-saved', 'idg' => $idg, 'bp' => $page), false);
    }

    $this->kernel->models->DBgamesTypes->store($idgs, array(
        'nazev' => $nazev,
        'aktivni' => $aktivni,
        'tournament_id_vyplaty' => $tournament_id_vyplaty,
        'tournament_minimalni_pocet_hracu' => $tournament_minimalni_pocet_hracu,
        'tournament_maximalni_pocet_hracu' => $tournament_maximalni_pocet_hracu,
        'tournament_minimalni_pocet_tymu' => $tournament_minimalni_pocet_tymu,
        'tournament_maximalni_pocet_tymu' => $tournament_maximalni_pocet_tymu,
        'cup_id_vyplaty' => $cup_id_vyplaty,
        'cup_minimalni_pocet_hracutymu' => $cup_minimalni_pocet_hracutymu,
        'cup_maximalni_pocet_hracutymu' => $cup_maximalni_pocet_hracutymu,
        'cup_pocet_postupujicich_hracutymu' => $cup_pocet_postupujicich_hracutymu,
        'cup_idealni_pocet_hracu_v_tymu' => $cup_idealni_pocet_hracu_v_tymu,
        'cup_idealni_pocet_hracutymu_na_turnaj' => $cup_idealni_pocet_hracutymu_na_turnaj
    ));

    $this->redirect(array('module' => $this->parent_module, 'action' => 'games-edit', 'message' => 'type-saved', 'idg' => $idg, 'bp' => $page), false);
}

public function PageAddWinnerPost()
{
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $idg = (int)getget('idg', '');
    $page = (int)getget('page', '');
    $aktivni = (int)getpost('aktivni', '');
    $winners_count = (int)getpost('winners_count', '');

    if ($nazev == '' || $idg < 1 || $winners_count < 1 || $winners_count > 10) {
        $this->redirect(array(
            'module' => $this->parent_module,
            'action' => 'games-edit',
            'message' => 'winner-not-added',
            'idg' => $idg,
            'bp' => $page
        ), false);
    }

    $this->kernel->models->DBgamesWinnerTypes->store(0, array(
        'nazev' => $nazev,
        'idg' => $idg,
        'aktivni' => $aktivni,
        'winners_count' => $winners_count
    ));

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'winner-added',
        'idg' => $idg,
        'bp' => $page
    ), false);
}

public function PageEditWinnerPost()
{
    $nazev = prepare_get_data_safely(getpost('nazev', ''));
    $idg = (int)getget('idg', '');
    $idgm = (int)getget('idgwt', '');
    $page = (int)getget('page', '');
    $aktivni = (int)getpost('aktivni', '');
    $misto_1 = (int)getpost('misto_1', '');
    $misto_2 = (int)getpost('misto_2', '');
    $misto_3 = (int)getpost('misto_3', '');
    $misto_4 = (int)getpost('misto_4', '');
    $misto_5 = (int)getpost('misto_5', '');
    $misto_6 = (int)getpost('misto_6', '');
    $misto_7 = (int)getpost('misto_7', '');
    $misto_8 = (int)getpost('misto_8', '');
    $misto_9 = (int)getpost('misto_9', '');
    $misto_10 = (int)getpost('misto_10', '');

    if ($nazev == '' || $idg < 1 || $idgm < 1) {
        $this->redirect(array(
            'module' => $this->parent_module,
            'action' => 'games-edit',
            'message' => 'winner-not-saved',
            'idg' => $idg,
            'bp' => $page
        ), false);
    }

    $this->kernel->models->DBgamesWinnerTypes->store($idgm, array(
        'nazev' => $nazev,
        'aktivni' => $aktivni,
        'misto_1' => $misto_1,
        'misto_2' => $misto_2,
        'misto_3' => $misto_3,
        'misto_4' => $misto_4,
        'misto_5' => $misto_5,
        'misto_6' => $misto_6,
        'misto_7' => $misto_7,
        'misto_8' => $misto_8,
        'misto_9' => $misto_9,
        'misto_10' => $misto_10
    ));

    $this->redirect(array(
        'module' => $this->parent_module,
        'action' => 'games-edit',
        'message' => 'winner-saved',
        'idg' => $idg,
        'bp' => $page
    ), false);
}


  