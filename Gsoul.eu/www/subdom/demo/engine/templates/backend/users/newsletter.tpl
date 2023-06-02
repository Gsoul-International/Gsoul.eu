<h1>Odeslat newsletter uživatelům</h1>
<form action="<?=$asend;?>" method="post">
  <?if($error==1){?><p><b>Všechny položky musí být vyplněny!</b></p><?}?>
  <?if($sended==1){?><p><b>Newsletter úspěšně odeslán.</b></p><?}?>
  <table>
    <tr>
      <th>E-mail odesílatele</th>      
      <th>Jméno odesílatele</th>      
    </tr>
    <tr>
      <td><input type="text" class="width-350" size="40" name="email_odesilatele" value="<?=$newsletter->email_odesilatele?>" /></td>      
      <td><input type="text" class="width-350" size="40" name="jmeno_odesilatele" value="<?=$newsletter->jmeno_odesilatele?>" /></td>      
    </tr>
    <tr>
      <th colspan="2">Předmět newsletteru</th>
    </tr>
    <tr>
      <td colspan="2"><input type="text" class="width-740" size="90" name="predmet" value="<?=$newsletter->predmet?>" /></td> 
    </tr> 
    <tr>
      <th colspan="2">Text newsletteru</th>
    </tr>
    <tr>
      <td colspan="2"><?=$zprava?></td> 
    </tr>
    <tr>
      <th colspan="2">Příjemci newsletteru</th>
    </tr>
    <tr>
      <td>
        <select name="prijemci">
          <option <?if($newsletter->prijemci=='1'){?>selected<?}?> value="1">Uživatelé odebírající newsletter</option>
          <option <?if($newsletter->prijemci=='0'){?>selected<?}?> value="0">Uživatelé neodebírající newsletter</option>
          <option <?if($newsletter->prijemci=='-1'){?>selected<?}?> value="-1">Všichni uživatelé</option>        
        </select>
      </td>
      <td align="right"><input type="submit" value="Zobrazit newsletter a odeslat" /></td> 
    </tr>        
  </table>
</form>
<br />
<table>
  <tr>
    <th width="30%">Zástupná proměnná</th>
    <th>Význam </th>
  </tr>  
  <tr>
    <th>{titul-jmeno-prijmeni}</th>
    <td>Jméno, příjmení a tituly uživatele.</td>
  </tr>
  <tr>
    <th>{telefon}</th>
    <td>Telefon uživatele.</td>
  </tr> 
  <tr>
    <th>{e-mail}</th>
    <td>E-mail uživatele.</td>
  </tr>     
</table>    
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">
    V tomto kroku vyplňujete údaje do newsletteru, v dalším kroku uvidíte přibližný náhled newsletteru ještě než se odešle uživatelům. V následujícím kroku se můžete vrátit a upravit text newsletteru, nebo newsletter odeslat.<br /><br /> 
    V předmětu i textu newsletteru můžete použít zástupné proměnné. Zástupné proměnné se při odeslání newsletteru nahradí údaji konkrétního uživatele. Pokud tedy zadáte následující text:<br /><br />  
    <i>Dobrý den, jmenujete se {titul-jmeno-prijmeni}?</i><br />
  </div>
</div>