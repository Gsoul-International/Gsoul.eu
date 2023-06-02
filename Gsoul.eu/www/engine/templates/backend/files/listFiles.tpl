<h1>Soubory - kategorie souborů</h1>
<?if(getget('message','')=='created'){?><h2>Kategorie souborů byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='not-created'){?><h2>Kategorie souborů nebyla vytvořena. Musíte vyplnit její název.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Kategorie souborů úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='category-not-found'){?><h2>Tato kategorie souborů neexistuje, proto nelze editovat.</h2><?}?>
<?if(count($filescat)>0){?>
  <table>
    <tr>
      <th width="70%">Název kategorie souborů</th><th colspan="2">Počet souborů</th>
    </tr>
    <?foreach($filescat as $d){?>
      <tr> 
        <td><?=$d->nazev;?></td>
        <td><?=$d->filesCount;?></td>
        <td align="right">
          <a href="<?=$d->adel?>" title="Smazat kategorii" onclick="return confirm('Opravdu si přejete smazat tuto kategorii? Smazáním dojde ke smazání všech souborů uvnitř kategorie. Tyto soubory pak nepůjdou uživatelům stáhnout.');"><i class="fa fa-trash-o"></i></a>
          &nbsp;
          <a href="<?=$d->aedit?>" title="Detail kategorie / přístup k souborům"><i class="fa fa-file-word-o"></i></a>
        </td>
      </tr>
    <?}?>
  </table>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné kategorie souborů.</strong><br /><br /> 
    <strong>Nahrávat soubory půjde až po založení kategorie.</strong>
  </p>
<?}?>
<h2>Vytvoření nové kategorie souborů</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="30%">Název kategorie souborů </th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>       
    <tr>
      <td colspan="2">Název je povinný.</td>
      <td align="right"><input type="submit" value="Vytvořit" /></td>    
    </tr>           
  </table>
</form>
<br /><br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu
    </div>
  </a>
  <div id="BackendHelp" class="BackendHelpBody">
    Soubory se nahrávají do Vámi vytvořených kategorií. Jednak tento způsob slouží k lepší přehlednosti, druherak můžete nakládat s celou kategorií souborů jako s jedním celkem, který můžete například hromadně přiřazovat do prvků, článků a podobně. Také pokud si později přejete smazat nebo zazálohovat celou kategorii, je to daleko jednodušší.    
    <br /><br />
    Pozor při smazání celé kategorie. Smazáním dojde ke smazání všech souborů uvnitř kategorie. Tyto soubory pak nepůjdou uživatelům stáhnout na webu, pokud je neodstraníte na místech, kde jste je použili. 
    <br /><br />
  </div>
</div>