<h1>Editace uživatele #<?=$user->uid;?></h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis uživatelů</a><br>
  <a href="<?=$ainfo;?>"><i class="fa fa-info"></i> Zobrazit uživatele</a>
  <a href="<?=$acoins;?>"><i class="fa fa-usd"></i> Účetní zůstatek</a>
</div>
<?if(getget('message','')=='user-saved'){?><h2>Uživatel #<?=(int)getget('uid','');?> úspěšně uložen.</h2><?}?>
<?if(getget('message','')=='password-saved'){?><h2>Uživateli #<?=(int)getget('uid','');?> bylo úspěšně změněno heslo.</h2><?}?>
<?if(getget('message','')=='password-short'){?><h2>Heslo nebylo změněno, musí obsahovat minimálně 4 znaky!</h2><?}?>
<?if(getget('message','')=='password-not-same'){?><h2>Heslo nebylo změněno, zadaná hesla se neshodují!</h2><?}?>
<?if(getget('message','')=='email-exists'){?><h2>Tento e-mail je již použit u jiného uživatele!</h2><?}?>
<?if(getget('message','')=='email-required'){?><h2>E-mail je povinný!</h2><?}?>
<form autocomplete="off" action="<?=$asave;?>" method="post">
<table>
  <tr>
    <th>Titul</th>
    <td><input type="text" class="width-50" size="4" name="titul_pred" maxlength="63" value="<?=$user->titul_pred?>" /></td>
    <th>Jméno</th>
    <td><input type="text" class="width-110" size="17" name="jmeno" maxlength="63" value="<?=$user->jmeno?>" /></td>
    <th>Příjmení</th>
    <td><input type="text" class="width-140" size="20" name="prijmeni" maxlength="63" value="<?=$user->prijmeni?>" /></td>
    <th>Titul 2</th>
    <td><input type="text" class="width-110" size="15" name="titul_za" maxlength="63" value="<?=$user->titul_za?>" /></td>    
  </tr>
  <tr>
    <th>Společnost</th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="firma" maxlength="63" value="<?=$user->firma?>" /></td>
    <th>IČ</th>
    <td><input type="text" class="width-140" size="20" name="ico" maxlength="63" value="<?=$user->ico?>" /></td>
    <th>DIČ</th>
    <td><input type="text" class="width-110" size="15" name="dic" maxlength="63" value="<?=$user->dic?>" /></td>   
  </tr>
  <tr>
    <th>Ulice</th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="ulice" maxlength="63" value="<?=$user->ulice?>" /></td>
    <th>č.p.</th>
    <td><input type="text" class="width-140" size="20" name="cislo_popisne" maxlength="63" value="<?=$user->cislo_popisne?>" /></td>
    <th>Město</th>
    <td><input type="text" class="width-110" size="15" name="mesto" maxlength="63" value="<?=$user->mesto?>" /></td>   
  </tr>  
  <tr>
    <th>PSČ </th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="psc" maxlength="63" value="<?=$user->psc?>" /></td>
    <th>Stát</th>
    <td colspan="3"><input class="width-140" type="text" size="20" name="stat" maxlength="63" value="<?=$user->stat?>" /></td>    
  </tr>  
  <tr>
    <th>Telefon</th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="telefon" maxlength="63" value="<?=$user->telefon?>" /></td>
    <th>E-mail</th>
    <td><input type="text" class="width-140" size="20" name="email" maxlength="63" value="<?=$user->email?>" /></td>
    <th>Nickname</th>
    <td><input type="text" class="width-110" size="15" name="osloveni" maxlength="63" value="<?=$user->osloveni?>" /></td>   
  </tr>   
  <tr>
    <td colspan="8" align="right">
      <input type="submit" value="Uložit" />
    </td>
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
  <tr>
    <th>Registrace uživatele</th>
    <td><?=TimestampToDateTime($user->registrace);?></td>  
    <th>Poslední přihlášení</th>
    <td><?if($user->pocet_prihlaseni>0){echo TimestampToDateTime($user->posledni_prihlaseni).' / '.$user->posledni_prihlaseni_ip;}?></td>
  </tr>
  <tr>
    <th>Počet přihlášení</th>
    <td><?=$user->pocet_prihlaseni;?>x</td>  
    <th>ID uživatele</th>
    <td>#<?=($user->uid);?></td>
  </tr>
  <tr>
    <th>Účetní zůstatek</th>
    <td><?=printcost($user->ucetni_zustatek);?> $</td>
    <td colspan="2" align="right">
      <input type="submit" value="Uložit" />
    </td>
  </tr>
</table>
</form>
<h2>Změna hesla</h2>
<form autocomplete="off" action="<?=$achangepass;?>" method="post">
<table>
  <tr>
    <th width="25%">Nové heslo</th>
    <td colspan="2"><input class="width-230" autocomplete="off" type="password" size="20" name="heslo_1" maxlength="63" value="" /></td>
  </tr>
  <tr>
    <th width="25%">Nové heslo znovu</th>
    <td colspan="2"><input class="width-230" autocomplete="off" type="password" size="20" name="heslo_2" maxlength="63" value="" /></td>
  </tr>
  <tr>
    <td width="60%" colspan="2">Nové heslo musí obsahovat minimálně 4 znaky.</td>
    <td align="right">
      <input onclick="return confirm('Opravdu si přejete uživateli změnit heslo?');" type="submit" autocomplete="off" value="Změnit heslo" />
    </td>
  </tr>
</table>
</form>