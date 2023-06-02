<h1>Hraní - Hry</h1>
<br>
<?if(getget('message','')=='not-found'){?><h2>Hra neexistuje.</h2><br><?}?>
<?if(getget('message','')=='created'){?><h2>Hra úspěšně přidána.</h2><br><?}?>
<?if(getget('message','')=='not-created'){?><h2>Hru se nepodařilo vytvořit - musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='deleted'){?><h2>Hra úspěšně smazána.</h2><br><?}?>
<table>
  <tr><th>#ID</th><th>Název</th><th colspan="3">Aktivní</th></tr>
  <?if(count($list)>0){
    foreach($list as $l){?>  
      <tr>
        <td width="10%"><b>#<?=$l->idg?></b></td>              
        <td><?=$l->nazev?></td>  
        <td><?=$l->aktivni==1?'Ano':'Ne'?></td>      
        <td width="3%"><?/*<a title="Smazat hru" href="<?=$l->adel? >" onclick="return confirm('Opravdu si přejete smazat tuto hru?');"><i class="fa fa-trash-o"></i></a>*/?></td>
        <td width="3%"><a title="Editovat hru" href="<?=$l->aedit?>"><i class="fa fa-pencil"></i></a></td>         
      </tr>              
    <?}?>
    <tr> 
      <td colspan="5" align="center">
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
<br>
<form method="post" action="<?=$anew?>">
  <table>
    <tr><th colspan="3">Přidat hru (název je povinný):</th></tr>
    <tr><td>Název: <input class="width-400" type="text" name="nazev" value="" /></td><td> Aktivní: <input type="checkbox" name="aktivni" value="1" checked /></td><td style="text-align:right"><input type="submit" value="Přidat" /></td></tr>
  </table>
</form>