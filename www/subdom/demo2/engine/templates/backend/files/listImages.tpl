<h1>Obrázky a videa - kategorie obrázků a videí</h1>
<?if(getget('message','')=='created'){?><h2>Kategorie obrázků byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='not-created'){?><h2>Kategorie obrázků nebyla vytvořena. Musíte vyplnit její název.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Kategorie obrázků úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='category-not-found'){?><h2>Tato kategorie obrázků neexistuje, proto nelze editovat.</h2><?}?>
<?if(count($filescat)>0){?>
  <table>
    <tr>
      <th width="70%">Název kategorie</th><th colspan="2">Počet obrázků a videí</th>
    </tr>
    <?foreach($filescat as $d){?>
      <tr> 
        <td><?=$d->nazev;?></td>
        <td><?=$d->filesCount;?></td>
        <td align="right">
          <a href="<?=$d->adel?>" title="Smazat kategorii" onclick="return confirm('Opravdu si přejete smazat tuto kategorii? Smazáním dojde ke smazání všech obrázků uvnitř kategorie. Tyto obrázky pak nepůjdou uživatelům zobrazit ani stáhnout.');"><i class="fa fa-trash-o"></i></a>
          &nbsp;
          <a href="<?=$d->aedit?>" title="Detail kategorie / přístup k obrázkům"><i class="fa fa-file-image-o"></i></a>
        </td>
      </tr>
    <?}?>
  </table>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné kategorie.</strong><br /><br /> 
    <strong>Nahrávat obrázky a videa půjde až po založení kategorie.</strong>
  </p>
<?}?>
<h2>Vytvoření nové kategorie</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="30%">Název kategorie</th>
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
    Obrázky a videa se nahrávají do Vámi vytvořených kategorií. Jednak tento způsob slouží k lepší přehlednosti, druherak můžete nakládat s celou galerií obrázků a videí jako s jedním celkem, který můžete například hromadně přiřazovat do prvků, článků, fotogalerií a podobně. Také pokud si později přejete smazat nebo zazálohovat celou galerii, je to daleko jednodušší.    
    <br /><br />
    Pozor při smazání celé kategorie! Smazáním dojde ke smazání všech obrázků a videí uvnitř kategorie. Tyto obrázky / videa pak nepůjdou uživatelům zobrazit (stáhnout) na webu, pokud je neodstraníte na místech, kde jste je použili. 
    <br /><br />
  </div>
</div>