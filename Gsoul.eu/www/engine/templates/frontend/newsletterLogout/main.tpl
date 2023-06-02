<h1><?=$systemTranslator['nwslgout_odhlaseni_odberu_newsletteru'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
  <form autocomplete="off" action="<?=$alogout;?>" method="post">
    <div class="col col-1-3"><label for="email"><?=$systemTranslator['nwslgout_zadejte_svuj_email'];?></label><input required autocomplete="off" type="text" size="20" name="email" id="email" maxlength="63" value="<?=str_replace('"','',$email);?>" /></div>           
    <div class="col col-1-3 align-center"><label>&nbsp;</label><button type="submit" class="large"><?=$systemTranslator['nwslgout_tlacitko_odhlasit'];?></button></div>
    <div class="col col-3-3">&nbsp;</div>  
  </form>
</div>