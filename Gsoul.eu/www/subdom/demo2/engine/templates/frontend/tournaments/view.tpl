<div class="breadcrumb">
  <?foreach($currGameModules as $cGM){foreach($modulePlatforms as $mp){if($mp->idgp==$cGM->idgp){?>
    <ul>
      <li><a href="/"><?=$mainpagename;?></a></li>   
      <li><a href="<?=$aback?>"><?=$systemTranslator['turnaje_turnaje2'];?></a></li>
      <li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li>
      <li><a href="<?=$abackGame?>"><?=$game->nazev?></a></li>          
      <li><a href=""><?=$systemTranslator['turnaje_zobrazeni_turnaje'];?><?if($tournament->id_cupu>0){?> - <?=$systemTranslator['turnaje_cup'];?> <?=$cup->titulek_cupu?> - <?=$tournament->id_kola_cupu?>.<?=$tournament->id_zapasu_cupu?><?}?></a></li>
    </ul>
  <?}}}?>
</div> 
<h1><?=$systemTranslator['turnaje_turnaje2'];?> - <?=$systemTranslator['turnaje_zobrazeni_turnaje'];?> <?if($tournament->id_cupu>0){?> - <?=$systemTranslator['turnaje_cup'];?> <?=$cup->titulek_cupu?> - <?=$tournament->id_kola_cupu?>.<?=$tournament->id_zapasu_cupu?><?}?></h1>
<div class="align-center img-max-100">
<?=$tournament->banner;?>
</div>
<div class="grid grid-server-detail gap-top-16">
  <div class="col col-1-1">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['odkaz_turnaje'];?></div>
			<div class="window-content" style="min-height:35px;"><div class="size-20 align-center gap-top-16">https://<?=$_SERVER['SERVER_NAME']?><?=$athis;?></div></div>
    </div>
  </div>
  <?if($tournament->id_cupu>0){?>
    <div class="col col-1-1">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['odkaz_cup'];?></div>
  			<div class="window-content" style="min-height:35px;"><div class=" align-center gap-top-16">
          <span class="size-20">https://<?=$_SERVER['SERVER_NAME']?><?=$acup;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <a href="<?=$acup;?>" class="button btn-dark"><?=$systemTranslator['zobrazit_cup'];?></a> <br />&nbsp;
        </div></div>
      </div>
    </div>
  <?}?>
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
				  <?if($tournament->neni_odmenovan==0){?>
  					<?=$systemTranslator['turnaje_buy_in2'];?>:
  					<span class="yellow"><?=printcost($tournament->cena)?> $</span>
					<?}?>
				</div>
				<br />
				<div class="size-20 align-center">
				<?if($tournament->datum_cas_konce>0&&$tournament->dohrano==1){?>
          <b><?=$systemTranslator['turnaje_turnaj_dohran'];?></b> 
        <?}elseif($tournament->datum_cas_startu<time()){?>  
          <b><?=$systemTranslator['turnaje_turnaj_prave_probiha'];?></b>            
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
        <?if($tournament->hraji_tymy==0&&$currentUserID>0){?>	
  				<br />
  				<div class="align-center pad-bottom-16">
  				  <?if(in_array($currentUserID,$playersArr)){}elseif($tournament->maximalni_pocet_hracu==count($players)){}elseif($tournament->dohrano==1){}else{?> 
              <form method="post" action="<?=$agetin;?>">
                <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;         
                <button type="submit" onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
              </form>                
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
              <?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?>              
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
				<?if($tournament->hraji_tymy==0&&$tournament->id_cupu==0){?>
  				<div class="row align-center">
  				  <?if(in_array($currentUserID,$playersArr)){?>
              <?=$systemTranslator['turnaje_jiz_jste_prihlasen'];?>
            <?}elseif($tournament->maximalni_pocet_hracu==count($players)){?> 
              <?=$systemTranslator['turnaje_v_turnaji_je_jiz_max_hracu'];?>
            <?}elseif($tournament->dohrano==1){?>       
              <?=$systemTranslator['turnaje_turnaj_je_jiz_dohran'];?>        
            <?}else{?> 
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
			<div class="window-header"><?=$systemTranslator['turnaje_vyhry'];?></div>
			<div class="window-content mCustomScrollbar window-content-large">
			  <?if($tournament->neni_odmenovan==1){?>
			    <?if($tournament->pocet_postupujicich>0){?>
			      <?for($i=1;$i<=$tournament->pocet_postupujicich;$i++){?>
			        <div class="row">
                <div class="text-circle-32 pad-left-2"><?=$i?>.</div>
                &nbsp;&nbsp;&nbsp;&nbsp; <?=$systemTranslator['turnaje_postup_dal'];?>
                <?if($tournament->prerozdelene_vyhry==1){?>
                  &nbsp;&nbsp; - &nbsp;&nbsp;   
                  <?if($tournament->hraji_tymy==1){?>
                    <?=$loggedTeams2[$postupujici_tymy[$i-1]]->nazev;?>
                  <?}else{?>
                    <?=$playersNicks[$postupujici_hraci[$i-1]]!=''?$playersNicks[$postupujici_hraci[$i-1]]:$users[$postupujici_hraci[$i-1]]->osloveni?>
                  <?}?>                  
                <?}?>
              </div>
			      <?}?>
			    <?}?>
			  <?}else{?>
          <?if(isset($vyplata->idgwt)&&$vyplata->idgwt>0){?>
  			   <?if($tournament->hraji_tymy==1){?>
            <?for($i=1;$i<=$vyplata->winners_count;$i++){$xx='misto_'.$i;$yy='coins_'.$i;?>        
              <div class="row">
                <div class="text-circle-32 pad-left-2"><?=$i?>.</div>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?=$vyplata->$xx;?>&nbsp;%<?if($tournament->prerozdelene_vyhry==1){?> &nbsp;&nbsp;&nbsp;&nbsp; <?=printcost($vyplataData->$yy);?>&nbsp;$<?}?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?if($tournament->prerozdelene_vyhry==1){$ln='idu_misto_'.$i?><?=$loggedTeams2[$vyplataData->$ln]->nazev;?><?}?>              
              </div>
            <?}?>
           <?}else{?>
            <?for($i=1;$i<=$vyplata->winners_count;$i++){$xx='misto_'.$i;$yy='coins_'.$i;?>        
              <div class="row">
                <div class="text-circle-32 pad-left-2"><?=$i?>.</div>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?=$vyplata->$xx;?>&nbsp;%<?if($tournament->prerozdelene_vyhry==1){?> &nbsp;&nbsp;&nbsp;&nbsp; <?=printcost($vyplataData->$yy);?>&nbsp;$<?}?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?if($tournament->prerozdelene_vyhry==1){$ln='idu_misto_'.$i?><?=$playersNicks[$vyplataData->$ln]!=''?$playersNicks[$vyplataData->$ln]:$users[$vyplataData->$ln]->osloveni?><?}?>
                
              </div>
            <?}?>
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
				  <?if($tournament->neni_odmenovan==0){?>
  					<?=$systemTranslator['turnaje_buy_in2'];?>:
  					<span class="yellow"><?=printcost($tournament->cena)?> $</span>
					<?}?>
				</div>
				<br />
				<div class="size-20 align-center">
					<?if($tournament->datum_cas_konce>0&&$tournament->dohrano==1){?>
            <b><?=$systemTranslator['turnaje_turnaj_dohran'];?></b> 
          <?}elseif($tournament->datum_cas_startu<time()){?>  
            <b><?=$systemTranslator['turnaje_turnaj_prave_probiha'];?></b>            
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
  					<?if(in_array($currentUserID,$playersArr)){}elseif($tournament->maximalni_pocet_hracu==count($players)){}elseif($tournament->dohrano==1){}else{?> 
              <?if($tournament->id_cupu==0){?>
                <?if($currentUserID>0){?>
                  <form method="post" action="<?=$agetin;?>">  
                    <input style="width:100px;" type="text" name="username" value="<?=$userX->data->osloveni?>" /> &nbsp;&nbsp;&nbsp;      
                    <button type="submit" onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_se_prihlasit'];?>');" ><?=$systemTranslator['turnaje_prihlasit_se'];?></button>
                  </form>
                <?}?> 
              <?}?>               
            <?}?> 	
  				</div>
				<?}?>
			</div>
		</div>
	</div>	
  <?if($tournament->hraji_tymy==1&&$tournament->dohrano==0&&$tournament->id_cupu==0&&$currentUserID>0){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['tymove_turnaje_nadpis_prihlaseni'];?></div>
  			<div class="window-content mCustomScrollbar">
  			 <div class="window-inner-wrap">
  			  <br />
          <div class="align-center">
            <b><?=$systemTranslator['tymove_turnaje_prihlaseni_tymu_do_turnaje'];?></b><br /><br />
          </div>
  			  <?if($currentUserTeam->idt>0){?>
  			    <?if($UserHaveLoggedOwnTeam==1){?>
  			       <div class="align-center">
  			         <?=$systemTranslator['tymove_turnaje_vas_prihlaseny_tym'];?> <?=$currentUserTeam->nazev;?>
               </div> 
            <?}else{?>
              <?if($tournament->maximalni_pocet_tymu<=count($loggedTeams)){?>
                <div class="align-center">
                  <?=$systemTranslator['tymove_turnaje_turnaj_je_jiz_plne_obsazen'];?>
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
  			    <?=$systemTranslator['tymove_turnaje_nemate_tym_k_prihlaseni'];?>
  			  <?}?>  			  
  			  <br /><br />
  			  <div class="align-center">
  			   <b><?=$systemTranslator['tymove_turnaje_prihlaseni_hrace_do_turnaje'];?></b><br /><br />
  			  </div>
  			  <div class="align-center">
    			  <?if(in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)) ){?>
              <?if(in_array($currentUserID,$playersArr)){?>
                <?=$systemTranslator['tymove_turnaje_jiz_jste_prihlasen_pod_tymem'];?> 
              <?}else{?>
                <?=$systemTranslator['nahradnik_jiz_jste_prihlasen_pod_tymem'];?>
              <?}?>
              <?=$loggedTeams2[$currentPlayer->id_tymu]->nazev;?> 
            <?}elseif($tournament->maximalni_pocet_hracu<=count($players)){?>
              <?=$systemTranslator['tymove_turnaje_turnaj_je_jiz_plne_obsazen'];?>
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
    			    <?=$systemTranslator['tymove_turnaje_ani_jeden_z_vasich_tymu_se_turnaje_neucastni'];?>
    			  <?}?>
  			  </div> 
          <br /> 			   			 
  			  <div class="align-center">
  			   <b><?=$systemTranslator['tymove_turnaje_prihlasene_tymy'];?></b>
  			  </div>
  			  <?if(count($loggedTeams2)<1){?>
  			    <br /><br /><b><?=$systemTranslator['tymove_turnaje_zatim_nebyly_prihlaseny_zadne_tymy'];?></b>
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
  <?if($tournament->hraji_tymy==1&&$tournament->dohrano==0&&$tournament->id_cupu==0&&$currentUserID>0){ //hraji tymy a neni dohrano a neni cup a prihlasen uzivatel?>
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
  <?if((in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)))&&$currentUserID>0 ){?>
  	<div class="col col-2-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['turnaje_chat'];?></div>
  			<div class="window-content mCustomScrollbar">
  			 <div class="pad-16">
  			  <?if(isset($chatData)&&count($chatData)>0){?><?foreach($chatData as $chD){?>              
            <div class="bold"><?=strftime('%d.%m.%Y %H:%M:%S',$chD->ts);?> / <?=$playersNicks[$chD->id_hrace]!=''?$playersNicks[$chD->id_hrace]:$users[$chD->id_hrace]->osloveni?></div>
            <div><?=$chD->obsah;?></div>             
          <?}?><?}?>
          <form method="post" action="<?=$anewchat;?>">    
            <input style="width:80%" type="text" name="obsah" />  &nbsp;&nbsp;&nbsp;            
            <button type="submit"><?=$systemTranslator['turnaje_odeslat'];?></button>   
          </form> 
         </div> 	
  			</div>
  		</div>
  	</div>
	<?}?>	
	<div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['turnaje_pravidla_turnaje_big'];?></div>
			<div class="window-content mCustomScrollbar">
				<div class="window-inner-wrap bg-white">
					<?if($tournament->pravidla_turnaje_mala!=$game->pravidla_turnaje){?>!!! <?}?>
          <?=str_replace("\n",'<br>',$tournament->pravidla_turnaje_mala);?> <br />
          <?if($tournament->pravidla_turnaje_velka!=$game->podrobna_pravidla_turnaje){?>!!! <?}?>
					<?=str_replace("\n",'<br>',$tournament->pravidla_turnaje_velka);?>
				</div>
				<div class="window-inner-wrap bg-nearlywhite">					
					<?=str_replace("\n",'<br>',$tournament->poznamka_zakladatele);?>
				</div>        
			</div>
		</div>
	</div>
	<?if($currentUserID==$tournament->id_uzivatele){?>
	  <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['turnaje_ukonceni_turnaje_big'];?></div>
			  <div class="window-content mCustomScrollbar">
			   <div class="pad-16 align-center">
			    <?if($tournament->dohrano==0&&count($screens)>0){?>
            <?if(count($playersArr)>=$vyplata->winners_count||$tournament->id_cupu>0){?>              
              <form method="post" action="<?=$aendtournament;?>" enctype="multipart/form-data">
                <b><?=$systemTranslator['turnaje_vyherni_poradi_hracu'];?>:</b> <br />    
                <?for($i=1;$i<=$winners_count;$i++){?>
                  <?=$i;?>. <?=$systemTranslator['turnaje_place'];?>:
                  <select name="position_<?=$i?>">              
                    <?if($tournament->hraji_tymy==1){?>
                      <option value="0"><?=$systemTranslator['tournaments_choose_team'];?></option>
                      <?foreach($loggedTeams as $lt){?><option value="<?=$lt->idt?>"><?=$lt->nazev?></option><?}?>
                    <?}else{?>
                      <option value="0"><?=$systemTranslator['tournaments_choose_player'];?></option>
                      <?foreach($players as $pl){?><option value="<?=$pl->id_hrace?>"><?=$playersNicks[$pl->id_hrace]!=''?$playersNicks[$pl->id_hrace]:$users[$pl->id_hrace]->osloveni?></option><?}?>
                    <?}?>
                  </select>
                  <br>
                <?}?>                                           
                <b><?=$systemTranslator['turnaje_poznamky'];?>:</b> <br />    
                <input type="text" placeholder="<?=$systemTranslator['turnaje_vyherni_poradi_hracu_poznamky'];?>" name="poznamka_skore" value="" /><br />  <br />
                <button onclick="return confirm('<?=$systemTranslator['turnaje_opravdu_nahrat_finalni_skore'];?>');" type="submit"><?=$systemTranslator['turnaje_ukoncit_turnaj'];?></button>
                
                <p><?=$systemTranslator['turnaje_na_zaklade_podkladu_obsluha_prerozdeli_vyhry'];?></p> <br />     
              </form>
            <?}else{?>
              <p><?=$systemTranslator['turnaje_turnaj_pujde_ukoncit_az_po_prihlaseni'];?> <?=$vyplata->winners_count?> <?=$systemTranslator['turnaje_hracu_aktualne_prihlaseno'];?> <?=count($playersArr)?> <?=$systemTranslator['turnaje_hracu'];?>.</p>        
            <?}?>
          <?}elseif($tournament->dohrano==0&&count($screens)==0){?>
            <p><?=$systemTranslator['turnaje_turnaj_pujde_ukoncit_po_nahrani_screenshotu'];?></p> <br>
          <?}else{?>
            <p><?=$systemTranslator['turnaje_turnaj_jste_jiz_ukoncili'];?></p> <br>
            <p>
            <?if($tournament->prerozdelene_vyhry==0){
              echo $systemTranslator['turnaje_obsluha_v_nejblizsi_dobe_prerozdeli_vyhry'];  
            }else{
              echo $systemTranslator['turnaje_vyhry_jsou_jiz_prerozdeleny'];
            }?> </p><br>
          <?}?>
         </div>
        </div>  			
  		</div>
  	</div>
	  <?if($tournament->dohrano==0){?>
	    <?if($tournament->id_cupu==0){?>	      
        <div class="col col-1-3">
      		<div class="window">
      			<div class="window-header"><?=$systemTranslator['turnaje_posunuti_startu_big'];?></div>
    			  <div class="window-content mCustomScrollbar">
    			   <div class="pad-16 align-center">
              <form method="post" action="<?=$atournamentExtend;?>">
                <?=$systemTranslator['turnaje_posunout_start_turnaje'];?><br> <br>
                <input type="text" name="datum_cas_startu" value="<?=strftime('%d.%m.%Y %H:%M',$tournament->datum_cas_startu);?>" />                         
                <br> <br>
                <button type="submit"><?=$systemTranslator['turnaje_posunout_start'];?></button>
              </form>
             </div>
            </div>
          </div>
        </div>      
      <?}?> 
    <?}?>
    <?if($tournament->dohrano==0){?>
	    <?if($tournament->id_cupu==0){?>
	       <div class="col col-1-3">
      		<div class="window">
      			<div class="window-header"><?=$systemTranslator['turnaje_vyrazeni_hracetymu_nadpis'];?></div>
    			  <div class="window-content mCustomScrollbar">
    			   <div class="pad-16 align-center">
              <form method="post" action="<?=$atournamentPlayerKick;?>">
                <?=$systemTranslator['turnaje_vyrazeni_hrace_popis'];?><br> <br>                
                <select name="idu">
                  <option value="0"><?=$systemTranslator['turnaje_zvolte_hrace'];?></option>
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
                <button type="submit" onclick="return submit('<?=$systemTranslator['turnaje_opravdu_vyradit_hrace'];?>');"><?=$systemTranslator['turnaje_vyradit_hrace'];?></button>
              </form>
              <?if($tournament->hraji_tymy==1){?>
                <br> <br>
                <form method="post" action="<?=$atournamentTeamKick;?>">
                  <?=$systemTranslator['turnaje_vyrazeni_tymu_popis'];?><br> <br>                
                  <select name="idu">
                    <option value="0"><?=$systemTranslator['turnaje_zvolte_tym'];?></option>                    
                    <?foreach($loggedTeams2 as $tX2){?>
                      <option value="<?=$tX2->idt;?>">
                        <?=$tX2->nazev;?>
                      </option>
                    <?}?>          
    							</select>  				  					                                                                      
                  <br> <br>
                  <button type="submit" onclick="return submit('<?=$systemTranslator['turnaje_opravdu_vyradit_tym'];?>');"><?=$systemTranslator['turnaje_vyradit_tym'];?></button>
                </form>              
              <?}?>
             </div>
            </div>
          </div>
        </div>         
	    <?}?> 
    <?}?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['prihlasovaci_udaje_turnaje'];?></div>
			  <div class="window-content mCustomScrollbar">
          <div class="pad-16 align-center">			    
          <form method="post" action="<?=$asaveturnamentlogin;?>" enctype="multipart/form-data">                 
            <b>*<?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?>:</b> <br />            
            <input type="text" name="titul_turnaje" value="<?=$tournament->titul_turnaje?>" /> <br /> <br />   
            <b>*<?=$systemTranslator['prihlasovaci_udaje_turnaje_heslo'];?>:</b> <br />            
            <input type="text" name="heslo_turnaje" value="<?=$tournament->heslo_turnaje?>" /> <br /> <br />                
            <button type="submit"><?=$systemTranslator['obecne_ulozit'];?></button>                
            <br />     
          </form>   
          </div>       
        </div>  			
  		</div>
  	</div>
  	<?if($tournament->dohrano==0){?>
	    <?if($tournament->id_cupu==0){?>
  	    <div class="col col-1-3">
      		<div class="window">
      			<div class="window-header"><?=$systemTranslator['zapasy_nastaveni_zapasu'];?></div>
    			  <div class="window-content mCustomScrollbar">
              <div class="pad-16 align-center">			    
              <form method="post" action="<?=$asaveturnamentinfos;?>">
                <table width="100%">
                  <?if($tournament->hraji_tymy==1){?>
                    <tr>           
                      <td colspan="2"><b><?=$systemTranslator['turnaje_maximalni_pocet_tymu'];?></b></td>
                    </tr><tr>
                      <td colspan="2"><input style="width:100%;" type="number" min="<?=$tournament->minimalni_pocet_tymu?>" name="maximalni_pocet_tymu" value="<?=$tournament->maximalni_pocet_tymu?>" /></td>
                    </tr>    
                  <?}else{?>
                    <tr>           
                      <td colspan="2"><b><?=$systemTranslator['turnaje_maximalni_pocet_hracu'];?></b></td>
                    </tr><tr>
                      <td colspan="2"><input style="width:100%;" type="number" min="<?=$tournament->minimalni_pocet_hracu?>" name="maximalni_pocet_hracu" value="<?=$tournament->maximalni_pocet_hracu?>" /></td>
                    </tr>
                  <?}?>
                  <tr><td colspan="2"><b><?=$systemTranslator['turnaje_poznamka_zakladatele'];?></b></td></tr> 
                  <tr><td colspan="2"><textarea name="poznamka_zakladatele" rows="3" cols="25"><?=$tournament->poznamka_zakladatele?></textarea></td></tr>
                  <tr><td colspan="2"><b><?=$systemTranslator['turnaje_pravidla_turnaje'];?></b></td></tr>
                  <tr><td colspan="2"><textarea name="pravidla_turnaje_mala" rows="3" cols="25"><?=$tournament->pravidla_turnaje_mala?></textarea></td></tr>
                  <tr><td colspan="2"><b><?=$systemTranslator['turnaje_podrobna_pravidla_turnaje'];?></b></td></tr>
                  <tr><td colspan="2"><textarea name="pravidla_turnaje_velka" rows="3" cols="25"><?=$tournament->pravidla_turnaje_velka?></textarea></td></tr>                                  
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
                <br />     
              </form>   
              </div>       
            </div>  			
      		</div>
      	</div>     
	    <?}?>
	  <?}?>
	<?}elseif( (in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)))&&$currentUserID>0 ){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['prihlasovaci_udaje_turnaje'];?></div>
		    <div class="window-content mCustomScrollbar">
          <div class="pad-16 align-center">				    
          <b><?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?>:</b> <br />            
          <?=$tournament->titul_turnaje?><br /> <br />   
          <b><?=$systemTranslator['prihlasovaci_udaje_turnaje_heslo'];?>:</b> <br />            
          <?=$tournament->heslo_turnaje?><br /> <br />
          </div>                              
        </div>  			
  		</div>
  	</div>
  <?}?>
  <div class="col col-1-3">
  	<div class="window">
  		<div class="window-header"><?=$systemTranslator['turnaje_informace'];?></div>
  		<div class="window-content mCustomScrollbar">
  			<div class="window-inner-wrap bg-white">
          <table>
            <tr>
              <td><b><?=$systemTranslator['turnaje_hra'];?>:</b></td>
              <td><?=$game->nazev?></td>
            </tr>            
            <?if($tournament->neni_odmenovan==0){?>
              <tr>    
                <td><b><?=$systemTranslator['turnaje_buy_in'];?></b></td>
                <td><?=printcost($tournament->cena)?> $</td>
              </tr>
            <?}?>
            <tr>
              <td><b><?=$systemTranslator['turnaje_typ_hry'];?></b></td>
              <td><?=$type->nazev?></td>
            </tr>            
            <tr> 
              <td><b><?=$systemTranslator['turnaje_hra_na_tymy'];?></b></td>
              <td><?=$tournament->hraji_tymy==1?$systemTranslator['turnaje_hra_na_tymy_ano']:$systemTranslator['turnaje_hra_na_tymy_ne'];?></td>
            </tr>
            <?if($tournament->hraji_tymy==1){?>
              <tr>
                <td><b><?=$systemTranslator['turnaje_pocet_tymu'];?></b></td>
                <td><?=$tournament->minimalni_pocet_tymu?> - <?=$tournament->maximalni_pocet_tymu?></td>
              </tr>
              <tr>
                <td><b><?=$systemTranslator['turnaje_pocet_hracu_na_team'];?></b></td>
                <td><?=ceil($tournament->maximalni_pocet_hracu/$tournament->maximalni_pocet_tymu)?></td>
              </tr>
            <?}?>
            <tr>
              <td><b><?=$systemTranslator['turnaje_pocet_hracu'];?></b></td>
              <td><?=$tournament->minimalni_pocet_hracu?> - <?=$tournament->maximalni_pocet_hracu?></td>
            </tr>            
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
              <td><b><?=$systemTranslator['turnaje_datum_dohrani'];?></b></td>
              <td>
                <?if($tournament->datum_cas_konce>0&&$tournament->dohrano==1){?>
                  <?=strftime('%d.%m.%Y ve %H:%M',$tournament->datum_cas_konce);?>
                <?}else{?>
                  <?=$systemTranslator['turnaje_zatim_nedohrano'];?>
                <?}?>
              </td>
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
    		<div class="window-header"><?=$systemTranslator['parametry_zapasu'];?></div>
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
  <?if(count($tournamentRounds)>0||$currentUserID==$tournament->id_uzivatele){?>
    <div class="col col-1-1">
      <div class="window">
    		<div class="window-header"><?=$systemTranslator['turnaje_podzapasy_nadpis_podzapasy'];?></div>
    		<div class="window-content mCustomScrollbar">
    			<div class="window-inner-wrap bg-white">
            <div class="grid">
              <?if($currentUserID==$tournament->id_uzivatele){?>
            	  <div class="col col-1-3">
              		<div class="window">
              			<div class="window-header"><?=$systemTranslator['turnaje_podzapasy_pridat_podzapas_big'];?></div>
            			  <div class="window-content mCustomScrollbar">
                      <div class="pad-16 align-center">  			               
                        <form method="post" action="<?=$aaddround;?>">                                                         
                          <b>*<?=$systemTranslator['turnaje_podzapasy_nazev'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_nazev'];?>" name="nazev" value="" /><br />  <br />
                          <b>*<?=$systemTranslator['turnaje_podzapasy_datum_a_cas'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_datum_a_cas'];?>" name="datum_cas" value="<?=strftime('%d.%m.%Y %H:%M',time());?>" /><br />  <br />
                          <b><?=$systemTranslator['turnaje_podzapasy_mapa'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_mapa'];?>" name="mapa" value="" /><br />  <br />
                          <b>*<?=$systemTranslator['turnaje_podzapasy_titulek'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_titulek'];?>" name="titulek" value="" /><br />  <br />
                          <b>*<?=$systemTranslator['turnaje_podzapasy_heslo'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_heslo'];?>" name="heslo" value="" /><br />  <br />
                          <b><?=$systemTranslator['turnaje_podzapasy_poznamka'];?>:</b> <br />    
                          <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_poznamka'];?>" name="poznamka" value="" /><br />  <br />
                          <b>*<?=$systemTranslator['turnaje_podzapasy_ucastnici_zapasu'];?>:</b> <br />   
                          <?if($tournament->hraji_tymy==1){?>                                    
                            <?foreach($loggedTeams2 as $tX2){?>
                              <div class="align-left">
                                <input type="checkbox" style="width:35px;" name="players[]" value="<?=$tX2->idt;?>" />
                                <?=$tX2->nazev;?>
                              </div>                     
                            <?}?>              							  				  					                                                                      
                            <br />                               
                          <?}else{?>
                            <?foreach($players as $pl){?>
                              <div class="align-left">
                                <input type="checkbox" style="width:35px;" name="players[]" value="<?=$pl->id_hrace;?>" />          
              				          <?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?>
                              </div>  
                            <?}?>                                    				
                            <br /> 
                          <?}?>
                          <button type="submit"><?=$systemTranslator['turnaje_podzapasy_pridej_zapas'];?></button>                  
                          <p><?=$systemTranslator['turnaje_podzapasy_poznamka_k_pridani'];?></p> <br />     
                        </form>                                    
                      </div>
                    </div>  			
              		</div>
              	</div>
              <?}?>
              <?foreach($tournamentRounds as $tRs){?>
                <div class="col col-1-3">
                  <div class="window">
                		<div class="window-header"><?=$tRs->nazev;?></div>
                		<div class="window-content mCustomScrollbar">
                			<div class="window-inner-wrap bg-white">
                			  <table>
                			    <tbody>                			      
                			      <tr><td><b><?=$systemTranslator['turnaje_podzapasy_datum_a_cas'];?>:</b></td><td colspan="2"><?=strftime('%d.%m.%Y %H:%M',$tRs->datum_cas);?></td></tr>
                			      <tr><td><b><?=$systemTranslator['turnaje_podzapasy_mapa'];?>:</b></td><td colspan="2"><?=$tRs->mapa;?></td></tr>
                			      <?if(in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)) ){?>
                              <tr><td><b><?=$systemTranslator['turnaje_podzapasy_titulek'];?>:</b></td><td colspan="2"><?=$tRs->titulek;?></td></tr>
                  			      <tr><td><b><?=$systemTranslator['turnaje_podzapasy_heslo'];?>:</b></td><td colspan="2"><?=$tRs->heslo;?></td></tr>
                			      <?}?>
                            <tr><td><b><?=$systemTranslator['turnaje_podzapasy_poznamka'];?>:</b></td><td colspan="2"><?=$tRs->poznamka;?></td></tr>
                			      <?if($tournament->hraji_tymy==1){?>                                    
                              <?foreach($loggedTeams2 as $tX2){?>
                                <?if(in_array($tX2->idt,$tournamentRoundsScoresTwo[$tRs->idgtr])){?>
                                  <tr>
                                    <td><b><?=$tX2->nazev;?></b></td>
                                    <td><?=$systemTranslator['turnaje_podzapasy_skore'];?>:</td> 
                                    <td><?=$tournamentRoundsScores[$tRs->idgtr][$tX2->idt];?></td>
                                  </tr>
                                <?}?>
                              <?}?>
                            <?}else{?>
                              <?foreach($players as $pl){?>
                                <?if(in_array($pl->id_hrace,$tournamentRoundsScoresTwo[$tRs->idgtr])){?>
                                  <tr>
                                    <td><b><?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?></b></td>
                                    <td><?=$systemTranslator['turnaje_podzapasy_skore'];?>:</td> 
                                    <td><?=$tournamentRoundsScores[$tRs->idgtr][$pl->id_hrace];?></td>
                                  </tr>    
                                <?}?>
                              <?}?>
                            <?}?>
                			    </tbody>
                			  </table>                			  
                        <?if($currentUserID==$tournament->id_uzivatele||$userX->data->prava>0){?>
                          <br /><center><b><?=$systemTranslator['turnaje_podzapasy_zmena_zapasu'];?></b></center><br /><br />
                          <form method="post" action="<?=$aeditround;?>">
                            <input type="hidden" name="idgtr" value="<?=$tRs->idgtr;?>" />
                            <b>*<?=$systemTranslator['turnaje_podzapasy_nazev'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_nazev'];?>" name="nazev" value="<?=$tRs->nazev;?>" /><br />  <br />
                            <b>*<?=$systemTranslator['turnaje_podzapasy_datum_a_cas'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_datum_a_cas'];?>" name="datum_cas" value="<?=strftime('%d.%m.%Y %H:%M',$tRs->datum_cas);?>" /><br />  <br />
                            <b><?=$systemTranslator['turnaje_podzapasy_mapa'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_mapa'];?>" name="mapa" value="<?=$tRs->mapa;?>" /><br />  <br />
                            <b>*<?=$systemTranslator['turnaje_podzapasy_titulek'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_titulek'];?>" name="titulek" value="<?=$tRs->titulek;?>" /><br />  <br />
                            <b>*<?=$systemTranslator['turnaje_podzapasy_heslo'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_heslo'];?>" name="heslo" value="<?=$tRs->heslo;?>" /><br />  <br />
                            <b><?=$systemTranslator['turnaje_podzapasy_poznamka'];?>:</b> <br />    
                            <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_poznamka'];?>" name="poznamka" value="<?=$tRs->poznamka;?>" /><br />  <br />                              
                            <?if($tournament->hraji_tymy==1){?>                                    
                              <?foreach($loggedTeams2 as $tX2){?>
                                <?if(in_array($tX2->idt,$tournamentRoundsScoresTwo[$tRs->idgtr])){?>
                                  <b><?=$tX2->nazev;?> <?=$systemTranslator['turnaje_podzapasy_skore'];?>:</b><br />
                                  <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_skore'];?>" name="score_<?=$tournamentRoundsScoresThree[$tRs->idgtr][$tX2->idt]?>" value="<?=$tournamentRoundsScores[$tRs->idgtr][$tX2->idt];?>" />
                                  <br />  <br />                                   
                                <?}?>
                              <?}?>
                            <?}else{?>
                              <?foreach($players as $pl){?>
                                <?if(in_array($pl->id_hrace,$tournamentRoundsScoresTwo[$tRs->idgtr])){?>
                                  <b><?=$pl->nick==''?$users[$pl->id_hrace]->osloveni:$pl->nick?> <?=$systemTranslator['turnaje_podzapasy_skore'];?>:</b><br />
                                  <input type="text" placeholder="<?=$systemTranslator['turnaje_podzapasy_skore'];?>" name="score_<?=$tournamentRoundsScoresThree[$tRs->idgtr][$pl->id_hrace]?>" value="<?=$tournamentRoundsScores[$tRs->idgtr][$pl->id_hrace];?>" />
                                  <br />  <br />                                  
                                <?}?>
                              <?}?>
                            <?}?>
                            <center><button type="submit"><?=$systemTranslator['turnaje_podzapasy_uloz_zapas'];?></button></center>    
                          </form>
                        <?}?>                			  
                      </div>
                    </div>
                  </div>                              
                </div>
              <?}?>                                                                                   
            </div>
    			</div>
        </div>
      </div>    
    </div>   
  <?}?>
  <?if(count($tournamentRounds)>0){?>
    <div class="col col-1-1">
      <div class="window">
    		<div class="window-header"><?=$systemTranslator['turnaje_podzapasy_celkove_skore'];?></div>
    		<div class="window-content mCustomScrollbar">
    			<div class="window-inner-wrap bg-white">
    			  <table width="100%">
    			    <tr>
    			      <th><?=$systemTranslator['turnaje_podzapasy_poradi'];?></th>
    			      <th><?if($tournament->hraji_tymy==1){?><?=$systemTranslator['turnaje_podzapasy_tymy'];?><?}else{?><?=$systemTranslator['turnaje_podzapasy_hraci'];?><?}?></th>
    			      <th><?=$systemTranslator['turnaje_podzapasy_skore'];?></th>
    			    </tr>
    			    <?$ii=0;foreach($tournamentRoundsTotalScores as $trtsTot){$ii++;?>
    			      <tr>
      			      <td><?=$ii;?>.</td>
      			      <td>
                    <?if($tournament->hraji_tymy==1){?>
                      <?=$loggedTeams2[$trtsTot->id_hrace_tymu]->nazev;?>                      
                    <?}else{?>
                      <?=$playersNicks[$trtsTot->id_hrace_tymu]!=''?$playersNicks[$trtsTot->id_hrace_tymu]:$users[$trtsTot->id_hrace_tymu]->osloveni?>
                    <?}?>
                  </td>
      			      <td><?=$trtsTot->finalskore;?></td>
      			    </tr>
    			    <?}?>
    			  </table>
    			</div>
    		</div>
    	</div>
  	</div>
  <?}?>
  <div class="col col-1-1">
    <div class="window">
  		<div class="window-header"><?=$systemTranslator['turnaje_screenshoty_nadpis'];?></div>
  		<div class="window-content mCustomScrollbar">
  			<div class="window-inner-wrap bg-white">
          <?if(count($screens)>0){?>
            <table width="100%">
              <tr>
                <td><b><?=$systemTranslator['turnaje_screenshoty_hrac'];?></b></td>
                <td><b><?=$systemTranslator['turnaje_screenshoty_datumcas'];?></b></td>
                <td><b><?=$systemTranslator['turnaje_screenshoty_typ'];?></b></td>
                <td align="right"><b><?/*=$systemTranslator['turnaje_screenshoty_zobrazit_stahnout'];*/?></b></td>
              </tr>
              <?foreach($screens as $ss){?>
                <tr>
                  <td>                   
                    <?=$playersNicks[$ss->idu]!=''?$playersNicks[$ss->idu]:$users[$ss->idu]->osloveni?>
                  </td>
                  <td><?=strftime('%d.%m.%Y %H:%M:%S',$ss->datum_cas)?></td>
                  <td><?=$ss->typ==0?$systemTranslator['turnaje_screenshoty_typ_screenshot']:$systemTranslator['turnaje_screenshoty_typ_config']?></td>
                  <td align="right"><a class="button" target="_blank" href="/<?=$ss->cesta?>" download ><?=$systemTranslator['turnaje_screenshoty_zobrazit_stahnout'];?></a></td>
                </tr>
              <?}?>              
            </table>
            <br />
          <?}else{?>
            <b><?=$systemTranslator['turnaje_screenshoty_no_data'];?></b> <br /><br />
          <?}?>
          <br />
          <?if($tournament->dohrano==0&&$currentUserID>0&&($currentUserID==$tournament->id_uzivatele || in_array($currentUserID,$playersArr) || (in_array($currentUserID,$alternatesArr)) )){?>
            <form method="post" action="<?=$auploadScreenshot;?>" enctype="multipart/form-data">                
              <b><?=$systemTranslator['turnaje_screenshoty_nahrajte_screenshot'];?></b> <br /> <br />            
              <input type="file" name="screenshot" /> &nbsp;&nbsp;&nbsp;
              <button type="submit"><?=$systemTranslator['turnaje_nahrat_screenshot'];?></button> <br /> <br />
              <?=$systemTranslator['turnaje_screenshoty_povolene_pripony'];?>                       
            </form>
          <?}?>
        </div>
      </div>
    </div>    
  </div>      
</div>