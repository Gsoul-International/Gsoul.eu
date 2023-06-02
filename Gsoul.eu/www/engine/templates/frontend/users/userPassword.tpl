<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_zapomenute_heslo'];?></a></li>     
  </ul>
</div>  
<h1><?=$systemTranslator['uzivatel_zapomenute_heslo'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form autocomplete="off" action="<?=$anewpassword;?>" method="post">
  <div class="col col-1-3"><label for="email"><?=$systemTranslator['uzivatel_zadejte_svuj_email'];?></label><input required autocomplete="off" type="text" size="20" name="email" id="email" maxlength="63" value="" /></div>           
  <div class="col col-1-3 align-center"><label>&nbsp;</label><button type="submit" class="large btn-dark"><?=$systemTranslator['uzivatel_odeslat_zapomenute_heslo'];?></button></div>
  <div class="col col-3-3">&nbsp;</div>
  <div class="col col-1-3 align-center"><br><a class="button large" href="<?=$alogin?>"><?=$systemTranslator['uzivatel_prihlasit_se'];?> -></a></div>  
  <div class="col col-1-3 align-center"><br><a class="button large" href="<?=$auserregistration?>"><?=$systemTranslator['uzivatel_registrace_uzivatele'];?> -></a></div>      
</form>
</div>