<h1>Editace prvku - <?=$boxesCategory->nazev;?> - <?=(trim($box->nazev)==''?'bez názvu':$box->nazev);?></h1>
<div class="BackendBack">
  <br>  
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis prvků</a><br>    
</div>
<?if(getget('message','')=='box-edited'){?><h2>Prvek uložen.</h2><?}?>
<form action="<?=$asave;?>" method="post">
  <table>
    <tr>
      <th width="40%">Název prvku</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="<?=$box->nazev;?>" /></td>
    </tr>               
    <tr>
      <th>Typ prvku</th>
      <td>
        <?=$modules[$box->modul];?>
      </td>    
    </tr> 
    <tr>
      <th>Zobrazovat na webu</th>
      <td><input type="checkbox" <?if(1==$box->zobrazovat){?>checked<?}?> name="zobrazovat" value="1" /></td>    
    </tr> 
    <tr>
      <th>Zobrazovat nadpis na webu</th>
      <td><input type="checkbox" <?if(1==$box->zobrazovat_nadpis){?>checked<?}?> name="zobrazovat_nadpis" value="1" /></td>    
    </tr> 
    <tr>
      <th>Nadpis na webu</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nadpis" value="<?=$box->nadpis;?>" /></td>    
    </tr>               
    <tr>
      <td colspan="2">Název doporučujeme kvůli orientaci v administraci vyplnit, i když je nepovinný.</td>
	</tr>
	<tr>
      <td colspan="2"><input type="submit" value="Uložit prvek" /></td>    
    </tr>           
  </table>
  <?=$tplSub;?>
</form>