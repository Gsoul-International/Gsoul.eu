<h1>Hraní - Turnaje - Detail turnaje</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis turnajů</a><br>     
</div>
<br>
<?if(getget('message','')=='viewing-saved'){?><h2>Zobrazení turnaje uloženo.</h2><br><?}?>
<table>
  <tr>
    <th width="30%">#ID</th>
    <td>#<?=$data->idc?> <a href="<?=$aview;?>" target="_blank">Zobrazit turnaj na webu -></a></td>
  </tr><tr>
    <th>Skrýt turnaj</th>
    <td>
      <form method="post" action="<?=$achangeView?>">
      <select name="skryty" style="width:450px">
        <option <?if($data->skryty==0){?>selected<?}?> value="0">Zobrazovat - turnaj je vidět ve výpisu zápasů a turnajů na webu</option>
        <option <?if($data->skryty==1){?>selected<?}?> value="1">Nezobrazovat - turnaj je skrytý - není vidět ve výpisu zápasů a turnajů na webu</option>
      </select>
      <input type="submit" value="Uložit" />
      </form>
    </td>
  </tr><tr>
    <th>Titulek turnaje</th>
    <td><?=$data->titulek_cupu?></td>
  </tr>  
  <tr>
    <th>Hra</th>
    <td><?=$game->nazev?></td>
  </tr><tr>
    <th>Typ hry</th>
    <td><?=$type->nazev?></td>
  </tr><tr>
    <th>Hra na týmy</th>
    <td><?=$data->hraji_tymy==1?'Ano':'Ne';?></td>
  </tr><tr>
    <th>Vstupní poplatek</th>
    <td><?=printcost($data->cena)?> $</td>
  </tr><tr>
    <th>Datum a čas vytvoření turnaje</th>
    <td><?=strftime('%d.%m.%Y %H:%M',$data->datum_vytvoreni);?></td>
  </tr><tr>
    <th>Datum a čas zahájení turnaje</th>
    <td><?=strftime('%d.%m.%Y %H:%M',$data->datum_cas_startu);?></td>
  </tr><tr>
    <th>Minimální počet hráčů (týmů)</th>
    <td><?=$data->minimalni_pocet_hracutymu?></td>
  </tr><tr>
    <th>Maximální počet hráčů (týmů)</th>
    <td><?=$data->maximalni_pocet_hracutymu?></td>
  </tr>
  <tr>
    <th>Ideální počet hráčů (týmů) na zápas</th>
    <td><?=$data->idealni_pocet_hracutymu_na_turnaj?></td>
  </tr>
  <tr>
    <th>Počet postupujících hráčů (týmů) v zápasu</th>
    <td><?=$data->pocet_postupujicich_hracutymu?></td>
  </tr>
  <tr>
    <th>Ideální počet hráčů v týmech</th>
    <td><?=$data->idealni_pocet_hracu_v_tymu?></td>
  </tr>
  <tr>
    <th>Poznámka zakladatele</th>
    <td><?=$data->poznamka_zakladatele?></td>
  </tr><tr>
    <th>Pravidla turnaje</th>
    <td><?=$data->pravidla_mala?></td>
  </tr><tr>
    <th>Podrobná pravidla turnaje</th>
    <td><?=$data->pravidla_velka?></td>
  </tr><tr>
    <th>Dohráno</th>
    <td><?=$data->dohrano==0?'Ne':'Ano';?></td>
  </tr><tr>
    <th>Zahájeno</th>
    <td><?=$data->zahajeno==0?'Ne':'Ano';?></td>
  </tr> 
  <tr>
    <th>Zakladatel turnaje</th>
    <td><?=$users[$data->id_uzivatele]->osloveni?> <a href="<?=$data->adetailcreator?>" target="_blank">Zobrazit -></a></td>
  </tr><tr>
    <th>Přihlášení hráči</th>
    <td>
      <?foreach($players as $pl){?>
        <?if($data->hraji_tymy==1){?>
          Tým: <?=$loggedTeams2[$pl->id_tymu]->nazev?>,
        <?}?>
        Nick: <?=$users[$pl->id_hrace]->osloveni?> (<?=$pl->nick?>), 
        <a href="<?=$pl->adetail?>" target="_blank">Zobrazit -></a>
        <br>
      <?}?>
    </td>
  </tr>
  <?if($data->hraji_tymy==1){?>
    <tr>
      <th>Přihlášení náhradníci</th>
      <td>
        <?foreach($alters as $at){?>          
          Tým: <?=$loggedTeams2[$at->id_tymu]->nazev?>,          
          Nick: <?=$usersA[$at->id_hrace]->osloveni?> (<?=$at->nick?>), 
          <a href="<?=$at->adetail?>" target="_blank">Zobrazit -></a>
          <br>
        <?}?>
      </td>
    </tr> 
  <?}?>     
</table>
<br>
<h2>Banner nad turnajem</h2>
<form method="post" action="<?=$achangeBanner?>">
  <?=$banner?>
  <input type="submit" value="Uložit" />
</form>
<br>