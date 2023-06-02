<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>     
  <li><a href="/?module=FTeams"><?=$systemTranslator['tymy'];?></a></li>
  <li><a href=""><?=$team->nazev;?></a></li>
  </ul>
</div> 
<h1><?=$systemTranslator['tymy'];?> - <?=$team->nazev;?> - <?=$team->nazev_hry;?></h1>
<div class="grid grid-server-detail gap-top-16">
  <div class="col col-1-1">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['odkaz_tymu'];?></div>
			<div class="window-content" style="min-height:35px;"><div class="size-20 align-center gap-top-16">https://<?=$_SERVER['SERVER_NAME']?><?=$athis;?></div></div>
    </div>
  </div>
  <div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$team->nazev;?> - <?=$team->nazev_hry;?></div>
			<div class="window-content window-content-large mCustomScrollbar">
				<div class="row">			
          <div class="user-avatar-32">
            <?if(strlen($leader->user_picture)>0){?>
  					  <img src="/<?=$leader->user_picture;?>" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />
            <?}elseif(strlen($leader->fb_picture)>0){?>
              <img src="<?=$leader->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />    						      						
  					<?}else{?>
  					  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />
  					<?}?>  					  				
  				</div>&nbsp;&nbsp;&nbsp;&nbsp;
          <b><?=$team->hrac?></b>          
				</div>
				<div class="window-inner-wrap bg-white">					
					<?=$team->prijima_hrace==1?$systemTranslator['tymy_prijima_hrace']:$systemTranslator['tymy_neprijima_hrace'];?>
				</div> 
				<div class="window-inner-wrap bg-nearlywhite">					
					<?=$team->popis;?>
				</div>        
			</div>
		</div>
	</div>
  <div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['tymy_detail_players'];?></div>
			<div class="window-content window-content-large mCustomScrollbar">
			 <div class="row">         		   
			   <div class="user-avatar-32">
          <?if(strlen($leader->user_picture)>0){?>
					  <img src="/<?=$leader->user_picture;?>" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />
          <?}elseif(strlen($leader->fb_picture)>0){?>
            <img src="<?=$leader->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />    						      						
					<?}else{?>
					  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($leader->osloveni,'UTF-8'));?>" />
					<?}?>  					  				
				</div>&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="user-name red"><b><?=$leader->osloveni;?></b></span>				
       </div>
			 <?foreach($users as $u){?>          
  				<div class="row">
  					<div class="user-avatar-32">
              <?if(strlen($u->user_picture)>0){?>
  						  <img src="/<?=$u->user_picture;?>" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />
              <?}elseif(strlen($u->fb_picture)>0){?>
                <img src="<?=$u->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />    						      						
  						<?}else{?>
  						  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />
  						<?}?>  					  				
  					</div>&nbsp;&nbsp;&nbsp;&nbsp;
  					<span class="user-name"><?=$u->osloveni;?></span> 
            <?if($currentUserID==$team->id_leadera){?>
              &nbsp;&nbsp;&nbsp;&nbsp;                					
              <a href="<?=$u->adel?>" onclick="return confirm('<?=$systemTranslator['obecne_otazka_opravdu'];?>');"><?=$systemTranslator['tymy_tlacitko_smazat'];?></a>
              <?}?> 					
  				</div>
				<?}?>
			</div>
		</div>
	</div>
	<div class="col col-1-3">
		<div class="window">
			<div class="window-header"><?=$systemTranslator['tymy_clenstvi_v_tymu'];?></div>
			<div class="window-content window-content-large mCustomScrollbar">
			  <?if($currentUserID==$team->id_leadera){?>
			    <div class="window-inner-wrap bg-nearlywhite">					
  					<?=$systemTranslator['tymy_clenstvi_jste_leader_tymu'];?>
  				</div>
          <?if(count($playersWaitingArr2)>0){?>  
    				<div class="window-inner-wrap bg-white">
    				  <b><?=$systemTranslator['tymy_hraci_cekajici_na_schvaleni'];?></b>
    				</div>  				
    				<?foreach($playersWaitingArr2 as $u){?>          
      				<div class="row">
      					<div class="user-avatar-32">
                  <?if(strlen($u->user_picture)>0){?>
      						  <img src="/<?=$u->user_picture;?>" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />
                  <?}elseif(strlen($u->fb_picture)>0){?>
                    <img src="<?=$u->fb_picture;?>" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />    						      						
      						<?}else{?>
      						  <img src="/img/userfiles/avatar.png" alt="<?=addslashes(mb_strtoupper($u->osloveni,'UTF-8'));?>" />
      						<?}?>  					  				
      					</div>&nbsp;&nbsp;&nbsp;&nbsp;
      					<span class="user-name"><?=$u->osloveni;?></span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?=$u->aadd?>" onclick="return confirm('<?=$systemTranslator['obecne_otazka_opravdu'];?>');"><?=$systemTranslator['tymy_tlacitko_pridat'];?></a>  					
                <a href="<?=$u->adel?>" onclick="return confirm('<?=$systemTranslator['obecne_otazka_opravdu'];?>');"><?=$systemTranslator['tymy_tlacitko_smazat'];?></a>
      				</div>
    				<?}?>
  				<?}?>
			  <?}elseif(in_array($currentUserID,$playersArr)){?>
			    <div class="window-inner-wrap bg-nearlywhite">					
  					<?=$systemTranslator['tymy_clenstvi_jste_v_tymu'];?>
  				</div> 
  				<div class="window-inner-wrap bg-white align-center">
  				  <form method="post" action="<?=$aleaveout;?>">                           
              <button onclick="return confirm('<?=$systemTranslator['obecne_otazka_opravdu'];?>');" type="submit"><?=$systemTranslator['tymy_clenstvi_tlacitko_odejit_z_tymu'];?></button>            
            </form> 
  				</div>
			  <?}else{?>
			    <div class="window-inner-wrap bg-nearlywhite">					
  					<?=$systemTranslator['tymy_clenstvi_nejste_v_tymu'];?>
  				</div>
          <div class="window-inner-wrap bg-white">
            <?if(in_array($currentUserID,$playersWaitingArr)){?>
              <?=$systemTranslator['tymy_clenstvi_cekate_na_schvaleni'];?>
            <?}elseif($team->prijima_hrace==1){?>
              <div class="align-center">
      				  <form method="post" action="<?=$agetin;?>">                           
                  <button type="submit"><?=$systemTranslator['tymy_clenstvi_tlacitko_prijit_do_tymu'];?></button>            
                </form> 
              </div>
            <?}else{?>
              <?=$systemTranslator['tymy_neprijima_hrace'];?>
            <?}?>
  				</div> 
			  <?}?>
			</div>
		</div>
	</div>
	<?if(in_array($currentUserID,$playersArr)){?>
  	<div class="col col-2-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['tymy_tymovy_chat'];?></div>
  			<div class="window-content window-content-large mCustomScrollbar ">
  			 <div class="pad-16">
  			  <?if(isset($chatData)&&count($chatData)>0){?><?foreach($chatData as $chD){?>              
            <div class="bold"><?=strftime('%d.%m.%Y %H:%M:%S',$chD->ts);?> / <?=$users2[$chD->id_uzivatele];?></div>
            <div><?=$chD->obsah;?></div>             
          <?}?><?}?>
          <form method="post" action="<?=$anewchat;?>">    
            <input style="width:80%" type="text" name="obsah" />  &nbsp;&nbsp;&nbsp;            
            <button type="submit"><?=$systemTranslator['tymy_tymovy_chat_odeslat'];?></button>   
          </form> 
         </div> 	
  			</div>
  		</div>
  	</div>
	<?}?>	
	<?if($currentUserID==$team->id_leadera||$isAdmin==1){?>
    <div class="col col-1-3">
  		<div class="window">
  			<div class="window-header"><?=$systemTranslator['tymy_nastaveni_tymu'];?></div>
  			<div class="window-content window-content-large mCustomScrollbar">
  			  <form method="post" action="<?=$asave;?>"> 
    			  <div class="window-inner-wrap bg-nearlywhite">					
    					<?=$systemTranslator['tymy_nastaveni_tymu_nazev'];?>
    				</div> 
    				<div class="window-inner-wrap bg-white">
    				  <input style="width:100%;" type="text" name="nazev" value="<?=$team->nazev?>" />
            </div>		
    				<div class="window-inner-wrap bg-nearlywhite">					
    					<?=$systemTranslator['tymy_nastaveni_tymu_popis'];?>
    				</div> 
    				<div class="window-inner-wrap bg-white">
    				  <textarea style="width:100%;" cols="10" rows="2" name="popis" ><?=$team->popis?></textarea>
            </div>
    				<div class="window-inner-wrap bg-nearlywhite">					
    					<?=$systemTranslator['tymy_nastaveni_tymu_prijma_cleny'];?>
    				</div>
            <div class="window-inner-wrap bg-white">
    				  <select name="prijima_hrace" style="width:100%;">                
                <option value="1" <?if($team->prijima_hrace==1){?>selected<?}?> ><?=$systemTranslator['tymy_prijima_hrace']?></option>
                <option value="0" <?if($team->prijima_hrace==0){?>selected<?}?> ><?=$systemTranslator['tymy_neprijima_hrace']?></option>
              </select>  
    				  <br /><br />
    				  <div class="align-center">
    				    <button type="submit"><?=$systemTranslator['tymy_nastaveni_tymu_ulozit_zmeny'];?></button>
              </div>   
            </div> 
    			</form>
  			</div>
  		</div>
    </div>
	<?}?>
</div>  