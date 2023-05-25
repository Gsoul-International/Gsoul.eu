<h1>Export E-mailů uživatelů</h1>
<h2>E-maily uživatelů, kteří odebírají novinky (celkem <?=count($emails_a);?>):</h2>
<textarea class="width-740" rows="5" cols="80"><?=implode(', ',$emails_a);?></textarea>
<h2>E-maily uživatelů, kteří neodebírají novinky (celkem <?=count($emails_b);?>):</h2>
<textarea class="width-740" rows="5" cols="80"><?=implode(', ',$emails_b);?></textarea>
<h2>E-maily všech uživatelů (celkem <?=count($emails_c);?>):</h2>
<textarea class="width-740" rows="5" cols="80"><?=implode(', ',$emails_c);?></textarea>
<br /><br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">    
      Po kliknutí do bílého pole s vypsanými E-maily můžete pomocí klávesových zkratek CTRL+A a CTRL+C vše jednoduše zkopírovat. V závislosti na počtu uživatelů se stránka může načítat delší dobu. <br /> 
  </div>
</div>