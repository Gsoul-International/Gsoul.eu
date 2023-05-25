<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle">
  <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_ucetni_zustatek'];?></h2></div>   
  <div class="col col-3-3"><?=$systemTranslator['uzivatel_aktualni_stav_uzivatelskeho_konta'];?>: <b><?=printcost($user->ucetni_zustatek)?> $.</b></div>            
  <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_herni_udaje'];?></h2></div>  
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_nickname'];?></b><br /><?=$user->osloveni?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_email'];?></b><br /><?=$user->email?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_overeny_email'];?></b><br /><?=$user->overen_email==1?$systemTranslator['obecne_ano']:$systemTranslator['obecne_ne']?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_paypal_email'];?></b><br /><?=$user->email_paypal?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_overeny_paypal_email'];?></b><br /><?=$user->overen_paypal_email==1?$systemTranslator['obecne_ano']:$systemTranslator['obecne_ne']?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_telefon'];?></b><br /><?=$user->telefon?>&nbsp;</div>
  <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_uzivatelske_udaje'];?></h2></div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_titul'];?></b><br /><?=$user->titul_pred?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_jmeno'];?></b><br /><?=$user->jmeno?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_prijmeni'];?></b><br /><?=$user->prijmeni?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_spolecnost'];?></b><br /><?=$user->firma?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_ic'];?></b><br /><?=$user->ico?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_dic'];?></b><br /><?=$user->dic?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_ulice'];?></b><br /><?=$user->ulice?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_cislo_popisne'];?></b><br /><?=$user->cislo_popisne?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_mesto'];?></b><br /><?=$user->mesto?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_psc'];?></b><br /><?=$user->psc?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_stat'];?></b><br /><?=$user->stat?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_odebirat_novinky'];?></b><br /><?if($user->odber_novinek==1){?><?=$systemTranslator['obecne_ano']?><?}?> <?if($user->odber_novinek==0){?><?=$systemTranslator['obecne_ne']?><?}?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_prihlaseni_pres_facebook'];?></b><br /><?=$user->fb_id==''?$systemTranslator['obecne_ne']:$systemTranslator['obecne_ano'];?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_facebook_avatar'];?></b><br /><?=$user->fb_picture==''?' - ':'<div class="user-avatar-48"><img src="'.$user->fb_picture.'" /></div>';?>&nbsp;</div>
  <div class="col col-1-3"><b><?=$systemTranslator['uzivatel_vlastni_avatar'];?></b><br /><?=$user->user_picture==''?' - ':'<div class="user-avatar-48"><img src="/'.$user->user_picture.'" /></div>';?>&nbsp;</div>
</div>