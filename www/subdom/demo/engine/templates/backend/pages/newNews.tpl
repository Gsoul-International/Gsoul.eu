<h1>Přidání novinky</h1>
<div class="BackendBack">
  &nbsp;<br>
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis novinek</a><br>  
</div>
<?if(getget('message','')=='name-required'){?><h2>Název novinky je povinný!</h2><?}?>
<?if(getget('message','')=='page-created'){?><h2>Novinka vytvořena. <a href="<?=$anewpage;?>">Editovat tuto novinku <i class="fa fa-arrow-right"></i></a></h2><?}?>
<form action="<?=$anew;?>" method="post" autocomplete="off">
  <table>
    <tr>
      <th>Název</th>
      <td colspan="3"><input type="text" class="width-400" size="35" name="nazev" maxlength="127" value="<?=$page->nazev?>" /></td>      
      <th>Zobrazovat</th>
      <td>
        <select name="zobrazovat" class="width-72">          
          <option <?if($page->zobrazovat==1){?>selected<?}?> value="1">Ano</option>          
          <option <?if($page->zobrazovat==0){?>selected<?}?> value="0">Ne</option>          
        </select>   
      </td>   
    </tr>
    <tr>
      <th>Datum novinky<br>(DD.MM.YYYY)</th>
      <td>
        <input type="text" class="width-200" size="35" name="datum" maxlength="15" value="<?=$page->datum?>" /> 
      </td>
      <th>Zobrazovat datum</th>
      <td>
        <select name="zobrazovat_datum" class="width-110">          
          <option <?if($page->zobrazovat_datum==1){?>selected<?}?> value="1">Ano</option>          
          <option <?if($page->zobrazovat_datum==0){?>selected<?}?> value="0">Ne</option>          
        </select>   
      </td>      
      <th>Priorita novinky</th>
      <td>
        <select name="sitemap_priorita" class="width-72">          
          <option <?if($page->sitemap_priorita==0.9){?>selected<?}?> value="0.9">Nejdůležitější</option>          
          <option <?if($page->sitemap_priorita==0.8){?>selected<?}?> value="0.8">Důležitá</option>          
          <option <?if($page->sitemap_priorita==0.7){?>selected<?}?> value="0.7">Běžná</option>          
          <option <?if($page->sitemap_priorita==0.6){?>selected<?}?> value="0.6">Méně důležitá</option>          
          <option <?if($page->sitemap_priorita==0.5){?>selected<?}?> value="0.5">Nedůležitá</option>          
        </select>   
      </td>                       
    </tr>
    <tr>
      <th>SEO description</th>
      <td colspan="5"><input type="text" class="width-600" size="35" name="seo_description" maxlength="255" value="<?=$page->seo_description?>" /></td>
    </tr>
    <tr>
      <th>SEO keywords</th>
      <td colspan="5"><input type="text" class="width-600" size="35" name="seo_keywords" maxlength="255" value="<?=$page->seo_keywords?>" /></td>
    </tr>
    <tr>
      <th>Titulek</th>
      <td colspan="5"><input type="text" class="width-600" size="35" name="seo_title" maxlength="127" value="<?=$page->seo_title?>" /></td>      
    </tr>
    <tr>
      <th>Zobrazovat v&nbsp;navigaci</th>
      <td>
        <select name="zobrazovat_v_navigaci" class="width-200">
          <option <?if($page->zobrazovat_v_navigaci==1){?>selected<?}?> value="1">Ano</option>
          <option <?if($page->zobrazovat_v_navigaci==0){?>selected<?}?> value="0">Ne</option>
        </select>
      </td>
      <th>Zobrazovat navigaci</th>
      <td>
        <select name="zobrazovat_navigaci" class="width-110">
          <option <?if($page->zobrazovat_navigaci==1){?>selected<?}?> value="1">Ano</option>
          <option <?if($page->zobrazovat_navigaci==0){?>selected<?}?> value="0">Ne</option>
        </select>
      </td>
      <th>Zobrazovat nadpis</th>
      <td>
        <select name="zobrazovat_nadpis" class="width-72">
          <option <?if($page->zobrazovat_nadpis==1){?>selected<?}?> value="1">Ano</option>
          <option <?if($page->zobrazovat_nadpis==0){?>selected<?}?> value="0">Ne</option>
        </select>
      </td>
    </tr>
  </table>
  <br />
  <div class="BackendHelp">
    <a onclick="$('#BackendImage').slideToggle('slow');">
      <div class="BackendHelpHeader"><i class="fa fa-picture-o"></i> Zobrazit / skrýt přiřazený obrázek novinky</div>
    </a>
    <div id="BackendImage" class="BackendHelpBody">
      <label>
        <input type="radio" name="id_obrazku" value="0" <?if(0==$page->id_obrazku){?>checked<?}?> />  
        Bez obrázku         
      </label>
      <?foreach($images as $i){?>
        <label>  
          <input type="radio" name="id_obrazku" value="<?=$i->idi?>" <?if($i->idi==$page->id_obrazku){?>checked<?}?> />  
          <?=$filescat[$i->id_ic]?> - <?=$i->nazev?><br />                    
          <?if($i->je_youtube==1){
            parse_str(parse_url($i->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);			       
            ?>           
            <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/default.jpg" alt="<?=$i->nazev;?>" style="max-width:50px;max-height:50px;" />                      
          <?}else{?>            
            <img src="/<?=str_replace('xxxx','50x50',$i->cesta);?>" alt="<?=$i->nazev;?>" />            
          <?}?>                                          
        </label>
      <?}?>
    </div>
  </div>
  <br />
  <table>    
    <tr>
      <th colspan="2">Úvodní text novinky</th>
    </tr>
    <tr>      
      <td colspan="2"><textarea class="width-740" size="35" rows="3" name="predtext" ><?=$page->predtext?></textarea></td>              
    </tr> 
    <tr>
      <th colspan="2">Text novinky</th>
    </tr>
    <tr>
      <td colspan="2"><?=$obsah;?></td>      
    </tr>
    <tr>
      <td>Název novinky je povinný.</td>
      <td align="right"><input type="submit" value="Přidat novinku" /></td>
    </tr>
  </table>
</form>
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendVars').slideToggle('slow');">
    <div class="BackendHelpHeader"><i class="fa fa-cube"></i> Zobrazit / skrýt zástupné proměnné prvků</div>
  </a>
  <div id="BackendVars" class="BackendHelpBody">
    <?if(count($boxes)>0){?>
      <table>
        <tr>
          <th>Zástupná proměnná</th>
          <th>Název</th>          
          <th>Umístnění prvku</th>
          <th>Typ prvku</th>
          <th>Zobrazovat</th>                    
        </tr>
        <?foreach($boxes as $b){?>
          <tr>
            <td><?=$b->_promenna;?></td>
            <td><?=$b->nazev;?></td>           
            <td><?=$b->_kategorie;?></td>
            <td><?=$b->_modul;?></td>    
            <td><?=($b->zobrazovat==1?'Ano':'Ne');?></td>                            
          </tr>        
        <?}?>
      </table>
      <br />
      <p>
        V textu novinky můžete používat tyto zástupné proměnné, které jsou vytvořeny na základě Prvků. Místo zástupných proměnných se pak zobrazí obsah prvků. Můžete tedy díky tomu efektivně vytvářet třeba fotogalerie, které použijete na více místech webu a podobně.  
      </p>
    <?}else{?>
      <strong>Zatím nejsou definovány žádné prvky. Definovat je můžete v Prvcích (položka vedle stránek v horním menu administrace).</strong>
    <?}?> 
  </div>
</div>
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader"><i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu</div>
  </a>
  <div id="BackendHelp" class="BackendHelpBody">
    Název novinky je povinnou součástí formuláře.<br /><br />
    Priorita novinky je kvůli SEO optimalizaci. Díky tomuto parametru si uživatel lehce může nejdůležitější novinky optimalizovat pro vyhledávače.<br /><br />      
    Zobrazovat v navigaci vyberte, pokud chcete zobrazovat novinku v drobečkové navigaci. Zároveň musí mít novinka nastaven atribut zobrazovat na hodnotu ano. 
    Zobrazovat navigaci vyberte, pokud chcete nad nadpisem vidět cestu k dané stránce (archiv novinek).
  </div>
</div>
