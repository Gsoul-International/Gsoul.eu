<h1>Hraní - Turnaje</h1>
<br>
<?if(getget('message','')=='not-found'){?><h2>Turnaj neexistuje.</h2><br><?}?>
<form method="post" action="<?=$afilter?>">
  <table>
    <tr>
      <th>Zobrazení</th>
      <td>
        <select style="width:100%" name="f_zobrazeni">
          <option value="0"> - Vše - </option>
          <option <?if($f_zobrazeni==1){?>selected<?}?> value="1">Zobrazovat</option>
          <option <?if($f_zobrazeni==2){?>selected<?}?> value="2">Nezobrazovat</option>
        </select>
      </td>
      <th>Hra</th>
      <td colspan="2">
        <select style="width:100%" name="f_hra">
          <?foreach($games3 as $g3k=>$g3v){?><option <?if($f_hra==$g3k){?>selected<?}?> value="<?=$g3k;?>"><?=$g3v;?></option><?}?>
        </select>
      </td>      
    </tr>
    <tr>
      <th>Dohráno</th>
      <td>
        <select style="width:100%" name="f_dohrano">
          <option value="0"> - Vše - </option>
          <option <?if($f_dohrano==1){?>selected<?}?> value="1">Dohráno</option>
          <option <?if($f_dohrano==2){?>selected<?}?> value="2">Nedohráno</option>
        </select>
      </td>
      <th>Zahájeno</th>
      <td>
        <select style="width:100%" name="f_zahajeno">
          <option value="0"> - Vše - </option>
          <option <?if($f_zahajeno==1){?>selected<?}?> value="1">Zahájeno</option>
          <option <?if($f_zahajeno==2){?>selected<?}?> value="2">Nezahájeno</option>
        </select>
      </td>
      <td style="text-align:right;"><input type="submit" value="Filtrovat" /></td>
    </tr>
  </table>
</form>
<br />
<div class="overflow-wrap">
	<table>
	  <tr><th>#ID</th><th>Hra</th><th>Typ hry</th><th>Start turnaje</th><th>Dohráno<br>Zahájeno</th><th colspan="2">Zobrazovat</th></tr>
	  <?if(count($list)>0){
		foreach($list as $l){?>  
		  <tr>
			<td width="10%"><b>#<?=$l->idc?></b></td>                      
			<td><?=$games2[$l->id_hry]?></td>			
			<td><?=$types2[$l->id_typu_hry]?></td>  			  
			<td><?=strftime('%d.%m.%Y %H:%M',$l->datum_cas_startu)?></td>
			<td>doh.:&nbsp;<?=$l->dohrano==1?'ano':'ne';?><br>zah.:&nbsp;<?=$l->zahajeno==1?'ano':'ne';?></td>  
			<td><?=$l->skryty==1?'ne':'ano';?></td>  			         
			<td width="3%"><a title="Detail turnaje" href="<?=$l->aedit?>"><i class="fa fa-pencil"></i></a></td>         
		  </tr>              
		<?}?>
		<tr> 
		  <td colspan="10" align="center">
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