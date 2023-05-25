<h1>Hraní - Editace hry</h1>
<div class="BackendBack">
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis her</a><br>  
</div>
<br>
<?if(getget('message','')=='saved'){?><h2>Hra úspěšně uložena.</h2><br><?}?>
<?if(getget('message','')=='not-saved'){?><h2>Hru se nepodařilo uložit - musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='type-added'){?><h2>Typ hry přidán.</h2><br><?}?>
<?if(getget('message','')=='type-not-added'){?><h2>Typ hry se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='type-saved'){?><h2>Typ hry uložen.</h2><br><?}?>
<?if(getget('message','')=='type-not-saved'){?><h2>Typ hry se nepodařilo uložit, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='type-deleted'){?><h2>Typ hry smazán.</h2><br><?}?>
<?if(getget('message','')=='winner-added'){?><h2>Výplata hry přidána.</h2><br><?}?>
<?if(getget('message','')=='winner-not-added'){?><h2>Výplatu hry se nepodařilo přidat, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='winner-saved'){?><h2>Výplata hry uložena.</h2><br><?}?>
<?if(getget('message','')=='winner-not-saved'){?><h2>Výplatu hry se nepodařila uložit, musíte zadat název.</h2><br><?}?>
<?if(getget('message','')=='created'){?><h2>Parametr úspěšně přidán.</h2><br><?}?>
<?if(getget('message','')=='edited'){?><h2>Parametr úspěšně změněn.</h2><br><?}?>
<?if(getget('message','')=='sub-created'){?><h2>Hodnota parametru úspěšně přidána.</h2><br><?}?>
<?if(getget('message','')=='sub-edited'){?><h2>Hodnota parametru úspěšně změněna.</h2><br><?}?>

<form method="post" action="<?=$aedit?>" enctype="multipart/form-data">
  <table>
    <tr><th width="50%">Název hry (je povinný):</th><th colspan="2">E-mail ohledně ukončeného zápasu (turnaje):</th></tr>
    <tr><td><input class="width-200" type="text" name="nazev" value="<?=$data->nazev?>" /></td><td colspan="2"><input class="width-200" type="text" name="mail_ukonceni_zapasu" value="<?=$data->mail_ukonceni_zapasu?>" /></td></tr>
    <tr>
      <td>
        <label><input type="checkbox" name="zaklada_jen_admin" value="1" <?if($data->zaklada_jen_admin==1){?>checked<?}?> /> <b>Zápas (turnaj) může zakládat jen administrátor systému gsoul.eu</b></label><br />
        <label><input type="checkbox" name="aktivni" value="1" <?if($data->aktivni==1){?>checked<?}?> /> <b>Aktivní - hra je zobrazena na gsoul.eu</b></label>
      </td>    
      <td colspan="2">
        <b>Aktuální obrázek hry:</b> 
        <a target="_blank" href="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$data->idg.'.png')){?><?=$data->idg;?>.png<?}else{?>default.jpg<?}?>"><img width="50" src="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$data->idg.'.png')){?><?=$data->idg;?>.png<?}else{?>default.jpg<?}?>" /></a><br />
        <b>Změnit obrázek:</b> <input type="file" value="" name="soubor" style="max-width:200px;width:200px;" />
      </td>
    </tr>  
    <tr>
      <td><b>Předvyplněná pravidla zápasu (turnaje):</b><br /><textarea style="width:100%" type="text" name="pravidla_turnaje" ><?=$data->pravidla_turnaje?></textarea></td>
      <td colspan="2"><b>Předvyplněná podrobná pravidla zápasu (turnaje):</b><br /><textarea style="width:100%" type="text" name="podrobna_pravidla_turnaje" ><?=$data->podrobna_pravidla_turnaje?></textarea></td>  
    </tr>    
    <tr>
      <td colspan="2">
        <label style="float:left;margin-right:8px;">
        <b>Zobrazení sloupců ve výpisu zápasů a turnajů:</b>
        </label>            
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_buyin" <?if($data->zobraz_buyin==1){?>checked<?}?> value="1" />
          Buy-in
        </label>
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_typhry" <?if($data->zobraz_typhry==1){?>checked<?}?> value="1" />
          Typ hry
        </label>        
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_pocethracu" <?if($data->zobraz_pocethracu==1){?>checked<?}?> value="1" />
          Počet hráčů
        </label>       
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_datumzahajeni" <?if($data->zobraz_datumzahajeni==1){?>checked<?}?> value="1" />
          Datum zahájení
        </label>
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobrazit_dohrano" <?if($data->zobrazit_dohrano==1){?>checked<?}?> value="1" />
          Dohráno
        </label>  
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_pravidla" <?if($data->zobraz_pravidla==1){?>checked<?}?> value="1" />
          Pravidla
        </label>     
        <label style="float:left;margin-right:8px;">
          <input type="checkbox" name="zobraz_login" <?if($data->zobraz_login==1){?>checked<?}?> value="1" />
          Titulek turnaje
        </label>                          
      </td>
      <td style="text-align:right; width:100px;"><input type="submit" value="Uložit" /></td>
    </tr>   
  </table>
</form>
<br>
<div class="overflow-wrap">
  <table>
    <tr><th colspan="2">Typy hry</th><th colspan="5">Nastavení zápasů</th><th colspan="6">Nastavení turnajů</th><th></th></tr> 
    <tr>
      <th>Název</th>
      <th>Aktivní</th>
      <th>Výplata pro Zápas </th>
      <th>Minimální počet hráčů v Zápasu</th>
      <th>Maximální počet hráčů v Zápasu</th>
      <th>Minimální počet týmů v Zápasu</th>
      <th>Maximální počet týmů v Zápasu</th>
      <th>Výplata pro Turnaj</th>
      <th>Minimální počet hráčů (týmů) v Turnaji</th>
      <th>Maximální počet hráčů (týmů) v Turnaji</th>
      <th>Počet postupujících hráčů (týmů) v zápasu Turnaje</th>
      <th>Ideální počet hráčů v týmu</th>
      <th>Ideální počet hráčů (týmů) na zápas v Turnaji</th>
      <th></th>
    </tr> 
    <form method="post" action="<?=$aaddtype?>" onsubmit="return confirm('Máte vše správně vyplněno a opravdu si přejete přidat nový typ hry?')">
      <tr>
        <td><input class="width-100p" type="text" name="nazev" value="" /></td>
        <td><input type="checkbox" name="aktivni" value="1" checked /></td>
        <td>
          <select name="tournament_id_vyplaty">
            <?foreach($winners as $wn){?>
              <option value="<?=$wn->idgwt?>"><?=$wn->nazev?></option>
            <?}?>
          </select>
        </td>
        <td><input type="number" name="tournament_minimalni_pocet_hracu" value="2" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
        <td><input type="number" name="tournament_maximalni_pocet_hracu" value="<?=$moduleGame->maximalni_pocet_hracu?>" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
        <td><input type="number" name="tournament_minimalni_pocet_tymu" value="2" min="2" max="<?=$moduleGame->maximalni_pocet_tymu?>" /></td>
        <td><input type="number" name="tournament_maximalni_pocet_tymu" value="<?=$moduleGame->maximalni_pocet_tymu?>" min="2" max="<?=$moduleGame->maximalni_pocet_tymu?>" /></td>
        <td>
          <select name="cup_id_vyplaty">
            <?foreach($winners as $wn){?>
              <option value="<?=$wn->idgwt?>"><?=$wn->nazev?></option>
            <?}?>
          </select>
        </td>
        <td><input type="number" name="cup_minimalni_pocet_hracutymu" value="2" min="2" max="<?=$moduleGame->maxHracuTymu?>" /></td>      
        <td><input type="number" name="cup_maximalni_pocet_hracutymu" value="<?=$moduleGame->maxHracuTymu?>" min="2" max="<?=$moduleGame->maxHracuTymu?>" /></td>
        <td><input type="number" name="cup_pocet_postupujicich_hracutymu" value="1" min="1" max="<?=$moduleGame->maxHracuTymu-1?>" /></td>
        <td><input type="number" name="cup_idealni_pocet_hracu_v_tymu" value="5" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
        <td><input type="number" name="cup_idealni_pocet_hracutymu_na_turnaj" value="2" min="2" max="<?=$moduleGame->maxHracuTymu-1?>" /></td>              
        <td style="text-align:right"><input type="submit" value="Přidat" /></td>
      </tr>
    </form>  
    <?foreach($types as $tx){?>
      <form method="post" action="<?=$tx->aedittype?>">
        <tr>
          <td><input class="width-100p" type="text" name="nazev" value="<?=$tx->nazev?>" /></td>
          <td><input type="checkbox" name="aktivni" value="1" <?if($tx->aktivni==1){?>checked<?}?> /></td>
          <td>
            <select name="tournament_id_vyplaty">
              <?foreach($winners as $wn){?>
                <option <?if($tx->tournament_id_vyplaty==$wn->idgwt){?>selected<?}?> value="<?=$wn->idgwt?>"><?=$wn->nazev?></option>
              <?}?>
            </select>
          </td>
          <td><input type="number" name="tournament_minimalni_pocet_hracu" value="<?=$tx->tournament_minimalni_pocet_hracu?>" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
          <td><input type="number" name="tournament_maximalni_pocet_hracu" value="<?=$tx->tournament_maximalni_pocet_hracu?>" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
          <td><input type="number" name="tournament_minimalni_pocet_tymu" value="<?=$tx->tournament_minimalni_pocet_tymu?>" min="2" max="<?=$moduleGame->maximalni_pocet_tymu?>" /></td>
          <td><input type="number" name="tournament_maximalni_pocet_tymu" value="<?=$tx->tournament_maximalni_pocet_tymu?>" min="2" max="<?=$moduleGame->maximalni_pocet_tymu?>" /></td>
          <td>
            <select name="cup_id_vyplaty">
              <?foreach($winners as $wn){?>
                <option <?if($tx->cup_id_vyplaty==$wn->idgwt){?>selected<?}?> value="<?=$wn->idgwt?>"><?=$wn->nazev?></option>
              <?}?>
            </select>
          </td>
          <td><input type="number" name="cup_minimalni_pocet_hracutymu" value="<?=$tx->cup_minimalni_pocet_hracutymu?>" min="2" max="<?=$moduleGame->maxHracuTymu?>" /></td>      
          <td><input type="number" name="cup_maximalni_pocet_hracutymu" value="<?=$tx->cup_maximalni_pocet_hracutymu?>" min="2" max="<?=$moduleGame->maxHracuTymu?>" /></td>
          <td><input type="number" name="cup_pocet_postupujicich_hracutymu" value="<?=$tx->cup_pocet_postupujicich_hracutymu?>" min="1" max="<?=$moduleGame->maxHracuTymu-1?>" /></td>
          <td><input type="number" name="cup_idealni_pocet_hracu_v_tymu" value="<?=$tx->cup_idealni_pocet_hracu_v_tymu?>" min="2" max="<?=$moduleGame->maximalni_pocet_hracu?>" /></td>
          <td><input type="number" name="cup_idealni_pocet_hracutymu_na_turnaj" value="<?=$tx->cup_idealni_pocet_hracutymu_na_turnaj?>" min="2" max="<?=$moduleGame->maxHracuTymu-1?>" /></td>              
          <td style="text-align:right"><input type="submit" value="Uložit" /></td>
        </tr>
      </form>  
    <?}?>
  </table>
</div>
<br>
<div class="overflow-wrap">
	<table>
	  <tr><th colspan="6">Přidat výplatu - název:</th><th colspan="6">Typ:</th><th colspan="2">Aktivní:</th></tr>
	  <form method="post" action="<?=$aaddwinner?>" onsubmit="return confirm('Máte vše správně vyplněno a opravdu si přejete přidat nový typ výplaty?')">
      <tr>
        <td colspan="6"><input class="width-200" type="text" name="nazev" value="" /></td>
        <td colspan="6">
          <select name="winners_count">
            <option value="1">vyplatit 1 hráče/tým</option>
            <option value="2">vyplatit 2 hráče/tým</option>
            <option value="3">vyplatit 3 hráče/tým</option>
            <option value="4">vyplatit 4 hráče/tým</option>
            <option value="5">vyplatit 5 hráčů/týmů</option>
            <option value="6">vyplatit 6 hráčů/týmů</option>
            <option value="7">vyplatit 7 hráčů/týmů</option>
            <option value="8">vyplatit 8 hráčů/týmů</option>
            <option value="9">vyplatit 9 hráčů/týmů</option>
            <option value="10">vyplatit 10 hráčů/týmů</option>
          </select>  
        </td>
        <td><input type="checkbox" name="aktivni" value="1" checked /></td>
        <td style="text-align:right"><input type="submit" value="Přidat" /></td>
      </tr>
	  </form>
	  <tr><th>Výplaty - název:</th><?for($i=1;$i<11;$i++){?><th><?=$i?>.&nbsp;m.:</th><?}?><th>Clk.</th><th colspan="2">Aktivní:</th></tr>  
	  <?foreach($winners as $wn){$xsum=0;for($i=1;$i<11;$i++){$xx='misto_'.$i;$xsum+=$wn->$xx;}?>    
		<form method="post" action="<?=$wn->aeditwin?>">
		  <tr style="background-color:<?if($xsum>100){?>#f99595;<?}elseif($xsum>99){?>#92d863<?}elseif($xsum>=90){?>#afe28c;<?}else{?>#f9f99a;<?}?>" >
			<td><input class="width-110" type="text" name="nazev" value="<?=$wn->nazev?>" /></td>
			<?for($i=1;$i<11;$i++){?>
			  <td><?if($i<=$wn->winners_count){$xx='misto_'.$i;?><input type="text" size="2" class="width-40" name="<?=$xx?>" value="<?=$wn->$xx?>"><?}else{?>-<?}?></td>
			<?}?>
			<td><b><?=$xsum?>%</b></td>
			<td><input type="checkbox" name="aktivni" value="1" <?if($wn->aktivni==1){?>checked<?}?> /></td>       
			<td style="text-align:right"><input type="submit" value="Uložit" /></td>
			</tr>
		</form>  
	  <?}?>
	  <tr><th colspan="14">Celková suma procent pro všechna vyplněná místa (1.m. až 10.m.) nesmí překročit 100%.<br>Pokud tato situace nastane, řádek se podbarví červeně a nebude se zobrazovat při zakládání zápasů a turnajů!<br>Pokud je řádek vysvícen zeleně, jsou korektně rozděleny celkové finance ze zápasu / z turnaje.</th></tr>
	</table>
</div>
<br />
<form method="post" action="<?=$anew?>">
  <table>
    <tr>
      <th>Název parametru</th>
      <th>Typ zadávání v zápasu (v&nbsp;turnaji)</th>          
      <th>Typ filtrování ve výpisu zápasů (turnajů)</th>
      <th>Zobrazovat ve výpisu zápasů (turnajů)</th>
      <th colspan="2">Pořadí</th>
    </tr>
    <tr>
      <td><input type="text" style="width:100%" name="nazev" value="" /></td>
      <td>
        <select name="typ_v_turnaji_cupu" style="width:100%" >
          <option value="1">Checkbox - více možností - zaškrtávátko</option>
          <option value="2">Select - jedna možnost - výběr z roletky</option>
          <option value="3">Text - vlastní napsaný text</option>
          <option value="0">Neaktivní - parametr bude skrytý a nebude jej možné zadat</option>
        </select>
      </td>
      <td>
        <select name="typ_v_tabulce" style="width:100%" >
          <option value="1">Checkbox - více možností - zaškrtávátko</option>
          <option value="2">Select - jedna možnost - výběr z roletky</option>          
          <option value="0">Neaktivní - dle parametru nepůjde vyhledávat</option>
        </select>
      </td>
      <td>
        <select name="zobrazovat_v_tabulce" style="width:100%" >
          <option value="1">Ano, zobrazovat</option>                              
          <option value="0">Ne, nezobrazovat</option>
        </select>
      </td>
      <td><input type="number" style="width:100%" name="poradi" value="<?=count($params)+1;?>" /></td>      
      <td style="text-align:right"><input type="submit" value="Přidat parametr" /></td>
    </tr>
  </table>
</form>
<?if(count($params)>0){foreach($params as $p){?>
  <br />
  <table>
    <form method="post" action="<?=$p->aedit?>"> 
      <tr>
        <th>Název parametru</th>
        <th>Typ zadávání v zápasu (v&nbsp;turnaji)</th>          
        <th>Typ filtrování ve výpisu zápasů (turnajů)</th>
        <th>Zobrazovat ve výpisu zápasů (turnajů)</th>
        <th colspan="2">Pořadí</th>
      </tr>
      <tr>
        <td><input type="text" style="width:100%" name="nazev" value="<?=$p->nazev?>" /></td>
        <td>
          <select name="typ_v_turnaji_cupu" style="width:100%" >
            <option <?if($p->typ_v_turnaji_cupu==1){?>selected<?}?> value="1">Checkbox - více možností - zaškrtávátko</option>
            <option <?if($p->typ_v_turnaji_cupu==2){?>selected<?}?> value="2">Select - jedna možnost - výběr z roletky</option>
            <option <?if($p->typ_v_turnaji_cupu==3){?>selected<?}?> value="3">Text - vlastní napsaný text</option>
            <option <?if($p->typ_v_turnaji_cupu==0){?>selected<?}?> value="0">Neaktivní - parametr bude skrytý a nebude jej možné zadat</option>
          </select>
        </td>
        <td>
          <select name="typ_v_tabulce" style="width:100%" >
            <option <?if($p->typ_v_tabulce==1){?>selected<?}?> value="1">Checkbox - více možností - zaškrtávátko</option>
            <option <?if($p->typ_v_tabulce==2){?>selected<?}?> value="2">Select - jedna možnost - výběr z roletky</option>          
            <option <?if($p->typ_v_tabulce==0){?>selected<?}?> value="0">Neaktivní - dle parametru nepůjde vyhledávat</option>
          </select>
        </td>
        <td>
          <select name="zobrazovat_v_tabulce" style="width:100%" >
            <option <?if($p->zobrazovat_v_tabulce==1){?>selected<?}?> value="1">Ano, zobrazovat</option>                              
            <option <?if($p->zobrazovat_v_tabulce==0){?>selected<?}?> value="0">Ne, nezobrazovat</option>
          </select>
        </td>
        <td><input type="number" style="width:100%" name="poradi" value="<?=$p->poradi?>" /></td>      
        <td style="text-align:right"><input type="submit" value="Uložit parametr" /></td>
      </tr>      
    </form> 
    <?if($p->typ_v_turnaji_cupu==3){?>
      <tr><td colspan="6" style="text-align:center;"><b>* PARAMETR JE TYPU "Text - vlastní napsaný text". Žádné parametry tedy nejsou k dispozici.</b> <b>U každého zápasu (turnaje) si hráči u tohoto parametru zadávají vlastní různé texty hodnot parametru a nelze tedy tyto hodnoty parametru hromadně editovat zde v rámci administrace. A to z důvodu, že každá textová hodnota je přímo závislá na daném zápasu (turnaji). Zároveň také nedoporučujeme nechat nastavený typ filtrování jako checkbox - více možností - zaškrtávátko.</b></td></tr>
    <?}else{?>  
      <tr>
        <th colspan="2">Hodnoty parametru - Název:</th>
        <th colspan="2">Zobrazovat:</th>
        <th colspan="2">Pořadí:</th>
      </tr>     
      <?if(count($subParams[$p->idp]>0)){foreach($subParams[$p->idp] as $sp){?>
        <form method="post" action="<?=$sp->aedit?>"> 
          <tr>          
            <td colspan="2"><input style="width:100%" type="text" name="nazev" value="<?=$sp->nazev?>" /></td>
            <td colspan="2">
              <select name="aktivni" style="width:100%" >
                <option <?if($sp->aktivni==1){?>selected<?}?> value="1">Ano, zobrazovat</option>                              
                <option <?if($sp->aktivni==0){?>selected<?}?> value="0">Ne, nezobrazovat</option>
              </select>
            </td>
            <td><input type="number" style="width:100%" name="poradi" value="<?=$sp->poradi?>" /></td> 
            <td style="text-align:right"><input type="submit" value="Uložit hodnotu" /></td>          
          </tr>
        </form> 
      <?}}?>     
      <form method="post" action="<?=$p->aadd?>"> 
        <tr> 
          <th>Přidat hodnotu:</th>         
          <td><input style="width:100%" type="text" name="nazev" value="" /></td>
          <td colspan="2">
            <select name="aktivni" style="width:100%" >
              <option value="1">Ano, zobrazovat</option>                              
              <option value="0">Ne, nezobrazovat</option>
            </select>
          </td>
          <td><input style="width:100%" type="number" name="poradi" value="<?=count($subParams[$p->idp])+1;?>" /></td>
          <td style="text-align:right"><input type="submit" value="Přidat hodnotu" /></td>          
        </tr>
      </form> 
    <?}?>
  </table>
<?}}?>
