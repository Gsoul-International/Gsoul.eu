<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Uživatelský účet</a></li>     
</ul> 
<h1>Uživatelský účet</h1>
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle">
<div class="col col-3-3"><h2>Účetní zůstatek</h2></div>
<div class="col col-3-3">Aktuální stav konta: <b><?=printcost($user->ucetni_zustatek)?> $.</b></div>
<div class="col col-3-3"><h2>Herní údaje</h2></div>
<div class="col col-1-3"><b>Nickname</b><br /><?=$user->osloveni?>&nbsp;</div>
<div class="col col-1-3"><b>E-mail</b><br /><?=$user->email?>&nbsp;</div>
<div class="col col-1-3"><b>Telefon</b><br /><?=$user->telefon?>&nbsp;</div>
<div class="col col-3-3"><h2>Uživatelské údaje</h2></div>
<div class="col col-1-3"><b>Titul</b><br /><?=$user->titul_pred?>&nbsp;</div>
<div class="col col-1-3"><b>Jméno</b><br /><?=$user->jmeno?>&nbsp;</div>
<div class="col col-1-3"><b>Příjmení</b><br /><?=$user->prijmeni?>&nbsp;</div>
<div class="col col-1-3"><b>Společnost</b><br /><?=$user->firma?>&nbsp;</div>
<div class="col col-1-3"><b>IČ</b><br /><?=$user->ico?>&nbsp;</div>
<div class="col col-1-3"><b>DIČ</b><br /><?=$user->dic?>&nbsp;</div>
<div class="col col-1-3"><b>Ulice</b><br /><?=$user->ulice?>&nbsp;</div>
<div class="col col-1-3"><b>č.p.</b><br /><?=$user->cislo_popisne?>&nbsp;</div>
<div class="col col-1-3"><b>Město</b><br /><?=$user->mesto?>&nbsp;</div>
<div class="col col-1-3"><b>PSČ</b><br /><?=$user->psc?>&nbsp;</div>
<div class="col col-1-3"><b>Stát</b><br /><?=$user->stat?>&nbsp;</div>
<div class="col col-1-3"><b>Odebírat novinky</b><br /><?if($user->odber_novinek==1){?>Ano<?}?> <?if($user->odber_novinek==0){?>Ne<?}?>&nbsp;</div>