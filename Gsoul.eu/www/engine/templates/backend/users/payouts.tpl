<h1>Výplaty uživatelů</h1>
<br>
<?if(getget('message','')=='payed'){?><h2>Platba nastavena jako uhrazena.</h2><br><?}?>
<?if(getget('message','')=='not-payed'){?><h2>Platba stornována, kredit byl uživateli vrácen zpět.</h2><br><?}?>
<?if(getget('message','')=='not-found'){?><h2>Platba obsahuje chybu, operace se nezdařila.</h2><br><?}?>
<div class="overflow-wrap">
	<table>
	  <tr><th>Datum a čas</th><th>Uživatel</th><th>PayPal E-mail</th><th>Kredit</th><th colspan="3">Částka</th></tr>
	  <?if(count($list)>0){
		foreach($list as $l){?>  
		  <tr>
			<td width="10%"><b><?=strftime('%d.%m.%Y %H:%M:%S',$l->dateTime)?></b></td>              
			<td><a href="<?=$l->auser?>" target="_blank">#<?=$l->userId?> <?=$l->user->osloveni?> <?=$l->user->jmeno?> <?=$l->user->prijmeni?> <?=$l->user->firma?></a></td>  
			<td><?=$l->paymentId?></td>
			<td><?=round($l->credit,2)?>&nbsp;$</td> 
			<td><?=round($l->cost,2).'&nbsp;'.$l->currency?></td>       
			<td width="3%"><a title="Stornovat" style="color:#CC0000;" href="<?=$l->anotpay?>" onclick="return confirm('Opravdu si přejete stornovat tuto platbu?');">STORNOVAT</a></td>
			<td width="3%"><a title="Uhradit" style="color:#00CC00;" href="<?=$l->apay?>" onclick="return confirm('Opravdu jste uhradili tuto platbu?');">UHRAZENO</a></td>         
		  </tr>              
		<?}?>
		<tr> 
		  <td colspan="7" align="center">
			<a class="PageAnchors" title="Předchozí strana" href="<?=$paginnator['prew'];?>" ><i class="fa fa-arrow-left"></i></a>     
			<?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
			  <?if($kp>(getget('page','0')-7)&&$kp<(getget('page','0')+9)){?>
			  <a <?if(getget('page','0')==($kp-1)){?>class="activeLink"<?}?> href="<?=$vp?>"><?=$kp?></a>
			  <?}?>
			<?}}?>      
			<a class="PageAnchors" title="Následující strana" href="<?=$paginnator['next'];?>"><i class="fa fa-arrow-right"></i></a>
		  </td>       
		</tr>
	  <?}?>
	</table>
</div>