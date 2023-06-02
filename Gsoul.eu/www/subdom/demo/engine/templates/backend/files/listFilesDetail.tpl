<h1>Soubory - <?=$filescat->nazev?></h1>
<div class="BackendBack">
  <br>
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis kategorií</a>
</div>
<?if(getget('message','')=='category-saved'){?><h2>Kategorie souborů byla úspěšně uložena.</h2><?}?>
<?if(getget('message','')=='category-not-saved'){?><h2>Kategorie souborů nebyla uložena. Její název musí být vyplněn.</h2><?}?>
<?if(getget('message','')=='file-not-found'){?><h2>Soubor neexistuje.</h2><?}?>
<?if(getget('message','')=='file-created'){?><h2>Soubor byl úspěšně nahrán.</h2><?}?>
<?if(getget('message','')=='file-saved'){?><h2>Název souboru byl úspěšně změněn.</h2><?}?>
<?if(getget('message','')=='file-not-saved'){?><h2>Název souboru nesmí být prázdný pro uložení.</h2><?}?>
<?if(getget('message','')=='file-deleted'){?><h2>Soubor byl úspěšně smazán.</h2><?}?>
<?if(getget('message','')=='file-not-created'){?><h2>Soubor se nepodařilo nahrát, zkontrolujte příponu souboru a velikost souboru. Název je povinný.</h2><?}?>
<table>
  <tr>
    <th width="33%">Název souboru</th>
    <th width="33%">Cesta k souboru</th>
    <th width="20%">Datum a čas nahrání</th>
    <th class="right">
      <a <?if($getOrder=='name'){?>class="activeLink"<?}?> href="<?=$aorders['name'];?>" title="Řadit dle názvu">
        <i class="fa fa-sort-alpha-asc"></i>
      </a>
      <a <?if($getOrder=='namedesc'){?>class="activeLink"<?}?> href="<?=$aorders['namedesc'];?>" title="Řadit dle názvu">
        <i class="fa fa-sort-alpha-desc"></i>
      </a>
      <a <?if($getOrder=='time'){?>class="activeLink"<?}?> href="<?=$aorders['time'];?>" title="Řadit dle času nahrání">
        <i class="fa fa-sort-amount-asc"></i>
      </a>
      <a <?if($getOrder=='timedesc'){?>class="activeLink"<?}?> href="<?=$aorders['timedesc'];?>" title="Řadit dle času nahrání">
        <i class="fa fa-sort-amount-desc"></i>
      </a>    
    </th>
  </tr>
  <?if(count($files)>0){?>
    <?foreach($files as $f){?>  
      <tr>
        <td>
          <form method="post" action="<?=$f->asave?>">
            <input type="text" name="nazev" value="<?=$f->nazev?>" maxlength="127" class="width-140" />
            <button type="submit" class="no-efect-button"><i class="fa fa-save" style="font-size:16px;"></i> uložit</button>            
          </form>
        </td>
        <td>
          <a target="_blank" href="/<?=$f->cesta?>" title="Stáhnout soubor">/<?=$f->cesta?></a>
        </td>
        <td><?=strftime('%d.%m.%Y %H:%M',$f->vytvoreni_timestamp);?></td>
        <td align="right">
          <a onclick="return confirm('Opravdu si přejete smazat tento soubor?');" href="<?=$f->adel;?>" title="Smazat soubor"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>  
    <?}?>
  
  <?}else{?>
    <tr><td colspan="4">V této kategorii nejsou zatím nahrány žádné soubory.</td></tr>
  <?}?>
</table>
<br>
<h2>Nahrát nový soubor</h2>
<form method="post" action="<?=$anew?>" enctype="multipart/form-data">
  <table>
    <tr>
      <th width="30%">Název souboru na webu</th>
      <td>
        <input class="width-350" type="text" value="" name="nazev" maxlength="127" size="35">
      </td>
      <td width="10%"> </td>
    </tr>
    <tr>
      <th width="30%">Soubor</th>
      <td>
        <input type="file" value="" name="soubor" maxlength="127" size="35">
      </td>
      <td width="10%"> </td>
    </tr>
    <tr>
      <td colspan="2">
        Název je povinný, maximální velikost souboru je <?=ini_get('upload_max_filesize');?>B.
        Povolené přípony souborů: <br /><?=str_replace(',',', ',$settings['povolene_pripony_souboru']);?>   
      </td>
      <td align="right">
        <input type="submit" value="Nahrát">
      </td>
    </tr>
  </table>
</form>
<br>
<h2>Změnit název kategorie</h2>
<form method="post" action="<?=$asavecat?>">
  <table>
    <tr>
      <th width="30%">Název kategorie</th>
      <td>
        <input class="width-350" type="text" value="<?=$filescat->nazev;?>" name="nazev" maxlength="127" size="35">
      </td>
      <td width="10%"> </td>
    </tr>  
    <tr>
      <td colspan="2">
        Název kategorie je povinný.
      </td>
      <td align="right">
        <input type="submit" value="Uložit">
      </td>
    </tr>
  </table>
</form>