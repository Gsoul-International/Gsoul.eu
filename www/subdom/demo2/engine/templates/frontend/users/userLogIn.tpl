<div class="breadcrumb">
  <ul>
    <li><a href="/"><?=$mainpagename;?></a></li>   
    <li><a href=""><?=$systemTranslator['uzivatel_prihlaseni_uzivatele'];?></a></li>     
  </ul>
</div>  
<h1><?=$systemTranslator['uzivatel_prihlaseni_uzivatele'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_prihlaseni_pomoci_gsoul_uctu'];?></h2></div>        
<form autocomplete="off" action="<?=$alogin;?>" method="post">
<div class="col col-1-3"><label for="email"><?=$systemTranslator['uzivatel_email'];?></label> <input required autocomplete="off" type="text" size="20" name="email" id="email" maxlength="63" value="" /></div>
<div class="col col-1-3"><label for="pass"><?=$systemTranslator['uzivatel_heslo'];?></label> <input required autocomplete="off" type="password" size="20" name="pass" id="pass" maxlength="63" value="" /></div>
<div class="col col-1-3 align-left"><label>&nbsp;</label><button type="submit" class="large btn-dark"><?=$systemTranslator['uzivatel_prihlasit_se'];?></button></div>      
<div class="col col-3-3">&nbsp;</div>
<div class="col col-1-3 align-center"><br><a class="button large" href="<?=$auserpassword?>"><?=$systemTranslator['uzivatel_zapomenute_heslo'];?> -></a></div>
<div class="col col-1-3 align-center"><br><a class="button large" href="<?=$auserregistration?>"><?=$systemTranslator['uzivatel_registrace_uzivatele'];?> -></a></div>         
</form>
<?/*?>
<div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_prihlaseni_pomoci_facebooku'];?></h2></div>   
<div class="col col-1-3 align-center">
  <script>
  window.fbAsyncInit = function() {    
    FB.init({appId: '2086791198314490', cookie: true, xfbml: true, version: 'v2.8'});//v2.8    
    FB.getLoginStatus(function(response) { if (response.status === 'connected') { fbLogout(); } });
  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  function fbLogin() {
    FB.login(function (response) {
      if (response.authResponse) {          
        getFbUserData();
      } else {
        document.getElementById('status').innerHTML = 'Přihlášení přes Facebook selhalo.';
      }
    }, {scope: 'email'});
  }
  function getFbUserData(){
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
    function (response) {        
      saveUserData(response);      
    });
  }
  function fbLogout() {
    FB.logout(function() {      
        //document.getElementById('status').innerHTML = 'Komunikace s Facebookem dokončena.';
    });
  }
  function saveUserData(userData){    
    fbLogout();
 
    post('/users/fb/login/',{oauth_provider:'facebook',userData: JSON.stringify(userData)},'post');    
  }
  function post(path, params, method) {
    method = method || "post"; 
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for(var key in params) {
      if(params.hasOwnProperty(key)) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
      }
    }
    document.body.appendChild(form);
    form.submit();
  }
  </script>        
  <a class="button btn-blue large" href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><?=$systemTranslator['uzivatel_prihlasit_se_pres_facebook'];?> -></a> <br /> <br /> 
  <div id="status"></div>
</div>
<?*/?>
</div>