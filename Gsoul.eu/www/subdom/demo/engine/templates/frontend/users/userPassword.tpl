<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Zapomenuté heslo</a></li>     
</ul>  
<h1>Zapomenuté heslo</h1>
<?if($message=='password-send'){?><div class="h3  gap-top-20">Nové heslo bylo zasláno na Váš E-mail.</div><?}?>
<?if($message=='password-not-send'){?><div class="h3 gap-top-20">Uživatel s tímto e-mailem neexistuje.</div><?}?>
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form autocomplete="off" action="<?=$anewpassword;?>" method="post">
  <div class="col col-1-3"><label for="email">Zadejte svůj E-mail</label><input required autocomplete="off" type="text" size="20" name="email" id="email" maxlength="63" value="" /></div>           
  <div class="col col-1-3 align-center">&nbsp;<br><input type="submit" autocomplete="off" value="Odeslat zapomenuté heslo" /></div>
  <div class="col col-3-3">&nbsp;</div>
  <div class="col col-1-3 align-center"><a href="<?=$alogin?>">Přihlásit se -></a></div>  
  <div class="col col-1-3 align-center"><a href="<?=$auserregistration?>">Registrace uživatele -></a></div>      
</form>