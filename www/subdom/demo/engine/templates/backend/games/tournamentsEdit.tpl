<h1>Hraní - Turnaje - Detail turnaje</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis turnajů</a><br>     
</div>
<br>
<?if(getget('message','')=='chat-deleted'){?><h2>Příspěvěk chatu byl úspěšně smazán.</h2><br><?}?>
<?if(getget('message','')=='winners-not-added'){?><h2>Přerozdělení výher se nezdařilo. Zvolte prosíme všechny hráče a každého jen jednou.</h2><br><?}?>
<?if(getget('message','')=='winners-added'){?><h2>Přerozdělení výher dokončeno.</h2><br><?}?>
<table>
  <tr>
    <th width="30%">#ID</th>
    <td>#<?=$data->idt?></td>
  </tr><tr>
    <th>Modul</th>
    <td><?=$module->nazev?></td>
  </tr><tr>
    <th>Hra</th>
    <td><?=$game->nazev?></td>
  </tr><tr>
    <th>Server</th>
    <td><?=$server->nazev?></td>
  </tr><tr>
    <th>Typ hry</th>
    <td><?=$type->nazev?></td>
  </tr><tr>
    <th>Mapa</th>
    <td><?=$map->nazev?></td>
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
    <th>Datum a čas ukončení turnaje</th>
    <td><?if($data->datum_cas_konce>0){?><?=strftime('%d.%m.%Y %H:%M',$data->datum_cas_konce);?><?}else{?> - <?}?></td>
  </tr><tr>
    <th>Minimální počet hráčů</th>
    <td><?=$data->minimalni_pocet_hracu?></td>
  </tr><tr>
    <th>Maximální počet hráčů</th>
    <td><?=$data->maximalni_pocet_hracu?></td>
  </tr><tr>
    <th>Počet kol</th>
    <td><?=$data->pocet_kol?></td>
  </tr><tr>
    <th>Poznámka zakladatele</th>
    <td><?=$data->poznamka_zakladatele?></td>
  </tr><tr>
    <th>Pravidla turnaje</th>
    <td><?=$data->pravidla_turnaje_mala?></td>
  </tr><tr>
    <th>Podrobná pravidla turnaje</th>
    <td><?=$data->pravidla_turnaje_velka?></td>
  </tr><tr>
    <th>Dohráno</th>
    <td><?=$data->dohrano==0?'Ne':'Ano';?></td>
  </tr><tr>
    <th>Výsledky</th>
    <td>
      <?=str_replace("\n",'<br>',$data->poznamka_skore);?>
      <?if($data->obrazek_skore!=''){?><br><a target="_blank" href="/<?=$data->obrazek_skore?>"><img width="100%" src="/<?=$data->obrazek_skore?>"></a><br><?}?>
      <?for($aaa='a';$aaa!='k';$aaa++){?>
        <?if(file_exists('userfiles/tournaments_score/screen-'.$data->idt.'-'.$aaa.'.jpg')){?>
          <a target="_blank" href="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.jpg"><img style="float:left;margin:3px;" width="170" height="170" src="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.jpg"></a>
        <?}?>
        <?if(file_exists('userfiles/tournaments_score/screen-'.$data->idt.'-'.$aaa.'.jpeg')){?>
          <a target="_blank" href="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.jpeg"><img style="float:left;margin:3px;" width="170" height="170" src="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.jpeg"></a>
        <?}?>
        <?if(file_exists('userfiles/tournaments_score/screen-'.$data->idt.'-'.$aaa.'.png')){?>
          <a target="_blank" href="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.png"><img style="float:left;margin:3px;" width="170" height="170" src="/userfiles/tournaments_score/screen-<?=$data->idt?>-<?=$aaa?>.png"></a>
        <?}?>
      <?}?>
    </td>
  </tr><tr>
    <th>Odměněno</th>
    <td><?=$data->prerozdelene_vyhry==0?'Ne':'Ano';?></td>
  </tr>
  <?if($data->dohrano==1){?>
  <tr>
    <th>Přeřozdělení výher</th>
    <td>
      <?if($data->prerozdelene_vyhry==1){?>
        <?if($winnersData->idgtw>0){?>
          <?if($winnersData->idu_misto_1>0){?>1.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_1]->osloveni?> (<?=printcost($winnersData->coins_1);?> $)<br><?}?>
          <?if($winnersData->idu_misto_2>0){?>2.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_2]->osloveni?> (<?=printcost($winnersData->coins_2);?> $)<br><?}?>
          <?if($winnersData->idu_misto_3>0){?>3.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_3]->osloveni?> (<?=printcost($winnersData->coins_3);?> $)<br><?}?>
          <?if($winnersData->idu_misto_4>0){?>4.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_4]->osloveni?> (<?=printcost($winnersData->coins_4);?> $)<br><?}?>
          <?if($winnersData->idu_misto_5>0){?>5.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_5]->osloveni?> (<?=printcost($winnersData->coins_5);?> $)<br><?}?>
          <?if($winnersData->idu_misto_6>0){?>6.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_6]->osloveni?> (<?=printcost($winnersData->coins_6);?> $)<br><?}?>
          <?if($winnersData->idu_misto_7>0){?>7.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_7]->osloveni?> (<?=printcost($winnersData->coins_7);?> $)<br><?}?>
          <?if($winnersData->idu_misto_8>0){?>8.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_8]->osloveni?> (<?=printcost($winnersData->coins_8);?> $)<br><?}?>
          <?if($winnersData->idu_misto_9>0){?>9.&nbsp;&nbsp;místo: <?=$users[$winnersData->idu_misto_9]->osloveni?> (<?=printcost($winnersData->coins_9);?> $)<br><?}?>
          <?if($winnersData->idu_misto_10>0){?>10.&nbsp;místo: <?=$users[$winnersData->idu_misto_10]->osloveni?> (<?=printcost($winnersData->coins_10);?> $)<br><?}?>
          
        <?}?>
      <?}else{?>
        <form method="post" action="<?=$aaddwinners?>">
          <?for($i=1;$i<=$winners_count;$i++){?>
            <?=$i;?>. místo:
            <select name="position_<?=$i?>">
              <option value="0"> - Zvolte hráče - </option>
              <?foreach($players as $pl){?><option value="<?=$pl->id_hrace?>"><?=$users[$pl->id_hrace]->osloveni?></option><?}?>
            </select>
            <br>
          <?}?>
          <br>
          <input type="submit" onclick="return confirm('Skutečně jste správně nastavili výhry? tento krok nelze vrátit zpět!');" value="Přerozdělit výhry ->">
        </form>
      <?}?>
    </td>
  </tr>
  <tr>
    <th>Získané finance</th>
    <td>Hráči <?=printcost(count($players)*$data->cena);?> + založení turnaje <?=printcost($moduleGames->poplatek_za_zalozeni_turnaje)?> = celkem <b><?=printcost((count($players)*$data->cena)+$moduleGames->poplatek_za_zalozeni_turnaje);?></b> </td>
  </tr>
  <tr>
    <th>Odevzdané finance</th> 
    <td>Hráči <?=printcost($winnersData->coins_1+$winnersData->coins_2+$winnersData->coins_3+$winnersData->coins_4+$winnersData->coins_5+$winnersData->coins_6+$winnersData->coins_7+$winnersData->coins_8+$winnersData->coins_9+$winnersData->coins_10);?> + odměna zakladateli <?=printcost($winnersData->odmena_zakladateli)?> = celkem <b><?=printcost($winnersData->coins_1+$winnersData->coins_2+$winnersData->coins_3+$winnersData->coins_4+$winnersData->coins_5+$winnersData->coins_6+$winnersData->coins_7+$winnersData->coins_8+$winnersData->coins_9+$winnersData->coins_10+$winnersData->odmena_zakladateli);?></b> </td>
  </tr>
  <tr>
    <th>Výdělek celkem</th>
    <td><b><?=printcost(((count($players)*$data->cena)+$moduleGames->poplatek_za_zalozeni_turnaje)-($winnersData->coins_1+$winnersData->coins_2+$winnersData->coins_3+$winnersData->coins_4+$winnersData->coins_5+$winnersData->coins_6+$winnersData->coins_7+$winnersData->coins_8+$winnersData->coins_9+$winnersData->coins_10+$winnersData->odmena_zakladateli));?></b> </td>
  </tr>
  <?}?>  
  <tr>
    <th>Zakladatel turnaje</th>
    <td><?=$users[$data->id_uzivatele]->osloveni?> <a href="<?=$data->adetailcreator?>" target="_blank">Zobrazit -></a></td>
  </tr><tr>
    <th>Přihlášení hráči</th>
    <td><?foreach($players as $pl){?>Nick: <?=$users[$pl->id_hrace]->osloveni?>, <a href="<?=$pl->adetail?>" target="_blank">Zobrazit -></a><br><?}?></td>
  </tr>  
</table>
<br>
<h2>Chat</h2>
<table>
  <tr><th width="20%">Datum a čas</th><th width="15%">Hráč</th><th colspan="2">Text</th></tr>
  <?if(isset($chatData)&&count($chatData)>0){foreach($chatData as $chD){?>
    <tr>
      <td><?=strftime('%d.%m.%Y %H:%M:%S',$chD->ts);?></td>
      <td><?=$users[$chD->id_hrace]->osloveni;?></td>
      <td><?=$chD->obsah;?></td>
      <td width="3%"><a title="Smazat" href="<?=$chD->adel?>" onclick="return confirm('Opravdu si přejete smazat tento příspěvek?');"><i class="fa fa-trash-o"></i></a></td>
    </tr>
  <?}}?>
</table>