<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Registrace uživatele</a></li>     
</ul> 
<h1>Registrace uživatele</h1>
<?if($message=='user-registered'){?><div class="h3 gap-top-20">Uživatelský účet byl zaregistrován. Nyní se můžete přihlásit. <a href="<?=$alogin?>">Přihlásit se -></a></div><?}?> 
<?if($message=='email-exists'){?><div class="h3 gap-top-20">Zvolený e-mail již jednou v systému existuje, zadejte prosíme jiný.</div><?}?>
<?if($message=='email-required'){?><div class="h3 gap-top-20">E-mail je povinný.</div><?}?>
<?if($message=='password-not-same'){?><div class="h3 gap-top-20">Zadaná hesla se neshodují.</div><?}?>
<?if($message=='password-short'){?><div class="h3 gap-top-20">Heslo je příliš krátké.</div><?}?>
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form autocomplete="off" action="<?=$auserregistration;?>" method="post">
  <div class="col col-3-3"><h2>Herní údaje</h2></div>
  <div class="col col-1-3"><label for="osloveni">Nickname*</label><input type="text" size="15" required name="osloveni" id="osloveni" maxlength="63" value="<?=$userreg->osloveni?>" /></div>
  <div class="col col-1-3"><label for="email">E-mail*</label><input type="email" size="20" required name="email" id="email" maxlength="63" value="<?=$userreg->email?>" /></div>
  <div class="col col-1-3"><label for="telefon">Telefon*</label> <td class="align-right"><input type="text" size="35" required name="telefon" id="telefon" maxlength="63" value="<?=$userreg->telefon?>" /></div>
  <div class="col col-1-3"><label for="heslo_1">Heslo*</label><input autocomplete="off" type="password" required size="20" name="heslo_1" id="heslo_1" maxlength="63" value="" /></div>
  <div class="col col-1-3"><label for="heslo_2">Heslo znovu*</label><input autocomplete="off" type="password" required size="20" name="heslo_2" id="heslo_2" maxlength="63" value="" /></div>
  <div class="col col-1-3"><label for="odber_novinek">Odebírat novinky</label> <select name="odber_novinek" id="odber_novinek"> <option value="1" <?if($userreg->odber_novinek==1){?>selected<?}?> >Ano</option> <option value="0" <?if($userreg->odber_novinek==0){?>selected<?}?> >Ne</option></select></div>   
  <div class="col col-3-3"><h2>Nepovinné uživatelské údaje</h2></div>
  <div class="col col-1-3"><label for="titul_pred">Titul</label><input type="text" size="4" name="titul_pred" id="titul_pred" maxlength="63" value="<?=$userreg->titul_pred?>" /></div>
  <div class="col col-1-3"><label for="jmeno">Jméno</label><input type="text" size="17" name="jmeno" id="jmeno" maxlength="63" value="<?=$userreg->jmeno?>" /></div>
  <div class="col col-1-3"><label for="prijmeni">Příjmení</label><input type="text" size="20" name="prijmeni" id="prijmeni" maxlength="63" value="<?=$userreg->prijmeni?>" /></div>
  <div class="col col-1-3"><label for="firma">Společnost</label><input type="text" size="35" name="firma" id="firma" maxlength="63" value="<?=$userreg->firma?>" /></div>
  <div class="col col-1-3"><label for="ico">IČ</label><input type="text" size="20" name="ico" id="ico" maxlength="63" value="<?=$userreg->ico?>" /></div>
  <div class="col col-1-3"><label for="dic">DIČ</label><input type="text" size="15" name="dic" id="dic" maxlength="63" value="<?=$userreg->dic?>" /></div>
  <div class="col col-1-3"><label for="ulice">Ulice</label><input type="text" size="35" name="ulice" id="ulice" maxlength="63" value="<?=$userreg->ulice?>" /></div>
  <div class="col col-1-3"><label for="cislo_popisne">č.p.</label><input type="text" size="20" name="cislo_popisne" id="cislo_popisne" maxlength="63" value="<?=$userreg->cislo_popisne?>" /></div>
  <div class="col col-1-3"><label for="mesto">Město</label><input type="text" size="15" name="mesto" id="mesto" maxlength="63" value="<?=$userreg->mesto?>" /></div>
  <div class="col col-1-3"><label for="psc">PSČ</label><input type="text" size="35" name="psc" id="psc" maxlength="63" value="<?=$userreg->psc?>" /></div>
  <div class="col col-1-3"><label for="stat">Stát</label><input type="text" size="20" name="stat" id="stat" maxlength="63" value="<?=$userreg->stat?>" /></div>
  <div class="col col-1-3 align-center">&nbsp;<br /><input type="submit" value="Registrovat se" /></div>
  <div class="col col-3-3"><b>Údaje označené hvězdičkou jsou povinné. Heslo musí obsahovat minimálně čtyři znaky.</b><br><b>Registrací souhlasíte se zpracováním osobních údajů, více informací naleznete v obchodních podmínkách uvedených zde na stránkách GSoul.cz.</b></div>
  <div class="col col-1-3 align-center"> <a href="<?=$alogin?>">Přihlásit se -></a></div>
  <div class="col col-1-3 align-center"> <a href="<?=$auserpassword?>">Zapomenuté heslo -></a></tr></div>
</form>