<h1>Hraní - Zápasy</h1>
<br>
<?if(getget('message','')=='not-found'){?><h2>Hra neexistuje.</h2><br><?}?>
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
      <th>Odměněno</th>
      <td>
        <select style="width:100%" name="f_odmeneno">
          <option value="0"> - Vše - </option>
          <option <?if($f_odmeneno==1){?>selected<?}?> value="1">Odměněno</option>
          <option <?if($f_odmeneno==2){?>selected<?}?> value="2">Neodměněno</option>
        </select>
      </td>
      <td style="text-align:right;"><input type="submit" value="Filtrovat" /></td>
    </tr>
  </table>
</form>
<br />
<div class="overflow-wrap">
	<table>
	  <tr><th>#ID</th><!-- <th>Modul</th> --><th>Hra</th><th>Typ hry</th><th>Start Zápasu</th><th>Dohráno<br>Odměněno</th><th colspan="2">Zobrazovat</th></tr>
	  <?if(count($list)>0){
		foreach($list as $l){?>  
		  <tr>
			<td width="10%"><b>#<?=$l->idt?></b></td>              
			<!-- <td><?=$modules2[$l->id_modulu]?></td>   -->
			<td><?=$games2[$l->id_hry]?></td>		
			<td><?=$types2[$l->id_typu_hry]?></td>  			  
			<td><?=strftime('%d.%m.%Y %H:%M',$l->datum_cas_startu)?></td>
			<td>doh.:&nbsp;<?=$l->dohrano==1?'ano':'ne';?><br>odm.:&nbsp;<?=$l->prerozdelene_vyhry==1?'ano':'ne';?></td>  
			<td><?=$l->skryty==1?'ne':'ano';?></td>  			      
			<td width="3%"><a title="Detail zápasu" href="<?=$l->aedit?>"><i class="fa fa-pencil"></i></a></td>         
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