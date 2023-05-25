<h1>Hraní - Zápasy - Detail zápasu</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis zápasů</a><br>     
</div>
<br>
<?if(getget('message','')=='chat-deleted'){?><h2>Příspěvěk chatu byl úspěšně smazán.</h2><br><?}?>
<?if(getget('message','')=='screen-deleted'){?><h2>Screnshot / config byl úspěšně smazán.</h2><br><?}?>
<?if(getget('message','')=='winners-not-added'){?><h2>Přerozdělení výher se nezdařilo. Zvolte prosíme všechny hráče a každého jen jednou.</h2><br><?}?>
<?if(getget('message','')=='winners-added'){?><h2>Přerozdělení výher dokončeno.</h2><br><?}?>
<?if(getget('message','')=='viewing-saved'){?><h2>Zobrazení zápasu uloženo.</h2><br><?}?>
<table>
  <tr>
    <th width="30%">#ID</th>
    <td>#<?=$data->idt?> <a href="<?=$aview;?>" target="_blank">Zobrazit zápas na webu -></a></td>
  </tr><tr>
    <th>Skrýt zápas</th>
    <td>
      <form method="post" action="<?=$achangeView?>">
      <select name="skryty" style="width:450px">
        <option <?if($data->skryty==0){?>selected<?}?> value="0">Zobrazovat - zápas je vidět ve výpisu zápasů a turnajů na webu</option>
        <option <?if($data->skryty==1){?>selected<?}?> value="1">Nezobrazovat - zápas je skrytý - není vidět - ve výpisu zápasů a turnajů na webu</option>
      </select>
      <input type="submit" value="Uložit" />
      </form>
    </td>
  </tr>
  <?if($data->id_cupu>0){?>
    <tr><th>TURNAJ</th><td><?=$cup->titulek_cupu?> - <?=$data->id_kola_cupu?>.<?=$data->id_zapasu_cupu?> <a href="<?=$aviewCup;?>" target="_blank">Zobrazit turnaj na webu -></a></td></tr>
  <?}?>
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
    <th>Datum a čas vytvoření zápasu</th>
    <td><?=strftime('%d.%m.%Y %H:%M',$data->datum_vytvoreni);?></td>
  </tr><tr>
    <th>Datum a čas zahájení zápasu</th>
    <td><?=strftime('%d.%m.%Y %H:%M',$data->datum_cas_startu);?></td>
  </tr><tr>
    <th>Datum a čas ukončení zápasu</th>
    <td><?if($data->datum_cas_konce>0){?><?=strftime('%d.%m.%Y %H:%M',$data->datum_cas_konce);?><?}else{?> - <?}?></td>
  </tr><tr>
    <th>Minimální počet hráčů</th>
    <td><?=$data->minimalni_pocet_hracu?></td>
  </tr><tr>
    <th>Maximální počet hráčů</th>
    <td><?=$data->maximalni_pocet_hracu?></td>
  </tr>
  <?if($data->hraji_tymy==1){?>
    <tr>
      <th>Minimální počet týmů</th>
      <td><?=$data->minimalni_pocet_tymu?></td>
    </tr>
    <tr>
      <th>Maximální počet týmů</th>
      <td><?=$data->maximalni_pocet_tymu?></td>
    </tr>
    <tr>
      <th>Počet hráčů na tým</th>
      <td><?=ceil($data->maximalni_pocet_hracu/$data->maximalni_pocet_tymu)?></td>
    </tr>
  <?}?>
  <tr>
    <th>Poznámka zakladatele</th>
    <td><?=$data->poznamka_zakladatele?></td>
  </tr><tr>
    <th>Pravidla zápasu</th>
    <td><?=$data->pravidla_turnaje_mala?></td>
  </tr><tr>
    <th>Podrobná pravidla zápasu</th>
    <td><?=$data->pravidla_turnaje_velka?></td>
  </tr><tr>
    <th>Dohráno</th>
    <td><?=$data->dohrano==0?'Ne':'Ano';?></td>
  </tr><tr>
    <th>Poznámka k výsledkům</th>
    <td>
      <?=str_replace("\n",'<br>',$data->poznamka_skore);?>        
    </td>
  </tr><tr>
    <th>Odměněno</th>
    <td><?=$data->prerozdelene_vyhry==0?'Ne':'Ano';?></td>
  </tr>
  <?if($data->dohrano==1&&$data->neni_odmenovan==0){?>
  <tr>
    <th>Přeřozdělení výher</th>
    <td>
      <?if($data->prerozdelene_vyhry==1){?>
        <?if($winnersData->idgtw>0){?>
          <?if($data->hraji_tymy==1){?>
            <?if($winnersData->idu_misto_1>0){?>1.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_1]->nazev?> (<?=printcost($winnersData->coins_1);?> $)<br><?}?>
            <?if($winnersData->idu_misto_2>0){?>2.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_2]->nazev?> (<?=printcost($winnersData->coins_2);?> $)<br><?}?>
            <?if($winnersData->idu_misto_3>0){?>3.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_3]->nazev?> (<?=printcost($winnersData->coins_3);?> $)<br><?}?>
            <?if($winnersData->idu_misto_4>0){?>4.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_4]->nazev?> (<?=printcost($winnersData->coins_4);?> $)<br><?}?>
            <?if($winnersData->idu_misto_5>0){?>5.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_5]->nazev?> (<?=printcost($winnersData->coins_5);?> $)<br><?}?>
            <?if($winnersData->idu_misto_6>0){?>6.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_6]->nazev?> (<?=printcost($winnersData->coins_6);?> $)<br><?}?>
            <?if($winnersData->idu_misto_7>0){?>7.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_7]->nazev?> (<?=printcost($winnersData->coins_7);?> $)<br><?}?>
            <?if($winnersData->idu_misto_8>0){?>8.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_8]->nazev?> (<?=printcost($winnersData->coins_8);?> $)<br><?}?>
            <?if($winnersData->idu_misto_9>0){?>9.&nbsp;&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_9]->nazev?> (<?=printcost($winnersData->coins_9);?> $)<br><?}?>
            <?if($winnersData->idu_misto_10>0){?>10.&nbsp;místo: <?=$loggedTeams2[$winnersData->idu_misto_10]->nazev?> (<?=printcost($winnersData->coins_10);?> $)<br><?}?>
          <?}else{?>
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
        <?}?>
      <?}else{?>
        <form method="post" action="<?=$aaddwinners?>">
          <?for($i=1;$i<=$winners_count;$i++){
            $preSname='idu_'.$i;
            $prescore=$prescores->$preSname;            
            ?>         
            <?=$i;?>. místo:
            <select name="position_<?=$i?>">              
              <?if($data->hraji_tymy==1){?>
                <option value="0"> - Zvolte tým - </option>
                <?foreach($loggedTeams as $lt){?><option <?if($prescore==$lt->idt){?>selected<?}?> value="<?=$lt->idt?>"><?=$lt->nazev?></option><?}?>
              <?}else{?>
                <option value="0"> - Zvolte hráče - </option>
                <?foreach($players as $pl){?><option <?if($prescore==$pl->id_hrace){?>selected<?}?> value="<?=$pl->id_hrace?>"><?=$users[$pl->id_hrace]->osloveni?></option><?}?>
              <?}?>
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
    <td>Hráči <?=printcost(count($players)*$data->cena);?> + založení zápasu <?=printcost($moduleGames->poplatek_za_zalozeni_turnaje)?> = celkem <b><?=printcost((count($players)*$data->cena)+$moduleGames->poplatek_za_zalozeni_turnaje);?></b> </td>
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
  <?if($data->dohrano==1&&$data->neni_odmenovan==1&&$data->pocet_postupujicich>0){?>
    <tr>
      <th>Přeřozdělení postupujících</th>
      <td>
        <?if($data->prerozdelene_vyhry==1){?>  
          <?for($i=1;$i<=$data->pocet_postupujicich;$i++){?>
		        <?=$i?>. postupující:                                              
            <?if($data->hraji_tymy==1){?>
              <?=$loggedTeams2[$postupujici_tymy[$i-1]]->nazev;?>
            <?}else{?>
              <?=$users[$postupujici_hraci[$i-1]]->osloveni?>
            <?}?> 
            <br />                                          
		      <?}?>                             
        <?}else{?>
          <form method="post" action="<?=$aaddwinners2?>">
            <?for($i=1;$i<=$data->pocet_postupujicich;$i++){?>
              <?=$i;?>. místo:
              <select name="position_<?=$i?>">              
                <?if($data->hraji_tymy==1){?>
                  <option value="0"> - Zvolte tým - </option>
                  <?foreach($loggedTeams as $lt){?><option value="<?=$lt->idt?>"><?=$lt->nazev?></option><?}?>
                <?}else{?>
                  <option value="0"> - Zvolte hráče - </option>
                  <?foreach($players as $pl){?><option value="<?=$pl->id_hrace?>"><?=$users[$pl->id_hrace]->osloveni?></option><?}?>
                <?}?>
              </select>
              <br>
            <?}?>
            <br>
            <input type="submit" onclick="return confirm('Skutečně jste správně nastavili postupující? tento krok nelze vrátit zpět!');" value="Přerozdělit postupující ->">
          </form>
        <?}?>
      </td>
    </tr>  
  <?}?>  
  <tr>
    <th>Zakladatel zápasu</th>
    <td><?=$users[$data->id_uzivatele]->osloveni?> <a href="<?=$data->adetailcreator?>" target="_blank">Zobrazit -></a></td>
  </tr>
  <tr>
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
<h2>Banner nad zápasem</h2>
<form method="post" action="<?=$achangeBanner?>">
  <?=$banner?>
  <input type="submit" value="Uložit" />
</form>
<br>
<h2>Screenshoty a configy</h2>
<table>
  <tr><th width="20%">Datum a čas</th><th width="15%">Nahrál</th><th colspan="3">Typ souboru</th></tr>
  <?if(isset($screens)&&count($screens)>0){foreach($screens as $sc){?>
    <tr>
      <td><?=strftime('%d.%m.%Y %H:%M:%S',$sc->datum_cas);?></td>
      <td><?=$users[$sc->idu]->osloveni;?><?if($data->hraji_tymy==1&&$users[$sc->idu]->osloveni==''){?><?=$usersA[$sc->idu]->osloveni;?><?}?></td>
      <td><?=$sc->typ==0?'Screenshot':'Config';?></td>
      <td><a target="_blank" href="/<?=$sc->cesta?>" download >Download</a></td>
      <td width="3%"><a title="Smazat" href="<?=$sc->adel?>" onclick="return confirm('Opravdu si přejete smazat tento příspěvek?');"><i class="fa fa-trash-o"></i></a></td>
    </tr>
  <?}}?>
</table>
<br>
<h2>Chat</h2>
<table>
  <tr><th width="20%">Datum a čas</th><th width="15%">Hráč</th><th colspan="2">Text</th></tr>
  <?if(isset($chatData)&&count($chatData)>0){foreach($chatData as $chD){?>
    <tr>
      <td><?=strftime('%d.%m.%Y %H:%M:%S',$chD->ts);?></td>
      <td><?=$users[$chD->id_hrace]->osloveni;?><?if($data->hraji_tymy==1&&$users[$chD->id_hrace]->osloveni==''){?><?=$usersA[$chD->id_hrace]->osloveni;?><?}?></td>     
      <td><?=$chD->obsah;?></td>
      <td width="3%"><a title="Smazat" href="<?=$chD->adel?>" onclick="return confirm('Opravdu si přejete smazat tento příspěvek?');"><i class="fa fa-trash-o"></i></a></td>
    </tr>
  <?}}?>
</table>