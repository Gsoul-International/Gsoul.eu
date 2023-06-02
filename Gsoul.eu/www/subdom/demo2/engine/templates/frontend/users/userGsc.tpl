<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_ucetni_zustatek'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_ucetni_zustatek'];?></h1>
<p><?=$systemTranslator['uzivatel_aktualni_stav_uzivatelskeho_konta'];?>: <b><?=printcost($user->ucetni_zustatek)?> $</b></p>
<br />
<a class="button large" href="<?=$apaypal?>"><?=$systemTranslator['uzivatel_zobrazit_vsechna_dobiti_a_vyplaceni_kreditu'];?></a>         
<?if(count($coins)>0){?>
  <h2><?=$systemTranslator['uzivatel_historie_uzivatelskeho_konta'];?></h2>
  <br>
  <table width="100%">
    <tr><th><?=$systemTranslator['obecne_datum_a_cas'];?></th><th><?=$systemTranslator['obecne_operace'];?></th><th><?=$systemTranslator['obecne_castka'];?></th><th><?=$systemTranslator['obecne_duvod'];?></th></tr>  
    <?foreach($coins as $cs){?>
      <tr>
        <td><?=strftime('%d.%m.%Y %H:%M',$cs->datum_cas)?></td>
        <td><?if($cs->coins<0){?><span style="color:#800000;"><?=$systemTranslator['obecne_vyber'];?></span><?}else{?><span style="color:#008000;"><?=$systemTranslator['obecne_vklad'];?></span><?}?></td>
        <td <?if($cs->coins<0){?>style="color:#800000;"<?}else{?>style="color:#008000;"<?}?> ><strong><?if($cs->coins>=0){?>+<?}?><?=printcost($cs->coins)?> $<strong></td>        
        <td><?
        echo str_replace(
          array('Poplatek za založení turnaje','Poplatek za založení cupu','Zápisné turnaje','Vyřazení z turnaje','Vyplacení kreditu','Dokoupení kreditu','Vraceni zamitnute transakce','Odměna za turnaj','Výhra v turnaji',),
          array($systemTranslator['transakce_poplatek_zalozeni_turnaje'],$systemTranslator['transakce_poplatek_zalozeni_cupu'],$systemTranslator['transakce_zapisne_turnaje'],$systemTranslator['transakce_vyrazeni_z_turnaje'],$systemTranslator['transakce_vyplaceni_kreditu'],$systemTranslator['transakce_dokoupeni_kreditu'],$systemTranslator['transakce_vraceni_zamitnute_transakce'],$systemTranslator['transakce_odmena_za_turnaj'],$systemTranslator['transakce_vyhra_v_turnaji']),
          $cs->duvod);
        ?></td>    
      </tr>
    <?}?>      
    <tr>     
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
<?}?>