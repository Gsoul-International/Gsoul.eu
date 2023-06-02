<h1>Super admin - Šablony textů pro editor</h1>
<?if(getget('message','')=='created'){?><h2>Šablona byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='edited'){?><h2>Šablona byla úspěšně uložena.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Šablona úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='template-not-found'){?><h2>Tato šablona neexistuje, proto nelze editovat / smazat.</h2><?}?>
<?if(count($templates)>0){?>  
  <?foreach($templates as $d){?>
    <form action="<?=$d->aedit;?>" method="post">
      <table>
        <tr>
          <th>Název šablony </th>
        </tr>
        <tr>
          <td><input type="text" size="35" class="width-740" maxlength="127" name="nazev" value="<?=$d->nazev;?>" /></td>
        </tr> 
        <tr>
          <th width="30%">Obsah šablony</th>
        </tr>
        <tr>
          <td><?=$d->editor;?></td>      
        </tr>          
        <tr>     
          <td align="right">
            <a onclick="return confirm('Opravdu si přejete smazat tuto šablonu?');" title="Smazat šablonu" href="<?=$d->adel;?>"><i class="fa fa-trash-o"></i></a>
            <input type="submit" value="Uložit šablonu" />
          </td>    
        </tr>           
      </table>
    </form>
    <br />
  <?}?> 
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné šablony.</strong>
  </p>
<?}?>
<h2>Vytvoření nové šablony</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th>Název šablony </th>
    </tr>
    <tr>
      <td><input type="text" size="35" class="width-740" maxlength="127" name="nazev" value="" /></td>
    </tr> 
    <tr>
      <th width="30%">Obsah šablony</th>
    </tr>
    <tr>
      <td><?=$aneweditor;?></td>      
    </tr>          
    <tr>     
      <td align="right"><input type="submit" value="Vytvořit šablonu" /></td>    
    </tr>           
  </table>
</form>