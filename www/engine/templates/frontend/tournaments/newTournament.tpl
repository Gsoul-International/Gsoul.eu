<div class="breadcrumb">
  <?foreach($currGameModules as $cGM){foreach($modulePlatforms as $mp){if($mp->idgp==$cGM->idgp){?>
    <ul>    
      <li><a href="/"><?=$mainpagename;?></a></li>   
      <li><a href="<?=$aback?>"><?=$systemTranslator['turnaje_turnaje2'];?></a></li>
      <li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li>
      <li><a href="<?=$abackGame?>"><?=$game->nazev?></a></li>               
      <li><a href=""><?=$systemTranslator['turnaje_pridani_noveho_turnaje'];?></a></li>
    </ul>
  <?}}}?>
</div> 
<h1><?=$systemTranslator['turnaje_turnaje2'];?> - <?=$systemTranslator['turnaje_pridani_noveho_turnaje_pro_hru'];?> <?=$game->nazev?></h1>
<br>
<?if(trim($systemTranslator['novy_match_informace'])!=''){?>
  <div class="text-box">
		<div class="icon-wrap">
			<em class="fa fa-info"></em>
		</div>
		<div class="content-wrap">
		  <?=$systemTranslator['novy_match_informace'];?>
		</div>
	</div>
	<br />
<?}?>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<form method="post" action="<?=$anext?>">
  <div class="col col-1-1">
    <div class="mCustomScrollbarX">
    	<table class="datatable" style="width:100%">
    		<thead>
    		  <tr>
            <th><?=$systemTranslator['turnaje_typ_hry'];?></th>
            <th><?=$systemTranslator['turnaje_vyplata'];?></th>
            <th><?=$systemTranslator['turnaje_minimalni_pocet_tymu'];?></th>
            <th><?=$systemTranslator['turnaje_maximalni_pocet_tymu'];?></th>
            <th><?=$systemTranslator['turnaje_minimalni_pocet_hracu'];?></th>
            <th><?=$systemTranslator['turnaje_maximalni_pocet_hracu'];?></th>        
          </tr>
        </thead>
  		  <tbody>
          <?foreach($types as $g){?>
            <tr>
              <td>
                <label style="display:contents;font-size:1em;cursor:pointer;">
                  <input type="radio" name="idgt" value="<?=$g->idgt?>" <?if($g->idgt==$ngData->idgt){?>checked<?}?> required />
                  <?=$g->nazev?>
                </label>
              </td> 
              <td>
                <?foreach($vyplaty as $vp){if($vp->idgwt==$g->tournament_id_vyplaty){?>
                  <?=$vp->nazev?>
                <?}}?>
              </td>
              <td><?=$g->tournament_minimalni_pocet_tymu?></td>
              <td><?=$g->tournament_maximalni_pocet_tymu?></td>
              <td><?=$g->tournament_minimalni_pocet_hracu?></td>
              <td><?=$g->tournament_maximalni_pocet_hracu?></td>              
            </tr>
          <?}?>
        </tbody>
      </table>      
    </div>
    <br />
  </div>    
  <div class="col col-1-3"><label for="cena"><?=$systemTranslator['turnaje_vstupni_poplatek'];?> [$]</label><input type="text" name="cena" id="cena" value="<?=$ngData->cena?>" required /></div>
  <div class="col col-1-3"><label for="datum_cas_startu"><?=$systemTranslator['turnaje_datum_a_cas_zahajeni'];?></label><input type="text" name="datum_cas_startu" id="datum_cas_startu" value="<?=$ngData->datum_cas_startu?>" required /></div>   
  <div class="col col-1-3">
    <label for="hraji_tymy"><?=$systemTranslator['turnaje_hra_na_tymy'];?></label>
    <select name="hraji_tymy" id="hraji_tymy" />
      <option <?if($ngData->hraji_tymy==0){?>selected<?}?> value="0"><?=$systemTranslator['turnaje_hra_na_tymy_ne'];?></option>
      <option <?if($ngData->hraji_tymy==1){?>selected<?}?> value="1"><?=$systemTranslator['turnaje_hra_na_tymy_ano'];?></option>
    </select>
  </div>    
  <div class="col col-1-3"><label for="poznamka_zakladatele"><?=$systemTranslator['turnaje_poznamka_zakladatele'];?></label><textarea name="poznamka_zakladatele" id="poznamka_zakladatele" rows="3" cols="25" required><?=$ngData->poznamka_zakladatele?></textarea></div>  
  <div class="col col-1-3"><label for="pravidla_turnaje_mala"><?=$systemTranslator['turnaje_pravidla_turnaje'];?></label><textarea name="pravidla_turnaje_mala" id="pravidla_turnaje_mala" rows="3" cols="25" required><?=$ngData->pravidla_turnaje_mala?></textarea></div>
  <div class="col col-1-3"><label for="pravidla_turnaje_velka"><?=$systemTranslator['turnaje_podrobna_pravidla_turnaje'];?></label><textarea name="pravidla_turnaje_velka" id="pravidla_turnaje_velka" rows="3" cols="25" required><?=$ngData->pravidla_turnaje_velka?></textarea></div>
  <div class="col col-1-3"><label for="titul_turnaje"><?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?></label><input type="text" name="titul_turnaje" id="titul_turnaje" value="<?=$ngData->titul_turnaje?>" /></div>
  <div class="col col-1-3"><label for="heslo_turnaje"><?=$systemTranslator['prihlasovaci_udaje_turnaje_heslo'];?></label><input type="text" name="heslo_turnaje" id="heslo_turnaje" value="<?=$ngData->heslo_turnaje?>" /></div>
  <?if(count($params)>0){?>    
      <?foreach($params as $p){if(count($subParams[$p->idp]>0)){?>        
        <?if($p->typ_v_turnaji_cupu==1){ //checkbox?>
          <div class="col col-1-3">
          <label><?=$p->nazev?>:</label>
          <?foreach($subParams[$p->idp] as $sp){?>
             <label style="padding-right:5px;color:#939390;" class="float-left">
              <input style="width:20px;" type="checkbox" name="params[]" <?if(in_array($sp->idpv,$ngData->params)){?>checked<?}?> value="<?=$sp->idpv?>" />
              <?=$sp->nazev?>
            </label>
          <?}?>
          </div>          
        <?}elseif($p->typ_v_turnaji_cupu==2){ //select?>
          <div class="col col-1-3">
            <label for="param_<?=$p->idp?>"><?=$p->nazev?>:</label> 
            <select name="params[]" id="param_<?=$p->idp?>">
              <option value="0"><?=$systemTranslator['obecne_nezadano'];?></option>
              <?foreach($subParams[$p->idp] as $sp){?>
                <option <?if(in_array($sp->idpv,$ngData->params)){?>selected<?}?> value="<?=$sp->idpv?>" ><?=$sp->nazev?></option>                             
              <?}?>
            </select>
          </div>
        <?}elseif($p->typ_v_turnaji_cupu==3){ //text?> 
          <div class="col col-1-3">
            <label for="param_<?=$p->idp?>"><?=$p->nazev?>:</label> 
            <input type="text" name="paramText_<?=$p->idp?>" id="param_<?=$p->idp?>" value="<?=$ngData->paramsText[$p->idp]?>" maxlength="127" />
          </div>
        <?}?>        
      <?}}?>    
  <?}?>
  <div class="col col-3-3 align-justify">
    <br />
    <div class="text-box">
			<div class="icon-wrap">
				<em class="fa fa-info"></em>
			</div>
			<div class="content-wrap">
				<b><?=$systemTranslator['turnaje_vstupni_naklady'];?></b> <?=$systemTranslator['turnaje_na_vytvoreni_turnaje_jsou'];?> <b><?=printcost($moduleGame->poplatek_za_zalozeni_turnaje)?> $</b>. <?=$systemTranslator['turnaje_tyto_naklady_vam_budou_odecteny_z_uctu_po_vytvoreni'];?><br><?=$systemTranslator['turnaje_info_o_prerozdeleni'];?><br><?=$systemTranslator['turnaje_info_odmena_ve_vysi'];?> <b><?=printcost($moduleGame->procenta_pro_zakladatele)?> % <?=$systemTranslator['turnaje_z_vkladu_hracu'];?>.</b>
			</div>
		</div>
  </div>
  <div class="col col-3-3 align-center"> 
    &nbsp;<br />
    <label style="display:none;">
      <input type="checkbox" name="having_licence" value="1" checked />
      <b><?=$systemTranslator['turnaje_cupy_potvrzeni_licence'];?></b>
      &nbsp;&nbsp;&nbsp;
      <a class="button" target="_blank" href="<?=$systemTranslator['turnaj_cup_odkaz_informace_o_licencich'];?>" title="<?=$systemTranslator['turnaj_cup_tlacitko_informace_o_licencich'];?>"><?=$systemTranslator['turnaj_cup_tlacitko_informace_o_licencich'];?></a>
    </label>
    <br />&nbsp;<br /> 
    <button type="submit" class="large"><?=$systemTranslator['turnaje_vytvorit_turnaj'];?></button>
  </div>
  
</form>
</div>