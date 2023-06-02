<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>     
  <li><a href="">Paypal</a></li>
</ul> 
<h1>Paypal</h1>
<div class="grid align-left grid-form grid-semipadded align-middle">
  <div class="col col-1-2">
    <h2>Dobití kreditu</h2>
    <form method="post" action="<?=$apaymentin?>">
      <table width="100%">
        <tr>
          <th>Částka k dobití ($ kreditu)</th>
          <td><input style="width:200px;" type="text" name="amount" value="1" /></td> 
        </tr>
        <tr>       
          <th>Měna</th>
          <td>
            <select style="width:200px;" name="currency">         
              <option value="USD">USD / $  (1.00 $ kreditu za 01.00 USD)</option>
              <option value="EUR">EUR / €  (1.00 $ kreditu za 00.80 EUR)</option>
              <option value="CZK">CZK / Kč (1.00 $ kreditu za 22.00 CZK)</option>
            </select>
          </td> 
        </tr>
        <tr> 
          <th></th>  
          <td><input style="width:200px;" type="submit" value="Dobít kredit" /></td> 
        </tr>  
      </table>
    </form>
    <br>&nbsp;
  </div>
  <div class="col col-1-2">
    <h2>Vyplacení kreditu</h2>
    <form method="post" action="<?=$apaymentout?>">
      <table width="100%">
        <tr>
          <th>Částka k vyplacení ($ kreditu)</th>
          <td><input style="width:200px;" type="text" name="amount" value="1" /></td>   
        </tr>
        <tr>       
          <th>Měna</th>
          <td>
            <select style="width:200px;" name="currency">         
              <option value="USD">USD / $  (1.00 $ kreditu za 01.00 USD)</option>
              <option value="EUR">EUR / €  (1.00 $ kreditu za 00.80 EUR)</option>
              <option value="CZK">CZK / Kč (1.00 $ kreditu za 22.00 CZK)</option>
            </select>
          </td>  
        </tr>
        <tr>   
          <th>Váš PayPal e-mail</th>      
          <td><input style="width:200px;" type="text" name="mail" value="@" /></td>       
        </tr>
        <tr> 
          <th></th>       
          <td><input style="width:200px;" type="submit" value="Vyplatit kredit" /></td> 
        </tr>  
      </table>
    </form>
  </div>
</div>
<?if(count($coins)>0){?>  
  <h2>Přehled transakcí</h2>
  <br>
  <table width="100%">
    <tr><th>ID</th><th>Datum a čas</th><th>Operace</th><th>Částka</th><th>Kredit</th><th>Paypal Status</th></tr>  
    <?foreach($coins as $cs){?>
      <tr>      
        <td><?=$cs->paymentId?></td>
        <td><?=strftime('%d.%m.%Y %H:%M',$cs->dateTime)?></td>
        <td><?if($cs->type=='pay-in'){?><span style="color:#008000;">Dobití kreditu</span><?}else{?><span style="color:#800000;">Čerpání kreditu</span><?}?></td>
        <td><?=printcost($cs->cost)?> <?=$cs->currency?></td>        
        <td><?=printcost($cs->credit)?> $</td>    
        <td>
          <?if($cs->status=='success'){?>
            <span style="color:#008000;">DOKONČENO</span>
          <?}elseif($cs->status=='unsuccess'){?>
            <span style="color:#800000;">NEZDAŘILO SE</span>
          <?}else{?>          
            V PROCESU
          <?}?>
        </td>
      </tr>
    <?}?>      
    <tr>     
  </table>  
  <?if(count($paginnator)>3){?>
    <ul class="pagination align-center">
      <li><a title="Předchozí strana" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
      <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
        <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
        <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
        <?}?>
      <?}}?>      
      <li><a title="Následující strana" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
    </ul>
  <?}?>
<?}?>