<h1>Hraní - Editace hry</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis her</a><br>     
</div>
<br>
<?if(getget('message','')=='saved'){?><h2>Hra úspěšně uložena.</h2><br><?}?>
<?if(getget('message','')=='not-saved'){?><h2>Hru se nepodařilo uložit - musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='server-added'){?><h2>Herní server přidán.</h2><br><?}?>
<?if(getget('message','')=='server-not-added'){?><h2>Herní server se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='server-saved'){?><h2>Herní server uložen.</h2><br><?}?>
<?if(getget('message','')=='server-not-saved'){?><h2>Herní server se nepodařilo uložit, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='server-deleted'){?><h2>Herní server smazán.</h2><br><?}?>
<?if(getget('message','')=='type-added'){?><h2>Typ hry přidán.</h2><br><?}?>
<?if(getget('message','')=='type-not-added'){?><h2>Typ hry se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='type-saved'){?><h2>Typ hry uložen.</h2><br><?}?>
<?if(getget('message','')=='type-not-saved'){?><h2>Typ hry se nepodařilo uložit, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='type-deleted'){?><h2>Typ hry smazán.</h2><br><?}?>
<?if(getget('message','')=='map-added'){?><h2>Mapa hry přidána.</h2><br><?}?>
<?if(getget('message','')=='map-not-added'){?><h2>Mapa hry se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='map-saved'){?><h2>Mapa hry uložena.</h2><br><?}?>
<?if(getget('message','')=='map-not-saved'){?><h2>Mapa hry se nepodařila uložit, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='map-deleted'){?><h2>Mapa hry smazána.</h2><br><?}?>
<?if(getget('message','')=='winner-added'){?><h2>Výplata hry přidána.</h2><br><?}?>
<?if(getget('message','')=='winner-not-added'){?><h2>Výplatu hry se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='winner-saved'){?><h2>Výplata hry uložena.</h2><br><?}?>
<?if(getget('message','')=='winner-not-saved'){?><h2>Výplatu hry se nepodařila uložit, musíte zadat název.</h2><br><?}?>

<form method="post" action="<?=$aedit?>">
  <table>
    <tr><th>Název hry (je povinný):</th><th colspan="3">E-mail ohledně ukončeného turnaje:</th></tr>
    <tr><td><input class="width-200" type="text" name="nazev" value="<?=$data->nazev?>" /></td><td><input class="width-200" type="text" name="mail_ukonceni_zapasu" value="<?=$data->mail_ukonceni_zapasu?>" /></td><td> Aktivní: <input type="checkbox" name="aktivni" value="1" <?if($data->aktivni==1){?>checked<?}?> /></td><td style="text-align:right"><input type="submit" value="Uložit" /></td></tr>
  </table>
</form>
<br>
<table>
  <form method="post" action="<?=$aaddserver?>">
    <tr><th>Přidat server:</th><td>Název: <input class="width-400" type="text" name="nazev" value="" /></td><td> Aktivní: <input type="checkbox" name="aktivni" value="1" checked /></td><td style="text-align:right"><input type="submit" value="Přidat" /></td></tr>
  </form>
  <form method="post" action="<?=$aaddtype?>">
    <tr><th>Přidat typ hry:</th><td>Název: <input class="width-400" type="text" name="nazev" value="" /></td><td> Aktivní: <input type="checkbox" name="aktivni" value="1" checked /></td><td style="text-align:right"><input type="submit" value="Přidat" /></td></tr>
  </form>
  <form method="post" action="<?=$aaddmap?>" enctype="multipart/form-data">
    <tr>
    <th>Přidat mapu:</th>
    <td>
      Název: <input class="width-200" type="text" name="nazev" value="" />
      Obrázek: <input type="file" value="" name="soubor" style="max-width:100px;width:100px;" />
    </td>
    <td> Aktivní: <input type="checkbox" name="aktivni" value="1" checked /></td>
    <td style="text-align:right"><input type="submit" value="Přidat" /></td>
    </tr>
  </form>
  <form method="post" action="<?=$aaddwinner?>">
    <tr>
      <th>Přidat Výplatu:</th>
      <td>
        Název: <input class="width-200" type="text" name="nazev" value="" />
        Typ:          
        <select name="winners_count">
          <option value="1">vyplatit 1 hráče</option>
          <option value="2">vyplatit 2 hráče</option>
          <option value="3">vyplatit 3 hráče</option>
          <option value="4">vyplatit 4 hráče</option>
          <option value="5">vyplatit 5 hráčů</option>
          <option value="6">vyplatit 6 hráčů</option>
          <option value="7">vyplatit 7 hráčů</option>
          <option value="8">vyplatit 8 hráčů</option>
          <option value="9">vyplatit 9 hráčů</option>
          <option value="10">vyplatit 10 hráčů</option>
        </select>  
      </td>
      <td> Aktivní: <input type="checkbox" name="aktivni" value="1" checked /></td>
      <td style="text-align:right"><input type="submit" value="Přidat" /></td>
    </tr>
  </form>
</table>
<br>
<table>
  <tr><th colspan="4">Herní servery</th></tr>  
  <?foreach($servers as $sx){?>
    <form method="post" action="<?=$sx->aeditserver?>">
      <tr>
        <td>Název: <input class="width-400" type="text" name="nazev" value="<?=$sx->nazev?>" /></td>
        <td>Aktivní: <input type="checkbox" name="aktivni" value="1" <?if($sx->aktivni==1){?>checked<?}?> /></td>
        <td width="3%"><?/*<a title="Smazat server" href="<?=$sx->adel? >" onclick="return confirm('Opravdu si přejete smazat tento server?');"><i class="fa fa-trash-o"></i></a><?*/?></td>
        <td style="text-align:right"><input type="submit" value="Uložit" /></td>
        </tr>
    </form>  
  <?}?>
</table>
<br>
<table>
  <tr><th colspan="4">Typy hry</th></tr>  
  <?foreach($types as $tx){?>
    <form method="post" action="<?=$tx->aedittype?>">
      <tr>
        <td>Název: <input class="width-400" type="text" name="nazev" value="<?=$tx->nazev?>" /></td>
        <td>Aktivní: <input type="checkbox" name="aktivni" value="1" <?if($tx->aktivni==1){?>checked<?}?> /></td>
        <td width="3%"><?/*<a title="Smazat server" href="<?=$tx->adel? >" onclick="return confirm('Opravdu si přejete smazat tento typ hry?');"><i class="fa fa-trash-o"></i></a>*/?></td>
        <td style="text-align:right"><input type="submit" value="Uložit" /></td>
        </tr>
    </form>  
  <?}?>
</table>
<br>
<table>
  <tr><th colspan="4">Mapy</th></tr>  
  <?foreach($maps as $mp){?>
    <form method="post" action="<?=$mp->aeditmap?>" enctype="multipart/form-data">
      <tr>
        <td>
          <a target="_blank" href="/img/userfiles/maps/<?if(file_exists('img/userfiles/maps/'.$mp->idgm.'.png')){?><?=$mp->idgm;?>.png<?}else{?>default.jpg<?}?>"><img width="30" src="/img/userfiles/maps/<?if(file_exists('img/userfiles/maps/'.$mp->idgm.'.png')){?><?=$mp->idgm;?>.png<?}else{?>default.jpg<?}?>" /></a>
          Název: <input class="width-200" type="text" name="nazev" value="<?=$mp->nazev?>" />
          Změnit obrázek: <input type="file" value="" name="soubor" style="max-width:200px;width:200px;" />
        </td>
        <td>Aktivní: <input type="checkbox" name="aktivni" value="1" <?if($mp->aktivni==1){?>checked<?}?> /></td>
        <td width="3%"><?/*<a title="Smazat mapu" href="<?=$mp->adel? >" onclick="return confirm('Opravdu si přejete smazat tuto mapu?');"><i class="fa fa-trash-o"></i></a>*/?></td>
        <td style="text-align:right"><input type="submit" value="Uložit" /></td>
        </tr>
    </form>  
  <?}?>
</table>
<br>
<table>
  <tr><th>Výplaty - název:</th><?for($i=1;$i<11;$i++){?><th><?=$i?>.&nbsp;m.:</th><?}?><th>Clk.</th><th colspan="2">Aktivní:</th></tr>  
  <?foreach($winners as $wn){$xsum=0;for($i=1;$i<11;$i++){$xx='misto_'.$i;$xsum+=$wn->$xx;}?>    
    <form method="post" action="<?=$wn->aeditwin?>">
      <tr style="background-color:<?if($xsum>90){?>#f99595;<?}elseif($xsum==90){?>#c6ffa0;<?}else{?>#f9f99a;<?}?>" >
        <td><input class="width-110" type="text" name="nazev" value="<?=$wn->nazev?>" /></td>
        <?for($i=1;$i<11;$i++){?>
          <td><?if($i<=$wn->winners_count){$xx='misto_'.$i;?><input type="text" size="2" class="width-40" name="<?=$xx?>" value="<?=$wn->$xx?>"><?}else{?>-<?}?></td>
        <?}?>
        <td><b><?=$xsum?>%</b></td>
        <td><input type="checkbox" name="aktivni" value="1" <?if($wn->aktivni==1){?>checked<?}?> /></td>       
        <td style="text-align:right"><input type="submit" value="Uložit" /></td>
        </tr>
    </form>  
  <?}?>
  <tr><th colspan="14">Celková suma procen pro všechna vyplněná místa (1.m. až 10.m.) nesmí překročit 90%.<br>Pokud tato situace nastane, řádek se podbarví červeně a nebude se zobrazovat při zakládání turnajů!<br>Pokud je řádek vysvícen zeleně, je korektně rozděleno 90% celkových financí z turnaje.<br>Při žlutém podsvícení není rozděleno celých 90%  financí z turnaje.</th></tr>
</table>