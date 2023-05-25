<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$SEO_title?></title>
    <meta property="og:title" content="<?=$SEO_title?>" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="<?=$SEO_title?>" />
    <meta name="keywords" content="<?=$SEO_keywords?>" />
    <meta name="description" content="<?=$SEO_description?>" />
    <meta property="og:description" content="<?=$SEO_description?>" /> 
    <meta name="twitter:description" content="<?=$SEO_description?>" />
    <meta name="twitter:site" content="@gsoul" />    
    <meta name="twitter:image" itemprop="image" content="/img/card.png" />
    <meta property="og:image" content="/img/card.png" />
    <meta property="og:url" content="http://<?=$_SERVER['SERVER_NAME']?><?=$_SERVER['REQUEST_URI']?>" />
    <meta property="og:country-name" content="Česká republika" />
    <meta property="og:site_name" content="GSOUL" />
    <meta name="author" content="MHMSYS s.r.o." />
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1" />            
    <link rel="icon" type="image/ico" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
    <link rel="stylesheet" type="text/css" href="/css/kernel.css" media="all"> 
    <link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/css/screen.css" media="screen">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css" media="all">    
    <link rel="stylesheet" type="text/css" href="/css/print.css" media="print"> 
    <script type="text/javascript" src="/js/plugins.js"></script>
    <script type="text/javascript" src="/js/script.js"></script>
    <?=$settings['CODE_HEADER'];?> 
  </head>
  <body <?if(getget('module')=='FMainPages'){?> class="hp" <?}?> >
    <div id="fb-root"></div>
    <script type="text/javascript" src="/js/fbjssdk.js"></script>  
    <div class="page-head">
      <div class="page-head-top">
        <div class="wrap">	
          <div class="logo">
  					<a href="/" title="GSOUL.EU"><img src="/img/logo.png" alt="GSOUL.EU" /></a>
  				</div>
  				<div class="nav-shortcut hide-970"></div>
  				<div class="nav-content hide-970">
  				  <?if(isset($boxes['menu_clanku_hlavicka'])&&count($boxes['menu_clanku_hlavicka'])>0){foreach($boxes['menu_clanku_hlavicka'] as $b){echo $kernel->modules->FBoxes->getContent($b);}}?>	
  				</div>
  				<div class="lang-shortcut">
            <?              
              $menuLangs='';
              foreach($kernel->languages as $lnk=>$lnv){
                if(file_exists('userfiles/langs/'.$lnk.'.png')){
                  $menuLangs.='<li><a href="/users/changelang?i='.$lnk.'" title="'.$lnv->nazev.'"><img src="/userfiles/langs/'.$lnk.'.png" alt="'.$lnv->nazev.'" /></a></li>';
                  if($lnk==$kernel->active_language){
                    echo '<img src="/userfiles/langs/'.$lnk.'.png" alt="'.$lnv->nazev.'" />';
                    }
                  }            
                }
            ?>  					
  				</div> 
          <?if($menuLangs!=''){?>
    				<div class="lang-content">
    					<div class="lang-content-wrap">
    						<ul>
    							<?=$menuLangs;?>
    						</ul>
    					</div>
    				</div>
          <?}?>   
  				<?if($kernel->user->uid>0){?>
    				<div class="user-shortcut">
    					<div class="user-avatar-48">
    					  <?if(strlen($kernel->user->data->user_picture)>0){?>
    						  <img src="/<?=$kernel->user->data->user_picture;?>" alt="<?=addslashes(mb_strtoupper($kernel->user->data->osloveni,'UTF-8'));?>" />
                <?}elseif(strlen($kernel->user->data->fb_picture)>0){?>
                  <img src="<?=$kernel->user->data->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($kernel->user->data->osloveni,'UTF-8'));?>" />    						      						
    						<?}else{?>
    						  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($kernel->user->data->osloveni,'UTF-8'));?>" />
    						<?}?>
    					</div>
    					<div class="user-name">
    						<?=mb_strtoupper($kernel->user->data->osloveni,'UTF-8');?>
    						<?if($notificationsCounts>0){?><span style="color:#ce1b1f;">(<?=$notificationsCounts;?>)</span><?}?>
    					</div>
    				</div>
    				<div class="user-content">
    					<div class="user-content-wrap">
    						<a href="<?=$ausergsc?>" title="<?=$systemTranslator['uzivatel_aktualni_kredit'];?>" class="balance">
    							<?=$systemTranslator['uzivatel_aktualni_kredit'];?>
    							<div class="white font-bold size-25"><?=printcost($kernel->user->data->ucetni_zustatek)?> $</div>
    						</a>
    						<div class="user-menu">
    							<ul> 
    							  <li><a href="<?=$anotifications?>" title="<?=$systemTranslator['notifikace'];?>" class="<?if(getget('module')=='FNotifications'){?>active<?}?>"><?if($notificationsCounts>0){?><span style="color:#ce1b1f;"><?}?><?=$systemTranslator['notifikace'];?> <?if($notificationsCounts>0){?>(<?=$notificationsCounts;?>)</span><?}?> </a></li>
                    <li><a href="<?=$ausercalendary?>" title="<?=$systemTranslator['uzivatel_kalendar'];?>" class="<?if(getget('module')=='FUsers'&&getget('action')=='userCalendar'){?>active<?}?>"><?=$systemTranslator['uzivatel_kalendar'];?></a></li>                    							
    								<li><a href="<?=$auseraccount?>" title="<?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?>" class="<?if(getget('module')=='FUsers'&&getget('action')==''){?>active<?}?> <?if(getget('module')=='FUsers'&&getget('action')=='userSettings'){?>active<?}?>"><?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?></a></li>
    								<?/*<li><a href="<?=$ausersettings? >" title="<?=$systemTranslator['uzivatel_nastaveni_uzivatele'];? >" class="<?if(getget('module')=='FUsers'&&getget('action')=='userSettings'){? >active<?}? >"><?=$systemTranslator['uzivatel_nastaveni_uzivatele'];? ></a></li>*/?> 									    								
    								<li><a href="<?=$auserpaypal?>" title="<?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?>" class="<?if(getget('module')=='FPaypal'){?>active<?}?>"><?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?></a></li>
                    <?if($kernel->user->data->prava>0){?><li><a href="<?=$aadmin?>" title="<?=$systemTranslator['uzivatel_administrace'];?>" target="_blank"><?=$systemTranslator['uzivatel_administrace'];?></a></li><?}?> 								
    								<li><a href="<?=$auserlogout?>" title="<?=$systemTranslator['uzivatel_odhlasit_se'];?>"><?=$systemTranslator['uzivatel_odhlasit_se'];?></a></li>  				
    							</ul>
    						</div>
    					</div>
    				</div>
  				<?}else{?>
  				  <div class="user-shortcut"> 
              <div class="user-avatar-48">                
    						<img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($kernel->user->data->osloveni,'UTF-8'));?>" />    						
    					</div>   					
    					<div class="user-name">
    						<?=$systemTranslator['uzivatel_prihlaseni'];?>                
    					</div>
    				</div>
    				<div class="user-content">
    					<div class="user-content-wrap">    					
    						<div class="user-menu">
    							<ul>
    								<li><a href="<?=$auserregistration?>" title="<?=$systemTranslator['uzivatel_registrace_uzivatele'];?>" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userRegistration'){?>active<?}?>"><?=$systemTranslator['uzivatel_registrace_uzivatele'];?></a></li>  								                                    
                    <li><a href="<?=$auserlogin?>" title="<?=$systemTranslator['uzivatel_prihlaseni_uzivatele'];?>" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userLogIn'){?>active<?}?>"><?=$systemTranslator['uzivatel_prihlaseni_uzivatele'];?></a></li>  
                    <li><a href="<?=$auserpassword?>" title="<?=$systemTranslator['uzivatel_zapomenute_heslo'];?>" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userPassword'){?>active<?}?>"><?=$systemTranslator['uzivatel_zapomenute_heslo'];?></a></li>       
    							</ul>
    						</div>
    					</div>
    				</div>  				    
  				<?}?>
  				<div class="nav-shortcut show-970"></div>
  				<div class="nav-content show-970">
  					<?if(isset($boxes['menu_clanku_hlavicka'])&&count($boxes['menu_clanku_hlavicka'])>0){foreach($boxes['menu_clanku_hlavicka'] as $b){echo $kernel->modules->FBoxes->getContent($b);}}?>	
  				</div>  				
        </div>
      </div>
      <?if(getget('module')=='FMainPages'){?>
        <div class="page-head-bottom align-center align-top">
          <div class="inner-wrap">
            <div class="size-28">
              <p style="text-align: center;">
                <span class="yellow" style="text-align: center;">G</span>SOUL 
                <?=$systemTranslator['uvodni_strana_hlavicka_is_a_web_app_aimed_on_esport'];?>                
              </p>
              <p><br></p>
              <p><?=$systemTranslator['uvodni_strana_hlavicka_join_or_create_tournament'];?></p>
              <p><?=$systemTranslator['uvodni_strana_hlavicka_connecting_gamers_souls'];?></p>
              <p><br></p>
            </div>
            <br>
            <br>
            <?if($kernel->user->uid<1){?>
              <a href="<?=$auserregistration?>" class="button btn-transparent large btn-wide"><?=$systemTranslator['uvodni_strana_hlavicka_register'];?></a>
            <?}else{?>
              <a href="/tournaments/" class="button btn-transparent large btn-wide"><?=$systemTranslator['turnaje_turnaje'];?></a>
            <?}?>
            <p><br></p>
          </div>
        </div>
      <?}?>
    </div>
    <div class="page-main">
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='user-saved'){frontendMessage('green',$systemTranslator['uzivatel_uzivatel_byl_ulozen']);}?>
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='email-exists'){frontendMessage('red',$systemTranslator['uzivatel_email_jiz_v_systemu_existuje']);}?>
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='email-required'){frontendMessage('red',$systemTranslator['uzivatel_email_je_povinny']);}?>
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='password-short'){frontendMessage('red',$systemTranslator['uzivatel_heslo_je_prilis_kratke_zmena_neuskutecnena']);}?> 
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='password-not-same'){frontendMessage('red',$systemTranslator['uzivatel_zadana_hesla_se_neshoduji_zmena_hesla_neuskutecnena']);}?>
      <?if($module=='FUsers'&&$action=='userSettings'&&$message=='password-saved'){frontendMessage('green',$systemTranslator['uzivatel_uzivatelske_heslo_bylo_zmeneno']);}?>      
      <?if($module=='FUsers'&&$action=='userPassword'&&$message=='password-send'){frontendMessage('green',$systemTranslator['uzivatel_nove_heslo_bylo_zaslano_na_vas_email']);}?>
      <?if($module=='FUsers'&&$action=='userPassword'&&$message=='password-not-send'){frontendMessage('red',$systemTranslator['uzivatel_uzivatel_s_timto_emailem_neexistuje']);}?>      
      <?if($module=='FUsers'&&$action=='userLogIn'&&$message=='user-success-logout'){frontendMessage('green',$systemTranslator['uzivatel_uzivatel_byl_uspesne_odhlasen']);}?>
      <?if($module=='FUsers'&&$message=='user-success-login'){frontendMessage('green',$systemTranslator['uzivatel_uzivatel_byl_uspesne_prihlasen']);}?>
      <?if($module=='FUsers'&&$action=='userLogIn'&&getget('LoginError','')=='1'){frontendMessage('red',$systemTranslator['uzivatel_prihlaseni_uzivatele_se_nezdarilo']);}?>      
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='user-registered'){frontendMessage('green',$systemTranslator['uzivatel_registrace_hlaska_v_poradku_zaregistrovano']);}?>
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='email-exists'){frontendMessage('red',$systemTranslator['uzivatel_registrace_hlaska_spatny_email']);}?>
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='email-required'){frontendMessage('red',$systemTranslator['uzivatel_registrace_hlaska_email_povinny']);}?>
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='password-not-same'){frontendMessage('red',$systemTranslator['uzivatel_registrace_hlaska_zadana_hesla_se_neshoduji']);}?>
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='password-short'){frontendMessage('red',$systemTranslator['uzivatel_registrace_hlaska_kratke_heslo']);}?>
      <?if($module=='FUsers'&&$action=='userRegistration'&&$message=='confirm-required'){frontendMessage('red',$systemTranslator['uzivatel_registrace_hlaska_souhlas_gdpr_vop']);}?>
            
      <?if($module=='FTeams'&&$message=='team-not-created'){frontendMessage('red',$systemTranslator['tym_se_nepodarilo_vytvorit']);}?>
      <?if($module=='FTeams'&&$message=='team-not-found'){frontendMessage('red',$systemTranslator['tym_neexistuje']);}?>           
      <?if($module=='FTeams'&&$message=='team-saved'){frontendMessage('green',$systemTranslator['tym_zmeny_uspesne_ulozeny']);}?>
      <?if($module=='FTeams'&&$message=='chat-failed'){frontendMessage('red',$systemTranslator['tym_zprava_do_teamoveho_chatu_nevlozena']);}?>
      <?if($module=='FTeams'&&$message=='chat-succes'){frontendMessage('green',$systemTranslator['tym_zprava_do_teamoveho_chatu_vlozena']);}?>
      <?if($module=='FTeams'&&$message=='add-player-request-failed'){frontendMessage('red',$systemTranslator['tym_zadost_o_clenstvi_neodeslana']);}?>
      <?if($module=='FTeams'&&$message=='add-player-request-succes'){frontendMessage('green',$systemTranslator['tym_zadost_o_clenstvi_odeslana']);}?>
      <?if($module=='FTeams'&&$message=='add-player-request-succes-2'){frontendMessage('green',$systemTranslator['tym_zadost_o_clenstvi_potvrzena']);}?>
      <?if($module=='FTeams'&&$message=='delete-player-request-succes'){frontendMessage('green',$systemTranslator['tym_odhlaseni_z_tymu_probehlo_uspesne']);}?>
      <?if($module=='FTeams'&&$message=='add-player-by-admin-succes'){frontendMessage('green',$systemTranslator['tym_hrac_byl_schvalen']);}?>
      <?if($module=='FTeams'&&$message=='saved-succes'){frontendMessage('green',$systemTranslator['tymy_nastaveni_tymu_zmeny_ulozeny']);}?>
      
      <?if($module=='FNotifications'&&$message=='notification-not-found'){frontendMessage('red',$systemTranslator['notification_not_found']);}?> 
      
      <?if($module=='FNewsletterLogout'&&$message=='logout-done'){frontendMessage('green',$systemTranslator['nwslgout_odhlaseni_odberu_newsletteru_probehlo_uspesne']);}?> 
      <?if($module=='FNewsletterLogout'&&$message=='email-incorrect'){frontendMessage('red',$systemTranslator['nwslgout_nespravny_email']);}?> 
      <?if($module=='FNewsletterLogout'&&$message=='email-used'){frontendMessage('green',$systemTranslator['nwslgout_email_jiz_odhlasen']);}?> 
       
      <?if($module=='FCups'&&$action=='new-cup'&&$message=='all-needed'){frontendMessage('red',$systemTranslator['cups_message_all-needed']);}?> 
      <?if($module=='FCups'&&$action=='new-cup'&&($message=='count-players'||$message=='counts')){frontendMessage('red',$systemTranslator['cups_message_counts']);}?> 
      <?if($module=='FCups'&&$action=='new-cup'&&$message=='date-error'){frontendMessage('red',$systemTranslator['cups_message_date-error']);}?> 
      <?if($module=='FCups'&&$action=='new-cup'&&$message=='rounds'){frontendMessage('red',$systemTranslator['cups_message_rounds']);}?> 
      <?if($module=='FCups'&&$action=='new-cup'&&$message=='coins'){frontendMessage('red',$systemTranslator['cups_message_coins']);}?>
      <?if($module=='FCups'&&$message=='cup-not-found'){frontendMessage('red',$systemTranslator['cups_cup_nenalezen']);}?>         
      <?if($module=='FCups'&&$message=='login-failed'){frontendMessage('red',$systemTranslator['cups_message_login-failed']);}?>  
      <?if($module=='FCups'&&$message=='login-succes'){frontendMessage('green',$systemTranslator['cups_message_login-succes']);}?>  
      <?if($module=='FCups'&&$message=='login-team-failed'){frontendMessage('red',$systemTranslator['cups_message_login-failed']);}?>  
      <?if($module=='FCups'&&$message=='login-team-succes'){frontendMessage('green',$systemTranslator['cups_message_login-succes']);}?>  
      <?if($module=='FCups'&&$message=='cup-extend-failed'){frontendMessage('red',$systemTranslator['cups_message_tournament-extend-failed']);}?>  
      <?if($module=='FCups'&&$message=='cup-extend-succes'){frontendMessage('green',$systemTranslator['cups_message_tournament-extend-succes']);}?>  
      <?if($module=='FCups'&&$message=='cup-not-saved'){frontendMessage('red',$systemTranslator['cups_message_cup_not_saved']);}?>  
      <?if($module=='FCups'&&$message=='cup-saved'){frontendMessage('green',$systemTranslator['cups_message_saved']);}?>       
      <?if($module=='FCups'&&$message=='cup-round-not-generated'){frontendMessage('red',$systemTranslator['cups_message_round_not_generated']);}?>  
      <?if($module=='FCups'&&$message=='cup-round-generated'){frontendMessage('green',$systemTranslator['cups_message_round_generated']);}?>  
      <?if($module=='FCups'&&$message=='cup-kickuser-failed'){frontendMessage('red',$systemTranslator['cups_message-kickuser-failed']);}?>  
      <?if($module=='FCups'&&$message=='cup-kickuser-succes'){frontendMessage('green',$systemTranslator['cups_message-kickuser-succes']);}?>  
      <?if($module=='FCups'&&$message=='cup-kickteam-failed'){frontendMessage('red',$systemTranslator['cups_message-kickteam-failed']);}?>  
      <?if($module=='FCups'&&$message=='cup-kickteam-succes'){frontendMessage('green',$systemTranslator['cups_message-kickteam-succes']);}?>  
      
      <?if($message=='tournament-not-found'){frontendMessage('red',$systemTranslator['turnaje_turnaj_nenalezen']);}?>    
      <?if($module=='FTournaments'&&$message=='tournament-saved'){frontendMessage('green',$systemTranslator['tournament_message_saved']);}?>   
      <?if($module=='FTournaments'&&$message=='all-needed'){frontendMessage('red',$systemTranslator['turnaje_message_all-needed']);}?>  
      <?if($module=='FTournaments'&&$message=='count-players'){frontendMessage('red',$systemTranslator['turnaje_message_count-players']);}?>  
      <?if($module=='FTournaments'&&$message=='count-teams'){frontendMessage('red',$systemTranslator['turnaje_message_count-teams']);}?>  
      <?if($module=='FTournaments'&&$message=='date-error'){frontendMessage('red',$systemTranslator['turnaje_message_date-error']);}?>  
      <?if($module=='FTournaments'&&$message=='rounds'){frontendMessage('red',$systemTranslator['turnaje_message_rounds']);}?>  
      <?if($module=='FTournaments'&&$message=='coins'){frontendMessage('red',$systemTranslator['turnaje_message_coins']);}?>           
      <?if($module=='FTournaments'&&$message=='login-failed'){frontendMessage('red',$systemTranslator['turnaje_message_login-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='login-team-failed'){frontendMessage('red',$systemTranslator['turnaje_message_login-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='chat-failed'){frontendMessage('red',$systemTranslator['turnaje_message_chat-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-end-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-end-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-extend-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-extend-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-screenshot-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-screenshot-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-kickuser-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-kickuser-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-kickteam-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-kickteam-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-round-added-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-roundadded-failed']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-round-edit-failed'){frontendMessage('red',$systemTranslator['turnaje_message_tournament-roundedit-failed']);}?>        
      <?if($module=='FTournaments'&&$message=='login-succes'){frontendMessage('green',$systemTranslator['turnaje_message_login-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='login-team-succes'){frontendMessage('green',$systemTranslator['turnaje_message_login-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='chat-succes'){frontendMessage('green',$systemTranslator['turnaje_message_chat-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-end-succes'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-end-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-extend-succes'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-extend-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-screenshot-succes'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-screenshot-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-kickuser-succes'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-kickuser-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-kickteam-succes'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-kickteam-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-round-added-success'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-roundadded-succes']);}?>  
      <?if($module=='FTournaments'&&$message=='tournament-round-edit-success'){frontendMessage('green',$systemTranslator['turnaje_message_tournament-roundedit-succes']);}?>  
      
      <?if($module=='FUsers'&&$message=='xtest'){frontendMessage('normal','Test 12345');}?>
      <div class="wrap">      
        <?=$page;?>      
      </div>
    </div>
    <div class="page-foot">
      <?if(isset($boxes['paticka_novinky'])&&count($boxes['paticka_novinky'])>0&&getget('module')=='FMainPages'){foreach($boxes['paticka_novinky'] as $b){echo $kernel->modules->FBoxes->getContent($b);}}?>      
      <div class="bg-yellow">
				<div class="wrap">
					<div class="size-35 align-center pad-top-16 pad-bottom-16">              
					  <span class="white italic"><?=$systemTranslator['paticka_vyberte_si_z_vice_jak'];?></span>
            <span class="black bold italic"><?=$systemTranslator['paticka_500_turnaju'];?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?=$systemTranslator['paticka_odkaz_na_ukazku_turnaju'];?>" title="<?=$systemTranslator['paticka_ukazat_turnaje'];?>" class="button extralarge btn-dark"><?=$systemTranslator['paticka_ukazat_turnaje'];?></a>  					  					  
					</div>         
				</div>         
			</div>         
      <div class="bg-dark-blue white">
				<div class="wrap">
					<div class="grid grid-padded align-middle">
						<div class="col col-1-3 align-left ">
							<div class="size-13">
                <?=$systemTranslator['paticka_copyright'];?> <br />
                <?=$systemTranslator['paticka_souhlas_s_obchodnimi_podminkami'];?>
                <br>
              </div>
						</div>
						<div class="col col-1-3 align-center">
						  <?if(isset($boxes['menu_clanku_paticka'])&&count($boxes['menu_clanku_paticka'])>0){foreach($boxes['menu_clanku_paticka'] as $b){echo $kernel->modules->FBoxes->getContent($b);}}?>							
						</div>
						<div class="col col-1-3 align-right size-14">
							<?/* <?=$systemTranslator['paticka_vytvoreno'];? >&nbsp;&nbsp;&nbsp;*/?>
							<?/*<a href="http://emotion-design.cz/" title="<?=$systemTranslator['paticka_partneri_design'];? > Emotion design s.r.o." target="_blank" class="align-middle pad-top-16">*/?><img src="/img/emotion-design.png" alt="emotion-design s.r.o." class="align-middle pad-top-8" ><?/*</a>*/?>
							&nbsp;&nbsp;&&nbsp;&nbsp;
							<?/*<a href="http://www.mhmsys.cz/" title="<?=$systemTranslator['paticka_partneri_web'];? > MHMSYS s.r.o." target="_blank" class="align-middle pad-top-16">*/?><img src="/img/mhm-sys.png" alt="MHMSYS s.r.o." class="align-middle pad-top-8" ><?/*</a>*/?>
						</div>
					</div>         
				</div>         
			</div>      
    </div>
    <?/*
    <div class="favorite-games">
      <span class="favorite-icon"></span>
			<a class="pad-left-16 pad-right-16" href="<?=$systemTranslator['obecne_show_favorite_games_link'];? >"><?=$systemTranslator['obecne_show_favorite_games'];? ></a>
    </div>
    */?>
	<div id="cookies">                                                      
		<p class="gap-0 size-16">          
			<?=$systemTranslator['obecne_cookies_1'];?> <a href="<?=trim($systemTranslator['obecne_cookies_2']);?>" target="_blank" title="<?=$systemTranslator['obecne_cookies_3'];?>"><?=$systemTranslator['obecne_cookies_3'];?></a> &nbsp;&nbsp;<a class="button" id="cookies-accept"><?=$systemTranslator['obecne_cookies_4'];?></a>
		</p>
	</div>
  </body>  
</html>