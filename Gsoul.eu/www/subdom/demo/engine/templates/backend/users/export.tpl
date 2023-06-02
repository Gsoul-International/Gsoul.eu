<h1>Export uživatelů</h1>
<form action="<?=$aexport?>" method="post">
<?if(getget('message','')=='no-user-found'){?><h2>S daným nastavením neexistuje žádný uživatel k exportu.</h2><?}?>
<p>
  <strong>Označte údaje, které chcete u uživatelů exportovat:</strong>  
</p>
<h2>Základní informace</h2>
<table>
  <tr>
    <th width="27%"><label for="uid">ID uživatele</label></th>
    <td><input type="checkbox" id="uid" name="uid" value="1" checked /></td> 
    <th width="27%"><label for="prava">Uživatelská práva</label></th>
    <td><input type="checkbox" id="prava" name="prava" value="1" checked /></td>
    <th width="27%"><label for="registrace">Datum registrace</label></th>
    <td><input type="checkbox" id="registrace" name="registrace" value="1" checked /></td>    
  </tr>   
  <tr>    
    <th width="27%"><label for="posledni_prihlaseni">Poslední přihlášení</label></th>
    <td><input type="checkbox" id="posledni_prihlaseni" name="posledni_prihlaseni" value="1" checked /></td>
    <th width="27%"><label for="pocet_prihlaseni">Počet přihlášení</label></th>
    <td><input type="checkbox" id="pocet_prihlaseni" name="pocet_prihlaseni" value="1" checked /></td> 
    <th width="27%"><label for="novinky">Odebírat novinky</label></th>
    <td><input type="checkbox" id="novinky" name="odber_novinek" value="1" checked /></td>    
  </tr>  
</table>
<h2>Fakturační adresa</h2>
<table>
  <tr>
    <th width="10%"><label for="titul_pred">Titul</label></th>
    <td><input type="checkbox" id="titul_pred" name="titul_pred" value="1" checked /></td>
    <th width="12%"><label for="jmeno">Jméno</label></th>
    <td><input type="checkbox" id="jmeno" name="jmeno" value="1" checked /></td>
    <th width="14%"><label for="prijmeni">Příjmení</label></th>
    <td><input type="checkbox" id="prijmeni" name="prijmeni" value="1" checked /></td>    
    <th width="20%"><label for="titul_za">Titul za jménem</label></th>
    <td><input type="checkbox" id="titul_za" name="titul_za" value="1" checked /></td>
    <th width="15%"><label for="firma">Společnost</label></th>
    <td><input type="checkbox" id="firma" name="firma" value="1" checked /></td>
  </tr>
  <tr>    
    <th><label for="ico">IČ</label></th>
    <td><input type="checkbox" id="ico" name="ico" value="1" checked /></td>
    <th><label for="dic">DIČ</label></th>
    <td><input type="checkbox" id="dic" name="dic" value="1" checked /></td>    
    <th><label for="ulice">Ulice</label></th>
    <td><input type="checkbox" id="ulice" name="ulice" value="1" checked /></td>
    <th><label for="cislo_popisne">č.p.</label></th>
    <td><input type="checkbox" id="cislo_popisne" name="cislo_popisne" value="1" checked /></td>
    <th><label for="mesto">Město</label></th>
    <td><input type="checkbox" id="mesto" name="mesto" value="1" checked /></td>  
  </tr>
  <tr>  
    <th><label for="psc">PSČ</label></th>
    <td><input type="checkbox" id="psc" name="psc" value="1" checked /></td>    
    <th><label for="stat">Stát</label></th>
    <td><input type="checkbox" id="stat" name="stat" value="1" checked /></td>
    <th><label for="email">E-mail</label></th>
    <td><input type="checkbox" id="email" name="email" value="1" checked /></td>
    <th><label for="telefon">Telefon</label></th>
    <td><input type="checkbox" id="telefon" name="telefon" value="1" checked /></td>    
    <th><label for="osloveni">Nickname</label></th>
    <td><input type="checkbox" id="osloveni" name="osloveni" value="1" checked /></td>        
  </tr>
</table>
<p>
  <strong>Vyberte skupiny uživatelů pro export:</strong>
</p>
<h2>Exportovat uživatele</h2>
<table>
  <tr>
    <th width="22%"><label for="e_uzivatele">S právy uživatel</label></th>
    <td width="5%"><input type="checkbox" id="e_uzivatele" name="e_uzivatele" value="1" checked /></td>
    <th width="25%"><label for="e_admini">S právy administrátor</label></th>
    <td width="5%"><input type="checkbox" id="e_admini" name="e_admini" value="1" checked /></td>
    <th width="28%"><label for="e_super">S právy superadministrátor</label></th>
    <td><input type="checkbox" id="e_super" name="e_super" value="1" checked /></td> 
  </tr>  
  <tr>
    <th><label for="odebiraji_novinky">Odebírají novinky</label></th>
    <td><input type="checkbox" id="odebiraji_novinky" name="odebiraji_novinky" value="1" checked /></td>
    <th><label for="neodebiraji_novinky">Neodebírají novinky</label></th>
    <td><input type="checkbox" id="neodebiraji_novinky" name="neodebiraji_novinky" value="1" checked /></td>
    <th><label>Typ exportu</label></th>
    <td>
      <select name="typ_exportu">
        <option value="excel">MS Excel</option>
        <option value="html">HTML</option>
        <option value="csv">CSV</option>
        <option value="text">TEXT</option>
      </select>
    </td>    
  </tr>  
  <tr>
    <td colspan="6" align="right">
      <input type="submit" value="Exportovat" />
    </td>  
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
  <div class="BackendHelpBody" id="BackendHelp">    
      Všechny typy exportních souborů využívají kódování UTF-8. Sloupce v CSV souboru jsou odděleny středníky (;), sloupce v textovém dokumentu jsou odděleny tabulátorem. Doporučujeme používat export pro MS Excel a HTML. Uživatelé jsou seřazení dle ID. V závislosti na počtu uživatelů a počtu parametrů může export trvat i několik minut. <br /> 
  </div>
</div>