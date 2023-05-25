<h1>Úvodní stránka</h1>
<?if(getget('message','')=='name-required'){?><h2>Název úvodní stránky je povinný!</h2><?}?>
<?if(getget('message','')=='page-saved'){?><h2>Úvodní stránka uložena.</h2><?}?>
<?foreach($mainpages as $mp){?>
  <form action="<?=$asave;?>" method="post" autocomplete="off">
    <input type="hidden" name="idmp" value="1" />    
    <table>
      <tr>
        <th>Název</th>        
        <td><input type="text" class="width-400" size="35" name="nazev" maxlength="127" value="<?=$mp->nazev?>" /></td>
        <th>Priorita&nbsp;stránky</th>
        <td>
          <select name="sitemap_priorita" class="width-72">          
            <option <?if($mp->sitemap_priorita==0.9){?>selected<?}?> value="0.9">Nejdůležitější</option>          
            <option <?if($mp->sitemap_priorita==0.8){?>selected<?}?> value="0.8">Důležitá</option>          
            <option <?if($mp->sitemap_priorita==0.7){?>selected<?}?> value="0.7">Běžná</option>          
            <option <?if($mp->sitemap_priorita==0.6){?>selected<?}?> value="0.6">Méně důležitá</option>          
            <option <?if($mp->sitemap_priorita==0.5){?>selected<?}?> value="0.5">Nedůležitá</option>          
          </select>   
        </td>          
      </tr>
      <tr>
        <th>SEO description</th>
        <td colspan="3"><input type="text" class="width-600" size="35" name="seo_description" maxlength="255" value="<?=$mp->seo_description?>" /></td>
      </tr>
      <tr>
        <th>SEO keywords</th>
        <td colspan="3"><input type="text" class="width-600" size="35" name="seo_keywords" maxlength="255" value="<?=$mp->seo_keywords?>" /></td>
      </tr>
      <tr>
        <th>Titulek</th>
        <td><input type="text" class="width-600" size="35" name="seo_title" maxlength="127" value="<?=$mp->seo_title?>" /></td>  
        <th>Zobrazovat nadpis</th>
        <td>
          <select name="zobrazovat_nadpis" class="width-72">
            <option <?if($mp->zobrazovat_nadpis==1){?>selected<?}?> value="1">Ano</option>
            <option <?if($mp->zobrazovat_nadpis==0){?>selected<?}?> value="0">Ne</option>
          </select>
        </td>    
      </tr>
      <tr>
        <th>Zobrazovat v&nbsp;navigaci</th>
        <td>
          <select name="zobrazovat_v_navigaci" class="width-200">
            <option <?if($mp->zobrazovat_v_navigaci==1){?>selected<?}?> value="1">Ano</option>
            <option <?if($mp->zobrazovat_v_navigaci==0){?>selected<?}?> value="0">Ne</option>
          </select>
        </td>
        <th>Zobrazovat navigaci</th>
        <td>
          <select name="zobrazovat_navigaci" class="width-110">
            <option <?if($mp->zobrazovat_navigaci==1){?>selected<?}?> value="1">Ano</option>
            <option <?if($mp->zobrazovat_navigaci==0){?>selected<?}?> value="0">Ne</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="4"><?=$mp->editor;?></td>
      </tr>
      <tr>
        <td colspan="2">Název stránky je povinný.</td>
        <td colspan="2" align="right"><input type="submit" value="Uložit stránku"></td>
      </tr>
    </table>
  </form>
  <br />
<?}?>
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
    Název stránky je povinný.<br /><br />    
    Priorita stránky je kvůli SEO optimalizaci. Díky tomuto parametru si uživatel lehce může nejdůležitější stránky optimalizovat pro vyhledávače. Nejdůležitější doporučujeme použít maximálně u třech stránek, důležité doporučujeme použít maximálně u dalších pěti stránek. Toto doporučení je ale závislé na počtu stránek, berte jej prosím pouze orientačně pro rozsah dvaceti až padesáti stránek webu.  <br /><br />   
    Zobrazovat v navigaci vyberte, pokud chcete zobrazovat stránku v drobečkové navigaci dané stránky a&nbsp;v&nbsp;drobečkové navigaci podstránkek dané stránky.  <br /><br />
    Zobrazovat navigaci vyberte, pokud chcete nad nadpisem vidět cestu k úvodní stránce.
  </div>
</div>