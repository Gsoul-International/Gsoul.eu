<div class="breadcrumb">
  <?foreach($currGameModules as $cGM){foreach($modulePlatforms as $mp){if($mp->idgp==$cGM->idgp){?>
    <ul>
      <li><a href="/"><?=$mainpagename;?></a></li>   
      <li><a href="<?=$aback?>"><?=$systemTranslator['cups_cups'];?></a></li>
      <li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li>
      <li><a href="<?=$abackGame?>"><?=$game->nazev?></a></li>          
      <li><a href=""><?=$systemTranslator['cups_zobrazeni_cupu'];?></a></li>
    </ul>
  <?}}}?>
</div> 
<h1><?=$systemTranslator['cups_cups'];?> - <?=$systemTranslator['cups_zobrazeni_cupu'];?></h1>
<div class="align-center img-max-100">
<?=$tournament->banner;?>
</div>
<div class="grid grid-server-detail gap-top-16">
  <div class="col col-1-1">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['cups_odkaz_na_cup'];?></div>
			<div class="window-content" style="min-height:35px;"><div class="size-20 align-center gap-top-16">https://<?=$_SERVER['SERVER_NAME']?><?=$athis;?></div></div>
    </div>
  </div>
  <div class="col col-1-3 show-970 fullwidth-970">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['turnaje_mapa_a_registrace'];?></div>
			<div class="window-content mCustomScrollbar window-content-large">
				<div class="tournament-map-wrap">
					<img src="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$game->idg.'.png')){?><?=$game->idg;?>.png<?}else{?>default.jpg<?}?>" alt="<?=$game->nazev?>" />
					<div class="tournament-map-name"><?=$game->nazev?></div>
				</div>
				<br />
				<div class="size-30 bold align-center">
					<?=$systemTranslator['turnaje_buy_in2'];?>:
					<span class="yellow"><?=printcost($tournament->cena)?> $</span>
				</div>
				<br />
				<div class="size-20 align-center">
				<?if($tournament->dohrano==1){?>
          <b><?=$systemTranslator['cupy_cup_je_jiz_dohran'];?></b> 
        <?}elseif($tournament->datum_cas_startu<time()||$tournament->zahajeno==1){?>  
          <b><?=$systemTranslator['cupy_cup_prave_probiha'];?></b>            
        <?}else{
          $timeXto=$tournament->datum_cas_startu-time();
          $timeXdays=(int)floor($timeXto/(86400));
          $timeXhours=(int)floor(($timeXto-floor($timeXdays*86400))/3600);
          $timeXminutes=(int)floor(((($timeXto-($timeXdays*86400))-$timeXhours*3600))/60);
          $timeXseconds=(int)floor((($timeXto-($timeXdays*86400))-($timeXhours*3600))-($timeXminutes*60));
          ?>
          <b><?=$systemTranslator['turnaje_zbyvajici_cas'];?>:</b>
					<div class="countdown light-gray">
            <div class="days"><?=number_format($timeXdays,(-2))?> days</div>
            <div class="hours"><?=sprintf('%02d',number_format($timeXhours,(-2)))?></div>
            <div class="minutes"><?=sprintf('%02d',number_format($timeXminutes,(-2)))?></div>
            <div class="seconds"><?=sprintf('%02d',number_format($timeXseconds,(-2)))?></div>
          </div>   
        <?}?>							
				</div>
        <?if($tournament->hraji_tymy==0){?>	
  				<br />
  				<div class="align-center pad-bottom-16">
  				  <?if(in_array($currentUserID,$playersArr)){}elseif($tournament->maximalni_pocet_hracutymu==count($players)){}elseif($tournament->dohrano==1||$tournament->zahajeno==1){}else{?> 
              <?if($currentUserID>0){?>
                <form method="post" action="<?=$agetin;?>">
                  <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;         
                  <button type="submit" onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
                </form>              
              <?}?>  
            <?}?> 				
  				</div>
				<?}?>
			</div>
		</div>
	</div>
	<div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['turnaje_prihlaseni_uzivatele'];?></div>
			<div class="window-content mCustomScrollbar window-content-large">
			  <?foreach($players as $pl){?>          
  				<div class="row">
  					<div class="user-avatar-32">
              <?if(strlen($users[$pl->id_hrace]->user_picture)>0){?>
  						  <img src="/<?=$users[$pl->id_hrace]->user_picture;?>" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />
              <?}elseif(strlen($users[$pl->id_hrace]->fb_picture)>0){?>
                <img src="<?=$users[$pl->id_hrace]->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />    						      						
  						<?}else{?>
  						  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />
  						<?}?>  					  				
  					</div>&nbsp;&nbsp;&nbsp;&nbsp;
  					<span class="user-name">
              <?if($pl->id_tymu>0&&$loggedTeams2[$pl->id_tymu]->idt>0){?>
                <b><?=$loggedTeams2[$pl->id_tymu]->nazev;?></b> -
              <?}?>
              <?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?> <?/*=$pl->id_hrace;*/?>              
            </span>
  				</div>
				<?}?>
				<?foreach($alternatesArr2 as $pl){?>          
  				<div class="row">
  					<div class="user-avatar-32">
              <?if(strlen($users[$pl->id_hrace]->user_picture)>0){?>
  						  <img src="/<?=$users[$pl->id_hrace]->user_picture;?>" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />
              <?}elseif(strlen($users[$pl->id_hrace]->fb_picture)>0){?>
                <img src="<?=$users[$pl->id_hrace]->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />    						      						
  						<?}else{?>
  						  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($users[$pl->id_hrace]->osloveni,'UTF-8'));?>" />
  						<?}?>  					  				
  					</div>&nbsp;&nbsp;&nbsp;&nbsp;
  					<span class="user-name">
  					  <b><?=$systemTranslator['nahradnik'];?> - </b>
              <?if($pl->id_tymu>0&&$loggedTeams2[$pl->id_tymu]->idt>0){?>
                <b><?=$loggedTeams2[$pl->id_tymu]->nazev;?></b> -
              <?}?>
              <?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?>              
            </span>
  				</div>
				<?}?>
				<?if($tournament->hraji_tymy==0){?>
  				<div class="row align-center">
  				  <?if(in_array($currentUserID,$playersArr)){?>
              <?=$systemTranslator['turnaje_jiz_jste_prihlasen'];?>
            <?}elseif($tournament->maximalni_pocet_hracutymu==count($players)||$tournament->zahajeno==1){?> 
              <?=$systemTranslator['cupy_v_cupu_je_jiz_max_hracu'];?>
            <?}elseif($tournament->dohrano==1){?>       
              <?=$systemTranslator['cupy_cup_je_jiz_dohran'];?>        
            <?}else{?>
              <?if($currentUserID>0){?>
                <form method="post" action="<?=$agetin;?>">
                  <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;         
                  <button type="submit" onclick="return confirm('<?=$systemTranslator['cupy_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
                </form>
              <?}?>  
            <?}?> 
          </div> 
        <?}?>    
			</div>
		</div>
	</div>
	<div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['turnaje_vyhry'];?></div>
			<div class="window-content mCustomScrollbar window-content-large">
			  <?if(isset($vyplata->idgwt)&&$vyplata->idgwt>0){?>
			   <?if($tournament->hraji_tymy==1){?>
          <?for($i=1;$i<=$vyplata->winners_count;$i++){$xx='misto_'.$i;$yy='coins_'.$i;?>        
            <div class="row">
              <div class="text-circle-32 pad-left-2"><?=$i?>.</div>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <?=$vyplata->$xx;?>&nbsp;%<?if($tournament->dohrano==1){?> &nbsp;&nbsp;&nbsp;&nbsp; <?=printcost($vyplataData->$yy);?>&nbsp;$<?}?>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <?if($tournament->dohrano==1){$ln='idu_misto_'.$i?><?=$loggedTeams2[$vyplataData->$ln]->nazev;?><?}?>              
            </div>
          <?}?>
         <?}else{?>
          <?for($i=1;$i<=$vyplata->winners_count;$i++){$xx='misto_'.$i;$yy='coins_'.$i;?>        
            <div class="row">
              <div class="text-circle-32 pad-left-2"><?=$i?>.</div>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <?=$vyplata->$xx;?>&nbsp;%<?if($tournament->dohrano==1){?> &nbsp;&nbsp;&nbsp;&nbsp; <?=printcost($vyplataData->$yy);?>&nbsp;$<?}?>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <?if($tournament->dohrano==1){$ln='idu_misto_'.$i?><?=$playersNicks[$vyplataData->$ln]!=''?$playersNicks[$vyplataData->$ln]:$users[$vyplataData->$ln]->osloveni?><?}?>
              
            </div>
          <?}?>
         <?}?>
        <?}?>   
			</div>
		</div>
	</div>
	<div class="col col-1-3 hide-970">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['turnaje_mapa_a_registrace'];?></div>
			<div class="window-content mCustomScrollbar window-content-large">
				<div class="tournament-map-wrap">
					<img src="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$game->idg.'.png')){?><?=$game->idg;?>.png<?}else{?>default.jpg<?}?>" alt="<?=$game->nazev?>" />
					<div class="tournament-map-name"><?=$game->nazev?></div>
				</div>
				<br />
				<div class="size-30 bold align-center">
					<?=$systemTranslator['turnaje_buy_in2'];?>:
					<span class="yellow"><?=printcost($tournament->cena)?> $</span>
				</div>
				<br />
				<div class="size-20 align-center">
					<?if($tournament->dohrano==1){?>
            <b><?=$systemTranslator['cupy_cup_je_jiz_dohran'];?></b> 
          <?}elseif($tournament->datum_cas_startu<time()||$tournament->zahajeno==1){?>  
            <b><?=$systemTranslator['cupy_cup_prave_probiha'];?></b>            
          <?}else{
            $timeXto=$tournament->datum_cas_startu-time();
            $timeXdays=(int)floor($timeXto/(86400));
            $timeXhours=(int)floor(($timeXto-floor($timeXdays*86400))/3600);
            $timeXminutes=(int)floor(((($timeXto-($timeXdays*86400))-$timeXhours*3600))/60);
            $timeXseconds=(int)floor((($timeXto-($timeXdays*86400))-($timeXhours*3600))-($timeXminutes*60));
            ?>
            <b><?=$systemTranslator['turnaje_zbyvajici_cas'];?>:</b>
  					<div class="countdown light-gray">
              <div class="days"><?=number_format($timeXdays,(-2))?> days</div>
              <div class="hours"><?=sprintf('%02d',number_format($timeXhours,(-2)))?></div>
              <div class="minutes"><?=sprintf('%02d',number_format($timeXminutes,(-2)))?></div>
              <div class="seconds"><?=sprintf('%02d',number_format($timeXseconds,(-2)))?></div>
            </div>   
          <?}?>					
				</div>	
				<?if($tournament->hraji_tymy==0){?>
  				<br />
  				<div class="align-center">
  					<?if(in_array($currentUserID,$playersArr)){}elseif($tournament->maximalni_pocet_hracutymu==count($players)){}elseif($tournament->dohrano==1||$tournament->zahajeno==1){}else{?> 
              <?if($currentUserID>0){?>
                <form method="post" action="<?=$agetin;?>">  
                  <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;      
                  <button type="submit" onclick="return confirm('<?=$systemTranslator['cupy_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
                </form>
              <?}?>                
            <?}?> 	
  				</div>
				<?}?>
			</div>
		</div>
	</div>	
  <?if($tournament->hraji_tymy==1&&$tournament->dohrano==0&&$tournament->zahajeno==0&&$currentUserID>0){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['tymove_cupy_nadpis_prihlaseni'];?></div>
  			<div class="window-content mCustomScrollbar">
  			 <div class="window-inner-wrap">
  			  <br />
          <div class="align-center">
            <b><?=$systemTranslator['tymove_cupy_prihlaseni_tymu_do_turnaje'];?></b><br /><br />
          </div>
  			  <?if($currentUserTeam->idt>0){?>
  			    <?if($UserHaveLoggedOwnTeam==1){?>
  			       <div class="align-center">
  			         <?=$systemTranslator['tymove_cupy_vas_prihlaseny_tym'];?> <?=$currentUserTeam->nazev;?>
               </div> 
            <?}else{?>
              <?if($tournament->maximalni_pocet_hracutymu<=count($loggedTeams)){?>
                <div class="align-center">
                  <?=$systemTranslator['tymove_cupy_cup_je_jiz_plne_obsazen'];?>
                </div>
              <?}else{?>
                <div class="align-center">
                  <form method="post" action="<?=$agetinTeam;?>">
    			          <button type="submit" onclick="return confirm('<?=$systemTranslator['tymove_turnaje_opravdu_prihlasit_tym'];?>');" ><?=$systemTranslator['tymove_turnaje_prihlasit_tym'];?> <?=$currentUserTeam->nazev;?></button>
    			        </form>
  			        </div>
  			      <?}?>
  			    <?}?>
  			  <?}else{?>
  			    <?=$systemTranslator['tymove_cupy_nemate_tym_k_prihlaseni'];?>
  			  <?}?>  			  
  			  <br /><br />
  			  <div class="align-center">
  			   <b><?=$systemTranslator['tymove_cupy_prihlaseni_hrace_do_turnaje'];?></b><br /><br />
  			  </div>
  			  <div class="align-center">
    			  <?if(in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)) ){?>
    			    <?if(in_array($currentUserID,$playersArr)){?>
                <?=$systemTranslator['tymove_cupy_jiz_jste_prihlasen_pod_tymem'];?> 
              <?}else{?>
                <?=$systemTranslator['nahradnik_jiz_jste_prihlasen_pod_tymem'];?>
              <?}?>
              <?=$loggedTeams2[$currentPlayer->id_tymu]->nazev;?>     			                 
            <?}elseif($tournament->maximalni_pocet_hracutymu<=count($players)){?>
              <?=$systemTranslator['tymove_cupy_cup_je_jiz_plne_obsazen'];?>
            <?}elseif(count($currentPlayerTeams)>0){?> 
    			    <form method="post" action="<?=$agetin;?>">
                <select name="id_tymu" style="width:244px;">
                  <?foreach($currentPlayerTeams as $cpt){?><option value="<?=$cpt->idt?>"><?=$cpt->nazev?></option><?}?>
                </select>  
                <br /><br />
                <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;      
                <button type="submit" onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
              </form>    
    			  <?}else{?>
    			    <?=$systemTranslator['tymove_cupy_ani_jeden_z_vasich_tymu_se_turnaje_neucastni'];?>
    			  <?}?>
  			  </div> 
          <br /> 			   			 
  			  <div class="align-center">
  			   <b><?=$systemTranslator['tymove_cupy_prihlasene_tymy'];?></b>
  			  </div>
  			  <?if(count($loggedTeams2)<1){?>
  			    <br /><br /><b><?=$systemTranslator['tymove_cupy_zatim_nebyly_prihlaseny_zadne_tymy'];?></b>
  			  <?}else{?>
  			    <?foreach($loggedTeams2 as $tX2){?>
      			  <div class="row"><b><?=$tX2->nazev;?></b></div>
    			  <?}?>
  			  <?}?>
  			 </div>
  			</div>
  		</div>  
    </div>
  <?}?>	  
  <?if($tournament->hraji_tymy==1&&$tournament->dohrano==0&&$currentUserID>0){ //hraji tymy a neni dohrano  a prihlasen uzivatel?>
    <?if( (count($currentPlayerTeams)>0) && (!in_array($currentUserID,$playersArr)) && (!in_array($currentUserID,$alternatesArr)) ){ //prihlasen uzivateluv tym a uzivatel jeste neni logly?>
      <div class="col col-1-3">
    		<div class="window">
    		  <div class="window-header"><?=$systemTranslator['tymove_turnaje_nadpis_prihlaseni_nahradnika'];?></div>
    			<div class="window-content mCustomScrollbar">
    			  <div class="window-inner-wrap">
    			    <div class="align-center">
      			    <b><?=$systemTranslator['tymove_turnaje_prihlaseni_nahradnika'];?></b>
      			    <br /><br />
      			    <form method="post" action="<?=$agetinAlter;?>">
                  <select name="id_tymu" style="width:244px;">
                    <?foreach($currentPlayerTeams as $cpt){?><option value="<?=$cpt->idt?>"><?=$cpt->nazev?></option><?}?>
                  </select>  
                  <br /><br />
                  <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;      
                  <button type="submit" onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
                </form>   
              </div>
            </div>
          </div> 
    		</div>
    	</div>
  	<?}?>
  <?}?>
	<div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['cups_pravidla_cupu_velke'];?></div>
			<div class="window-content mCustomScrollbar">
				<div class="window-inner-wrap bg-white">
					<?if($tournament->pravidla_mala!=$game->pravidla_turnaje){?>!!! <?}?>
          <?=str_replace("\n",'<br>',$tournament->pravidla_mala);?> <br />
          <?if($tournament->pravidla_velka!=$game->podrobna_pravidla_turnaje){?>!!! <?}?>
					<?=str_replace("\n",'<br>',$tournament->pravidla_velka);?>
				</div>
				<div class="window-inner-wrap bg-nearlywhite">					
					<?=str_replace("\n",'<br>',$tournament->poznamka_zakladatele);?>
				</div>        
			</div>
		</div>
	</div>
  <div class="col col-1-3">
  	<div class="window">
  		<div class="window-header"><?=$systemTranslator['turnaje_informace'];?></div>
  		<div class="window-content mCustomScrollbar">
  			<div class="window-inner-wrap bg-white">
          <table>
            <tr>    
              <td><b><?=$systemTranslator['cups_titulek_cupu'];?></b></td>
              <td><?=$tournament->titulek_cupu?></td>
            </tr>
            <tr>
              <td><b><?=$systemTranslator['turnaje_hra'];?>:</b></td>
              <td><?=$game->nazev?></td>
            </tr>            
            <tr>
              <td><b><?=$systemTranslator['turnaje_typ_hry'];?></b></td>
              <td><?=$type->nazev?></td>
            </tr>            
            <tr>    
              <td><b><?=$systemTranslator['turnaje_buy_in'];?></b></td>
              <td><?=printcost($tournament->cena)?> $</td>
            </tr>
            <tr> 
              <td><b><?=$systemTranslator['turnaje_hra_na_tymy'];?></b></td>
              <td><?=$tournament->hraji_tymy==1?$systemTranslator['turnaje_hra_na_tymy_ano']:$systemTranslator['turnaje_hra_na_tymy_ne'];?></td>
            </tr>
            <?if($tournament->hraji_tymy==1){?>
              <tr>
                <td><b><?=$systemTranslator['cups_minimalni_pocet_tymu'];?></b></td>
                <td><?=$tournament->minimalni_pocet_hracutymu?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_maximalni_pocet_tymu'];?></b></td>
                <td><?=$tournament->maximalni_pocet_hracutymu?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_idealni_pocet_tymu_na_turnaj'];?></b></td>
                <td><?=$tournament->idealni_pocet_hracutymu_na_turnaj?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_pocet_postupujicich_tymu_v_turnaji'];?></b></td>
                <td><?=$tournament->pocet_postupujicich_hracutymu?></td>
              </tr>
            <?}else{?>
              <tr>
                <td><b><?=$systemTranslator['cups_minimalni_pocet_hracu'];?></b></td>
                <td><?=$tournament->minimalni_pocet_hracutymu?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_maximalni_pocet_hracu'];?></b></td>
                <td><?=$tournament->maximalni_pocet_hracutymu?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_idealni_pocet_hracu_na_turnaj'];?></b></td>
                <td><?=$tournament->idealni_pocet_hracutymu_na_turnaj?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['cups_pocet_postupujicich_hracu_v_turnaji'];?></b></td>
                <td><?=$tournament->pocet_postupujicich_hracutymu?></td>
              </tr>
            <?}?>
            <?if($tournament->hraji_tymy==1){?>
              <tr>
                <td><b><?=$systemTranslator['cups_idealni_pocet_hracu_v_tymu'];?></b></td>
                <td><?=$tournament->idealni_pocet_hracu_v_tymu?></td>
              </tr> 
            <?}?>                                                          
            <tr>
              <td><b><?=$systemTranslator['turnaje_datum_vytvoreni'];?></b></td>
              <td><?=strftime('%d.%m.%Y %H:%M',$tournament->datum_vytvoreni);?></td>
            </tr>
            <tr>
              <td><b><?=$systemTranslator['turnaje_datum_zahajeni'];?></b></td>
              <td><?=strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu);?></td>
            </tr>
            <tr>
              <td><b><?=$systemTranslator['turnaje_dohrano'];?></b></td>
              <td><?=$tournament->dohrano==1?$systemTranslator['obecne_ano']:$systemTranslator['obecne_ne'];?></td>
            </tr>           
            <tr>
              <td><b><?=$systemTranslator['turnaje_zalozil'];?></b></td>
              <td><?=$playersNicks[$tournament->id_uzivatele]!=''?$playersNicks[$tournament->id_uzivatele]:$users[$tournament->id_uzivatele]->osloveni?></td>
            </tr>
          </table>
        </div>
  		</div>
  	</div>
  </div> 
  <?if( count($params)>0 && ( count($paramsUsedText)>0 || count($paramsUsed)>0 ) ){?>
    <div class="col col-1-3">
    	<div class="window">
    		<div class="window-header"><?=$systemTranslator['parametry_turnaje'];?></div>
    		<div class="window-content mCustomScrollbar">
    			<div class="window-inner-wrap bg-white">
            <table>
              <?
              foreach($params as $ps){
                  if($ps->typ_v_turnaji_cupu==3){ // vlastni text
                    if(isset($paramsUsedText[$ps->idp])){
                      echo '<tr><td><b>'.$ps->nazev.':</b></td></tr>';
                      echo '<tr><td>'.$paramsUsedText[$ps->idp].'</td></tr>';
                      }
                  }else{ // checkbox / select
                    $printParamData=array();
                    foreach($subParams[$ps->idp] as $pUk=>$pUv){
                      if(in_array($pUk,$paramsUsed)){
                        $printParamData[]=$pUv->nazev;
                        }
                      }
                    if(count($printParamData)>0){
                      echo '<tr><td><b>'.$ps->nazev.':</b></td></tr>';                    
                      echo '<tr><td>'.implode(', ',$printParamData).'</td></tr>';
                      }
                  }
                }
              ?>
            </table>
          </div>
    		</div>
    	</div>
    </div>  
  <?}?>
  <?if($currentUserID==$tournament->id_uzivatele&&$tournament->zahajeno==0){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['cups_nastaveni_cupu'];?></div>
  			<div class="window-content mCustomScrollbar">
  			  <div class="pad-16 align-center">
            <form method="post" action="<?=$asaveCupSettings;?>">
              <table width="100%">
                <tr>    
                  <td width="50%"><b><?=$systemTranslator['cups_titulek_cupu'];?></b></td>
                  <td width="50%"><input style="width:100%;" type="text" name="titulek_cupu" value="<?=$tournament->titulek_cupu?>" /></td>            
                </tr>
                <tr>
                  <td width="50%"><b><?=$tournament->hraji_tymy==1?$systemTranslator['cups_idealni_pocet_tymu_na_turnaj']:$systemTranslator['cups_idealni_pocet_hracu_na_turnaj'];?></b></td>
                  <td width="50%"><input style="width:100%;" type="number" min="2" name="idealni_pocet_hracutymu_na_turnaj" value="<?=$tournament->idealni_pocet_hracutymu_na_turnaj?>" /></td>
                </tr>
                <tr>           
                  <td width="50%"><b><?=$tournament->hraji_tymy==1?$systemTranslator['cups_pocet_postupujicich_tymu_v_turnaji']:$systemTranslator['cups_pocet_postupujicich_hracu_v_turnaji'];?></b></td>
                  <td><input style="width:100%;" type="number" min="1" name="pocet_postupujicich_hracutymu" value="<?=$tournament->pocet_postupujicich_hracutymu?>" /></td>
                </tr> 
                <tr>           
                  <td width="50%"><b><?=$tournament->hraji_tymy==1?$systemTranslator['cups_maximalni_pocet_tymu']:$systemTranslator['cups_maximalni_pocet_hracu'];?></b></td>
                  <td><input style="width:100%;" type="number" min="<?=$tournament->idealni_pocet_hracutymu_na_turnaj?>" name="maximalni_pocet_hracutymu" value="<?=$tournament->maximalni_pocet_hracutymu?>" /></td>
                </tr>                                
                <tr><td colspan="2"><b><?=$systemTranslator['cups_poznamka_zakladatele'];?></b></td></tr> 
                <tr><td colspan="2"><textarea name="poznamka_zakladatele" rows="3" cols="25"><?=$tournament->poznamka_zakladatele?></textarea></td></tr>
                <tr><td colspan="2"><b><?=$systemTranslator['cups_pravidla_cupu'];?></b></td></tr>
                <tr><td colspan="2"><textarea name="pravidla_mala" rows="3" cols="25"><?=$tournament->pravidla_mala?></textarea></td></tr>
                <tr><td colspan="2"><b><?=$systemTranslator['cups_podrobna_pravidla_cupu'];?></b></td></tr>
                <tr><td colspan="2"><textarea name="pravidla_velka" rows="3" cols="25"><?=$tournament->pravidla_velka?></textarea></td></tr>                
                <?if(count($params)>0){?>    
                  <?foreach($params as $p){if(count($subParams[$p->idp]>0)){?>        
                    <tr><td colspan="2"><b><?=$p->nazev;?></b></td></tr>
                    <tr><td colspan="2">
                    <?if($p->typ_v_turnaji_cupu==1){ //checkbox?>                        
                      <?foreach($subParams[$p->idp] as $sp){?>
                        <label style="padding-right:5px;color:#939390;" class="float-left">
                          <input style="width:20px;" type="checkbox" name="params[]" <?if(in_array($sp->idpv,$paramsUsed)){?>checked<?}?> value="<?=$sp->idpv?>" />
                          <?=$sp->nazev?>
                        </label>
                      <?}?>                            
                    <?}elseif($p->typ_v_turnaji_cupu==2){ //select?>                        
                        <select name="params[]" id="param_<?=$p->idp?>">
                          <option value="0"><?=$systemTranslator['obecne_nezadano'];?></option>
                          <?foreach($subParams[$p->idp] as $sp){?>
                            <option <?if(in_array($sp->idpv,$paramsUsed)){?>selected<?}?> value="<?=$sp->idpv?>" ><?=$sp->nazev?></option>                             
                          <?}?>
                        </select>                        
                    <?}elseif($p->typ_v_turnaji_cupu==3){ //text?>                                                 
                        <input type="text" name="paramText_<?=$p->idp?>" id="param_<?=$p->idp?>" value="<?=$paramsUsedText[$p->idp]?>" maxlength="127" />                        
                    <?}?> 
                    </td></tr>       
                  <?}}?>    
                <?}?>            
                <tr>            
                  <td colspan="2" class="align-center"><button type="submit"><?=$systemTranslator['obecne_ulozit'];?></button></td>          
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['cups_vyrazeni_hracetymu_nadpis'];?></div>
			  <div class="window-content mCustomScrollbar">
			   <div class="pad-16 align-center">
          <form method="post" action="<?=$atournamentPlayerKick;?>">
            <?=$systemTranslator['cups_vyrazeni_hrace_popis'];?><br> <br>                
            <select name="idu">
              <option value="0"><?=$systemTranslator['cups_zvolte_hrace'];?></option>
              <?foreach($players as $pl){?>
                <option value="<?=$pl->id_hrace;?>">
                  <?if($pl->id_tymu>0&&$loggedTeams2[$pl->id_tymu]->idt>0){?>
                    <?=$loggedTeams2[$pl->id_tymu]->nazev;?> - 
                  <?}?>
                  <?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?>
                </option>
              <?}?>          
						</select>  				  					                                                                      
            <br> <br>
            <button type="submit" onclick="return submit('<?=$systemTranslator['cups_opravdu_vyradit_hrace'];?>');"><?=$systemTranslator['cups_vyradit_hrace'];?></button>
          </form>
          <?if($tournament->hraji_tymy==1){?>
            <br> <br>
            <form method="post" action="<?=$atournamentTeamKick;?>">
              <?=$systemTranslator['cups_vyrazeni_tymu_popis'];?><br> <br>                
              <select name="idu">
                <option value="0"><?=$systemTranslator['cups_zvolte_tym'];?></option>                    
                <?foreach($loggedTeams2 as $tX2){?>
                  <option value="<?=$tX2->idt;?>">
                    <?=$tX2->nazev;?>
                  </option>
                <?}?>          
							</select>  				  					                                                                      
              <br> <br>
              <button type="submit" onclick="return submit('<?=$systemTranslator['cups_opravdu_vyradit_tym'];?>');"><?=$systemTranslator['cups_vyradit_tym'];?></button>
            </form>              
          <?}?>
         </div>
        </div>
      </div>
    </div>      
  <?}?>
  <?if($currentUserID==$tournament->id_uzivatele&&$tournament->zahajeno==0){?>    
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['turnaje_posunuti_startu_big'];?></div>
			  <div class="window-content mCustomScrollbar">
			   <div class="pad-16 align-center">
          <form method="post" action="<?=$atournamentExtend;?>">
            <?=$systemTranslator['cups_posunout_start_cupu'];?><br> <br>
            <input type="text" name="datum_cas_startu" value="<?=strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu);?>" />             
            <br> <br>
            <button type="submit"><?=$systemTranslator['turnaje_posunout_start'];?></button>
          </form>
         </div>
        </div>
      </div>
    </div>   
  <?}?>
  <?if($currentUserID==$tournament->id_uzivatele&&$tournament->dohrano==0&&count($spider)>0&&$roundToGenerate<=$lastSpiderRound&&$lastRoundDone==1){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['cupy_zahajeni_kola'];?></div>
			  <div class="window-content mCustomScrollbar">
			   <div class="pad-16 align-center">
          <form method="post" action="<?=$atournamentRoundGenerate;?>">
            <?=$systemTranslator['cups_rozlosovat_zapasy_a_zahajit_nove_kolo_text'];?><br> <br>                      
            <br> <br>
            <button type="submit"><?=$systemTranslator['cups_zahajit_nove_kolo'];?></button>
          </form>
         </div>
        </div>
      </div>
    </div>     
  <?}?>        
  <?if(count($spider)>0){?>
    <div class="col col-1-1">
      <div class="window window-matches-wrap">
				<div class="window-header"><?=$systemTranslator['cups_rozpis_zapasu_nadpis'];?></div>
				<div class="window-content window-matches mCustomScrollbar">
				  <div class="grid grid-nospace">
				    <?foreach($spider as $web){?> 
				      <div class="col col-1-4">
				        <?for($i=0;$i<$web->celkovyPocetTurnaju;$i++){?>
				          <?if(isset($tournaments[$web->kolo][$i+1])&&$tournaments[$web->kolo][$i+1]->idt>0){$webTour=$tournaments[$web->kolo][$i+1];?>
                    <a class="match <?
                    		if($webTour->hraji_tymy==0){
                    			foreach($players as $pl){if(isset($tournamentsHraciTymy[$webTour->idt][$pl->id_hrace])){
                    				echo ' player-'.$pl->id_hrace.' ';                    				
                    				}}                    			                    			
                    		}else{
                    			foreach($loggedTeams2 as $tX2){if(isset($tournamentsHraciTymy[$webTour->idt][$tX2->idt])){
                    				echo ' player-'.$tX2->idt.' ';                    				
                    				}}
                    		}                                       
                      ?>" href="<?=$webTour->aLink;?>" target="_blank">
  										<span class="white"><b><?=$systemTranslator['cups_rozpis_zapasu_zapas'];?> <?=$web->kolo;?>.<?=$i+1;?></b></span><br />
  										<!--
  										<?if(($i+1)<$web->celkovyPocetTurnaju){?>
  										  <?=$systemTranslator['cups_rozpis_zapasu_pocet_ucastniku'];?>: <b><?=$web->PocetHracuPlnyZapas;?></b><br /> 
                        <?=$systemTranslator['cups_rozpis_zapasu_pocet_postupujicich'];?>: <b><?=$web->PocetPostupujicichPlnyZapas;?></b><br />
  										<?}else{?>
  										  <?=$systemTranslator['cups_rozpis_zapasu_pocet_ucastniku'];?>: <b><?=$web->PocetHracuPosledniKolo;?></b><br /> 
                        <?=$systemTranslator['cups_rozpis_zapasu_pocet_postupujicich'];?>: <b><?=$web->PocetPostupujicichPosledniKolo;?></b><br />
  										<?}?>
  										-->
  										<?=$systemTranslator['cups_rozpis_zapasu_start'];?>: <?=strftime('%d.%m.%Y %H:%M',$webTour->datum_cas_startu);?><br /><br />
  										<!--<?if($webTour->datum_cas_konce>100000){?><?=$systemTranslator['cups_rozpis_zapasu_konec'];?>: <?=strftime('%d.%m.%Y %H:%M',$webTour->datum_cas_konce);?><br /><?}?>-->
  										<div class="players">
												<?if($webTour->hraji_tymy==0){?>
		                      <?foreach($players as $pl){if(isset($tournamentsHraciTymy[$webTour->idt][$pl->id_hrace])){?>                          				                            
                          <div class="highlight-player player-<?=$pl->id_hrace?><?if($webTour->prerozdelene_vyhry==1){?> <?=$tournamentsHraciTymy[$webTour->idt][$pl->id_hrace]==1?'win':'lose';?><?}?>"><span class="fa fa-eye"></span>&nbsp;<span class="nick"><?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?></span></div>
                				<?}}?>
		            				<?}else{?>
		            				  <?foreach($loggedTeams2 as $tX2){if(isset($tournamentsHraciTymy[$webTour->idt][$tX2->idt])){?>
                          <div class="highlight-player player-<?=$tX2->idt?><?if($webTour->prerozdelene_vyhry==1){?> <?=$tournamentsHraciTymy[$webTour->idt][$tX2->idt]==1?'win':'lose';?><?}?>"><span class="fa fa-eye"></span>&nbsp;<span class="nick"><?=$tX2->nazev;?></span></div>
              				  <?}}?> 
		            				<?}?>
              				</div>
  									</a>
									<?}else{?>
									  <div class="match match-not-started">
									    <b><?=str_replace('XXX',$web->kolo.'.'.($i+1),$systemTranslator['cups_rozpis_zapasu_zapas_nezalozen']);?></b>
									  </div>
									<?}?>
                <?}?> 
				      </div>
				    <?}?>
				  </div>
        </div>
			</div>
    </div>
  <?}?>
  <?if(count($spider)>0){foreach($spider as $web){?>
    <div class="col col-1-1">
  		<div class="window">
  			<div class="window-header">
          <?=$web->kolo;?>. <?=$systemTranslator['cups_patro_pavouka_nadpis'];?>
          <?if($web->celkovyPocetTurnaju==1){?> - <?=$systemTranslator['cups_finalni_zapas'];?><?}?>
          - <?=$systemTranslator['cups_pocet_ucastniku'];?>: <?=$web->celkovyPocetHracuTymu;?><?if($web->pocetPostupujicich>0){?>, <?=$systemTranslator['cups_pocet_postupujicich'];?>: <?=$web->pocetPostupujicich;?><?}?>          
        </div>
  			<div style="min-height:35px;" class="window-content">
  			  <div class="pad-16 align-center">
  			    <div class="overflow-table">
              <table class="datatable" style="width:100%">
                <tr>
                  <th style="text-align:center;"><?=$systemTranslator['cups_cislo_konkretniho_zapasu'];?></th>
                  <th style="text-align:center;"><?=$systemTranslator['cups_pocet_ucastniku'];?></th>
                  <?if($web->celkovyPocetTurnaju>1){?><th style="text-align:center;"><?=$systemTranslator['cups_pocet_postupujicich'];?></th><?}?>
                  <th style="text-align:center;"><?=$systemTranslator['cups_turnaj_zalozen'];?></th>
                  <th style="text-align:center;"><?=$systemTranslator['cups_turnaj_odehran'];?></th>
                  <th style="text-align:center;"><?=$systemTranslator['cups_datum_cas_startu_turnaje'];?></th>
                  <th style="text-align:center;"><?=$systemTranslator['cups_datum_cas_konce_turnaje'];?></th>
                  <th style="text-align:center;"><?=$systemTranslator['cups_hraji_v_turnaji'];?></th>
                  <th></th>
                </tr>
                <?for($i=0;$i<$web->celkovyPocetTurnaju;$i++){?>
                  <tr>
                    <td style="text-align:center;"><?=$web->kolo;?>.<?=$i+1;?></td>
                    
                    <?if(($i+1)<$web->celkovyPocetTurnaju){?>
                      <td style="text-align:center;"><?=$web->PocetHracuPlnyZapas;?></td>
                      <?if($web->celkovyPocetTurnaju>1){?><td style="text-align:center;"><?=$web->PocetPostupujicichPlnyZapas;?></td><?}?>
                    <?}else{?>
                      <td style="text-align:center;"><?=$web->PocetHracuPosledniKolo;?></td>
                      <?if($web->celkovyPocetTurnaju>1){?><td style="text-align:center;"><?=$web->PocetPostupujicichPosledniKolo;?></td><?}?>
                    <?}?>
                    
                    <?if(isset($tournaments[$web->kolo][$i+1])&&$tournaments[$web->kolo][$i+1]->idt>0){$webTour=$tournaments[$web->kolo][$i+1];?>
                      <td style="text-align:center;"><?=$systemTranslator['obecne_ano'];?></td>
                      <td style="text-align:center;"><?if($webTour->dohrano==1&&$webTour->prerozdelene_vyhry==1){?><?=$systemTranslator['obecne_ano'];?><?}else{?><?=$systemTranslator['obecne_ne'];?><?}?></td>
                      <td style="text-align:center;"><?=strftime('%d.%m.%Y %H:%M',$webTour->datum_cas_startu);?></td>
                      <td style="text-align:center;"><?if($webTour->datum_cas_konce>100000&&$webTour->prerozdelene_vyhry==1){?><?=strftime('%d.%m.%Y %H:%M',$webTour->datum_cas_konce);?><?}else{?>-<?}?></td>
                      <td style="text-align:center;"><?if($webTour->hraji_v_turnaji==1){?><?=$systemTranslator['obecne_ano'];?><?}else{?><?=$systemTranslator['obecne_ne'];?><?}?></td>
                      <td style="text-align:center;"><a href="<?=$webTour->aLink?>" class="button btn-dark" target="_blank" title="<?=$systemTranslator['turnaje_zobrazit'];?>"><?=$systemTranslator['turnaje_zobrazit'];?></a></td>
                    <?}else{?>                       
                      <td style="text-align:center;"><?=$systemTranslator['obecne_ne'];?></td>
                      <td style="text-align:center;"><?=$systemTranslator['obecne_ne'];?></td>      
                      <td style="text-align:center;">-</td>  
                      <td style="text-align:center;">-</td>
                      <td style="text-align:center;"><?=$systemTranslator['obecne_ne'];?></td>   
                      <td style="text-align:center;"></td>                         
                    <?}?>
                  </tr>
                <?}?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?}}?>       
</div>        
