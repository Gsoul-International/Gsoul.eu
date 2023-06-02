<h1>Hraní - Editace modulu - <?=$data->nazev?></h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis modulů</a><br>     
</div>
<br>
<?if(getget('message','')=='saved'){?><h2>Hra úspěšně uložena.</h2><br><?}?>
<?if(getget('message','')=='created'){?><h2>Hra úspěšně přidána.</h2><br><?}?>
<?if(getget('message','')=='not-found'){?><h2>Hra nenalezena.</h2><br><?}?>
<?if(getget('message','')=='deleted'){?><h2>Hra úspěšně smazána.</h2><br><?}?>
<h2>Přiřazené hry k modulu <?=$data->nazev?></h2>
<br>
<div class="overflow-wrap">
	<table>
	  <tr><th>Hra</th><th>Maximum hráčů</th><th>Maximum týmů</th><th>Poplatek za založení v $ </th><th colspan="3">Procenta pro zakladatele turnaje max. 9%  </th></tr>
	  <?if(count($games)!=count($usedGames)){?>
		<form method="post" action="<?=$aaddgame?>">
		  <tr>
			<td><select name="idgam" class="width-140"><?foreach($games as $gm){if(!isset($usedGames[$gm->idg])){?><option value="<?=$gm->idg?>"><?=$gm->nazev?></option><?}}?></select></td>
			<td><input type="text" class="width-72" name="maximalni_pocet_hracu" value="1" /></td>
			<td><input type="text" class="width-72" name="maximalni_pocet_tymu" value="1" /></td>			
			<td><input type="text" class="width-72" name="poplatek_za_zalozeni_turnaje" value="0,00" /></td>
			<td><input type="text" class="width-72" name="procenta_pro_zakladatele" value="0,00" /></td>
			<td colspan="2" width="11%" style="text-align:right"><input type="submit" value="Přidat hru" /></td></tr>
		</form>
	  <?}?>
	  <?foreach($spojeni as $sx){?>
		<form method="post" action="<?=$sx->aedit?>">
		  <tr>
			<td><?=$games2[$sx->idgam]->nazev?></td>
			<td><input type="text" class="width-72" name="maximalni_pocet_hracu" value="<?=$sx->maximalni_pocet_hracu?>" /></td>
			<td><input type="text" class="width-72" name="maximalni_pocet_tymu" value="<?=$sx->maximalni_pocet_tymu?>" /></td>			
			<td><input type="text" class="width-72" name="poplatek_za_zalozeni_turnaje" value="<?=$sx->poplatek_za_zalozeni_turnaje?>" /></td>
			<td><input type="text" class="width-72" name="procenta_pro_zakladatele" value="<?=$sx->procenta_pro_zakladatele?>" /></td>
			<td width="3%"><a title="Smazat server" href="<?=$sx->adel?>" onclick="return confirm('Opravdu si přejete smazat tuto hru z tohoto modulu?');"><i class="fa fa-trash-o"></i></a></td>
			<td style="text-align:right"><input type="submit" value="Uložit" /></td>
		  </tr>
		</form>  
	  <?}?>
	</table>
</div>
<br>
