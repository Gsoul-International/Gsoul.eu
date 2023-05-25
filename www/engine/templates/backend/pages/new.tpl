<h1>Přidání stránky</h1>
<div class="BackendBack">
  &nbsp;<br>
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis stránek</a><br>  
</div>
<?if(getget('message','')=='name-required'){?><h2>Název stránky je povinný!</h2><?}?>
<?if(getget('message','')=='page-created'){?><h2>Stránka vytvořena. <a href="<?=$anewpage;?>">Editovat tuto stránku <i class="fa fa-arrow-right"></i></a></h2><?}?>
<form action="<?=$anew;?>" method="post" autocomplete="off">
  <table>
    <tr>
      <th>Název</th>
      <td colspan="3"><input type="text" class="width-400" size="35" name="nazev" maxlength="127" value="<?=$page->nazev?>" /></td>   
	</tr>
	<tr>
      <th>Zobrazovat</th>
      <td>
        <select name="zobrazovat" class="width-72">          
          <option <?if($page->zobrazovat==1){?>selected<?}?> value="1">Ano</option>          
          <option <?if($page->zobrazovat==0){?>selected<?}?> value="0">Ne</option>          
        </select>   
      </td> 
      <th>Otevírat stránku</th>
      <td>
        <select name="nove_okno" class="width-200">          
          <option <?if($page->nove_okno==2){?>selected<?}?> value="2">v novém okně prohlížeče</option>          
          <option <?if($page->nove_okno==1){?>selected<?}?> value="1">v nové záložce prohlížeče</option>          
          <option <?if($page->nove_okno==0){?>selected<?}?> value="0">ve stejné záložce prohlížeče</option>          
        </select>   
      </td>  
    </tr>
    <tr>   
      <th>Priorita stránky</th>
      <td>
        <select name="sitemap_priorita" class="width-110">          
          <option <?if($page->sitemap_priorita==0.9){?>selected<?}?> value="0.9">Nejdůležitější</option>          
          <option <?if($page->sitemap_priorita==0.8){?>selected<?}?> value="0.8">Důležitá</option>          
          <option <?if($page->sitemap_priorita==0.7){?>selected<?}?> value="0.7">Běžná</option>          
          <option <?if($page->sitemap_priorita==0.6){?>selected<?}?> value="0.6">Méně důležitá</option>          
          <option <?if($page->sitemap_priorita==0.5){?>selected<?}?> value="0.5">Nedůležitá</option>          
        </select>   
      </td>    
      <th>Typ stránky</th>
      <td>
        <select name="typ" class="width-72">          
          <option <?if($page->typ==1){?>selected<?}?> value="1">Odkaz</option>          
          <option <?if($page->typ==0){?>selected<?}?> value="0">Text</option>          
        </select>   
      </td>                 
    </tr>
    <tr>
      <th>SEO description</th>
      <td colspan="3"><input type="text" class="width-600" size="35" name="seo_description" maxlength="255" value="<?=$page->seo_description?>" /></td>
    </tr>
    <tr>
      <th>SEO keywords</th>
      <td colspan="3"><input type="text" class="width-600" size="35" name="seo_keywords" maxlength="255" value="<?=$page->seo_keywords?>" /></td>
    </tr>
    <tr>
      <th>Titulek</th>
      <td colspan="3"><input type="text" class="width-600" size="35" name="seo_title" maxlength="127" value="<?=$page->seo_title?>" /></td>      
    </tr>
    <tr>
      <th>Jazyk</th>
      <td>        
        <select class="width-600" name="id_jazyka">
          <?foreach($languages as $lngk=>$lngv){?><option value="<?=$lngk;?>" <?if($lngk==$page->id_jazyka){?>selected<?}?> ><?=$lngv->nazev?></option><?}?>
        </select>
      </td> 
      <th>Zobrazovat v&nbsp;navigaci</th>
      <td>
        <select name="zobrazovat_v_navigaci" class="width-200">
          <option <?if($page->zobrazovat_v_navigaci==1){?>selected<?}?> value="1">Ano</option>
          <option <?if($page->zobrazovat_v_navigaci==0){?>selected<?}?> value="0">Ne</option>
        </select>
      </td>     
    </tr>
    <tr>
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
      <div class="BackendHelpHeader"><i class="fa fa-picture-o"></i> Zobrazit / skrýt přiřazený obrázek stránky (slouží, pokud je stránka použita jako podmenu)</div>
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
      <th colspan="2">Nadřazená stránka</th>
    </tr>
    <tr>
      <td colspan="2">
        <select name="parent_id" class="width-740">
          <?=$parentsTree;?>        
        </select>
     </td>      
    </tr>  
    <tr>
      <th colspan="2">URL adresa odkazu stránky</th>
    </tr>
    <tr>
      <td colspan="2"><input type="text" class="width-740" size="35" name="odkaz" maxlength="255" value="<?=$page->odkaz?>" /></td>      
    </tr>  
    <tr>
      <th colspan="2">Text stránky</th>
    </tr>
    <tr>
      <td colspan="2"><?=$obsah;?></td>      
    </tr>
    <tr>
      <td>Název stránky je povinný.</td>
      <td align="right"><input type="submit" value="Přidat stránku" /></td>
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
        V textu stránky můžete používat tyto zástupné proměnné, které jsou vytvořeny na základě Prvků. Místo zástupných proměnných se pak zobrazí obsah prvků. Můžete tedy díky tomu efektivně vytvářet třeba fotogalerie, které použijete na více místech webu a podobně.  
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
    Název stránky je povinnou součástí formuláře.<br /><br />
    Priorita stránky je kvůli SEO optimalizaci. Díky tomuto parametru si uživatel lehce může nejdůležitější stránky optimalizovat pro vyhledávače. Nejdůležitější doporučujeme použít maximálně u třech stránek, důležité doporučujeme použít maximálně u dalších pěti stránek. Toto doporučení je ale závislé na počtu stránek, berte jej prosím pouze orientačně pro rozsah dvaceti až padesáti stránek webu.  <br /><br />   
    Typem určujete, jestli stránka bude mít text, nebo bude pouze někam odkazovat. Můžete odkazovat na úplně jiné stránky internetu, nebo na jiné místo na Vašem webu.     <br /><br />
    Zobrazovat v navigaci vyberte, pokud chcete zobrazovat stránku v drobečkové navigaci dané stránky a&nbsp;v&nbsp;drobečkové navigaci podstránkek dané stránky. Zároveň musí mít stránka nastaven atribut zobrazovat na hodnotu ano.  <br /><br />
    Zobrazovat navigaci vyberte, pokud chcete nad nadpisem vidět cestu k dané stránce (rodiče této stránky).
  </div>
</div>
