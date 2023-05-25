<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_registrace_uzivatele'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_registrace_uzivatele'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form autocomplete="off" action="<?=$auserregistration;?>" method="post">
  <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_herni_udaje'];?></h2></div>
  <div class="col col-1-3"><label for="osloveni"><?=$systemTranslator['uzivatel_nickname'];?> <span class="red">*</span></label><input type="text" size="15" required name="osloveni" id="osloveni" maxlength="63" value="<?=$userreg->osloveni?>" /></div>
  <div class="col col-1-3"><label for="email"><?=$systemTranslator['uzivatel_email'];?> <span class="red">*</span></label><input type="email" size="20" required name="email" id="email" maxlength="63" value="<?=$userreg->email?>" /></div>
  <div class="col col-1-3"><label for="email_paypal"><?=$systemTranslator['uzivatel_paypal_email'];?> <span class="red">*</span></label><input type="email" size="20" required name="email_paypal" id="email_paypal" maxlength="63" value="<?=$userreg->email_paypal?>" /></div>  
  <div class="col col-1-3"><label for="heslo_1"><?=$systemTranslator['uzivatel_heslo'];?> <span class="red">*</span></label><input autocomplete="off" type="password" required size="20" name="heslo_1" id="heslo_1" maxlength="63" value="<?=$userreg->heslo_1?>" /></div>
  <div class="col col-1-3"><label for="heslo_2"><?=$systemTranslator['uzivatel_heslo_znovu'];?> <span class="red">*</span></label><input autocomplete="off" type="password" required size="20" name="heslo_2" id="heslo_2" maxlength="63" value="<?=$userreg->heslo_2?>" /></div>
  <div class="col col-1-3">
      <label><?=$systemTranslator['uzivatel_registrace_souhlas_s_podminkami'];?> <span class="red">*</span></label>
      <label>
        <input type="checkbox" name="terms_agree" value="1" <?if($userreg->terms_agree==1){?>checked<?}?> style="width:auto;" required />         
        <?=$systemTranslator['uzivatel_registrace_souhlas_s_gdpr_vop'];?>
      </label>                                     
  </div>   
  <div class="col col-1-3 align-center">
    <label>&nbsp;</label>
    <a class="button large" target="_blank" href="<?=$systemTranslator['uzivatel_registrace_cist_gdpr_link'];?>" title="<?=$systemTranslator['uzivatel_registrace_cist_gdpr'];?>"><?=$systemTranslator['uzivatel_registrace_cist_gdpr'];?> -></a>      
  </div> 
  <div class="col col-1-3 align-center">
    <label>&nbsp;</label>
    <a class="button large" target="_blank" href="<?=$systemTranslator['uzivatel_registrace_cist_vop_link'];?>" title="<?=$systemTranslator['uzivatel_registrace_cist_vop'];?>"><?=$systemTranslator['uzivatel_registrace_cist_vop'];?> -></a>
  </div> 
  <div class="col col-1-3 align-center"><label>&nbsp;</label><button type="submit" class="large btn-dark"><?=$systemTranslator['uzivatel_registrovat_se'];?></button></div>
  <div class="col col-3-3"><h2><?=$systemTranslator['uzivatel_nepovinne_uzivatelske_udaje'];?></h2></div> 
  <!-- <div class="col col-1-3"><label for="titul_pred"><?=$systemTranslator['uzivatel_titul'];?></label><input type="text" size="4" name="titul_pred" id="titul_pred" maxlength="63" value="<?=$userreg->titul_pred?>" /></div> -->
  <div class="col col-1-3"><label for="jmeno"><?=$systemTranslator['uzivatel_jmeno'];?></label><input type="text" size="17" name="jmeno" id="jmeno" maxlength="63" value="<?=$userreg->jmeno?>" /></div>
  <div class="col col-1-3"><label for="prijmeni"><?=$systemTranslator['uzivatel_prijmeni'];?></label><input type="text" size="20" name="prijmeni" id="prijmeni" maxlength="63" value="<?=$userreg->prijmeni?>" /></div>
  <div class="col col-1-3"><label for="firma"><?=$systemTranslator['uzivatel_spolecnost'];?></label><input type="text" size="35" name="firma" id="firma" maxlength="63" value="<?=$userreg->firma?>" /></div>
  <div class="col col-1-3"><label for="ico"><?=$systemTranslator['uzivatel_ic'];?></label><input type="text" size="20" name="ico" id="ico" maxlength="63" value="<?=$userreg->ico?>" /></div>
  <div class="col col-1-3"><label for="dic"><?=$systemTranslator['uzivatel_dic'];?></label><input type="text" size="15" name="dic" id="dic" maxlength="63" value="<?=$userreg->dic?>" /></div>
  <div class="col col-1-3"><label for="ulice"><?=$systemTranslator['uzivatel_ulice'];?></label><input type="text" size="35" name="ulice" id="ulice" maxlength="63" value="<?=$userreg->ulice?>" /></div>
  <div class="col col-1-3"><label for="cislo_popisne"><?=$systemTranslator['uzivatel_cislo_popisne'];?></label><input type="text" size="20" name="cislo_popisne" id="cislo_popisne" maxlength="63" value="<?=$userreg->cislo_popisne?>" /></div>
  <div class="col col-1-3"><label for="mesto"><?=$systemTranslator['uzivatel_mesto'];?></label><input type="text" size="15" name="mesto" id="mesto" maxlength="63" value="<?=$userreg->mesto?>" /></div>
  <div class="col col-1-3"><label for="psc"><?=$systemTranslator['uzivatel_psc'];?></label><input type="text" size="35" name="psc" id="psc" maxlength="63" value="<?=$userreg->psc?>" /></div>
  <div class="col col-1-3"><label for="stat"><?=$systemTranslator['uzivatel_stat'];?></label><input type="text" size="20" name="stat" id="stat" maxlength="63" value="<?=$userreg->stat?>" /></div>
  <!-- <div class="col col-1-3"><label for="telefon"><?=$systemTranslator['uzivatel_telefon'];?></label> <td class="align-right"><input type="text" size="35" name="telefon" id="telefon" maxlength="63" value="<?=$userreg->telefon?>" /></div> -->
  <div class="col col-1-3"><label for="odber_novinek"><?=$systemTranslator['uzivatel_odebirat_novinky'];?></label> <select name="odber_novinek" id="odber_novinek"> <option value="1" <?if($userreg->odber_novinek==1){?>selected<?}?> ><?=$systemTranslator['obecne_ano'];?></option> <option value="0" <?if($userreg->odber_novinek==0){?>selected<?}?> ><?=$systemTranslator['obecne_ne'];?></option></select></div>  
  <div class="col col-1-3 align-center"><label>&nbsp;</label><button type="submit" class="large btn-dark"><?=$systemTranslator['uzivatel_registrovat_se'];?></button></div>   
  <div class="col col-3-3">
    <br />
    <div class="text-box">
			<div class="icon-wrap">
				<em class="fa fa-info"></em>
			</div>
			<div class="content-wrap">
				<?=$systemTranslator['uzivatel_registrace_povinne_udaje'];?><br>        
        <?=$systemTranslator['uzivatel_registrace_souhlas_s_obchodnimi_podminkami'];?>				
			</div>
		</div>
		<br />
  </div>
  <div class="col col-1-3 align-center"><br><a class="button large" href="<?=$alogin?>"><?=$systemTranslator['uzivatel_prihlasit_se'];?> -></a></div>
  <div class="col col-1-3 align-center"><br><a class="button large" href="<?=$auserpassword?>"><?=$systemTranslator['uzivatel_zapomenute_heslo'];?> -></a></tr></div>
</form>
</div>