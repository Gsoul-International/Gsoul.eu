<h1>Účetní zůstatek uživatele #<?=$user->uid;?></h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis uživatelů</a><br>
  <?if($user->prava<=$prava){?><a href="<?=$aedit;?>"><i class="fa fa-pencil"></i> Editovat uživatele</a><?}?>
  <?if($user->prava<=$prava){?><a href="<?=$ainfo;?>"><i class="fa fa-info"></i> Zobrazit uživatele</a><?}?>
</div>
<table>
  <tr>
    <th>Nickname</th>
    <td><strong><?=$user->osloveni;?></strong></td>    
    <th>Jméno, příjmení, společnost</th>
    <td><?=$user->titul_pred.' '.$user->jmeno.' '.$user->prijmeni.' '.$user->titul_za.' '.$user->firma;?></td>
    <th>Účetní zůstatek</th>
    <td><?=printcost($user->ucetni_zustatek);?> $</td>
  </tr>
</table>
<br>
<?if(getget('message','')=='added'){?><h2>Operace s kontem uživatele proběhla v pořádku.</h2><?}?>
<?if(getget('message','')=='not-added'){?><h2>Operace s kontem uživatele se nezdařila. Musí být vyplněn důvod a částka nesmí být nulová.</h2><?}?>
<?if(in_array('users_payments_changes',$thisUserRights->povoleneKody)){?>
<form action="<?=$aadd?>" method="post">
  <table>
    <tr>
      <th>Kladná / záporná částka k přidání / odebrání $</th>
      <th colspan="2">Důvod</th>          
    </tr>
    <tr>
      <td><input type="text" name="coins" value="0,00" class="width-140" /> $</td>
      <td><input type="text" name="duvod" value="" maxlength="255" class="width-230" /></td>
      <td><input type="submit" value="Přidat / odebrat"></td>
    </tr>
  </table>
</form>
<?}?>
<?if(count($coins)>0){?>
  <h2>Historie účetního zůstatku</h2>
  <table>
    <tr><th>Datum a čas</th><th>Operace</th><th>Částka</th><th>Důvod</th></tr>  
    <?foreach($coins as $cs){?>
      <tr>
        <td><?=strftime('%d.%m.%Y %H:%M',$cs->datum_cas)?></td>
        <td><?if($cs->coins<0){?><span style="color:#800000;">Výběr</span><?}else{?><span style="color:#008000;">Vklad</span><?}?></td>
        <td <?if($cs->coins<0){?>style="color:#800000;"<?}else{?>style="color:#008000;"<?}?> ><strong><?if($cs->coins>=0){?>+<?}?><?=$cs->coins?><strong></td>        
        <td><?=$cs->duvod?></td>    
      </tr>
    <?}?>      
    <tr> 
      <td colspan="4" align="center">
        <a class="PageAnchors" title="Předchozí strana" href="<?=$paginnator['prew'];?>" >
          <i class="fa fa-arrow-left"></i>
        </a>     
        <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
          <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
          <a <?if(getget('page','0')==($kp-1)){?>class="activeLink"<?}?> href="<?=$vp?>"><?=$kp?></a>
          <?}?>
        <?}}?>      
        <a class="PageAnchors" title="Následující strana" href="<?=$paginnator['next'];?>">
          <i class="fa fa-arrow-right"></i>
        </a>
      </td>       
    </tr>    
  </table>
<?}?>
