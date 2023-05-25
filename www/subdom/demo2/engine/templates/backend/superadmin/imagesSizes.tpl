<h1>Super admin - Velikosti obrázků</h1>
<?if(getget('message','')=='created'){?><h2>Velikost byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Velikost úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='size-not-found'){?><h2>Tato velikost neexistuje, proto nelze editovat / smazat.</h2><?}?>
<?if(count($tree)>0){?>
  <table>
    <tr>
      <th width="25%">Maximální šířka obrázku</th><th colspan="2">Maximální výška obrázku</th>
    </tr>
    <?foreach($tree as $d){?>
      <tr> 
        <td><?=$d->x.' px ';?></td>
        <td><?=$d->y.' px ';?></td>
        <td align="right">
          <a href="<?=$d->adel?>" title="Smazat velikost" onclick="return confirm('Opravdu si přejete smazat velikost <?=$d->x;?>x<?=$d->y;?>?');"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>
    <?}?>
  </table>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné velikosti obrázků. Napříč celým systémem tedy nejdou nahrávat obrázky!</strong>
  </p>
<?}?>
<h2>Vytvoření nové velikosti obrázků</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="30%">Maximální šířka obrázku </th>
      <td><input type="text" size="35" class="width-200" maxlength="4" name="x" value="" /> px</td>
      <td width="10%"> &nbsp; </td>    
    </tr> 
    <tr>
      <th width="30%">Maximální výška obrázku </th>
      <td><input type="text" size="35" class="width-200" maxlength="4" name="y" value="" /> px</td>
      <td width="10%"> &nbsp; </td>    
    </tr>          
    <tr>
      <td colspan="2">Minimální velikost je pochopitelně 1 x 1 px.</td>
      <td align="right"><input type="submit" value="Vytvořit velikost" /></td>    
    </tr>           
  </table>
</form>