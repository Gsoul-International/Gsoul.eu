<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="<?=$aback?>">Tournaments</a></li>     
  <li><a href="">Přidání nového turnaje</a></li>
</ul> 
<h1>Tournaments - přidání nového turnaje pro hru <?=$game->nazev?></h1>
<br>
<?if($message=='all-needed'){?><div class="h3 ">Turnaj se nepodařilo vytvořit - všechny údaje jsou povinné, prosíme o jejich vyplnění.</div> <br> <?}?>
<?if($message=='count-players'){?><div class="h3 ">Turnaj se nepodařilo vytvořit - minimální i maximální počet hráčů nesmí být nulový, maximální počet hráčů nesmí být menší, než minimální počer.</div><br><?}?>
<?if($message=='date-error'){?><div class="h3 ">Turnaj se nepodařilo vytvořit - zahájení musí být minimálně za hodinu a ukončení musí být minimálně hodinu po zahájení.  </div><br><?}?>
<?if($message=='rounds'){?><div class="h3 ">Turnaj se nepodařilo vytvořit - problém s počtem kol.  </div><br><?}?>
<?if($message=='coins'){?><div class="h3 ">Turnaj se nepodařilo vytvořit - máte nedostatek finančních prostředků k vytvoření tohoto turnaje.  </div><br><?}?>
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form method="post" action="<?=$anext?>">
  <div class="col col-1-3"><label for="idgs">Server</label><select name="idgs" id="idgs" /><?foreach($servers as $g){?><option <?if($g->idgs==$ngData->idgs){?>selected<?}?> value="<?=$g->idgs?>"><?=$g->nazev?></option><?}?></select></div>
  <div class="col col-1-3"><label for="idgt">Typ hry</label><select name="idgt" id="idgt" /><?foreach($types as $g){?><option <?if($g->idgt==$ngData->idgt){?>selected<?}?> value="<?=$g->idgt?>"><?=$g->nazev?></option><?}?></select></div>
  <div class="col col-1-3"><label for="idgm">Mapa</label><select name="idgm" id="idgm" /><?foreach($maps as $g){?><option <?if($g->idgm==$ngData->idgm){?>selected<?}?> value="<?=$g->idgm?>"><?=$g->nazev?></option><?}?></select></div>
  <div class="col col-1-3"><label for="cena">Vstupní poplatek [$]</label><input type="text" name="cena" id="cena" value="<?=$ngData->cena?>" /></div>
  <div class="col col-1-3"><label for="datum_cas_startu">Datum a čas zahájení</label><input type="text" name="datum_cas_startu" id="datum_cas_startu" value="<?=$ngData->datum_cas_startu?>" /></div>  
  <div class="col col-1-3"><label for="minimalni_pocet_hracu">Minimální počet hráčů</label><input type="text" name="minimalni_pocet_hracu" id="minimalni_pocet_hracu" min="1" max="<?=$moduleGame->maximalni_pocet_hracu?>" value="<?=$ngData->minimalni_pocet_hracu?>" /></div>
  <div class="col col-1-3"><label for="maximalni_pocet_hracu">Maximální počet hráčů</label><input type="text" name="maximalni_pocet_hracu" id="maximalni_pocet_hracu" min="1" max="<?=$moduleGame->maximalni_pocet_hracu?>" value="<?=$ngData->maximalni_pocet_hracu?>" /></div>
  <div class="col col-1-3"><label for="pocet_kol">Počet kol</label> <input type="text" name="pocet_kol" id="pocet_kol" min="1" max="<?=$moduleGame->maximalni_pocet_kol?>" value="<?=$ngData->pocet_kol?>" /></div>
  <div class="col col-1-3">
    <label for="id_vyplaty">Výplata</label> 
    <select id="id_vyplaty" name="id_vyplaty">
      <?foreach($vyplaty as $vp){$xsum=0;for($i=1;$i<11;$i++){$xx='misto_'.$i;$xsum+=$vp->$xx;}if($xsum<=90){?>
         <option value="<?=$vp->idgwt?>"><?=$vp->nazev?></option>
      <?}}?>
    </select>
  </div>  
  <div class="col col-1-3"><label for="poznamka_zakladatele">Poznámka zakladatele</label><textarea name="poznamka_zakladatele" id="poznamka_zakladatele" rows="3" cols="25"><?=$ngData->poznamka_zakladatele?></textarea></div>  
  <div class="col col-1-3"><label for="pravidla_turnaje_mala">Pravidla turnaje</label><textarea name="pravidla_turnaje_mala" id="pravidla_turnaje_mala" rows="3" cols="25"><?=$ngData->pravidla_turnaje_mala?></textarea></div>
  <div class="col col-1-3"><label for="pravidla_turnaje_velka">Podrobná pravidla turnaje</label><textarea name="pravidla_turnaje_velka" id="pravidla_turnaje_velka" rows="3" cols="25"><?=$ngData->pravidla_turnaje_velka?></textarea></div>
  <div class="col col-3-3 align-justify">&nbsp;<br><b>Vstupní náklady</b> na vytvoření turnaje jsou <b><?=printcost($moduleGame->poplatek_za_zalozeni_turnaje)?> $</b>. Tyto náklady budou odečteny z&nbsp;Vašeho účtu ihned po vytvoření turnaje.<br>Po skončení turnaje máte povinnost nahrát správné a pravdivé výsledky celého turnaje. Na základě těchto výsledků budou přerozděleny výhry.<br>Za Vaši práci s turnajem získáte při přerozdělení výher k případné výhře také odměnu ve výši <b><?=printcost($moduleGame->procenta_pro_zakladatele)?> % z vkladu všech hráčů.</b></div>
  <div class="col col-3-3 align-center"> &nbsp;<br /> <input type="submit" value="Vytvořit turnaj"></div>
  
</form>
