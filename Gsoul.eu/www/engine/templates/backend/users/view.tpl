<h1>Detail uživatele #<?=$user->uid;?></h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis uživatelů</a><br>
  <?if($user->prava<=$prava){?><a href="<?=$aedit;?>"><i class="fa fa-pencil"></i> Editovat uživatele</a> <a href="<?=$acoins;?>"><i class="fa fa-usd"></i> Účetní zůstatek</a><?}?>
</div>
<table>
  <tr>
    <th width="25%">Jméno a příjmení</th>
    <td>
      <strong><?=$user->titul_pred.' '.$user->jmeno.' '.$user->prijmeni.' '.$user->titul_za;?></strong>       
    </td>
  </tr>
  <tr>
    <th>Společnost</th>
    <td><?=$user->firma;?></td>
  </tr>  
  <tr>
    <th>Adresa</th>
    <td><?=$user->ulice.' '.$user->cislo_popisne.'<br>'.$user->mesto.' '.$user->psc.'<br>'.$user->stat;?></td>
  </tr>
  <tr>
    <th>Telefon</th>
    <td><?=$user->telefon;?></td>
  </tr>
  <tr>
    <th>E-mail</th>
    <td><a href="mailto:<?=$user->email;?>"><?=$user->email;?></a></td>
  </tr>
  <tr>
    <th>PayPal E-mail</th>
    <td><a href="mailto:<?=$user->email_paypal;?>"><?=$user->email_paypal;?></a></td>
  </tr>
  <tr>
    <th>IČ</th>
    <td><?=$user->ico;?></td>
  </tr>
  <tr>
    <th>DIČ</th>
    <td><?=$user->dic;?></td>
  </tr>
  <tr>
    <th>Nickname</th>
    <td><?=$user->osloveni;?></td>
  </tr>
</table>
<h2>Systémové informace</h2>
<table>
  <tr>
    <th width="25%">Práva</th>
    <td>
        <?
        if($user->prava==0){
          echo 'Uživatel';
        }elseif($user->prava==1){
          echo 'Administrátor';
        }elseif($user->prava==2){
          echo 'Super administrátor';
        }else{
          echo 'Nedefinováno';
        }
        ?>
      </td>
  </tr>
  <tr>
    <th>Odebírá novinky</th>
    <td><?=AnoNe($user->odber_novinek);?></td>
  </tr>
  <tr>
    <th>Ověřen e-mail</th>
    <td><?=AnoNe($user->overen_email);?></td>
  </tr>
  <tr>
    <th>Ověřen PayPal e-mail</th>
    <td><?=AnoNe($user->overen_paypal_email);?></td>
  </tr>
  <tr>
    <th>Registrace uživatele</th>
    <td><?=TimestampToDateTime($user->registrace);?></td>
  </tr>
  <tr>
    <th>Poslední přihlášení</th>
    <td><?if($user->pocet_prihlaseni>0){echo TimestampToDateTime($user->posledni_prihlaseni).' z IP adresy '.$user->posledni_prihlaseni_ip;}?></td>
  </tr>
  <tr>
    <th>Počet přihlášení</th>
    <td><?=$user->pocet_prihlaseni;?>x</td>
  </tr>
  <tr>
    <th>Účetní zůstatek</th>
    <td><?if(in_array('users_payments_views',$thisUserRights->povoleneKody)){?><?=printcost($user->ucetni_zustatek);?> $<?}else{?>nemáte oprávnění<?}?></td>
  </tr>
  <tr>
    <th>ID uživatele</th>
    <td>#<?=($user->uid);?></td>
  </tr>
</table>
<h2>Facebook informace</h2>
<table>
  <tr>
    <th width="25%">FB ID</th>
    <td>#<?=$user->fb_id?></td>
  </tr>
  <tr>
    <th>FB Pohlaví</th>
    <td><?=$user->fb_gender;?></td>
  </tr>
  <tr>
    <th>FB Lokace</th>
    <td><?=$user->fb_locale;?></td>
  </tr>  
  <tr>
    <th>FB Profilový obrázek</th>
    <td><?if($user->fb_picture!=''){?><img src="<?=$user->fb_picture;?>" /><?}else{?>-<?}?></td>
  </tr>
  <tr>
    <th>Vlastní profilový obrázek</th>
    <td><?if($user->user_picture!=''){?><img src="/<?=$user->user_picture;?>" /> <a href="<?=$adelavatar?>" title="Odstranit" onclick="return confirm('Opravdu odstranit?');">Odstranit</a><?}else{?>-<?}?></td>
  </tr>    
</table>
