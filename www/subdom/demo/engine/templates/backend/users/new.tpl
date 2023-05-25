<h1>Přidání uživatele</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis uživatelů</a><br>  
</div>
<?if(getget('message','')=='password-short'){?><h2>Heslo musí obsahovat minimálně 4 znaky!</h2><?}?>
<?if(getget('message','')=='password-not-same'){?><h2>Zadaná hesla se neshodují!</h2><?}?>
<?if(getget('message','')=='email-exists'){?><h2>Tento e-mail je již použit u jiného uživatele!</h2><?}?>
<?if(getget('message','')=='email-required'){?><h2>E-mail je povinný!</h2><?}?>
<?if(getget('message','')=='user-created'){?><h2>Uživatel vytvořen. <a href="<?=$anewuser;?>">Zobrazit tohoto uživatele <i class="fa fa-arrow-right"></i></a></h2><?}?>
<form action="<?=$anew;?>" method="post" autocomplete="off">
<table>
  <tr>
    <th>Titul</th>
    <td><input type="text" class="width-50" size="4" name="titul_pred" maxlength="63" value="<?=$user->titul_pred?>" /></td>
    <th>Jméno</th>
    <td><input type="text" class="width-110" size="14" name="jmeno" maxlength="63" value="<?=$user->jmeno?>" /></td>
    <th>Příjmení</th>
    <td><input type="text" class="width-140" size="20" name="prijmeni" maxlength="63" value="<?=$user->prijmeni?>" /></td>
    <th>Titul 2</th>
    <td><input type="text" class="width-110" size="15" name="titul_za" maxlength="63" value="<?=$user->titul_za?>" /></td>    
  </tr>
  <tr>
    <th>Společnost</th>
    <td colspan="3"><input type="text" class="width-230" size="35" name="firma" maxlength="63" value="<?=$user->firma?>" /></td>
    <th>IČ</th>
    <td><input type="text" class="width-140" size="20" name="ico" maxlength="63" value="<?=$user->ico?>" /></td>
    <th>DIČ</th>
    <td><input type="text" class="width-110" size="15" name="dic" maxlength="63" value="<?=$user->dic?>" /></td>   
  </tr>
  <tr>
    <th>Ulice</th>
    <td colspan="3"><input type="text" class="width-230" size="35" name="ulice" maxlength="63" value="<?=$user->ulice?>" /></td>
    <th>č.p.</th>
    <td><input type="text" class="width-140" size="20" name="cislo_popisne" maxlength="63" value="<?=$user->cislo_popisne?>" /></td>
    <th>Město</th>
    <td><input type="text" class="width-110" size="15" name="mesto" maxlength="63" value="<?=$user->mesto?>" /></td>   
  </tr>  
  <tr>
    <th>PSČ </th>
    <td colspan="3"><input type="text" class="width-230" size="35" name="psc" maxlength="63" value="<?=$user->psc?>" /></td>
    <th>Stát</th>
    <td colspan="3"><input type="text" class="width-140" size="20" name="stat" maxlength="63" value="<?=$user->stat?>" /></td>    
  </tr>  
  <tr>
    <th>Telefon</th>
    <td colspan="3"><input type="text" class="width-230" size="35" name="telefon" maxlength="63" value="<?=$user->telefon?>" /></td>
    <th>E-mail</th>
    <td><input type="text" class="width-140" size="20" name="email" maxlength="63" value="<?=$user->email?>" /></td>
    <th>Nickname</th>
    <td><input type="text" class="width-110" size="15" name="osloveni" maxlength="63" value="<?=$user->osloveni?>" /></td>   
  </tr>     
</table>
<h2>Systémové informace</h2>
<table>
  <tr>
    <th width="25%">Práva</th>
    <td>
      <select name="prava">
        <?if($prava==2){?><option value="2" <?if($user->prava==2){?>selected<?}?> >Super administrátor</option><?}?>
        <option value="1" <?if($user->prava==1){?>selected<?}?> >Administrátor</option>
        <option value="0" <?if($user->prava==0){?>selected<?}?> >Uživatel</option>
      </select>         
    </td>
    <th>Odebírat novinky</th>
    <td>
      <select name="odber_novinek">
        <option value="1" <?if($user->odber_novinek==1){?>selected<?}?> >Ano</option>
        <option value="0" <?if($user->odber_novinek==0){?>selected<?}?> >Ne</option>
      </select>      
  </tr>
</table>
<h2>Heslo</h2>
<table>
  <tr>
    <th width="25%">Heslo</th>
    <td colspan="2"><input autocomplete="off" type="password" class="width-230" size="20" name="heslo_1" maxlength="63" value="" /></td>
  </tr>
  <tr>
    <th width="25%">Heslo znovu</th>
    <td colspan="2"><input autocomplete="off" type="password" class="width-230" size="20" name="heslo_2" maxlength="63" value="" /></td>
  </tr>
  <tr>
    <td width="60%" colspan="2">Heslo musí obsahovat minimálně 4 znaky. E-mail je povinný.</td>
    <td align="right">
      <input type="submit" value="Přidat uživatele" />
    </td>
  </tr>
</table>
</form>