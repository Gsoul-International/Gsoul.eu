<h1>Jazyky - editace - <?=$l->nazev?></h1>
<div class="BackendBack">
  <br>
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis jazyků</a><br>
</div>
<?if($message=='saved'){?><p><b>Jazyk úspěšně uložen.</b></p><br /><?}?>
<br />
<table>
  <tr>      
    <th>Jazyk</th>
    <th colspan="2">Vlaječka</th>
    <th>Zobrazovat</th>
    <th colspan="2">Výchozí</th>
  </tr>
  <form method="post" action="<?=$aeditlang?>" enctype="multipart/form-data">
    <tr>
      <td><input type="text" name="nazev" value="<?=$l->nazev?>" /></td>
      <td><?if(file_exists('userfiles/langs/'.$l->idl.'.png')){?><img src="/<?='userfiles/langs/'.$l->idl.'.png';?>" /><?}else{?>-<?}?></td>
      <td>Změnit:<br /><input type="file" name="image" /></td>
      <td><input type="checkbox" name="aktivni" value="1" <?if($l->aktivni==1){?>checked<?}?> /></td>
      <td><input type="checkbox" name="vychozi" value="1" <?if($l->vychozi==1){?>checked<?}?> /></td>
      <td style="text-align:right"><input type="submit" value="Uložit" /></td>
    </tr>
  </form> 
</table>
<br />
<table>
  <tr>
    <th>Překlad k porovnání:</th>
    <td>
      <?foreach($nextLanguages as $nl){?>
        <a href="<?=$nl->link?>">
          <?if($nl->active==1){?>
            <b><?=$nl->nazev?></b>
          <?}else{?>
            <?=$nl->nazev?>
          <?}?>  
        </a> &nbsp; 
      <?}?>
    </td>    
  </tr>
  <tr><td colspan="2"><b>UPOZORNĚNÍ: kliknutím na jiný překlad k porovnání se neuložená data ztratí!</b></td></tr>
</table>
<br />
<form method="post" action="<?=$aeditlangvalues?>">
  <table>
    <tr>
      <th>Umístnění / Text k přeložení</th>
      <?if($druhePreklady->idp>0){?>
        <th><i>Překlad - <?=$druhePreklady->nazev?></i></th>
      <?}?>
      <th width="50%">Překlad</th>
    </tr>
    <?foreach($keys as $k){?>  
      <tr>
        <td><?=$k->popis?></td>
        <?if($druhePreklady->idp>0){?>
          <td>
            <?if(isset($druhePreklady->vals[$k->idlk])){?> 
              <i><?=trim($druhePreklady->vals[$k->idlk]->hodnota);?></i>
            <?}?>
          </td>
        <?}?>
        <td>
          <?if(isset($vals[$k->idlk])){?>
            <input type="text" name="exist[<?=$vals[$k->idlk]->idlv?>][]" value="<?=trim($vals[$k->idlk]->hodnota);?>" style="width:100%;<?if(trim($vals[$k->idlk]->hodnota)==''){?>background-color:#ef9292;<?}?>" />  
          <?}else{?>
            <input type="text" name="not_exist[<?=$k->idlk?>][]" value="" style="width:100%;background-color:#ef9292;" />
          <?}?>
        </td>
      </tr>
    <?}?>
    <tr>
      <td colspan="2" style="text-align:center"><input type="submit" value="Uložit" /></td>
    </tr>
  </table>
</form>