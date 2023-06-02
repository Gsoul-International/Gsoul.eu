<?php
class Config{
  private $config;
  public function __construct(){
    $this->config=new stdClass();           
    $this->config->name=array('web'=>'gsoul.eu');
    $this->config->title=array(
      'prefix'=>'',
      'suffix'=>' | GSOUL.eu'
      );
    $this->config->defualt_system='https://demo2.gsoul.eu/';
    $this->config->default_email=array(
      'from_name'=>'gsoul.eu',
      'from_email'=>'info@gsoul.eu'
      );
    $this->config->database=array(
      'host'=>'wm109.wedos.net',
      'user'=>'a168145_gsoul',
      'password'=>'s7uViqH779JENUd12-',
      'db'=>'d168145_gsoul',
      'charset'=>'utf8',
      'collation'=>'utf8_czech_ci'
      );
    $this->config->domain=$_SERVER['SERVER_NAME'];
    $this->config->domain_http='http://'.$_SERVER['SERVER_NAME'];
    $this->config->domain_https='https://'.$_SERVER['SERVER_NAME']; 
    $this->config->models=array(
      'engine/models/boxes.php'=>'DBboxes',
      'engine/models/boxesCategories.php'=>'DBboxesCategories',
      'engine/models/filesCategories.php'=>'DBfilesCategories',
      'engine/models/files.php'=>'DBfiles',
      'engine/models/imagesCategories.php'=>'DBimagesCategories',
      'engine/models/images.php'=>'DBimages',
      'engine/models/imagesSizes.php'=>'DBimagesSizes',
      'engine/models/users.php'=>'DBusers',
      'engine/models/usersCoins.php'=>'DBusersCoins',
      'engine/models/settings.php'=>'DBsettings',
      'engine/models/rewrites.php'=>'DBrewrites',
      'engine/models/pages.php'=>'DBpages',
      'engine/models/templates.php'=>'DBtemplates',
      'engine/models/mainPages.php'=>'DBmainPages',
      'engine/models/news.php'=>'DBnews',      
      'engine/models/games.php'=>'DBgames',
      'engine/models/gamesParameters.php'=>'DBgamesParameters',
      'engine/models/gamesParametersValues.php'=>'DBgamesParametersValues',
      'engine/models/gamesPlatforms.php'=>'DBgamesPlatforms',
      'engine/models/gamesPlatformsGames.php'=>'DBgamesPlatformsGames',      
      'engine/models/gamesTypes.php'=>'DBgamesTypes',
      'engine/models/gamesWinnerTypes.php'=>'DBgamesWinnerTypes',
      'engine/models/gamesModules.php'=>'DBgamesModules',
      'engine/models/gamesModulesVsGames.php'=>'DBgamesModulesVsGames',      
      'engine/models/gamesTournaments.php'=>'DBgamesTournaments',
      'engine/models/gamesTournamentsPlayers.php'=>'DBgamesTournamentsPlayers',
      'engine/models/gamesTournamentsParameters.php'=>'DBgamesTournamentsParameters',
      'engine/models/gamesTournamentsChat.php'=>'DBgamesTournamentsChat', 
      'engine/models/gamesTournamentsWinners.php'=>'DBgamesTournamentsWinners',
      'engine/models/paypalPayments.php'=>'DBpaypalPayments',                                                                        
      'engine/models/languages.php'=>'DBlanguages',                                                                        
      'engine/models/languagesKeywords.php'=>'DBlanguagesKeywords',                                                                        
      'engine/models/languagesValues.php'=>'DBlanguagesValues',
      'engine/models/gamesCups.php'=>'DBgamesCups',   
      'engine/models/gamesCupsParameters.php'=>'DBgamesCupsParameters',   
      'engine/models/gamesCupsPlayers.php'=>'DBgamesCupsPlayers',                                                                           
      'engine/models/paypalOrders.php'=>'DBpaypalOrders',
      'engine/models/gamesTournamentsScreenshots.php'=>'DBgamesTournamentsScreenshots',
      'engine/models/gamesTournamentsPrescores.php'=>'DBgamesTournamentsPrescores',
      'engine/models/gamesTournamentsRounds.php'=>'DBgamesTournamentsRounds',
      'engine/models/gamesTournamentsRoundsScore.php'=>'DBgamesTournamentsRoundsScore',
      'engine/models/newsletterLogouts.php'=>'DBnewsletterLogouts',
      'engine/models/gamesCupsAlternatesPlayers.php'=>'DBgamesCupsAlternatesPlayers',
      'engine/models/gamesTournamentsAlternatesPlayers.php'=>'DBgamesTournamentsAlternatesPlayers',
      );
    $this->config->helpers=array(
      'engine/helpers/templater.php',
      'engine/helpers/class.phpmailer.php',
      'engine/helpers/filemanager.php',
      'engine/helpers/mhmthumb.php'
      );
    $this->config->modules=array(
      //hlavní moduly:
      'engine/modules/frontend.php'=>'Frontend',
      'engine/modules/backend.php'=>'Backend',
      'engine/modules/ajax.php'=>'Ajax',
      'engine/modules/cron.php'=>'Cron',
      //moduly frontendu:
      'engine/modules/frontend/fusers.php'=>'FUsers',
      'engine/modules/frontend/fboxes.php'=>'FBoxes',
      'engine/modules/frontend/fmainPages.php'=>'FMainPages',
      'engine/modules/frontend/fpages.php'=>'FPages',
      'engine/modules/frontend/fsitemap.php'=>'FSitemap',
      'engine/modules/frontend/fnews.php'=>'FNews',
      'engine/modules/frontend/ftournaments.php'=>'FTournaments',     
      'engine/modules/frontend/fpaypalFour.php'=>'FPaypal',
      'engine/modules/frontend/fteams.php'=>'FTeams',
      'engine/modules/frontend/fnotifications.php'=>'FNotifications',   
      'engine/modules/frontend/fcups.php'=>'FCups',
      'engine/modules/frontend/fnewsletterLogout.php'=>'FNewsletterLogout',                     
      //moduly backendu:  
      'engine/modules/backend/bgame.php'=>'BGame',   
      'engine/modules/backend/bgameGames.php'=>'BGameGames',  
      'engine/modules/backend/bgameModules.php'=>'BGameModules',
      'engine/modules/backend/bgameTournaments.php'=>'BGameTournaments',
      'engine/modules/backend/bgameCups.php'=>'BGameCups',
      'engine/modules/backend/bgamePlatforms.php'=>'BGamePlatforms',            
      'engine/modules/backend/busers.php'=>'BUsers',        
      'engine/modules/backend/bpages.php'=>'BPages',  
      'engine/modules/backend/bboxes.php'=>'BBoxes',  
      'engine/modules/backend/bfiles.php'=>'BFiles',  
      'engine/modules/backend/bsettings.php'=>'BSettings',  
      'engine/modules/backend/bsuperadmin.php'=>'BSuperAdmin',
      //moduly ajaxu:
      'engine/modules/ajax/atemplates.php'=>'ATemplates',     
      'engine/modules/ajax/afiles.php'=>'AFiles',     
      'engine/modules/ajax/aimages.php'=>'AImages',     
      );    
    $this->config->modulesNames=array(
      //hlavní moduly:
      'Frontend'=>'Frontend',
      'Backend'=>'Backend',
      'Ajax'=>'Ajax',
      'Cron'=>'Cron',
      //moduly frontendu:
      'FUsers'=>'Uživatelé',
      'FBoxes'=>'Prvky',
      'FPages'=>'Stránky',
      'FMainPages'=>'Hlavní stránky',
      'FPaypal'=>'Paypal',
      'FSitemap'=>'Mapa stránek',
      //moduly backendu: 
      'BGame'=>'Hraní',
      'BUsers'=>'Uživatelé',
      'BPages'=>'Stránky',
      'BFiles'=>'Obrázky videa soubory',
      'BBoxes'=>'Prvky',
      'BSuperAdmin'=>'Super admin',     
      'BSettings'=>'Nastavení'       
      ); 
    $this->config->modulesIcons=array(
      'BGame'=>'<i class="fa fa-gamepad "></i>',
      'BUsers'=>'<i class="fa fa-users "></i>',
      'BPages'=>'<i class="fa fa-file-text"></i>',
      'BFiles'=>'<i class="fa fa-folder-open"></i>',
      'BBoxes'=>'<i class="fa fa-cubes"></i>',
      'BSuperAdmin'=>'<i class="fa fa-wrench"></i>',   
      'BSettings'=>'<i class="fa fa-cogs"></i>'
      );      
    $this->config->ignorateBoxes=array(
      'Ajax',
      'ATemplates',
      'AFiles',
      'AImages',
      'Cron',
      'Backend',
      'BUsers',
      'BPages',
      'BBoxes',
      'BSuperAdmin',          
      'BSettings'  
      );    
    $this->config->max_memmory_limit='100M';
    $this->config->max_time_limit='3600';     
    $this->config->charset=array(
      'charset'=>'utf8',
      'collation'=>'utf8_czech_ci'
      );    
    global $systemRewrites;
    $this->config->systemRewrites=$systemRewrites;
    }    
  public function GetData(){
    return $this->config;
    }
  }
