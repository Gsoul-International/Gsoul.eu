<h1>Odeslat newsletter uživatelům - 2. krok - Náhled newsletteru</h1>
<form action="<?=$asend;?>" method="post">
  <table>
    <tr>
      <th width="15%">Odesílatel:</th>
      <td>&lt;<?=$newsletter->email_odesilatele?>&gt; "<?=$newsletter->jmeno_odesilatele?>"</td>
    </tr>
    <tr>
      <th>Příjemci:</th>
      <td>
        <?if($newsletter->prijemci=='1'){?>Uživatelé odebírající newsletter<?}?>
        <?if($newsletter->prijemci=='0'){?>Uživatelé neodebírající newsletter<?}?>
        <?if($newsletter->prijemci=='-1'){?>Všichni uživatelé<?}?>
      </td>
    </tr>
    <tr>
      <th>Předmět:</th>
      <td><?=$newsletter->predmet2?></td>
    </tr>
    <tr>
      <th>Zpráva:</th>
      <td><?=$newsletter->zprava2?></td>
    </tr>  
    <tr>
      <td><a href="<?=$aback?>"><i class="fa fa-arrow-left"></i> Zpět</a></td>
      <td align="right"><input type="submit" onclick="return confirm('Opravdu si přejete odeslat newsletter?');" value="Odeslat newsletter" /></td> 
    </tr>
  </table>      
</form>
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">    
      Zde vidíte, jak bude přibližně vypadat newsletter, který Vám přijde do E-mailu. 
      Zobrazení se může lišit v&nbsp;závislosti na prohlížeči, nebo E-mailovém klientovi. 
      V závislosti na počtu uživatelů se newsletter může zasílat i&nbsp;několik minut či desítek minut.
      Po kliknutí na tlačítko "Odeslat newsletter" proto prosím buďte trpěliví. <br /> 
  </div>
</div>