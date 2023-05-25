<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="<?=$aback?>">Tournaments</a></li>     
  <li><a href="">Zobrazení turnaje</a></li>
</ul> 
<h1>Tournaments - Zobrazení turnaje</h1>
<?if(getget('message','')=='login-failed'){?><h2>Přihlášení do turnaje se nezdařilo. Buď nemáte dostatek financí na vstup do turnaje, nebo turnaj již dosáhl maxima hráčů.</h2><br><?}?>
<?if(getget('message','')=='login-succes'){?><h2>Vítejte v tomto turnaji.</h2><br><?}?>
<?if(getget('message','')=='chat-failed'){?><h2>Poslání zprávy do chatu selhalo.</h2><br><?}?>
<?if(getget('message','')=='chat-succes'){?><h2>Zpráva přidána do chatu.</h2><br><?}?>
<?if(getget('message','')=='tournament-end-failed'){?><h2>Ukončení turnaje se nezdařilo. Buď jste nenahráli obrázek (typu PNG/JPG/JPEG), nebo nemáte dostatečná práva ukončit turnaj.</h2><br><?}?>
<?if(getget('message','')=='tournament-end-succes'){?><h2>Ukončení turnaje proběhlo úspěšně. Obsluha v nejbližší době přerozdělí výhry.</h2><br><?}?>
<?if(getget('message','')=='tournament-extend-failed'){?><h2>Prodloužení startu turnaje se nezdařilo.</h2><br><?}?>
<?if(getget('message','')=='tournament-extend-succes'){?><h2>Prodloužení startu turnaje proběhlo úspěšně.</h2><br><?}?>
<div class="grid grid-semipadded align-center grid-server gap-top-16">
  <div class="col col-header col-2-9">Hra</div><div class="col col-header col-1-9">Server</div><div class="col col-header col-1-9">Buy in</div><div class="col col-header col-1-9">Typ hry</div><div class="col col-header col-1-9">Mapa</div><div class="col col-header col-1-9">Hráčů</div><div class="col col-header col-1-9">Počet kol</div><div class="col col-header col-1-9">Dohráno</div>
  <div class="grid grid-server-item">
    <div class="col col-2-9"><?=$game->nazev?></div>
    <div class="col col-1-9"><?=$server->nazev?></div>
    <div class="col col-1-9"><?=printcost($tournament->cena)?></div>
    <div class="col col-1-9"><?=$type->nazev?></div>
    <div class="col col-1-9"><?=$map->nazev?></div>    
    <div class="col col-1-9"><?=$tournament->minimalni_pocet_hracu?> až <?=$tournament->maximalni_pocet_hracu?></div>
    <div class="col col-1-9"><?=$tournament->pocet_kol?></div>   
    <div class="col col-1-9"><?=$tournament->dohrano==1?'Ano':'Ne';?></div>        
  </div>   
  <div class="col col-header col-3-9">Datum a čas vytvoření</div> 
  <div class="col col-header col-3-9">Datum a čas zahájení</div>   
  <div class="col col-header col-3-9">Turnaj založil</div> 
  <div class="grid grid-server-item">
    <div class="col col-3-9"><?=strftime('%d.%m.%Y %H:%M',$tournament->datum_vytvoreni);?></div>
    <div class="col col-3-9"><?=strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu);?></div>    
    <div class="col col-3-9"><?=$users[$tournament->id_uzivatele]->osloveni?></div>
  </div>
  <?if($tournament->datum_cas_konce>0&&$tournament->dohrano==1){?>
    <div class="col col-header col-1-1">Turnaj dohrán <?=strftime('%d.%m.%Y ve %H:%M',$tournament->datum_cas_konce);?></div>  
  <?}?> 
</div>
<div class="grid grid-server-detail gap-top-16">
  <div class="col col-1-3">
    <div class="grid players server-detail-section grid-semipadded">
      <div class="col col-header col-1-1">Přihlášení hráči</div>      
      <?foreach($players as $pl){?><div class="col col-1-1"><?=$users[$pl->id_hrace]->osloveni?></div><?}?>    
      <div class="col col-1-1">
        <?if(in_array($currentUserID,$playersArr)){?>
          Již jste přihlášen. 
        <?}elseif($tournament->maximalni_pocet_hracu==count($players)){?> 
          V turnaji je již přihlášen maximální počet hráčů.  
        <?}elseif($tournament->dohrano==1){?>       
          Turnaj je již dohrán.        
        <?}else{?>
          <form method="post" action="<?=$agetin;?>">
            <input class="button-<?if($zustatek>=$tournament->cena){?>green<?}else{?>red<?}?>" onclick="return confirm('Opravdu si přejete se přihlásit do tohoto turnaje? Operace je již pak nevratná.');" type="submit" value="Přihlásit se (poplatek <?=printcost($tournament->cena)?> $)" />    
          </form>
        <?}?>      
      </div>
    </div>
    <?if($currentUserID==$tournament->id_uzivatele){?>
    <div class="grid payout server-detail-section grid-semipadded">
      <div class="col col-header col-1-1">Ukončení turnaje</div>
      <div class="col col-1-1">
        <?if($tournament->dohrano==0){?>
          <?if(count($playersArr)>=$vyplata->winners_count){?>
            <form method="post" action="<?=$aendtournament;?>" enctype="multipart/form-data">
              <p>Jste zakladatelem turnaje, nahráním obrázku s výsledky turnaj ukončíte.</p> <br />   
              <b>*Hlavní screenshot s výsledky:</b> <br />            
              <input type="file" name="obrazek_skore" /> <br /> <br />
              <b>Související screenshoty:</b> <br />  
              <input type="file" name="obrazek_skore_a" /> <br />
              <input type="file" name="obrazek_skore_b" /> <br /> 
              <input type="file" name="obrazek_skore_c" /> <br /> 
              <input type="file" name="obrazek_skore_d" /> <br /> 
              <input type="file" name="obrazek_skore_e" /> <br />
              <input type="file" name="obrazek_skore_f" /> <br /> 
              <input type="file" name="obrazek_skore_g" /> <br />
              <input type="file" name="obrazek_skore_h" /> <br />
              <input type="file" name="obrazek_skore_i" /> <br /> 
              <input type="file" name="obrazek_skore_j" /> <br /> <br />                           
              <b>Výherní pořadí hráčů, poznámky:</b> <br />    
              <textarea rows="10" placeholder="Pořadí hráču a poznámka" name="poznamka_skore"></textarea>  <br />  <br />
              <input onclick="return confirm('Opravdu si přejete nahrát finální skóre? Operace je nevratná.');" type="submit" value="Nahrát skóre a ukončit tím turnaj" />
              <p>Na základě podkladů obsluha potvrdí přerozdělí výher.</p> <br />     
            </form>
          <?}else{?>
            <p>Turnaj půjde ukončit až po přihlášení <?=$vyplata->winners_count?> hráčů, aktuálně přihlášeno <?=count($playersArr)?> hráčů.</p>        
          <?}?>
        <?}else{?>
          <p>Turnaj jste již ukončili.</p> <br>
          <p>
          <?if($tournament->prerozdelene_vyhry==0){
            echo 'Obsluha v nejbližší době přerozdělí výhry.';  
          }else{
            echo 'Výhry již jsou přerozděleny.';
          }?> </p><br>
        <?}?>
      </div>
    </div>
    <?if($tournament->dohrano==0){?>
      <div class="grid payout server-detail-section grid-semipadded">
        <div class="col col-header col-1-1">Posunutí startu</div>
        <div class="col col-1-1">
          <form method="post" action="<?=$atournamentExtend;?>">
            Posunout start turnaje<br> <br>
            <select name="extend">
              <option value="1">o hodinu</option>
              <option value="2">o 2 hodiny</option>
              <option value="4">o 4 hodiny</option>
              <option value="8">o 8 hodin</option>
              <option value="16">o 16 hodin</option>
              <option value="24">o den</option>
              <option value="48">o 2 dny</option>
              <option value="72">o 3 dny</option>
              <option value="168">o týden</option>              
            </select>             <br> <br>
            <input type="submit" value="Posunout start" />
          </form>
        </div>
      </div> 
      <?}?>
    <?}?>
  </div>
  <div class="col col-1-3">
    <div class="grid payout server-detail-section grid-semipadded">
      <?if(isset($vyplata->idgwt)&&$vyplata->idgwt>0){?>
        <div class="col col-header col-2-3">Místo</div>
        <div class="col col-header col-1-3">Výplata</div>
        <?for($i=1;$i<=$vyplata->winners_count;$i++){$xx='misto_'.$i;$yy='coins_'.$i;?>        
          <div class="col col-2-3"><?=$i?>.<?if($tournament->prerozdelene_vyhry==1){$ln='idu_misto_'.$i?><?=$users[$vyplataData->$ln]->osloveni?><?}?></div>
          <div class="col col-1-3"><?=$vyplata->$xx;?> %<?if($tournament->prerozdelene_vyhry==1){?><br><?=printcost($vyplataData->$yy);?><?}?></div>
        <?}?>       
      <?}?>      
    </div>        
    <?if(in_array($currentUserID,$playersArr)){?>
      <div class="chat server-detail-section">
        <?if(isset($chatData)&&count($chatData)>0){?>
          <div class="history"> 
            <?foreach($chatData as $chD){?>
              <div class="message">
                <div class="time"><?=strftime('%d.%m.%Y %H:%M:%S',$chD->ts);?> / <?=$users[$chD->id_hrace]->osloveni;?></div>
                <div class="content"><?=$chD->obsah;?></div>
              </div>
            <?}?>
          </div>
        <?}?>
        <div class="text-field">
          <form method="post" action="<?=$anewchat;?>">    
            <textarea maxlength="999" name="obsah"></textarea>              
            <button type="submit"><em class="fa fa-paper-plane-o"></em></button>   
          </form>  
        </div>
      </div>
    <?}?>
  </div>
  <div class="col col-1-3">
    <form method="post" action="<?=$agetin;?>">
      <div class="map server-detail-section">
        <img src="/img/userfiles/maps/<?if(file_exists('img/userfiles/maps/'.$map->idgm.'.png')){?><?=$map->idgm;?>.png<?}else{?>default.jpg<?}?>" alt="<?=$map->nazev?>">
        <?if($tournament->datum_cas_konce>0&&$tournament->dohrano==1){?>
          <div class="countdown">Turnaj dohrán</div> 
        <?}elseif($tournament->datum_cas_startu<time()){?>  
          <div class="countdown">Turnaj právě probíhá</div>            
        <?}else{
          $timeXto=$tournament->datum_cas_startu-time();
          $timeXdays=(int)floor($timeXto/(86400));
          $timeXhours=(int)floor(($timeXto-floor($timeXdays*86400))/3600);
          $timeXminutes=(int)floor(((($timeXto-($timeXdays*86400))-$timeXhours*3600))/60);
          $timeXseconds=(int)floor((($timeXto-($timeXdays*86400))-($timeXhours*3600))-($timeXminutes*60));
          ?>
          <div class="countdown">
            <div class="days"><?=number_format($timeXdays,(-2))?></div>
            <div class="hours"><?=sprintf('%02d',number_format($timeXhours,(-2)))?></div>
            <div class="minutes"><?=sprintf('%02d',number_format($timeXminutes,(-2)))?></div>
            <div class="seconds"><?=sprintf('%02d',number_format($timeXseconds,(-2)))?></div>
          </div>   
        <?}?>
        <?if(in_array($currentUserID,$playersArr)){}elseif($tournament->maximalni_pocet_hracu==count($players)){}elseif($tournament->dohrano==1){}else{?>          
              <input class="button button-<?if($zustatek>=$tournament->cena){?>green<?}else{?>red<?}?>" onclick="return confirm('Opravdu si přejete se přihlásit do tohoto turnaje? Operace je již pak nevratná.');" type="submit" value="Přihlásit se (<?=printcost($tournament->cena)?> $)" />                
          <?}?>              
      </div>
    </form>
    <div class="note server-detail-section">
      <b>Poznámka zakladatele</b><br>
      <?=$tournament->poznamka_zakladatele?>
    </div>
    <div class="rules server-detail-section">
      <b>Pravidla turnaje</b><br>
      <?=$tournament->pravidla_turnaje_mala?><br><br>
      <?=$tournament->pravidla_turnaje_velka?>
    </div>
  </div>
</div>