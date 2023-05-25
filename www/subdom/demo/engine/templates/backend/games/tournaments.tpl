<h1>Hraní - Turnaje</h1>
<br>
<?if(getget('message','')=='not-found'){?><h2>Hra neexistuje.</h2><br><?}?>
<table>
  <tr><th>#ID</th><!-- <th>Modul</th> --><th>Hra</th><th>Server</th><th>Typ hry</th><th>Mapa</th><th>Start turnaje</th><th>Dohráno</th><th>Odměněno</th><th colspan="2">Počet kol</th></tr>
  <?if(count($list)>0){
    foreach($list as $l){?>  
      <tr>
        <td width="10%"><b>#<?=$l->idt?></b></td>              
        <!-- <td><?=$modules2[$l->id_modulu]?></td>   -->
        <td><?=$games2[$l->id_hry]?></td>
        <td><?=$servers2[$l->id_serveru]?></td>  
        <td><?=$types2[$l->id_typu_hry]?></td>  
        <td><?=$maps2[$l->id_mapy]?></td>  
        <td><?=strftime('%d.%m.%Y %H:%M',$l->datum_cas_startu)?></td>
        <td><?=$l->dohrano==1?'ano':'ne';?></td>  
        <td><?=$l->prerozdelene_vyhry==1?'ano':'ne';?></td>  
        <td><?=$l->pocet_kol?></td>              
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