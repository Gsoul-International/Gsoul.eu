<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>     
  <li><a href=""><?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?></a></li>
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?></h1>
<?if($user->overen_paypal_email==1){?>
  <div class="grid align-left grid-form grid-semipadded align-middle">
    <div class="col col-1-2">    
      <h2><?=$systemTranslator['paypal_dobiti_kreditu'];?></h2>
      <form method="post" action="<?=$apaymentin?>">
        <table width="100%">
          <tr>
            <th><?=$systemTranslator['paypal_castka_k_dobiti_kreditu'];?></th>
            <td class="align-center"><input style="width:200px;" type="text" name="amount" value="1" /></td> 
          </tr>
          <tr>       
            <th><?=$systemTranslator['paypal_mena'];?></th>
            <td class="align-center">
              <select style="width:200px;" name="currency">         
                <option value="USD">USD / $  (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];?> 01.00 USD)</option>                 
                <?/*
                <option value="EUR">EUR / €  (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];? > 00.80 EUR)</option>
                <option value="CZK">CZK / Kč (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];? > 22.00 CZK)</option>
                */?>
              </select>
            </td> 
          </tr>
          <tr>  
            <td></td>                                         
            <td class="align-center"><button type="submit" class="large"><?=$systemTranslator['paypal_dobit_kredit'];?></button></td> 
          </tr>  
        </table>
      </form>      
    </div>
    <div class="col col-1-2">
      <h2><?=$systemTranslator['paypal_vyplaceni_kreditu'];?></h2>
      <form method="post" action="<?=$apaymentout?>">
        <table width="100%">
          <tr>
            <th><?=$systemTranslator['paypal_castka_k_vyplaceni_kreditu'];?></th>
            <td class="align-center"><input style="width:200px;" type="text" name="amount" value="1" /></td>   
          </tr>
          <tr>       
            <th><?=$systemTranslator['paypal_mena'];?></th>
            <td class="align-center">
              <select style="width:200px;" name="currency">         
                <option value="USD">USD / $  (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];?> 01.00 USD)</option>
                <?/*
                <option value="EUR">EUR / €  (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];? > 00.80 EUR)</option>
                <option value="CZK">CZK / Kč (1.00 $ <?=$systemTranslator['paypal_kreditu_za'];? > 22.00 CZK)</option>
                */?>
              </select>
            </td>  
          </tr>        
          <tr>  
            <td></td>            
            <td class="align-center"><button type="submit" class="large"><?=$systemTranslator['paypal_vyplatit_kredit'];?></button></td> 
          </tr>  
        </table>
      </form>
    </div>
  </div>
<?}else{?>
  <h2><?=$systemTranslator['paypal_neovereny_email_nejde_delat_nic'];?></h2>
<?}?>
<?if(count($coins)>0){?> 
  <?if(getget('message')=='refresh-success'){?><div class="h3 gap-top-20"><?=$systemTranslator['paypal_refresh_success'];?></div><?}?> 
  <h2><?=$systemTranslator['paypal_prehled_transakci'];?></h2>
  <br>
  <table width="100%">
    <tr><th><?=$systemTranslator['obecne_id'];?></th><th><?=$systemTranslator['obecne_datum_a_cas'];?></th><th><?=$systemTranslator['obecne_operace'];?></th><th><?=$systemTranslator['obecne_castka'];?></th><th><?=$systemTranslator['obecne_kredit'];?></th><th><?=$systemTranslator['paypal_status'];?></th></tr>  
    <?foreach($coins as $cs){?>                                                     
      <tr>      
        <td><?=$cs->paymentId?></td>
        <td><?=strftime('%d.%m.%Y %H:%M',$cs->dateTime)?></td>
        <td><?if($cs->type=='pay-in'){?><span style="color:#008000;"><?=$systemTranslator['paypal_dobiti_kreditu'];?></span><?}else{?><span style="color:#800000;"><?=$systemTranslator['paypal_cerpani_kreditu'];?></span><?}?></td>
        <td><?=printcost($cs->cost)?> <?=$cs->currency?></td>        
        <td><?=printcost($cs->credit)?> $</td>    
        <td>
          <?if($cs->status=='success'){?>
            <span style="color:#008000;"><?=$systemTranslator['paypal_dokonceno'];?></span>
          <?}elseif($cs->status=='unsuccess'){?>
            <span style="color:#800000;"><?=$systemTranslator['paypal_nezdarilo_se'];?></span>
          <?}else{?>          
            <?=$systemTranslator['paypal_v_procesu'];?>            
          <?}?>
        </td>
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