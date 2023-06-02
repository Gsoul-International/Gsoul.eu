<h1>Editace kategorie prvků #<?=(int)getget('idbc','0');?> </h1>
<?if($message=='saved'){?><p><b>Změny úspěšně uloženy.</b></p><?}?>
<div class="BackendBack">
  <br />
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis kategorií prvků</a><br>
</div>
<form method="post" action="<?=$asave?>">
  <table>
    <tr>
      <th width="35%">Název kategorie</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="<?=$setting->nazev?>" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr> 
    <tr>
      <th width="35%">Interní název kategorie</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="interni_nazev" value="<?=$setting->interni_nazev?>" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>   
    <tr>
      <th width="35%">Popis kategorie pro&nbsp;administrátora</th>
      <td><input type="text" size="35" class="width-350" name="popis" value="<?=$setting->popis?>" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>
    <tr>
      <th width="35%">Zobrazovat kategorii v&nbsp;administraci</th>
      <td colspan="2">
        <select name="zobrazovat_admin" class="width-350">        
          <option value="1">Ano</option>          
          <option value="0" <?if($setting->zobrazovat_admin==0){?>selected<?}?> >Ne</option>         
        </select>
      </td>       
    </tr>                      
    <tr>
      <td colspan="2">Název doporučujeme kvůli orientaci v administraci vyplnit, i když je nepovinný.</td>
      <td align="right"><input type="submit" value="Uložit" /></td>    
    </tr>           
  </table>
</form>