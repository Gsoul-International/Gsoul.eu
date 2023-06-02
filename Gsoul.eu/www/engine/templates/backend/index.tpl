<!DOCTYPE html>
<html lang="cs">
  <head>
    <script type="text/javascript">var StartPageLoad = new Date()</script>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$SEO_title?></title>
    <meta name="keywords" content="<?=$SEO_keywords?>" />
    <meta name="description" content="<?=$SEO_description?>" />
    <meta name="author" content="Gsoul" />
    <meta name="robots" content="index, follow" />
    <link rel="icon" type="image/ico" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/kernel.css" media="all"> 
    <link rel="stylesheet" type="text/css" href="/css/backend.css" media="all"> 
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="/css/jquery-ui-1.9.2.custom.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="/editor/editor.css" media="all">
    <script type="text/javascript" src="/js/backendPlugins.js"></script> 
    <script type="text/javascript" src="/editor/nicedit.js"></script>        
    <script type="text/javascript" src="/editor/niceditInit.js"></script>         
  </head>
  <body class="BackendBody">
  
    <div class="BackendWrap">
      <div class="BackendHeader">     
        <?if(count($topMenu)>0){?>         
          <ul class="topMenu">
            <li class="logo <?if(getget('module','')=='Backend'){?>active<?}?>">
              <a href="<?=$abackend?>" title="Úvodní strana administrace">
                <img align="middle" src="/img/logo.png" alt="Gsoul" /> <br>
              </a>
            </li>            
            <?foreach($topMenu as $tm){?>
              <li <?if($tm->active==1){?>class="active"<?}?> >
                <a title="<?=$tm->name;?>" href="<?=$tm->ahref;?>">
                  <div class="BackendMenuIcons"><?=$tm->icon;?></div>
                  <?=$tm->name;?>
                </a>  
              </li>    
            <?}?>
            <?if($user->prava!=2){?>
            <li>
              <a title="Zobrazit web" href="/" target="_blank">
                <div class="BackendMenuIcons"><i class="fa fa-star"></i></div>
                Zobrazit web
              </a>  
            </li>  
            <?}?> 
            <li>
              <a title="Odhlásit se" href="<?=$alogout;?>">
                <div class="BackendMenuIcons"><i class="fa fa-sign-out"></i></div>
                Odhlásit se
              </a>  
            </li>   
          </ul>
        <?}?>
        <div class="ClearBoth"></div>
      </div>
      <div class="BackendContent">      
        <div class="BackendContentLeft">    
          <div class="BackendContentMargin">      
            <?=$leftMenu;?>
            <div class="BackendUser">
              <div class="BackendUserMargin">
              <table align="center">          
                <tr><th>Přihlášen uživatel</th><td><?=trim($user->titul_pred.' '.$user->jmeno.' '.$user->prijmeni.' '.$user->titul_za);?></td></tr>                        
                <tr><th>Poslední přihlášení</th><td><?=TimestampToDateTime($user->posledni_prihlaseni);?></td></tr>
                <!--<tr><th>IP adresa</th><td><?=$user->posledni_prihlaseni_ip;?></td></tr>-->
                <tr><th>Čas "on ready"</th><td><span id="PageLoad2">?</span> sec.</td></tr>
                <tr><th>Čas "on load"</th><td><span id="PageLoad">?</span> sec.</td></tr>
                <tr><th>Čas vygenerování</th><td><?=number_format(round(((microtime(true)-$time_start)),3),3,',',' ');?> sec.</td></tr>
                <tr><th>Využití paměti</th><td><?=convert_memory(memory_get_usage());?></td></tr>
              </table>
              </div>                             
            </div>
          </div>
        </div>      
        <div class="BackendContentRight">
          <div class="BackendContentMargin">
            <?=$content;?>
          </div>
        </div>  
      </div>
      <div class="ClearBoth"></div>
      <div class="BackendFooter">
      </div>
    </div>
  </body>  
</html>
  