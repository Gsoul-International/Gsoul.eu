<h1>Obrázky - <?=$imagescat->nazev?></h1>
<div class="BackendBack">
  <br>
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis kategorií</a>
</div>
<?if(getget('message','')=='category-saved'){?><h2>Kategorie obrázků byla úspěšně uložena.</h2><?}?>
<?if(getget('message','')=='category-not-saved'){?><h2>Kategorie obrázků nebyla uložena. Její název musí být vyplněn.</h2><?}?>
<?if(getget('message','')=='image-not-found'){?><h2>Obrázek neexistuje.</h2><?}?>
<?if(getget('message','')=='image-created'){?><h2>Obrázek byl úspěšně nahrán.</h2><?}?>
<?if(getget('message','')=='image-saved'){?><h2>Název, popis a URL adresa obrázku byly úspěšně změněny.</h2><?}?>
<?if(getget('message','')=='image-deleted'){?><h2>Obrázek byl úspěšně smazán.</h2><?}?>
<?if(getget('message','')=='image-not-created'){?><h2>Obrázek se nepodařilo nahrát, zkontrolujte příponu obrázku a velikost obrázku.</h2><?}?>
<table>
  <tr>
    <th width="15%">Obrázek</th>    
    <th width="50%">Název obrázku, popis obrázku</th>
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
  <?if(count($images)>0){?>
    <?foreach($images as $f){?>  
      <tr>
        <td>
          <?if($f->je_youtube==1){
            parse_str(parse_url($f->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);			       
            ?>
            <a href="<?=$f->youtube_adresa;?>" target="_blank" title="<?=$f->popis;?>">
              <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/default.jpg" alt="<?=$f->nazev;?>" style="max-width:50px;max-height:50px;" />
            </a>          
          <?}else{?>
            <a href="/<?=$f->sizes['maxi'];?>" target="_blank" title="<?=$f->popis;?>">
              <img src="/<?=$f->sizes['mini'];?>" alt="<?=$f->nazev;?>" />
            </a>
          <?}?>
        </td>
        <td>
          <form method="post" action="<?=$f->asave?>">
            <input class="width-140" type="text" name="nazev" value="<?=$f->nazev?>" />
            <input class="width-140" type="text" name="popis" value="<?=$f->popis?>" />        
            <button type="submit" class="no-efect-button"><i class="fa fa-save" style="font-size:16px;"></i> uložit</button>            
          </form>
        </td>        
        <td><?=strftime('%d.%m.%Y %H:%M',$f->vytvoreni_timestamp);?></td>
        <td align="right">
          <a onclick="return confirm('Opravdu si přejete smazat tento obrázek?');" href="<?=$f->adel;?>" title="Smazat obrázek"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>  
    <?}?>
  
  <?}else{?>
    <tr><td colspan="4">V této kategorii nejsou zatím nahrány žádné obrázky.</td></tr>
  <?}?>
</table>
<br>
<h2>Nahrát nový obrázek</h2>
<form method="post" action="<?=$anew?>" enctype="multipart/form-data">
  <table>
    <tr>
      <th width="30%">Název obrázku na webu</th>
      <td>
        <input class="width-350" type="text" value="" name="nazev" size="35">
      </td>
      <td width="10%"> </td>
    </tr>
    <tr>
      <th width="30%">Popis obrázku na webu</th>
      <td>
        <input class="width-350" type="text" value="" name="popis" size="35">
      </td>
      <td width="10%"> </td>
    </tr>
    <tr>
      <th width="30%">Obrázek</th>
      <td>
        <input type="file" value="" name="soubor" maxlength="127" size="35">
      </td>
      <td align="right">
        <input type="submit" value="Nahrát">
      </td>
    </tr>       
    <tr>
      <td colspan="3">
        Maximální velikost obrázku je <?=ini_get('upload_max_filesize');?>B. 
        Povolené přípony souborů: <?=str_replace(',',', ',$settings['povolene_pripony_obrazku']);?>. <br />      
      </td>      
    </tr>
  </table>
</form>
<br>
<h2>Nahrát YouTube video</h2>
<form method="post" action="<?=$anew2?>" enctype="multipart/form-data">
  <table>
    <tr>
      <th width="30%">Název videa na webu</th>
      <td>
        <input class="width-350" type="text" value="" name="nazev" size="35">
      </td>
      <td width="10%"> </td>
    </tr>
    <tr>
      <th width="30%">Popis videa na webu</th>
      <td>
        <input class="width-350" type="text" value="" name="popis" size="35">
      </td>
      <td width="10%"> </td>
    </tr>    
    <tr>
      <th width="30%">URL adresa YouTube videa</th>
      <td>
        <input class="width-350" type="text" value="https://www.youtube.com/watch?v=zPonioDYnoY" name="youtube_adresa" maxlength="127" size="35">
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
        <input class="width-350" type="text" value="<?=$imagescat->nazev?>" name="nazev" maxlength="127" size="35">
      </td>
      <td align="right">
        <input type="submit" value="Uložit">
      </td>
    </tr>      
  </table>
</form>