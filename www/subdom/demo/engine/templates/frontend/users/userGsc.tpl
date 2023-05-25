<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Účetní zůstatek</a></li>     
</ul> 
<h1>Účetní zůstatek</h1>
<p>Aktuální stav uživatelského konta: <b><?=printcost($user->ucetni_zustatek)?> $.</b></p>
<?/*<h2>Dobití kreditu</h2>
<table width="100%">
  <tr>
    <td><a href="<?=$apayinusd? >">Dobít 10.00 $ kreditu za 12.10 $ vč. DPH</a></td>
    <td><a href="<?=$apayineur? >">Dobít 10.00 $ kreditu za 10.20 € vč. DPH</a></td>
    <td><a href="<?=$apayinczk? >">Dobít 10.00 $ kreditu za 255.00 Kč vč. DPH</a></td>
  </tr>  
</table>
<?if($user->ucetni_zustatek>=10){? >
  <h2>Vyplacení kreditu</h2>
  <table width="100%">
    <tr>
      <td><a href="<?=$apayoutusd? >">Vyplatit 10.00 $ kreditu v 10.00 $</a></td>
      <td><a href="<?=$apayouteur? >">Vyplatit 10.00 $ kreditu v 8.40 €</a></td>
      <td><a href="<?=$apayoutczk? >">Vyplatit 10.00 $ kreditu v 210.00 Kč</a></td>
    </tr> 
  </table> 
<?}*/?>
<br />
<a href="<?=$apaypal?>">Zobrazit všechna dobití a vyplacení kreditu -></a>
<?if(count($coins)>0){?>
  <h2>Historie uživatelského konta</h2>
  <br>
  <table width="100%">
    <tr><th>Datum a čas</th><th>Operace</th><th>Částka</th><th>Důvod</th></tr>  
    <?foreach($coins as $cs){?>
      <tr>
        <td><?=strftime('%d.%m.%Y %H:%M',$cs->datum_cas)?></td>
        <td><?if($cs->coins<0){?><span style="color:#800000;">Výběr</span><?}else{?><span style="color:#008000;">Vklad</span><?}?></td>
        <td <?if($cs->coins<0){?>style="color:#800000;"<?}else{?>style="color:#008000;"<?}?> ><strong><?if($cs->coins>=0){?>+<?}?><?=printcost($cs->coins)?> $<strong></td>        
        <td><?=$cs->duvod?></td>    
      </tr>
    <?}?>      
    <tr>     
  </table>  
  <?if(count($paginnator)>3){?>
    <ul class="pagination align-center">
      <li><a title="Předchozí strana" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
      <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
        <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
        <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
        <?}?>
      <?}}?>      
      <li><a title="Následující strana" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
    </ul>
  <?}?>
<?}?>