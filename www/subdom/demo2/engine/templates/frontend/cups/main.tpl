<div class="breadcrumb">
  <?foreach($currGameModules as $cGM){foreach($modulePlatforms as $mp){if($mp->idgp==$cGM->idgp){?>
    <ul>
      <li><a href="/"><?=$mainpagename;?></a></li>   
      <li><a href="<?=$athis?>"><?=$systemTranslator['cups_cups'];?></a></li>
      <li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li>
      <li><a href=""><?=$currGame->nazev?></a></li>     
    </ul>
  <?}}}?>
</div> 
<div class="grid align-left grid-form grid-semipadded align-middle mobile-1000-align-left cup-buttons">
  <div class="col col-1-3">
    <a class="button large" href="<?=$aGoToTournaments?>" title="<?=$systemTranslator['turnaje_turnaje'];?>"><?=$systemTranslator['turnaje_turnaje'];?></a> 
  </div>
  <div class="col col-1-3 align-center">
    <a class="button large btn-dark" href="<?=$aGoToCups?>" title="<?=$systemTranslator['cups_cups_velke'];?>"><?=$systemTranslator['cups_cups_velke'];?></a>
  </div>
  <div class="col col-1-3 align-right">  
    <?if(count($games)>0&&$userID>0&&$user->overen_email==1&&($currGame->zaklada_jen_admin==0||$user->prava>0)){?>
      <form method="post" action="<?=$anewpost?>">    
        <input type="hidden" name="idg" value="<?=$currGame->idg?>" />
        <button type="submit" class="large"><?=$systemTranslator['cups_vytvorit_novy_cup'];?></button>
      </form>
    <?}?>
  </div>
</div>
<h1><?=$systemTranslator['cups_cups'];?></h1>

<?if(count($params)>0){?>
  <form method="post" action="<?=$athisGame?>">
    <input type="hidden" name="action" value="setFilter" />
    <table width="100%">
      <?foreach($params as $p){if(count($subParams[$p->idp])>0){?> 
        <tr>           
          <td><b style="padding:8px;" class="float-left"><?=$p->nazev?>:</b></td>
          <td>
          <?foreach($subParams[$p->idp] as $sp){?>
            <label style="padding:8px" class="float-left">
              <input type="checkbox" name="params[]" <?if(in_array($sp->idpv,$filterParams)){?>checked<?}?> value="<?=$sp->idpv?>" />
              <?=$sp->nazev?>
            </label>
          <?}?>
          </td>
        </tr>
      <?}}?>
      <tr><td colspan="2" align="right"><button type="submit" class="large"><?=$systemTranslator['obecne_filtrovat'];?></button></td></tr>  
    </table>   
  </form>
<?}?>
<div class="mCustomScrollbarX">
	<table class="datatable" style="width:100%">
		<thead>
			<tr>
        <?if(count($params)>0){foreach($params as $p){if(count($subParams[$p->idp])>0&&$p->v_tabulce==1){?>
          <th><?=$p->nazev?></th>
        <?}}}?>				
				<?if($currGame->zobraz_login==1){?><th><?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?></th><?}?>
        <?if($currGame->zobraz_typhry==1){?><th><?=$systemTranslator['turnaje_typ_hry'];?></th><?}?>
				<?if($currGame->zobraz_buyin==1){?><th><?=$systemTranslator['turnaje_buy_in'];?></th><?}?>
				<?if($currGame->zobraz_pocethracu==1){?><th><?=$systemTranslator['turnaje_hracutymu'];?></th><?}?>
        <?if($currGame->zobraz_datumzahajeni==1){?><th><?=$systemTranslator['turnaje_v_case'];?></th><?}?>												        
				<?if($currGame->zobrazit_dohrano==1){?><th><?=$systemTranslator['turnaje_dohrano'];?></th><?}?>
        <?if($currGame->zobraz_pravidla==1){?><th><?=$systemTranslator['turnaje_pravidla'];?></th><?}?>			
			</tr>
		</thead>
		<tbody>
		  <?foreach($tournaments as $ts){?>           
        <tr <?if($userID>=0){?>class='clickable-row' data-href='<?=$ts->aview?>'<?}else{?>class=""<?}?> >
          <?if(count($params)>0){foreach($params as $p){if(count($subParams[$p->idp])>0&&$p->v_tabulce==1){?>
            <td><?foreach($subParams[$p->idp] as $sp){if(in_array($sp->idpv,$ts->params)){?><?=$sp->nazev?> <?}}?></td>
          <?}}}?>          
          <?if($currGame->zobraz_login==1){?> <td><?=$ts->titulek_cupu;?></td><?}?>
          <?if($currGame->zobraz_typhry==1){?><td><?=$types2[$ts->id_typu_hry]?></td><?}?>
          <?if($currGame->zobraz_buyin==1){?><td><?=printcost($ts->cena)?>&nbsp;$</td><?}?>
          <?if($currGame->zobraz_pocethracu==1){?><td><?=$ts->minimalni_pocet_hracutymu?> -&nbsp;<?=$ts->maximalni_pocet_hracutymu?></td><?}?>
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
<ul class="pagination align-center">
  <li><a title="<?=$systemTranslator['strankovani_predchozi_strana'];?>" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
  <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
    <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
    <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
    <?}?>
  <?}}?>      
  <li><a title="<?=$systemTranslator['strankovani_nasledujici_strana'];?>" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
</ul>