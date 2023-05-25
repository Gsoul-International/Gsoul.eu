<!DOCTYPE html>
<html lang="cs">
  <head>
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
    <script src="/js/jquery-2.1.3.min.js"></script>   
  </head>
  <body class="BackendBody">    
    <div class="BackendLoginPreWrap">    
      <div class="BackendLoginSpace"></div>
      <div class="BackendLoginWrap">
        <div id="BackendLoginForm">
          <table align="center" width="100%" class="LoginTable">
            <tr>
              <td align="center" valign="center">
                <a title="Gsoul" target="_blank"><img src="/img/logo.png" /></a>
                <h1>System Gsoul</h1>                               
              </td>
            </tr>                          
                <?if(getget('LoginError')=='1'){?>
                  <tr>
                    <td valign="center" align="center">
                      <strong>Přihlášení se nezdařilo.</strong> 
                    </td>
                  </tr>
                <?}?>                
            <tr>
              <td valign="center" align="center">
                <form action="<?=$alogin?>" method="post">
                  <table class="LoginTable2">
                    <tr>
                      <th>E-mail:</th>
                      <td colspan="2" align="left" class="width-242">
                        <input class="width-242" size="30" type="text" name="email" value="" />
                      </td>                      
                    </tr>
                    <tr>
                      <th>Heslo:</th>
                      <td align="left" class="width-202">
                        <input class="width-202" size="30" type="password" name="pass" value="" />
                      </td>
                      <td align="right" class="width-40">
                        <input alt="Přihlásit se" type="submit" value="&gt;&gt;" />
                      </td>
                    </tr>                    
                  </table>
                </form>            
              </td>
            </tr>        
            <tr>
              <td align="center" valign="center">                                 
              </td>        
            </tr>
          </table>
        </div>
      </div> 
    </div> 
  </body>  
</html>