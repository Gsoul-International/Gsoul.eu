<div class="breadcrumb">
  <?foreach($currGameModules as $cGM){foreach($modulePlatforms as $mp){if($mp->idgp==$cGM->idgp){?>
    <ul>
      <li><a href="/"><?=$mainpagename;?></a></li>   
      <li><a href="<?=$athis?>"><?=$systemTranslator['turnaje_turnaje2'];?></a></li>
      <li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li>
      <li><a href=""><?=$currGame->nazev?></a></li>     
    </ul>
  <?}}}?>
</div>
<div class="grid align-left grid-form grid-semipadded align-top tournament-buttons">   
  <div class="col col-1-1">
    <h1 class="gap-top-0 pad-top-0">
      <?=$systemTranslator['cups_cups'];?> - <?=$currGame->nazev?>
      <?if(count($currGameModules)>0){?>
        <small> - <?=implode(', ',$currGameplatforms);?></small>
      <?}?>
    </h1>
  </div>     
  <?if(count($games)>0&&$userID>0&&$user->overen_email==1&&($currGame->zaklada_jen_admin==0||$user->prava>0)){?>
    <?if(count($params)>0){?>
      <div class="col col-1-4 align-center">    
        <form method="post" action="<?=$anewpost?>">    
          <input type="hidden" name="idg" value="<?=$currGame->idg?>" />
          <button type="submit" class="large"><?=$systemTranslator['turnaje_vytvorit_novy_turnaj'];?></button>
        </form>  
      </div>
      <div class="col col-1-4 align-center">  
        <form method="post" action="<?=$anewcuppost?>">    
          <input type="hidden" name="idg" value="<?=$currGame->idg?>" />
          <button type="submit" class="large" ><?=$systemTranslator['cups_vytvorit_novy_cup'];?></button>
        </form>    
      </div>
      <div class="col col-1-4 align-center">   
        <a class="button large" href="<?=$achangethegame?>" title="<?=$systemTranslator['vypis_turnaju_zmenit_hru'];?>"><?=$systemTranslator['vypis_turnaju_zmenit_hru'];?></a>      
      </div>
      <div class="col col-1-4 align-center">   
        <?if($filterEnabled==false){?> 
          <a class="button large" href="<?=$achangefilterview?>" title="<?=$systemTranslator['vypis_turnaju_zobrazit_filtr'];?>"><?=$systemTranslator['vypis_turnaju_zobrazit_filtr'];?></a>
        <?}else{?>
          <a class="button large" href="<?=$achangefilterview?>" title="<?=$systemTranslator['vypis_turnaju_skryt_filtr'];?>"><?=$systemTranslator['vypis_turnaju_skryt_filtr'];?></a>
        <?}?>       
      </div>
    <?}else{?>
      <div class="col col-1-3 align-center">    
        <form method="post" action="<?=$anewpost?>">    
          <input type="hidden" name="idg" value="<?=$currGame->idg?>" />
          <button type="submit" class="large"><?=$systemTranslator['turnaje_vytvorit_novy_turnaj'];?></button>
        </form>  
      </div>
      <div class="col col-1-3 align-center">  
        <form method="post" action="<?=$anewcuppost?>">    
          <input type="hidden" name="idg" value="<?=$currGame->idg?>" />
          <button type="submit" class="large" ><?=$systemTranslator['cups_vytvorit_novy_cup'];?></button>
        </form>    
      </div>
      <div class="col col-1-3 align-center">   
        <a class="button large" href="<?=$achangethegame?>" title="<?=$systemTranslator['vypis_turnaju_zmenit_hru'];?>"><?=$systemTranslator['vypis_turnaju_zmenit_hru'];?></a>      
      </div>
    <?}?>
  <?}else{?>
    <?if(count($params)>0){?>
      <div class="col col-1-2 align-center">   
        <a class="button large" href="<?=$achangethegame?>" title="<?=$systemTranslator['vypis_turnaju_zmenit_hru'];?>"><?=$systemTranslator['vypis_turnaju_zmenit_hru'];?></a>
      </div>
      <div class="col col-1-2 align-center">  
        <?if($filterEnabled==false){?> 
          <a class="button large" href="<?=$achangefilterview?>" title="<?=$systemTranslator['vypis_turnaju_zobrazit_filtr'];?>"><?=$systemTranslator['vypis_turnaju_zobrazit_filtr'];?></a>
        <?}else{?>
          <a class="button large" href="<?=$achangefilterview?>" title="<?=$systemTranslator['vypis_turnaju_skryt_filtr'];?>"><?=$systemTranslator['vypis_turnaju_skryt_filtr'];?></a>
        <?}?>        
      </div>
    <?}else{?>
      <div class="col col-1-1 align-center">   
        <a class="button large" href="<?=$achangethegame?>" title="<?=$systemTranslator['vypis_turnaju_zmenit_hru'];?>"><?=$systemTranslator['vypis_turnaju_zmenit_hru'];?></a>
      </div>
    <?}?>
  <?}?>    
</div>
<br />
<?if(count($params)>0&&$filterEnabled==true){?>
  <form method="post" action="<?=$athisGame?>">
    <input type="hidden" name="action" value="setFilter" />
    <table style="width:100%">
      <tbody>
      <?foreach($params as $p){if($p->typ_v_tabulce>0){?> 
        <tr>           
          <td valign="top"><b style="padding:8px;" class="float-left"><?=$p->nazev?>:</b></td>
          <td>
            <?
            if($p->typ_v_turnaji_cupu==3){
              if($p->typ_v_tabulce==1){ //checkbox?>   
                <?foreach($subParamsText[$p->idp] as $sp){if(trim($sp)!=''){?>
                  <label style="padding:8px" class="float-left">
                    <input type="checkbox" name="paramsText[]" <?if(in_array($p->idp.'_'.$sp,$filterParamsText)){?>checked<?}?> value="<?=$p->idp.'_'.$sp?>" />
                    <?=$sp?>
                  </label>
                <?}}?>
              <?}elseif($p->typ_v_tabulce==2){ //select?>                        
                <select name="paramsText[]" id="param_<?=$p->idp?>" style="width:100%">
                  <option value="0"><?=$systemTranslator['obecne_nezadano'];?></option>
                  <?foreach($subParamsText[$p->idp] as $sp){if(trim($sp)!=''){?>
                    <option <?if(in_array($p->idp.'_'.$sp,$filterParamsText)){?>selected<?}?> value="<?=$p->idp.'_'.$sp?>" ><?=$sp?></option>                             
                  <?}}?>
                </select>                        
              <?}
            }else{
              if($p->typ_v_tabulce==1){ //checkbox?>   
                <?foreach($subParams[$p->idp] as $sp){?>
                  <label style="padding:8px" class="float-left">
                    <input type="checkbox" name="params[]" <?if(in_array($sp->idpv,$filterParams)){?>checked<?}?> value="<?=$sp->idpv?>" />
                    <?=$sp->nazev?>
                  </label>
                <?}?>
              <?}elseif($p->typ_v_tabulce==2){ //select?>                        
                <select name="params[]" id="param_<?=$p->idp?>" style="width:100%">
                  <option value="0"><?=$systemTranslator['obecne_nezadano'];?></option>
                  <?foreach($subParams[$p->idp] as $sp){?>
                    <option <?if(in_array($sp->idpv,$filterParams)){?>selected<?}?> value="<?=$sp->idpv?>" ><?=$sp->nazev?></option>                             
                  <?}?>
                </select>                        
              <?}
            }?>            
          </td>                            
        </tr>
      <?}}?>
      <tr>
        <td colspan="2" class="align-center">
          <a class="button" href="<?=$aunsetFilter;?>" style="padding: 10px 0px;color:darkred;" title="<?=$systemTranslator['obecne_zrusit_filtry'];?>"><i class="fa fa-times-circle"></i></a>   
          &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
          <button type="submit" style="padding: 10px 0px;color:green;" title="<?=$systemTranslator['obecne_filtrovat'];?>"><i class="fa fa-search"></i></button>
        </td>
      </tr>
      </tbody>      
    </table>    
  </form>
  <br />
<?}?>
<?if(count($paginnator)>2){?>
  <ul class="pagination align-center">
    <li><a title="<?=$systemTranslator['strankovani_predchozi_strana'];?>" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
    <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
      <?if($kp>(getget('page','0')-6)&&$kp<(getget('page','0')+8)){?>
      <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
      <?}?>
    <?}}?>      
    <li><a title="<?=$systemTranslator['strankovani_nasledujici_strana'];?>" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
  </ul>
<?}?>
<?if(count($tournaments)>0){?>
  <div class="mCustomScrollbarX">
  	<table class="datatable" style="width:100%">
  		<thead>
  			<tr>
  			  <th><?=$systemTranslator['vypis_turnaju_typ_turnaje'];?></th>
          <?if(count($params)>0){foreach($params as $p){if($p->zobrazovat_v_tabulce==1){?>
            <th><?=$p->nazev?></th>
          <?}}}?>  				
  				<?if($currGame->zobraz_login==1){?><th><?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?></th><?}?>
          <?if($currGame->zobraz_typhry==1){?><th><?=$systemTranslator['turnaje_typ_hry'];?></th><?}?>
  				<?if($currGame->zobraz_buyin==1){?><th><?=$systemTranslator['turnaje_buy_in'];?></th><?}?>
  				<?if($currGame->zobraz_pocethracu==1){?><th><?=$systemTranslator['turnaje_hracu'];?></th><?}?>
          <?if($currGame->zobraz_datumzahajeni==1){?><th><?=$systemTranslator['turnaje_v_case'];?></th><?}?>				  				  				          
  				<?if($currGame->zobrazit_dohrano==1){?><th><?=$systemTranslator['turnaje_dohrano'];?></th><?}?>
          <?if($currGame->zobraz_pravidla==1){?><th><?=$systemTranslator['turnaje_pravidla'];?></th><?}?>			
  			</tr>
  		</thead>
  		<tbody>
  		  <?foreach($tournaments as $ts){?>           
          <tr <?if($userID>=0){?>class='clickable-row' data-href='<?=$ts->aview?>'<?}else{?>class=""<?}?> >
            <td><?=$ts->type=="cup"?$systemTranslator['vypis_turnaju_typ_turnaje_turnaj']:$systemTranslator['vypis_turnaju_typ_turnaje_zapas'];?></td>
            <?if(count($params)>0){foreach($params as $p){if($p->zobrazovat_v_tabulce==1){?>
              <td>
                <?
                if($p->typ_v_turnaji_cupu==3){
                  echo $ts->paramsText[$p->idp];
                }else{
                  $spXa=array();
                  foreach($subParams[$p->idp] as $sp){                    
                    if(in_array($sp->idpv,$ts->params)){
                      $spXa[]=$sp->nazev;
                      }
                    }
                  echo implode(', ',$spXa);
                }                  
                ?>
              </td>
            <?}}}?>            
            <?if($currGame->zobraz_login==1){?><td><?=$ts->titulek;?></td><?}?>
            <?if($currGame->zobraz_typhry==1){?><td><?=$types2[$ts->id_typu_hry]?></td><?}?>
            <?if($currGame->zobraz_buyin==1){?><td><?=printcost($ts->cena)?>&nbsp;$</td><?}?>
            <?if($currGame->zobraz_pocethracu==1){?><td><?=$ts->minimalni_pocet?> -&nbsp;<?=$ts->maximalni_pocet?></td><?}?>
            <?if($currGame->zobraz_datumzahajeni==1){?><td><?=strftime('%d.%m.<br>%H:%M',$ts->datum_cas_startu);?></td><?}?>                                    
            <?if($currGame->zobrazit_dohrano==1){?><td><?=$ts->dohrano==1?$systemTranslator['obecne_ano']:$systemTranslator['obecne_ne'];?></td><?}?>
            <?if($currGame->zobraz_pravidla==1){?>
              <td>
                <a class="xpointer rules-help" data-message="<?=str_replace(array('"',"'"),array('',''),$ts->pravidla_mala)?>" onclick="return false;">
                  <?if($ts->pravidla_mala!=$currGame->pravidla_turnaje){?>!!! <?}?>
                  <?=$systemTranslator['turnaje_pravidla'];?>
                </a>
              </td>
            <?}?>                   
          </tr>  
        <?}?>    
  		</tbody>
  	</table>	
  </div>
<?}else{?>
  <?=frontendMessage('normal',$systemTranslator['vypis_turnaju_zaznamy_nenalezeny']);?>
<?}?>
<?if(count($paginnator)>2){?>
  <ul class="pagination align-center">
    <li><a title="<?=$systemTranslator['strankovani_predchozi_strana'];?>" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
    <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
      <?if($kp>(getget('page','0')-6)&&$kp<(getget('page','0')+8)){?>
      <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
      <?}?>
    <?}}?>      
    <li><a title="<?=$systemTranslator['strankovani_nasledujici_strana'];?>" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
  </ul>
<?}?>