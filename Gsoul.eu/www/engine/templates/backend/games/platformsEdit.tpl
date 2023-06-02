<h1>Hraní - Editace platformy</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis platforem</a><br>     
</div>
<br>
<?if(getget('message','')=='saved'){?><h2>Platforma úspěšně uložena.</h2><br><?}?>
<?if(getget('message','')=='not-saved'){?><h2>Platformu se nepodařilo uložit - musíte zadat název.</h2><br><?}?>

<form method="post" action="<?=$aedit?>" enctype="multipart/form-data">
  <table>
    <tr><th>Název platformy (je povinný):</th><th colspan="3">Zobrazovat:</th></tr>
    <tr><td><input class="width-200" type="text" name="nazev" value="<?=$data->nazev?>" /></td><td></td><td> Aktivní: <input type="checkbox" name="aktivni" value="1" <?if($data->aktivni==1){?>checked<?}?> /></td></tr>
    <tr>
      <td colspan="3">
        Obrázek:
        <a target="_blank" href="/img/userfiles/platforms/<?if(file_exists('img/userfiles/platforms/'.$data->idgp.'.png')){?><?=$data->idgp;?>.png<?}else{?>default.jpg<?}?>"><img width="30" src="/img/userfiles/platforms/<?if(file_exists('img/userfiles/platforms/'.$data->idgp.'.png')){?><?=$data->idgp;?>.png<?}else{?>default.jpg<?}?>" /></a>
        Změnit obrázek: <input type="file" value="" name="soubor" style="max-width:200px;width:200px;" />
      </td>
    </tr>  
    <tr><td colspan="3">Přiřazené hry k této platformě:</td></tr>
    <tr>
      <td colspan="3">
        <?foreach($dataGames as $dg){?>
          <label style="float:left;margin-right:10px;">
            <input type="checkbox" name="hry[]" <?if(in_array($dg->idg,$dataGamesPlatforms)){?>checked<?}?> value="<?=$dg->idg;?>" />
            <?=$dg->nazev;?>
          </label>
        <?}?>
      </td>
    </tr>
    <tr><td colspan="4" style="text-align:right"><input type="submit" value="Uložit" /></td></tr>   
  </table>
</form>
