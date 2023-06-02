<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_kalendar'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_kalendar'];?></h1> 
<div class="grid align-left grid-form grid-semipadded align-middle">  
  <div class="col col-1-3 align-center">
    <a class="button large <?if($tc!=0){?>btn-dark<?}?>" href="<?=$aTournamentsCups?>" title="<?=$systemTranslator['turnaje_turnaje'];?> + <?=$systemTranslator['cups_cups_velke'];?>"><?=$systemTranslator['turnaje_turnaje'];?> + <?=$systemTranslator['cups_cups_velke'];?></a>
  </div>
  <div class="col col-1-3 align-center">
    <a class="button large <?if($tc!=1){?>btn-dark<?}?>" href="<?=$aOnlyTournaments?>" title="<?=$systemTranslator['turnaje_turnaje'];?>"><?=$systemTranslator['turnaje_turnaje'];?></a> 
  </div>
  <div class="col col-1-3 align-center">
    <a class="button large <?if($tc!=2){?>btn-dark<?}?>" href="<?=$aOnlyCups?>" title="<?=$systemTranslator['cups_cups_velke'];?>"><?=$systemTranslator['cups_cups_velke'];?></a>
  </div>
  
  <div class="col col-1-1 align-center"> 
    <br />         
    <div class="window">
		  <div class="window-header">
		    <a href="<?=$ayearminus?>"><em class="fa fa-angle-double-left"></em></a>
		    &nbsp;
		    <a href="<?=$amonthminus?>"><em class="fa fa-angle-left"></em></a>
		    &nbsp;&nbsp;
        <big><?=(int)$m;?> / <?=(int)$y;?></big>
        &nbsp;&nbsp;
        <a href="<?=$amonthplus?>"><em class="fa fa-angle-right"></em></a>
        &nbsp;
        <a href="<?=$ayearplus?>"><em class="fa fa-angle-double-right"></em></a>
      </div>    
      <div class="window-content" style="min-height:25px;"> 
        &nbsp;<br />   
        <table align="center" width="96%" border="1">            
          <tr>
            <th class="calendary-header" width="14%"><?=$systemTranslator['obecne_kalendar_zkratka_pondeli'];?></th>
            <th class="calendary-header" width="14%"><?=$systemTranslator['obecne_kalendar_zkratka_utery'];?></th>
            <th class="calendary-header" width="14%"><?=$systemTranslator['obecne_kalendar_zkratka_streda'];?></th>
            <th class="calendary-header" width="14%"><?=$systemTranslator['obecne_kalendar_zkratka_ctvrtek'];?></th>
            <th class="calendary-header" width="14%"><?=$systemTranslator['obecne_kalendar_zkratka_patek'];?></th>
            <th class="calendary-header" width="15%"><?=$systemTranslator['obecne_kalendar_zkratka_sobota'];?></th>
            <th class="calendary-header"><?=$systemTranslator['obecne_kalendar_zkratka_nedele'];?></th>
          </tr> 
          <tr>
            <?
            $statusY=((int)$d).'-'.((int)$m).'-'.((int)$y); 
            $startX=strftime('%A',mktime(12,0,0,$m,1,$y));
            if($startX=='Monday'){$start=0;}
            if($startX=='Tuesday'){$start=1;}
            if($startX=='Wednesday'){$start=2;}
            if($startX=='Thursday'){$start=3;}
            if($startX=='Friday'){$start=4;}
            if($startX=='Saturday'){$start=5;}
            if($startX=='Sunday'){$start=6;}          
            $g=1;$hh=1;?>
            <?for($f=0;$f<$start;$f++,$g++){?><td class="bg-lightlightlightgray"> &nbsp;</td><?}?>
            <?for($dd=1;$dd<=cal_days_in_month(CAL_GREGORIAN,$m,$y);$dd++,$g++){
              $statusX=((int)$dd).'-'.((int)$m).'-'.((int)$y); 
              $setMatchs=count($data[$statusX])>0?1:0;           
              ?>            
              <td class="align-center status-<?=$statusX?> calendary-matchs-<?=$setMatchs;?>">
                <b><a class="calendary-link<?if($dd==$d){echo '-active';}?>" href="<?=$daysLinks[$dd];?>">&nbsp;<?=$dd;?>&nbsp;</a></b>                
              </td>            
              <?if($g%7==0){if($dd!=cal_days_in_month(CAL_GREGORIAN,$m,$y)){$hh++;}?></tr><tr><?}?>
            <?}?>
            <?$g--;?>
            <?for(;$g%7!=0;$g++){?><td class="bg-lightlightlightgray">&nbsp;</td><?}?>
          </tr>          
        </table> 
        
        <?if(count($data[$statusY])>0){?>
          <div class="overflow-table align-left">
          	<table class="datatable" style="width:100%">
          		<thead>
          			<tr>          			  
          			  <th><?=$systemTranslator['turnaje_hra'];?></th>
          			  <th><?=$systemTranslator['prihlasovaci_udaje_turnaje_titulek'];?></th>
          			  <th><?=$systemTranslator['turnaje_v_case'];?></th>          			            			  
          			  <th><?=$systemTranslator['turnaje_dohrano'];?></th>
          			  <th>&nbsp;</th>
          			</tr>
		          </thead>
		          <tbody>
                <?foreach($data[$statusY] as $dX){?>  
                  <tr class='clickable-row' data-href='<?=$dX->aview?>'>                       
                    <td><?=$dX->GameName;?></td>  
                    <td><?=$dX->Titulek?></td>  
                    <td><?=strftime('%d.%m.<br>%H:%M',$dX->datum_cas_startu);?></td>                                          
                    <td><?=$dX->dohrano==1?$systemTranslator['obecne_ano']:$systemTranslator['obecne_ne'];?></td>       
                    <td>
                      <?if($dX->typ==1){?>
                        <?=$systemTranslator['turnaje_turnaje'];?>
                      <?}else{?>
                        <?=$systemTranslator['cups_cups_velke'];?>
                      <?}?>
                    </td>
                  </tr>
                <?}?>
              </tbody>
            </table>
          </div>
        <?}else{?>
          <br />          
        <?}?>   
        &nbsp; 
        <br />     
      </div>
    </div>        
  </div>
</div>