<!DOCTYPE html>
<html>
  <head>
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
  <body>
    <div id="fb-root"></div>
    <script type="text/javascript" src="/js/fbjssdk.js"></script>  
    <div class="page-head">
			<div class="wrap">			
				<div class="nav-mobile"></div>
				<div class="nav-mobile-content">
					<nav>
						<ul>
							<li><a href="/" title="GSOUL.cz"><div class="logo"></div></a></li>											
							<li <?if(getget('module')=='FTournaments'){?>class="active"<?}?> ><a href="<?=$atournaments?>">Tournaments</a></li>			
              <?if($kernel->user->uid>0){if($kernel->user->data->prava>0){?>  		
                <li><a href="<?=$aadmin?>" title="Administrace" target="_blank">Administrace <em class="fa fa-wrench"></em></a></li>			
              <?}}?>																				
							<li>
							  <?if($kernel->user->uid>0){?>  								
  								<a href="<?=$auseraccount?>" title="Uživatelský účet" class="inline <?if(getget('module')=='FUsers'&&getget('action')==''){?>active<?}?>"><em class="fa fa-user"></em></a>
  								<a href="<?=$ausersettings?>" title="Nastavení" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userSettings'){?>active<?}?>"><em class="fa fa-cogs"></em></a> 								
  								<a href="<?=$ausergsc?>" title="Účetní zůstatek (<?=printcost($kernel->user->data->ucetni_zustatek)?> $)" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userGsc'){?>active<?}?>"><em class="fa fa-usd"></em></a> 
  								<a href="<?=$auserpaypal?>" title="Dobití & vyplacení kreditu" class="inline <?if(getget('module')=='FPaypal'){?>active<?}?>"><em class="fa fa-paypal "></em></a>
                  <?/*if($kernel->user->data->prava>0){? ><a href="<?=$aadmin? >" title="Administrace" class="inline" target="_blank"><em class="fa fa-wrench"></em></a><?}*/?> 								
  								<a href="<?=$auserlogout?>" title="Odhlášení uživatele" class="inline"><em class="fa fa-sign-out"></em></a>  								
								<?}else{?>
                  <a href="<?=$auserregistration?>" title="Registrace uživatele" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userRegistration'){?>active<?}?>"><em class="fa fa-user-plus"></em></a>  								  
                  <a href="<?=$auserpassword?>" title="Zapomenuté heslo" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userPassword'){?>active<?}?>"><em class="fa fa-unlock-alt"></em></a>                
                  <a href="<?=$auserlogin?>" title="Přihlášení uživatele" class="inline <?if(getget('module')=='FUsers'&&getget('action')=='userLogIn'){?>active<?}?>"><em class="fa fa-sign-in"></em></a>                                
								<?}?>
							</li>
						</ul>
					</nav>
				</div>
				<?/* //docasne skryto  
        <div class="left-mobile"></div>
  			<div class="left-mobile-content">
  			  <div class="content"><p class="align-center">Lorem ipsum <br> dolor sit amet<br> dolor sit amet!</p></div>		
    			<div class="carousel-wrap col-3">
    				<ul class="list-none">
    					<li>
    						<a href="/"><img src="/img/userfiles/csgo.png" alt="CS:GO" /></a>
    					</li>
    					<li>
    						<a href="/"><img src="/img/userfiles/lol.png" alt="LoL" /></a>
    					</li>
    					<li>
    						<a href="/"><img src="/img/userfiles/wot.png" alt="WoT" /></a>
    					</li>
    					<li>
    						<a href="/"><img src="/img/userfiles/wow.png" alt="WoW" /></a>
    					</li>
    				</ul>
    			</div>			 
  			</div> */?>
			</div>
		</div>
		 <?/* //docasne skryto  
    <div class="page-left">	
      <div class="content"><p class="align-center">Lorem ipsum <br> dolor sit amet<br> dolor sit amet!</p></div>		
			<div class="carousel-wrap col-3">
				<ul class="list-none">
					<li>
						<a href="/"><img src="/img/userfiles/csgo.png" alt="CS:GO" /></a>
					</li>
					<li>
						<a href="/"><img src="/img/userfiles/lol.png" alt="LoL" /></a>
					</li>
					<li>
						<a href="/"><img src="/img/userfiles/wot.png" alt="WoT" /></a>
					</li>
					<li>
						<a href="/"><img src="/img/userfiles/wow.png" alt="WoW" /></a>
					</li>
				</ul>
			</div>			
		</div>
		*/?>
    <div class="page-main"><div class="wrap"><div class="grid align-left grid-form grid-semipadded align-middle"><div class="col col-3-3"><?=$page;?></div></div></div></div>      
    <?/* //docasne skryto   
    <div class="page-right">  <!-- staci pridat do class active -->
			<div class="content"><p class="align-center">Lorem ipsum <br>dolor sit amet</p></div>
			<div class="carousel-wrap">
				<ul class="list-none">
				  <li>
						<a href="/"><img src="/img/userfiles/wow.png" alt="WoW" /></a>
					</li>					
					<li>
						<a href="/"><img src="/img/userfiles/lol.png" alt="LoL" /></a>
					</li>
					<li>
						<a href="/"><img src="/img/userfiles/csgo.png" alt="CS:GO" /></a>
					</li>
					<li>
						<a href="/"><img src="/img/userfiles/wot.png" alt="WoT" /></a>
					</li>					
				</ul>
			</div>
		</div>
	  */?>
    <div class="page-foot grid grid-condensed align-middle">
			<div class="col col-25-100"><div class="size-11"><?=$settings['FOOTER_TEXT'];?></div></div>
			<div class="col col-50-100"><?if(isset($boxes['paticka'])&&count($boxes['paticka'])>0){foreach($boxes['paticka'] as $b){echo $kernel->modules->FBoxes->getContent($b);}}?></div>
			<div class="col col-25-100 align-right pad-top-2"><a href="http://www.mhmsys.cz/" title="Web vytvořila společnost MHMSYS s.r.o." target="_blank"><img src="/img/mhm.png" alt="MHMSYS s.r.o."></a></div>
		</div>         
  </body>  
</html>