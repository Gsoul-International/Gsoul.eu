<h1>Uživatelé</h1>
<div class="BackendBack">
  &nbsp;<br>
  <a href="<?=$anew;?>"><i class="fa fa-user-plus"></i> Přidat uživatele</a><br>  
</div>
<?if(getget('message','')=='user-not-found'){?><h2>Uživatel #<?=(int)getget('uid','');?> v systému neexistuje.</h2><?}?>
<?if(getget('message','')=='user-not-found-2'){?><h2>Uživatele se nepodařilo nalézt, uživatel neexistuje.</h2><?}?>
<?if(getget('message','')=='user-deleted'){?><h2>Uživatel #<?=(int)getget('uid','');?> úspěšně smazán.</h2><?}?>
<table>
  <tr><th colspan="26" class="center">Filtr dle počátečního písmene příjmení, jména, společnosti, nickname nebo e-mailu</th></tr>
  <tr>
    <?foreach($filtr as $f){?>
      <td class="BackendCenter" <?if($f->nazev!='Vše'){?>width="26"<?}?> ><a href="<?=$f->url?>" <?if($f->active==1){?>class="activeLink"<?}?> ><?=$f->nazev?></a></td>    
    <?}?>
  </tr>
</table>
<br />
<table>
  <?if(count($users)==0){?>
    <tr><th class="BackendCenter">Žádní uživatelé nebyli nalezeni</th></tr>
  <?}else{?>
    <tr>
      <th>ID</th>
      <th>Uživatel</th>
      <th>Nickname</th>
      <th>$</th>
      <th>Práva</th>
      <th>Poslední přihlášení</th>
      <th colspan="4" width="11%" style="text-align:right;">
        <a <?if(getget('order','')!='uid'&&getget('order','')!='last_access'){?>class="activeLink"<?}?> href="<?=$order_1;?>" title="Řadit dle příjmení a jména">
          <i class="fa fa-sort-alpha-asc"></i>
        </a>    
        <a <?if(getget('order','')=='uid'){?>class="activeLink"<?}?> href="<?=$order_2;?>" title="Řadit dle ID">
          <i class="fa fa-sort-numeric-asc"></i>
        </a>  
        <a <?if(getget('order','')=='last_access'){?>class="activeLink"<?}?> href="<?=$order_3;?>" title="Řadit dle posledního přihlášení">
          <i class="fa fa-sort-amount-desc"></i>
        </a>
      </th>
    </tr>
    <?foreach($users as $u){?>
      <tr>
        <td><strong>#<?=$u->uid;?></strong></td>
        <td><?=$u->titul_pred.' '.$u->jmeno.' '.$u->prijmeni.' '.$u->titul_za?> <?=$u->firma;?></td>
        <td><strong><?=$u->osloveni;?></strong></td>
        <td><b><?=printcost($u->ucetni_zustatek);?></b></td>
        <td>
          <?
          if($u->prava==0){
            echo 'Uživatel';
          }elseif($u->prava==1){
            echo 'Administrátor';
          }elseif($u->prava==2){
            echo 'Super administrátor';
          }else{
            echo 'Nedefinováno';
          }
          ?>
        </td>
        <td><?if($u->pocet_prihlaseni>0){echo TimestampToDateTime($u->posledni_prihlaseni);}?></td>      
        <?if($prava<$u->prava){?>
          <td colspan="2"></td>
        <?}else{?>   
          <td><a title="Smazat uživatele" href="<?=$u->adel;?>" onclick="return confirm('Opravdu si přejete smazat uživatele <?=trim(stripAllSlashes($u->titul_pred.' '.$u->jmeno.' '.$u->prijmeni.' '.$u->titul_za));?>?');"><i class="fa fa-trash-o"></i></a></td> 
          <td><a title="Editovat uživatele" href="<?=$u->aedit;?>"><i class="fa fa-pencil"></i></a></td>
        <?}?>
        <td><a title="Detail uživatele" href="<?=$u->ainfo;?>"><i class="fa fa-info"></i></a></td>
        <td><a title="Účetní zůstatek uživatele" href="<?=$u->acoins;?>"><i class="fa fa-usd"></i></a></td>
      </tr>
    <?}?>  
    <tr> 
      <td colspan="10" align="center">
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
  <?}?>
</table>
<br />
<table style="border:none;">
  <tr>
    <td style="border:none;" width="45%">
      <form action="<?=$agobyid?>" method="post">
        <table>
          <tr><th class="center">Přejít do detailu uživatele dle ID</th></tr>
          <tr>
            <td align="center">
              ID: 
              <input class="width-170" type="text" size="25" name="data" value="" />
              <input type="submit" value="Přejít">
            </td>
          </tr>
        </table>
      </form>
    </td>
    <td style="border:none;">&nbsp;</td>
    <td style="border:none;" width="45%">
      <form action="<?=$agobyemail?>" method="post">
        <table>
          <tr><th class="center">Přejít do detailu uživatele dle E-mailu</th></tr>
          <tr>
            <td align="center">
              E-mail:
              <input class="width-170" type="text" size="25" name="data" value="" />
              <input type="submit" value="Přejít">
            </td>
          </tr>
        </table>
      </form>
    </td>    
  </tr>
</table>