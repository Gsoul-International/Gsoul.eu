<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>     
  <li><a href=""><?=$systemTranslator['tymy'];?></a></li>
  </ul>
</div> 
<h1><?=$systemTranslator['tymy'];?></h1>      
  <form method="post" action="<?=$afilter?>">
    <div class="grid align-left grid-form grid-semipadded align-middle">
      <div class="col col-1-3"><label for="leader"><?=$systemTranslator['tymy_vyhledani_dle_leadera'];?></label><input type="text" size="15" name="f_leader" id="leader" maxlength="63" value="<?=$filter->f_leader?>"></div>
      <div class="col col-1-3"><label for="nazev"><?=$systemTranslator['tymy_vyhledani_dle_nazvu_tymu'];?></label><input type="text" size="15" name="f_nazev" id="nazev" maxlength="63" value="<?=$filter->f_nazev?>"></div>
      <div class="col col-1-3"><label for="nazevhry"><?=$systemTranslator['tymy_vyhledani_dle_nazvu_hry'];?></label><input type="text" size="15" name="f_nazevhry" id="nazevhry" maxlength="63" value="<?=$filter->f_nazevhry?>"></div>
      <div class="col col-1-3">
        <label for="zobrazit"><?=$systemTranslator['tymy_filtr_zobrazeni_tymu'];?></label>
        <select name="f_zobrazit" id="zobrazit">
          <option value="leader" <?if($filter->f_zobrazit=='leader'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_zobrazeni_tymu_jsem_leader'];?></option>
          <option value="leader_clen" <?if($filter->f_zobrazit=='leader_clen'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_zobrazeni_tymu_jsem_leader_nebo_clen'];?></option>
          <option value="clen" <?if($filter->f_zobrazit=='clen'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_zobrazeni_tymu_jsem_clen'];?></option>
          <option value="vse" <?if($filter->f_zobrazit=='vse'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_zobrazeni_tymu_vse'];?></option>
        </select>        
      </div>
      <div class="col col-1-3">
        <label for="prijima_cleny"><?=$systemTranslator['tymy_filtr_prijima_cleny'];?></label>
        <select name="f_prijima_cleny" id="prijima_cleny">
          <option value="vse" <?if($filter->f_prijima_cleny=='vse'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_prijima_cleny_vse'];?></option>
          <option value="ano" <?if($filter->f_prijima_cleny=='ano'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_prijima_cleny_ano']?></option>
          <option value="ne" <?if($filter->f_prijima_cleny=='ne'){?>selected<?}?> ><?=$systemTranslator['tymy_filtr_prijima_cleny_ne']?></option>
        </select>        
      </div>
      <div class="col col-1-3">
        <label for="poradi"><?=$systemTranslator['tymy_filtr_poradi'];?></label>
        <select name="f_poradi" id="poradi">
          <option value="idtdesc" <?if($filter->f_poradi=='idtdesc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_od_nejnovejsich'];?></option>
          <option value="idtasc" <?if($filter->f_poradi=='idtasc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_od_nejstarsich'];?></option>
          <option value="nazevtymuasc" <?if($filter->f_poradi=='nazevtymuasc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_tymu_az'];?></option>
          <option value="nazevtymudesc" <?if($filter->f_poradi=='nazevtymudesc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_tymu_za'];?></option>
          <option value="nazevhryasc" <?if($filter->f_poradi=='nazevhryasc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_hry_az'];?></option>
          <option value="nazevhrydesc" <?if($filter->f_poradi=='nazevhrydesc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_hry_za'];?></option>   
          <option value="nazevleaderasc" <?if($filter->f_poradi=='nazevleaderasc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_leadera_az'];?></option>
          <option value="nazevleaderdesc" <?if($filter->f_poradi=='nazevleaderdesc'){?>selected<?}?> ><?=$systemTranslator['tymy_poradi_nazev_leadera_za'];?></option>                   
        </select>        
      </div>
      <div class="col col-3-3  align-center"><br /><button type="submit" class="large"><?=$systemTranslator['tymy_tlacitko_filtrovat'];?></button></div>
    </div>
  </form>
  <br>
<?if(count($teams)>0){?> 
  <h2><?=$systemTranslator['prehled_vsech_tymu'];?></h2>
  <br />
  <table class="datatable" style="width:100%">
    <thead>
      <tr><th><?=$systemTranslator['tymy_hra'];?></th><th><?=$systemTranslator['tymy_nazev_tymu'];?></th><th><?=$systemTranslator['tymy_leader'];?></th><th><?=$systemTranslator['tymy_pocet_clenu'];?></th><th><?=$systemTranslator['tymy_prijma_nove_cleny'];?></th><th></th></tr> 
    </thead>
    <tbody> 
    <?foreach($teams as $t){?>                                                           
      <tr class="clickable-row" data-href="<?=$t->aDetail;?>" role="row">    
        <td><?=$t->nazev_hry?></td>
        <td><?=$t->nazev?></td>
        <td><?=$t->hrac?></td>
        <td><?=$t->pocet?></td>
        <td><?=$t->prijima_hrace==1?$systemTranslator['tymy_prijima_hrace']:$systemTranslator['tymy_neprijima_hrace'];?></td>
        <td><a href="<?=$t->aDetail;?>" title="<?=$systemTranslator['tymy_zobrazit_tym']?>" class="button"><?=$systemTranslator['tymy_zobrazit_tym']?></a></td>        
      </tr>     
    <?}?>          
    </tbody>    
  </table>  
  <?if(count($paginnator)>3){?>
    <ul class="pagination align-center">
      <li><a title="<?=$systemTranslator['strankovani_predchozi_strana'];?>" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
      <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
        <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
        <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
        <?}?>
      <?}}?>      
      <li><a title="<?=$systemTranslator['strankovani_nasledujici_strana'];?>" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
    </ul>
  <?}?>
  <br />
<?}else{?>
  <?=frontendMessage('normal',$systemTranslator['tymy_danym_filtrovacim_kriteriim_neodpovida_zadny_tym']);?>
<?}?>
<?if(count($gamesForCreate)>0){?>
  <h2><?=$systemTranslator['vytvor_svuj_tym'];?></h2>
  <form method="post" action="<?=$acreate?>">
    <table width="100%">
      <tr>
        <th><?=$systemTranslator['zvolte_hru'];?></th>
        <td class="align-center">        
          <select style="width:100%;" name="idg"> 
            <?foreach($gamesForCreate as $gfcK=>$gfcV){?>        
              <option value="<?=$gfcK;?>"><?=$gfcV;?></option>
            <?}?>            
          </select>
        </td> 
        <td class="align-center"><button type="submit" class="large"><?=$systemTranslator['vytvorit_tym'];?></button></td>  
      </tr>      
    </table>
  </form>
<?}else{?>
  <h2><?=$systemTranslator['jiz_mate_tymy_pro_vsechny_hry'];?></h2>
<?}?>


