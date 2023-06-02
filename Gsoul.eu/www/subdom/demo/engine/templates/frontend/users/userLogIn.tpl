<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Přihlášení uživatele</a></li>     
</ul>  
<h1>Přihlášení uživatele</h1>
<?if($message=='user-success-logout'){?><div class="h3 gap-top-20">Uživatel byl úspěšně odhlášen.</div><?}?>
<?if($message=='user-success-login'){?><div class="h3 gap-top-20">Uživatel byl úspěšně přihlášen.</div><?}?>
<?if(getget('LoginError','')=='1'){?><div class="h3 gap-top-20">Přihlášení uživatele se nezdařilo.</div><?}?> 
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<div class="col col-3-3"><h2>Přihlášení pomocí GSoul účtu</h2></div>
<form autocomplete="off" action="<?=$alogin;?>" method="post">
<div class="col col-1-3"><label for="email">E-mail</label> <input required autocomplete="off" type="text" size="20" name="email" id="email" maxlength="63" value="" /></div>
<div class="col col-1-3"><label for="pass">Heslo</label> <input required autocomplete="off" type="password" size="20" name="pass" id="pass" maxlength="63" value="" /></div>
<div class="col col-1-3 align-center">&nbsp;<br /> <input type="submit" autocomplete="off" value="Přihlásit se" /></div>
<div class="col col-3-3">&nbsp;</div>
<div class="col col-1-3 align-center"><a href="<?=$auserpassword?>">Zapomenuté heslo -></a></div>
<div class="col col-1-3 align-center"><a href="<?=$auserregistration?>">Registrace uživatele -></a></div>         
</form>
<div class="col col-3-3"><h2>Přihlášení pomocí Facebook účtu</h2></div>
<div class="col col-1-3 align-center">
  <script>
  window.fbAsyncInit = function() {    
    FB.init({appId: '2086791198314490', cookie: true, xfbml: true, version: 'v2.8'});    
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
  <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">Přihlásit se přes facebook -></a> <br /> <br /> 
  <div id="status"></div>
</div>