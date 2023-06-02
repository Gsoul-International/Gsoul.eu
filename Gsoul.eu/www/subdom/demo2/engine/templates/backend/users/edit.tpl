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
<?if(getget('message','')=='rights-changed'){?><h2>Administrátorská práva tohoto uživatele byla úspěšně změněna.</h2><?}?>
<form autocomplete="off" action="<?=$asave;?>" method="post">
<table>
  <tr>
    <th>Titul</th>
    <td><input type="text" class="width-50" size="4" name="titul_pred" maxlength="63" value="<?=$user->titul_pred?>" /></td>
    <th>Jméno</th>
    <td><input type="text" class="width-110" size="17" name="jmeno" maxlength="63" value="<?=$user->jmeno?>" /></td>
  </tr>
  <tr>
	<th>Příjmení</th>
    <td><input type="text" class="width-140" size="20" name="prijmeni" maxlength="63" value="<?=$user->prijmeni?>" /></td>
    <th>Titul 2</th>
    <td><input type="text" class="width-110" size="15" name="titul_za" maxlength="63" value="<?=$user->titul_za?>" /></td>    
  </tr>
  <tr>
    <th>Společnost</th>
    <td><input class="width-230" type="text" size="35" name="firma" maxlength="63" value="<?=$user->firma?>" /></td>
    <th>IČ</th>
    <td><input type="text" class="width-140" size="20" name="ico" maxlength="63" value="<?=$user->ico?>" /></td>
  </tr>
  <tr>
    <th>DIČ</th>
    <td><input type="text" class="width-110" size="15" name="dic" maxlength="63" value="<?=$user->dic?>" /></td>   
    <th>Ulice</th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="ulice" maxlength="63" value="<?=$user->ulice?>" /></td>
  </tr>
  <tr>
	<th>č.p.</th>
	<td><input type="text" class="width-140" size="20" name="cislo_popisne" maxlength="63" value="<?=$user->cislo_popisne?>" /></td>
    <th>Město</th>
    <td><input type="text" class="width-110" size="15" name="mesto" maxlength="63" value="<?=$user->mesto?>" /></td>   
  </tr>  
  <tr>
    <th>PSČ </th>
    <td><input class="width-230" type="text" size="35" name="psc" maxlength="63" value="<?=$user->psc?>" /></td>
    <th>Stát</th>
    <td><input class="width-140" type="text" size="20" name="stat" maxlength="63" value="<?=$user->stat?>" /></td>
  </tr>
  <tr>
	<th>Paypal E-mail</th>
    <td><input type="text" class="width-110" size="20" name="email_paypal" maxlength="63" value="<?=$user->email_paypal?>" /></td>      
    <th>Telefon</th>
    <td colspan="3"><input class="width-230" type="text" size="35" name="telefon" maxlength="63" value="<?=$user->telefon?>" /></td>
  </tr>
  <tr>
    <th>E-mail</th>
    <td><input type="text" class="width-140" size="20" name="email" maxlength="63" value="<?=$user->email?>" /></td>
    <th>Nickname</th>
    <td><input type="text" class="width-110" size="15" name="osloveni" maxlength="63" value="<?=$user->osloveni?>" /></td>   
  </tr>   
  <tr>
    <td colspan="4" align="right">
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
    <th>Ověřen e-mail</th>
    <td>
      <select name="overen_email">        
        <option value="0" <?if($user->overen_email==0){?>selected<?}?> >Ne</option>
        <option value="1" <?if($user->overen_email==1){?>selected<?}?> >Ano</option>
      </select>      
    </td>
    <th>Ověřen PayPal e-mail</th>
    <td>
      <select name="overen_paypal_email">        
        <option value="0" <?if($user->overen_paypal_email==0){?>selected<?}?> >Ne</option>
        <option value="1" <?if($user->overen_paypal_email==1){?>selected<?}?> >Ano</option>
      </select>      
    </td>
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
    <td><?if(in_array('users_payments_views',$thisUserRights->povoleneKody)){?><?=printcost($user->ucetni_zustatek);?> $<?}else{?>nemáte oprávnění<?}?> </td>
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
<?if($user->prava==1&&in_array('users_change_admins',$thisUserRights->povoleneKody)){?>
	<h2>Nastavení administrátorských práv</h2>
	<form autocomplete="off" action="<?=$achangerights;?>" method="post">
		<table>
		  <tr>
		  	<th width="15%">Menu</th><th width="15%">Podmenu</th><th>Akce</th>
		  </tr>
		  <?foreach($adminsRights as $aR){?>
		  	<tr class="tr-hovered">
		  		<td><?=$aR->modul?></td>
		  		<td><?=$aR->podmodul?></td>
		  		<td>
		  			<label>
		  				<input type="checkbox" name="idar[]" value="<?=$aR->idar?>" <?if(in_array($aR->idar,$enabledRights)){?>checked<?}?> />
			  			<?=$aR->akce?>
		  			</label>
		  		</td>		  				  		
		  	</tr>
		  <?}?>
		  <tr>				
				<td colspan="3" align="right">
				  <input onclick="return confirm('Opravdu si přejete administrátorovi změnit práva?');" type="submit" autocomplete="off" value="Změnit práva" />
				</td>
			</tr>
		</table>
	</form>
	<style>
		.tr-hovered{background-color:#eeeeee;}
		.tr-hovered:hover{background-color:#ddd;}
	</style>
	<h2>Vizualizace administrátorských práv (aktuálně uložené nastavení)</h2>
	<?
	//vizualizace
	$rights=new stdClass();
	$rights->povoleneKody=array(); 
	$rights->povoleneModuly=array(); 
	$rights->povolenePodmoduly=array();  
	foreach($adminsRights as $aR){
		if(in_array($aR->idar,$enabledRights)){
			$rights->povoleneKody[$aR->kod]=$aR->kod; 
			$rights->povoleneModuly[$aR->povoluje_modul]=$aR->povoluje_modul; 
			$rights->povolenePodmoduly[$aR->povoluje_podmodul]=$aR->povoluje_podmodul;			
			}
		}
	$colors=array(
		'hrani'=>'888',
		'hrani_hry'=>'888',
		'hrani_moduly'=>'888',
		'hrani_platformy'=>'888',
		'hrani_zapasy'=>'888',
		'hrani_turnaje'=>'888',
		'uzivatele'=>'888',
		'uzivatele_sprava_uzivatelu'=>'888',
		'uzivatele_vyplaty_uzivatelu'=>'888',
		'uzivatele_odeslat_newsletter'=>'888',
		'uzivatele_exportovat_uzivatele'=>'888',
		'uzivatele_exportovat_emaily'=>'888',
		'uzivatele_exportovat_telefony'=>'888',
		'uzivatele_odhlasene_newslettery'=>'888',
		'stranky'=>'888',
		'stranky_uvodni_stranka'=>'888',
		'stranky_sprava_stranek'=>'888',		
		'stranky_sprava_novinek'=>'888',		
		'prvky'=>'888',
		'prvky_napoveda_kategorie'=>'888',
		'bfiles'=>'888',
		'bfiles_obrazky'=>'888',
		'bfiles_soubory'=>'888',
		'nastaveni'=>'888',
		'nastaveni_zaklad'=>'888',
		'nastaveni_cache'=>'888',
		'nastaveni_jazyky'=>'888'
		);
	//nastaveni / jazyky:
	if(in_array('languages_view',$rights->povoleneKody)&&in_array('languages_changes',$rights->povoleneKody)){$colors['nastaveni_jazyky']='0A0';}
	if(!in_array('languages_view',$rights->povoleneKody)&&in_array('languages_changes',$rights->povoleneKody)){$colors['nastaveni_jazyky']='F00';}
	if(in_array('languages_view',$rights->povoleneKody)&&!in_array('languages_changes',$rights->povoleneKody)){$colors['nastaveni_jazyky']='00F';}
	//nastaveni / cache:
	if(in_array('settings_caches_view',$rights->povoleneKody)&&in_array('settings_caches_changes',$rights->povoleneKody)){$colors['nastaveni_cache']='0A0';}
	if(!in_array('settings_caches_view',$rights->povoleneKody)&&in_array('settings_caches_changes',$rights->povoleneKody)){$colors['nastaveni_cache']='F00';}
	if(in_array('settings_caches_view',$rights->povoleneKody)&&!in_array('settings_caches_changes',$rights->povoleneKody)){$colors['nastaveni_cache']='00F';}
	//nastaveni / zakladni:
	if(in_array('base_settings_view',$rights->povoleneKody)&&in_array('base_settings_changes',$rights->povoleneKody)){$colors['nastaveni_zaklad']='0A0';}
	if(!in_array('base_settings_view',$rights->povoleneKody)&&in_array('base_settings_changes',$rights->povoleneKody)){$colors['nastaveni_zaklad']='F00';}
	if(in_array('base_settings_view',$rights->povoleneKody)&&!in_array('base_settings_changes',$rights->povoleneKody)){$colors['nastaveni_zaklad']='00F';}
	//bfiles / soubory:
	if(in_array('files_categories_view',$rights->povoleneKody)&&in_array('files_categories_changes',$rights->povoleneKody)){$colors['bfiles_soubory']='0A0';}
	if(!in_array('files_categories_view',$rights->povoleneKody)&&in_array('files_categories_changes',$rights->povoleneKody)){$colors['bfiles_soubory']='F00';}
	if(in_array('files_categories_view',$rights->povoleneKody)&&!in_array('files_categories_changes',$rights->povoleneKody)){$colors['bfiles_soubory']='00F';}
	//bfiles / obrazky:
	if(in_array('images_categories_view',$rights->povoleneKody)&&in_array('images_categories_changes',$rights->povoleneKody)){$colors['bfiles_obrazky']='0A0';}
	if(!in_array('images_categories_view',$rights->povoleneKody)&&in_array('images_categories_changes',$rights->povoleneKody)){$colors['bfiles_obrazky']='F00';}
	if(in_array('images_categories_view',$rights->povoleneKody)&&!in_array('images_categories_changes',$rights->povoleneKody)){$colors['bfiles_obrazky']='00F';}
	//prvky:
	if(in_array('boxes_views',$rights->povoleneKody)&&in_array('boxes_changes',$rights->povoleneKody)){$colors['prvky_napoveda_kategorie']='0A0';}
	if(!in_array('boxes_views',$rights->povoleneKody)&&in_array('boxes_changes',$rights->povoleneKody)){$colors['prvky_napoveda_kategorie']='F00';}
	if(in_array('boxes_views',$rights->povoleneKody)&&!in_array('boxes_changes',$rights->povoleneKody)){$colors['prvky_napoveda_kategorie']='00F';}
	//stranky / uvodni stranka:
	if(in_array('homepage_views',$rights->povoleneKody)&&in_array('homepage_changes',$rights->povoleneKody)){$colors['stranky_uvodni_stranka']='0A0';}
	if(!in_array('homepage_views',$rights->povoleneKody)&&in_array('homepage_changes',$rights->povoleneKody)){$colors['stranky_uvodni_stranka']='F00';}
	if(in_array('homepage_views',$rights->povoleneKody)&&!in_array('homepage_changes',$rights->povoleneKody)){$colors['stranky_uvodni_stranka']='00F';}
	//stranky / stranky:
	if(in_array('pages_views',$rights->povoleneKody)&&in_array('pages_changes',$rights->povoleneKody)){$colors['stranky_sprava_stranek']='0A0';}
	if(!in_array('pages_views',$rights->povoleneKody)&&in_array('pages_changes',$rights->povoleneKody)){$colors['stranky_sprava_stranek']='F00';}
	if(in_array('pages_views',$rights->povoleneKody)&&!in_array('pages_changes',$rights->povoleneKody)){$colors['stranky_sprava_stranek']='00F';}
	//stranky / novinky:
	if(in_array('news_view',$rights->povoleneKody)&&in_array('news_changes',$rights->povoleneKody)){$colors['stranky_sprava_novinek']='0A0';}
	if(!in_array('news_view',$rights->povoleneKody)&&in_array('news_changes',$rights->povoleneKody)){$colors['stranky_sprava_novinek']='F00';}
	if(in_array('news_view',$rights->povoleneKody)&&!in_array('news_changes',$rights->povoleneKody)){$colors['stranky_sprava_novinek']='00F';}
	//uzivatele / odhlasene newslettery:
	if(in_array('users_newsletter_logouts_views',$rights->povoleneKody)&&in_array('users_newsletter_logouts_changes',$rights->povoleneKody)){$colors['uzivatele_odhlasene_newslettery']='0A0';}
	if(!in_array('users_newsletter_logouts_views',$rights->povoleneKody)&&in_array('users_newsletter_logouts_changes',$rights->povoleneKody)){$colors['uzivatele_odhlasene_newslettery']='F00';}
	if(in_array('users_newsletter_logouts_views',$rights->povoleneKody)&&!in_array('users_newsletter_logouts_changes',$rights->povoleneKody)){$colors['uzivatele_odhlasene_newslettery']='00F';}
	//uzivatele / exporty, exporty tel., exporty mail., newsletter 
	if(in_array('users_export_phones',$rights->povoleneKody)){$colors['uzivatele_exportovat_telefony']='0A0';}
	if(in_array('users_export_emails',$rights->povoleneKody)){$colors['uzivatele_exportovat_emaily']='0A0';}
	if(in_array('users_export',$rights->povoleneKody)){$colors['uzivatele_exportovat_uzivatele']='0A0';}
	if(in_array('users_newsletter_send',$rights->povoleneKody)){$colors['uzivatele_odeslat_newsletter']='0A0';}
	//uzivatele / vyplaty:
	if(in_array('users_payouts_view',$rights->povoleneKody)&&in_array('users_payouts_changes',$rights->povoleneKody)){$colors['uzivatele_vyplaty_uzivatelu']='0A0';}
	if(!in_array('users_payouts_view',$rights->povoleneKody)&&in_array('users_payouts_changes',$rights->povoleneKody)){$colors['uzivatele_vyplaty_uzivatelu']='F00';}
	if(in_array('users_payouts_view',$rights->povoleneKody)&&!in_array('users_payouts_changes',$rights->povoleneKody)){$colors['uzivatele_vyplaty_uzivatelu']='00F';}
	//uzivatele / sprava uzivatelu
	if(in_array('users_views',$rights->povoleneKody)&&(in_array('users_creates_edit',$rights->povoleneKody)||in_array('users_change_admins',$rights->povoleneKody)||in_array('users_payments_changes',$rights->povoleneKody))){$colors['uzivatele_sprava_uzivatelu']='0A0';}
	if(!in_array('users_views',$rights->povoleneKody)&&(in_array('users_creates_edit',$rights->povoleneKody)||in_array('users_change_admins',$rights->povoleneKody)||in_array('users_payments_changes',$rights->povoleneKody))){$colors['uzivatele_sprava_uzivatelu']='F00';}
	if(in_array('users_views',$rights->povoleneKody)&&!(in_array('users_creates_edit',$rights->povoleneKody)||in_array('users_change_admins',$rights->povoleneKody)||in_array('users_payments_changes',$rights->povoleneKody))){$colors['uzivatele_sprava_uzivatelu']='00F';}
	//hry / turnaje:
	if(in_array('games_cups_view',$rights->povoleneKody)&&in_array('games_cups_changes',$rights->povoleneKody)){$colors['hrani_turnaje']='0A0';}
	if(!in_array('games_cups_view',$rights->povoleneKody)&&in_array('games_cups_changes',$rights->povoleneKody)){$colors['hrani_turnaje']='F00';}
	if(in_array('games_cups_view',$rights->povoleneKody)&&!in_array('games_cups_changes',$rights->povoleneKody)){$colors['hrani_turnaje']='00F';}
	//hry / zapasy:
	if(in_array('games_tournaments_view',$rights->povoleneKody)&&in_array('games_tournaments_changes',$rights->povoleneKody)){$colors['hrani_zapasy']='0A0';}
	if(!in_array('games_tournaments_view',$rights->povoleneKody)&&in_array('games_tournaments_changes',$rights->povoleneKody)){$colors['hrani_zapasy']='F00';}
	if(in_array('games_tournaments_view',$rights->povoleneKody)&&!in_array('games_tournaments_changes',$rights->povoleneKody)){$colors['hrani_zapasy']='00F';}
	//hry / platformy:
	if(in_array('games_platforms_view',$rights->povoleneKody)&&in_array('games_platforms_changes',$rights->povoleneKody)){$colors['hrani_platformy']='0A0';}
	if(!in_array('games_platforms_view',$rights->povoleneKody)&&in_array('games_platforms_changes',$rights->povoleneKody)){$colors['hrani_platformy']='F00';}
	if(in_array('games_platforms_view',$rights->povoleneKody)&&!in_array('games_platforms_changes',$rights->povoleneKody)){$colors['hrani_platformy']='00F';}
	//hry / moduly:
	if(in_array('games_modules_view',$rights->povoleneKody)&&in_array('games_modules_changes',$rights->povoleneKody)){$colors['hrani_moduly']='0A0';}
	if(!in_array('games_modules_view',$rights->povoleneKody)&&in_array('games_modules_changes',$rights->povoleneKody)){$colors['hrani_moduly']='F00';}
	if(in_array('games_modules_view',$rights->povoleneKody)&&!in_array('games_modules_changes',$rights->povoleneKody)){$colors['hrani_moduly']='00F';}
	//hry / hry:
	if(in_array('game_games_view',$rights->povoleneKody)&&in_array('game_games_creategame',$rights->povoleneKody)){$colors['hrani_hry']='0A0';}
	if(!in_array('game_games_view',$rights->povoleneKody)&&in_array('game_games_creategame',$rights->povoleneKody)){$colors['hrani_hry']='F00';}
	if(in_array('game_games_view',$rights->povoleneKody)&&!in_array('game_games_creategame',$rights->povoleneKody)){$colors['hrani_hry']='00F';}				
	//hlavni moduly:
	if(in_array('BSettings',$rights->povoleneModuly)){$colors['nastaveni']='00F';}
	if(in_array('BFiles',$rights->povoleneModuly)){$colors['bfiles']='00F';}
	if(in_array('BBoxes',$rights->povoleneModuly)){$colors['prvky']='00F';}
	if(in_array('BPages',$rights->povoleneModuly)){$colors['stranky']='00F';}
	if(in_array('BUsers',$rights->povoleneModuly)){$colors['uzivatele']='00F';}
	if(in_array('BGame',$rights->povoleneModuly)){$colors['hrani']='00F';}
	?>
	<table>		
		<tr>
			<th>Menu</th>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani'];?>;"><i class="fa fa-gamepad "></i> Hraní</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele'];?>;"><i class="fa fa-users "></i> Uživatelé</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky'];?>;"><i class="fa fa-file-text "></i> Stránky</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['prvky'];?>;"><i class="fa fa-cubes "></i> Prvky</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['bfiles'];?>;"><i class="fa fa-folder-open "></i> Obrázky, videa, soubory</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['nastaveni'];?>;"><i class="fa fa-cogs "></i> Nastavení</td>
		</tr>		
		<tr>
			<th rowspan="7">Podmenu</th>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani_hry'];?>;"><span class="icon"><i class="fa fa-gamepad"></i></span> Hry</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_sprava_uzivatelu'];?>;"><span class="icon"><i class="fa fa-user"></i></span> Správa uživatelů</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky_uvodni_stranka'];?>;"><span class="icon"><i class="fa fa-home"></i></span> Úvodní stránka</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['prvky_napoveda_kategorie'];?>;"><span class="icon"><i class="fa fa-question-circle"></i></span> Nápověda prvků</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['bfiles_obrazky'];?>;"><span class="icon"><i class="fa fa-file-image-o"></i></span> Obrázky a videa</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['nastaveni_zaklad'];?>;"><span class="icon"><i class="fa fa-cog"></i></span> Základní nastavení</td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani_moduly'];?>;"><span class="icon"><i class="fa fa-database"></i></span> Moduly</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_vyplaty_uzivatelu'];?>;"><span class="icon"><i class="fa fa-usd"></i></span> Výplaty uživatelů</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky_sprava_stranek'];?>;"><span class="icon"><i class="fa fa-bars"></i></span> Správa stránek</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['prvky_napoveda_kategorie'];?>;"><span class="icon"><i class="fa fa-cube"></i></span> Konkrétní prvky X </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['bfiles_soubory'];?>;"><span class="icon"><i class="fa fa-file-word-o"></i></span> Soubory </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['nastaveni_cache'];?>;"><span class="icon"><i class="fa fa-refresh"></i></span> Systémové cache </td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani_platformy'];?>;"><span class="icon"><i class="fa fa-desktop"></i></span> Platformy</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_odeslat_newsletter'];?>;"><span class="icon"><i class="fa fa-envelope-o"></i></span> Odeslat newsletter </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky_sprava_stranek'];?>;"><span class="icon"><i class="fa fa-plus"></i></span> Přidat stránku </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['prvky_napoveda_kategorie'];?>;"><span class="icon"><i class="fa fa-cube"></i></span> Konkrétní prvky Y </td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['nastaveni_jazyky'];?>;"><span class="icon"><i class="fa fa-language"></i></span> Jazyky</td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani_zapasy'];?>;"><span class="icon"><i class="fa fa-trophy"></i></span> Zápasy</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_exportovat_uzivatele'];?>;"><span class="icon"><i class="fa fa-reply-all"></i></span> Exportovat uživatele </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky_sprava_novinek'];?>;"><span class="icon"><i class="fa fa-bars"></i></span> Správa novinek </td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['prvky_napoveda_kategorie'];?>;"><span class="icon"><i class="fa fa-cube"></i></span> Konkrétní prvky Z </td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['hrani_turnaje'];?>;"><span class="icon"><i class="fa fa-trophy"></i></span> Turnaje</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_exportovat_emaily'];?>;"><span class="icon"><i class="fa fa-reply"></i></span> Exportovat e-maily uživatelů</td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['stranky_sprava_novinek'];?>;"><span class="icon"><i class="fa fa-plus"></i></span> Přidat novinku </td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_exportovat_telefony'];?>;"><span class="icon"><i class="fa fa-reply"></i></span> Exportovat telefony uživatelů </td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
		</tr>
		<tr>			
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;color:#<?=$colors['uzivatele_odhlasene_newslettery'];?>;"><span class="icon"><i class="fa fa-envelope-o"></i></span> Odhlášené newslettery </td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
			<td style="text-align:left;font-weight:bold;"></td>
		</tr>
		<tr>
			<th>Agenda</th>
			<td colspan="6" style="font-weight:bold;">
				<span style="color:#888">šedá barva = neaktivní (skryté) položky</span> ;
				<span style="color:#0000FF">modrá barva = zobrazovat položky</span> ; 
				<span style="color:#00AA00">zelená barva = zobrazovat a měnit položky</span> ; 
				<span style="color:#FF0000">červená = možnost měnit položky bez zobrazení (chybné nastavení práv!)</span>
			</td>
		</tr>
	</table>
<?}?>
