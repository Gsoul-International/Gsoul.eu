<?php
class BGame extends Module
{
    public function __construct()
    {
        $this->parent_module = 'Backend';
        $this->rights = new stdClass();
    }

    public function Main()
    {
        $this->seo_title = 'Hraní';
        $this->subTit = ' | Hraní';
        $this->rights = $this->getUserRights();

        if (
            !in_array('game_games_view', $this->rights->povoleneKody) &&
            !in_array('games_modules_view', $this->rights->povoleneKody) &&
            !in_array('games_platforms_view', $this->rights->povoleneKody) &&
            !in_array('games_tournaments_view', $this->rights->povoleneKody) &&
            !in_array('games_cups_view', $this->rights->povoleneKody)
        ) {
            $this->Redirect(array('module' => 'Backend'), false);
        }

        if (in_array('games_cups_view', $this->rights->povoleneKody)) {
            $action = getget('action', 'cups');
        } elseif (in_array('games_tournaments_view', $this->rights->povoleneKody)) {
            $action = getget('action', 'tournaments');
        } elseif (in_array('games_platforms_view', $this->rights->povoleneKody)) {
            $action = getget('action', 'platforms');
        } elseif (in_array('games_modules_view', $this->rights->povoleneKody)) {
            $action = getget('action', 'modules');
        } elseif (in_array('game_games_view', $this->rights->povoleneKody)) {
            $action = getget('action', 'games');
        }

        if ($action == 'games' && in_array('game_games_view', $this->rights->povoleneKody)) {
            $this->PageGames();
        } elseif ($action == 'games-edit' && in_array('game_games_view', $this->rights->povoleneKody)) {
            $this->PageGamesEdit();
        } elseif ($action == 'games-add-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesAddPost();
        } elseif ($action == 'games-edit-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesEditPost();
        } elseif ($action == 'games-add-type-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesAddTypePost();
        } elseif ($action == 'games-edit-type-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesEditTypePost();
        } elseif ($action == 'games-add-winner-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesAddWinnerPost();
        } elseif ($action == 'games-edit-winner-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesEditWinnerPost();
        } elseif ($action == 'games-add-param-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesAddParameterPost();
        } elseif ($action == 'games-edit-param-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesEditParameterPost();
        } elseif ($action == 'games-add-subparam-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesAddSubParameterPost();
        } elseif ($action == 'games-edit-subparam-post' && in_array('game_games_creategame', $this->rights->povoleneKody)) {
            $this->PageGamesEditSubParameterPost();
        } elseif ($action == 'modules' && in_array('games_modules_view', $this->rights->povoleneKody)) {
            $this->PageModules();
        } elseif ($action == 'modules-edit' && in_array('games_modules_view', $this->rights->povoleneKody)) {
            $this->PageModulesEdit();
        } elseif ($action == 'modules-add-game' && in_array('games_modules_changes', $this->rights->povoleneKody)) {
            $this->PageModulesAddGame();
        } elseif ($action == 'modules-edit-game' && in_array('games_modules_changes', $this->rights->povoleneKody)) {
            $this->PageModulesEditGame();
        } elseif ($action == 'modules-del-game' && in_array('games_modules_changes', $this->rights->povoleneKody)) {
            $this->PageModulesDelGame();
        } elseif ($action == 'cups' && in_array('games_cups_view', $this->rights->povoleneKody)) {
            $this->PageCups();
        } elseif ($action == 'cups-edit' && in_array('games_cups_view', $this->rights->povoleneKody)) {
            $this->PageCupsEdit();
        } elseif ($action == 'cups-change-view' && in_array('games_cups_changes', $this->rights->povoleneKody)) {
            $this->PageCupsChangeView();
        } elseif ($action == 'cups-change-banner' && in_array('games_cups_changes', $this->rights->povoleneKody)) {
            $this->PageCupsChangeBanner();
        } elseif ($action == 'cups-set-filter' && in_array('games_cups_view', $this->rights->povoleneKody)) {
            $this->PageCupsSetFilter();
        } elseif ($action == 'tournaments' && in_array('games_tournaments_view', $this->rights->povoleneKody)) {
            $this->PageTournaments();
        } elseif ($action == 'tournaments-edit' && in_array('games_tournaments_view', $this->rights->povoleneKody)) {
            $this->PageTournamentsEdit();
        } elseif ($action == 'tournaments-del-chat' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsDelChat();
        } elseif ($action == 'tournaments-del-screen' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsDelScreen();
        } elseif ($action == 'tournaments-add-winners' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsAddWinners();
        } elseif ($action == 'tournaments-add-winners-2' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsAddWinnersTwo();
        } elseif ($action == 'tournaments-change-view' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsChangeView();
        } elseif ($action == 'tournaments-change-banner' && in_array('games_tournaments_changes', $this->rights->povoleneKody)) {
            $this->PageTournamentsChangeBanner();
        } elseif ($action == 'tournaments-set-filter' && in_array('games_tournaments_view', $this->rights->povoleneKody)) {
            $this->PageTournamentsSetFilter();
        } elseif ($action == 'platforms' && in_array('games_platforms_view', $this->rights->povoleneKody)) {
            $this->PagePlatforms();
        } elseif ($action == 'platform-edit' && in_array('games_platforms_view', $this->rights->povoleneKody)) {
            $this->PagePlatformEdit();
        } elseif ($action == 'platform-delete-post' && in_array('games_platforms_changes', $this->rights->povoleneKody)) {
            $this->PagePlatformDelete();
        } elseif ($action == 'platform-add-post' && in_array('games_platforms_changes', $this->rights->povoleneKody)) {
            $this->PagePlatformAddPost();
        } elseif ($action == 'platform-edit-post' && in_array('games_platforms_changes', $this->rights->povoleneKody)) {
            $this->PagePlatformEditPost();
        } else {
            $this->Redirect();
        }
    }
}


  private function SetLeftMenu()
{
    $menu = array(
        $this->Anchor(array('action' => 'games')) => '<span class="icon"><i class="fa fa-gamepad"></i></span> Hry',
        $this->Anchor(array('action' => 'modules')) => '<span class="icon"><i class="fa fa-database"></i></span> Moduly',
        $this->Anchor(array('action' => 'platforms')) => '<span class="icon"><i class="fa fa-desktop"></i></span> Platformy',
        $this->Anchor(array('action' => 'tournaments')) => '<span class="icon"><i class="fa fa-trophy"></i></span> Zápasy',
        $this->Anchor(array('action' => 'cups')) => '<span class="icon"><i class="fa fa-trophy"></i></span> Turnaje',
    );

    if (!in_array('games_cups_view', $this->rights->povoleneKody)) {
        unset($menu[$this->Anchor(array('action' => 'cups'))]);
    }
    if (!in_array('games_tournaments_view', $this->rights->povoleneKody)) {
        unset($menu[$this->Anchor(array('action' => 'tournaments'))]);
    }
    if (!in_array('games_platforms_view', $this->rights->povoleneKody)) {
        unset($menu[$this->Anchor(array('action' => 'platforms'))]);
    }
    if (!in_array('games_modules_view', $this->rights->povoleneKody)) {
        unset($menu[$this->Anchor(array('action' => 'modules'))]);
    }
    if (!in_array('game_games_view', $this->rights->povoleneKody)) {
        unset($menu[$this->Anchor(array('action' => 'games'))]);
    }

    if (in_array('games_cups_view', $this->rights->povoleneKody)) {
        $active = 'cups';
    }
    if (in_array('games_tournaments_view', $this->rights->povoleneKody)) {
        $active = 'tournaments';
    }
    if (in_array('games_platforms_view', $this->rights->povoleneKody)) {
        $active = 'platforms';
    }
    if (in_array('games_modules_view', $this->rights->povoleneKody)) {
        $active = 'modules';
    }
    if (in_array('game_games_view', $this->rights->povoleneKody)) {
        $active = 'games';
    }

    if (getget('action', '') == 'games') {
        $active = 'games';
    }
    if (getget('action', '') == 'games-edit') {
        $active = 'games';
    }
    if (getget('action', '') == 'platforms') {
        $active = 'platforms';
    }
    if (getget('action', '') == 'platform-edit') {
        $active = 'platforms';
    }
    if (getget('action', '') == 'modules') {
        $active = 'modules';
    }
    if (getget('action', '') == 'modules-edit') {
        $active = 'modules';
    }
    if (getget('action', '') == 'tournaments') {
        $active = 'tournaments';
    }
    if (getget('action', '') == 'tournaments-edit') {
        $active = 'tournaments';
    }
    if (getget('action', '') == 'cups') {
        $active = 'cups';
    }
    if (getget('action', '') == 'cups-edit') {
        $active = 'cups';
    }

    $tpl2 = new Templater();
    $tpl2->add('menu', $menu);
    $tpl2->add('active', $this->Anchor(array('action' => $active)));
    $this->content2 = $tpl2->fetch('backend/games/leftMenu.tpl');
}

  
  private function PageGames()
{
    $data = $this->kernel->modules->BGameGames->PageMain();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageGamesEdit()
{
    $data = $this->kernel->modules->BGameGames->PageEdit();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageGamesAddPost()
{
    $this->kernel->modules->BGameGames->PageAddPost();
}

private function PageGamesEditPost()
{
    $this->kernel->modules->BGameGames->PageEditPost();
} 

private function PageGamesAddTypePost()
{
    $this->kernel->modules->BGameGames->PageAddTypePost();
}

private function PageGamesEditTypePost()
{
    $this->kernel->modules->BGameGames->PageEditTypePost();
}     

private function PageModules()
{
    $data = $this->kernel->modules->BGameModules->PageMain();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}     

private function PageModulesEdit()
{
    $data = $this->kernel->modules->BGameModules->PageEdit();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}     

private function PageModulesAddGame()
{
    $this->kernel->modules->BGameModules->PageAddGamePost();
}

private function PageModulesEditGame()
{
    $this->kernel->modules->BGameModules->PageEditGamePost();
}

private function PageModulesDelGame()
{
    $this->kernel->modules->BGameModules->PageDeleteGamePost();
}

private function PageTournaments()
{
    $data = $this->kernel->modules->BGameTournaments->PageMain();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageTournamentsEdit()
{
    $data = $this->kernel->modules->BGameTournaments->PageEdit();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageTournamentsChangeView()
{
    $this->kernel->modules->BGameTournaments->PageTournamentsChangeView();
}

private function PageTournamentsChangeBanner()
{
    $this->kernel->modules->BGameTournaments->PageTournamentsChangeBanner();
}

private function PageTournamentsDelChat()
{
    $this->kernel->modules->BGameTournaments->PageDelChatPost();
}

private function PageTournamentsDelScreen()
{
    $this->kernel->modules->BGameTournaments->PageDelScreenPost();
}

private function PageTournamentsAddWinners()
{
    $this->kernel->modules->BGameTournaments->PageAddWinnersPost();
}

private function PageTournamentsAddWinnersTwo()
{
    $this->kernel->modules->BGameTournaments->PageAddWinnersTwoPost();
}

private function PageTournamentsSetFilter()
{
    $this->kernel->modules->BGameTournaments->PageTournamentsSetFilter();
}

  private function PageGamesAddWinnerPost()
{
    $this->kernel->modules->BGameGames->PageAddWinnerPost();
}

private function PageGamesEditWinnerPost()
{
    $this->kernel->modules->BGameGames->PageEditWinnerPost();
}

private function PageGamesAddParameterPost()
{
    $this->kernel->modules->BGameGames->PageGamesAddParameterPost();
}

private function PageGamesEditParameterPost()
{
    $this->kernel->modules->BGameGames->PageGamesEditParameterPost();
}

private function PageGamesAddSubParameterPost()
{
    $this->kernel->modules->BGameGames->PageGamesAddSubParameterPost();
}

private function PageGamesEditSubParameterPost()
{
    $this->kernel->modules->BGameGames->PageGamesEditSubParameterPost();
}

private function PagePlatforms()
{
    $data = $this->kernel->modules->BGamePlatforms->PageMain();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PagePlatformEdit()
{
    $data = $this->kernel->modules->BGamePlatforms->PageEdit();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PagePlatformDelete()
{
    $this->kernel->modules->BGamePlatforms->PageDeletePost();
}

private function PagePlatformAddPost()
{
    $this->kernel->modules->BGamePlatforms->PageAddPost();
}

private function PagePlatformEditPost()
{
    $this->kernel->modules->BGamePlatforms->PageEditPost();
}

private function PageCups()
{
    $data = $this->kernel->modules->BGameCups->PageMain();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageCupsEdit()
{
    $data = $this->kernel->modules->BGameCups->PageEdit();
    $this->seo_title = $data->seo_title.$this->subTit;
    $this->content = $data->content;
    $this->SetLeftMenu();
    $this->execute();
}

private function PageCupsChangeView()
{
    $this->kernel->modules->BGameCups->PageCupsChangeView();
}

private function PageCupsChangeBanner()
{
    $this->kernel->modules->BGameCups->PageCupsChangeBanner();
}

private function PageCupsSetFilter()
{
    $this->kernel->modules->BGameCups->PageCupsSetFilter();
}

