<h1>Odhlášené newslettery</h1>
<br>
<?if(getget('message','')=='changed'){?><h2>Stav emailu úspěšně změněn.</h2><br><?}?>
<?if(getget('message','')=='not-found'){?><h2>Neexistující záznam, operace se nezdařila.</h2><br><?}?>
<table>
  <tr>
    <td align="center"><a <?if($type==0){?>style="color:#00CC00;"<?}?> href="<?=$avse;?>"><b>Vše</b></a></td>
    <td align="center"><a <?if($type==2){?>style="color:#00CC00;"<?}?> href="<?=$anehotovo;?>"><b>Nevyřízeno</b></a></td>
    <td align="center" colspan="2"><a <?if($type==1){?>style="color:#00CC00;"<?}?> href="<?=$ahotovo;?>"><b>Vyřízeno</b></a></td>
  </tr>
  <tr><th width="33%">Datum a čas</th><th width="33%">E-mail</th><th width="33%" colspan="2">Stav</th></tr>
  <?if(count($list)>0){
    foreach($list as $l){?>  
      <tr>
        <td><b><?=strftime('%d.%m.%Y&nbsp;%H:%M:%S',$l->ts)?></b></td>                       
        <td><?=$l->email?></td>
        <td>
          <?if($l->hotovo==1){?>
            <b style="color:#00CC00;">Vyřízeno</b>
          <?}else{?>
            <b style="color:#CC0000;">Čeká na vyřízení</b>
          <?}?>
        </td>
        <td>
          <?if($l->hotovo==0){?>
            <a title="Nastavit vyřízeno" href="<?=$l->asetup?>" onclick="return confirm('Opravdu si přejete nastavit na vyřízeno?');">Nastavit vyřízeno</a>
          <?}else{?>
            <a title="Nastavit nevyřízeno" href="<?=$l->asetdown?>" onclick="return confirm('Opravdu si přejete nastavit na nevyřízeno?');">Nastavit nevyřízeno</a>
          <?}?>
        </td>               
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