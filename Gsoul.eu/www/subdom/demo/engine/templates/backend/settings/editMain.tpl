<h1>Editace základní proměnné #<?=(int)getget('ids','0');?> </h1>
<?if($message=='saved'){?><p><b>Změny úspěšně uloženy.</b></p><?}?>
<div class="BackendBack">
  <br />
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis základního nastavení</a><br>
</div>
<form method="post" action="<?=$asave?>">
  <table>
    <tr><th><?=$setting->nazev?> {<?=$setting->klic?>}</th></tr>
    <tr> <td><?=$setting->popis?></td></tr>
    <?if($setting->typ=='int'){?>
      <tr><th>Zadejte celé číslo</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='float'){?>
      <tr><th>Zadejte desetinné číslo (můžete použít desetinnou čárku i desetinnou tečku)</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='ano_ne'){?>
      <tr><th>Zvolte hodnotu ano / ne</th></tr>
      <tr><td>
        <select name="hodnota">
          <option value="1">Ano</option>
          <option <?if($setting->hodnota=='0'){?>selected<?}?> value="0">Ne</option>
        </select>                                
      </td></tr>
    <?}?>
    <?if($setting->typ=='text'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='textarea'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><textarea class="width-740" cols="50" rows="5" name="hodnota"><?=$setting->hodnota;?></textarea></td></tr>
    <?}?>
    <?if($setting->typ=='editor'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><?=$editor;?></td></tr>
    <?}?>    
    <tr><td align="right"><input type="submit" value="uložit" /></td></tr>      
  </table>
</form>