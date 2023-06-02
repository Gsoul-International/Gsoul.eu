<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>     
  <li><a href=""><?=$systemTranslator['notifikace'];?></a></li>
  </ul>
</div> 
<h1><?=$systemTranslator['notifikace'];?></h1> 
<?if(count($notifies)>0){?>  
  <br />
  <div class="align-right">
    <a href="<?=$areadall;?>" title="<?=$systemTranslator['notifikace_precist_vse'];?>" class="button"><?=$systemTranslator['notifikace_precist_vse'];?></a>   
  </div>         
  <br />
  <table class="datatable" style="width:100%">
    <thead>
      <tr><th><?=$systemTranslator['notifikace_datum_cas'];?></th><th><?=$systemTranslator['notifikace_jdn'];?></th><th><?=$systemTranslator['notifikace_precteno'];?></th><th></th></tr> 
    </thead>
    <tbody> 
    <?foreach($notifies as $t){?>                                                           
      <tr class="clickable-row" data-href="<?=$t->aDetail;?>" role="row">    
        <td <?if($t->precteno==0){?>class="bold"<?}?> ><?=strftime('%d.%m.%Y<br />%H:%M',$t->ts);?></td>
        <td <?if($t->precteno==0){?>class="bold"<?}?> ><?=str_replace(array('A_DATA','B_DATA','C_DATA','D_DATA'),array($t->data_a,$t->data_b,$t->data_c,$t->data_d),$systemTranslator[$t->typ])?></td>
        <td <?if($t->precteno==0){?>class="bold"<?}?> ><?=$t->precteno==1?$systemTranslator['notifikace_hdn_precteno']:$systemTranslator['notifikace_hdn_neprecteno'];?></td>        
        <td><a href="<?=$t->aDetail;?>" title="<?=$systemTranslator['notifikace_zobrazit']?>" class="button"><?=$systemTranslator['notifikace_zobrazit']?></a></td>        
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
  <?=frontendMessage('normal',$systemTranslator['notifikace_zatim_nemate_zadne_notifikace']);?>
<?}?>