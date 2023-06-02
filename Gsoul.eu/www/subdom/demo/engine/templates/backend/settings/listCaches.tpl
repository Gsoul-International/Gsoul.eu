<h1>Systémové cache</h1>
<?if($message=='rewrites-regenerated'){?>
  <h2>Cache url odkazů přegenerována.</h2>
<?}?>
<?if($message=='settings-regenerated'){?>
  <h2>Cache základního nastavení přegenerována.</h2>
<?}?>
<?if($message=='images-sizes-regenerated'){?>
  <h2>Cache velikostí obrázků přegenerována.</h2>
<?}?>
  <table>
    <tr>
      <th>Cache URL odkazů</th>
      <td>Poslední vygenerování: <?=$rewritesTime;?></td>
      <td align="right"><a href="<?=$aRewritesRegenerate;?>">Přegenerovat cache <i class="fa fa-arrow-right"></i></a></td>
    </tr>
    <tr>
      <th>Cache základního nastavení</th>
      <td>Poslední vygenerování: <?=$settingsTime;?></td>
      <td align="right"><a href="<?=$aSettingsRegenerate;?>">Přegenerovat cache <i class="fa fa-arrow-right"></i></a></td>
    </tr>    
    <tr>
      <th>Cache velikostí obrázků</th>
      <td>Poslední vygenerování: <?=$imagesSizesTime;?></td>
      <td align="right"><a href="<?=$aImagesSizesRegenerate;?>">Přegenerovat cache <i class="fa fa-arrow-right"></i></a></td>
    </tr>
  </table>
<br /><br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">    
      Systémové cache slouží k rychlejšímu načítání jednotlivých stránek systému. Některé se přegenerují při&nbsp;přeuložení dat v administraci, jiné se přeukládají v závislosti na čase. V této části je můžete přegenerovávat libovolně bez ohledu na jejich nastavení.<br /> <br />
      Cache URL odkazů: V této cache jsou uloženy systémové URL adresy, i URL adresy stránek apod. Díky této cache systém rychleji načítá odkazy na stránky napříč celým webem. 
      <br /> <br />
      Cache základního nastavení: V této cache je uloženo základní nastavení webu. Díky této cache systém rychleji načítá celý web.      
      <br /> <br />
      Cache velikostí obrázků: V této cache jsou uloženy základní rozměry náhledů obrázků. Díky této cache systém rychleji načítá obrázky na webu.  
  </div>
</div>