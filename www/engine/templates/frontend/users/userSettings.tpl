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
  <form autocomplete="off" action="<?=$asave;?>" method="post" enctype="multipart/form-data">  
    <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_herni_udaje'];?></h2></div>
    <div class="col col-1-3"><label for="osloveni"><?=$systemTranslator['uzivatel_nickname'];?>*</label><input type="text" size="15" required name="osloveni" id="osloveni" maxlength="63" value="<?=$user->osloveni?>" /></div>
    <div class="col col-1-3"><label for="email"><?=$systemTranslator['uzivatel_email'];?>*</label><input type="email" size="20" required name="email" id="email" maxlength="63" value="<?=$user->email?>" /></div>
    <div class="col col-1-3"><label for="email_paypal"><?=$systemTranslator['uzivatel_paypal_email'];?>*</label><input type="email" size="20" required name="email_paypal" id="email_paypal" maxlength="63" value="<?=$user->email_paypal?>" /></div>
    <!-- <div class="col col-1-3"><label for="telefon"><?=$systemTranslator['uzivatel_telefon'];?></label><input  type="text" size="35" name="telefon" id="telefon" maxlength="63" value="<?=$user->telefon?>" /></div> -->
    <div class="col col-3-3  align-center"><br /><button type="submit" class="large btn-dark"><?=$systemTranslator['obecne_ulozit'];?></button></div>
    <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_uzivatelske_udaje'];?></h2></div>
    <div class="col col-1-3"><label for="titul_pred"><?=$systemTranslator['uzivatel_titul'];?></label><input type="text" size="4" name="titul_pred" id="titul_pred" maxlength="63" value="<?=$user->titul_pred?>" /></div>
    <div class="col col-1-3"><label for="jmeno"><?=$systemTranslator['uzivatel_jmeno'];?></label><input type="text"  size="17" name="jmeno" id="jmeno" maxlength="63" value="<?=$user->jmeno?>" /></div>
    <div class="col col-1-3"><label for="prijmeni"><?=$systemTranslator['uzivatel_prijmeni'];?></label><input type="text" size="20" name="prijmeni" id="prijmeni" maxlength="63" value="<?=$user->prijmeni?>" /></div>                 
    <div class="col col-1-3"><label for="firma"><?=$systemTranslator['uzivatel_spolecnost'];?></label><input  type="text" size="35" name="firma" id="firma" maxlength="63" value="<?=$user->firma?>" /></div>
    <div class="col col-1-3"><label for="ico"><?=$systemTranslator['uzivatel_ic'];?></label><input type="text" size="20" name="ico" id="ico" maxlength="63" value="<?=$user->ico?>" /></div>
    <div class="col col-1-3"><label for="dic"><?=$systemTranslator['uzivatel_dic'];?></label><input type="text" size="15" name="dic" id="dic" maxlength="63" value="<?=$user->dic?>" /></div>
    <div class="col col-1-3"><label for="ulice"><?=$systemTranslator['uzivatel_ulice'];?></label><input  type="text" size="35" name="ulice" id="ulice" maxlength="63" value="<?=$user->ulice?>" /></div>
    <div class="col col-1-3"><label for="cislo_popisne"><?=$systemTranslator['uzivatel_cislo_popisne'];?></label><input type="text" size="20" name="cislo_popisne" id="cislo_popisne" maxlength="63" value="<?=$user->cislo_popisne?>" /></div>
    <div class="col col-1-3"><label for="mesto"><?=$systemTranslator['uzivatel_mesto'];?></label><input type="text" size="15" name="mesto" id="mesto" maxlength="63" value="<?=$user->mesto?>" /></div>
    <div class="col col-1-3"><label for="psc"><?=$systemTranslator['uzivatel_psc'];?></label><input type="text" size="35" name="psc" id="psc" maxlength="63" value="<?=$user->psc?>" /></div>
    <div class="col col-1-3"><label for="stat"><?=$systemTranslator['uzivatel_stat'];?></label><input type="text" size="20" name="stat" id="stat" maxlength="63" value="<?=$user->stat?>" /></div>
    <div class="col col-1-3"><label for="odber_novinek"><?=$systemTranslator['uzivatel_odebirat_novinky'];?></label><select name="odber_novinek" id="odber_novinek"><option value="1" <?if($user->odber_novinek==1){?>selected<?}?> ><?=$systemTranslator['obecne_ano'];?></option><option value="0" <?if($user->odber_novinek==0){?>selected<?}?> ><?=$systemTranslator['obecne_ne'];?></option></select></div>    
    <!-- <div class="col col-1-3"><label><?=$systemTranslator['uzivatel_facebook_avatar'];?></label><?=$user->fb_picture==''?' - ':'<div class="user-avatar-48"><img src="'.$user->fb_picture.'" /></div>';?>&nbsp;</div> -->
    <div class="col col-1-3"><label><?=$systemTranslator['uzivatel_vlastni_avatar'];?></label><?=$user->user_picture==''?' - ':'<div class="user-avatar-48 float-left"><img src="/'.$user->user_picture.'" /></div>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; <a href="'.$adelavatar.'" class="button" title="'.$systemTranslator['obecne_odstranit'].'" onclick="return confirm(\''.$systemTranslator['obecne_otazka_opravdu'].'\');">'.$systemTranslator['obecne_odstranit'].'</a>';?>&nbsp;</div>                
    <div class="col col-1-3"><label for="avatar"><?=$systemTranslator['uzivatel_nahrat_zmenit_vlastni_avatar'];?></label><input type="file" size="15" name="avatar" id="avatar" maxlength="63" /></div>
    <div class="col col-1-3  align-center"><br /><button type="submit" class="large btn-dark"><?=$systemTranslator['obecne_ulozit'];?></button></div>             
  </form>      
  <form autocomplete="off" action="<?=$apassword;?>" method="post">
    <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_zmena_hesla'];?></h2></div>     
    <div class="col col-1-3"> <label for="heslo_1"><?=$systemTranslator['uzivatel_nove_heslo'];?>*</label><input required autocomplete="off" type="password" size="20" name="heslo_1" id="heslo_1" maxlength="63" value="" /></div>
    <div class="col col-1-3"> <label for="heslo_2"><?=$systemTranslator['uzivatel_nove_heslo_znovu'];?>*</label><input required autocomplete="off" type="password" size="20" name="heslo_2" id="heslo_2" maxlength="63" value="" /></div>
    <div class="col col-1-3 align-center"> <label>&nbsp;</label><button type="submit" onclick="return confirm('<?=$systemTranslator['uzivatel_opravdu_chcete_zmenit_heslo_plus_4_znaky'];?>');" class="large btn-dark"><?=$systemTranslator['uzivatel_zmenit_heslo'];?></button></div>       
  </form>
</div>