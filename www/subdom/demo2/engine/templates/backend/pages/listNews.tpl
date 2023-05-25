<h1>Novinky</h1>
<div class="BackendBack">
  &nbsp;<br>
  <a href="<?=$anew;?>"><i class="fa fa-plus"></i> Přidat novinku</a><br>  
</div>
<?if(getget('message','')=='page-not-found'){?><h2>Novinka s ID #<?=(int)getget('idn','0');?> v systému neexistuje!</h2><?}?>
<?if(getget('message','')=='page-deleted'){?><h2>Novinka s ID #<?=(int)getget('idn','0');?> byla smazána.</h2><?}?>
<table>
  <?if(count($news)==0){?>
    <tr><th class="BackendCenter">Žádné novinky nebyly nalezeny.</th></tr>
  <?}else{?>
    <tr><th width="11%">Datum</th><th>Jazyk</th><th colspan="3">Název</th></tr>
    <?foreach($news as $n){?>
      <tr>
        <td><?if($n->zobrazovat_datum==1){?><b><?=strftime('%d.%m.%Y',$n->datum);?></b><?}else{?><i><?=strftime('%d.%m.%Y',$n->datum);?></i><?}?></td>
        <td>
          <?if(isset($languages[$n->id_jazyka])){?>
            <?if(file_exists('userfiles/langs/'.$n->id_jazyka.'.png')){?><img src="/<?='userfiles/langs/'.$n->id_jazyka.'.png';?>" /><?}?>
            <?=$languages[$n->id_jazyka]->nazev?>
          <?}else{?>
            -
          <?}?>
        </td>
        <td><?if($n->zobrazovat==1){?><b><?=$n->nazev;?></b><?}else{?><i><?=$n->nazev;?></i><?}?></td>
        <td width="3%"><a title="Smazat novinku" href="<?=$n->adel?>" onclick="return confirm('Opravdu si přejete smazat novinku z <?=strftime('%d.%m.%Y',$n->datum);?>?');"><i class="fa fa-trash-o"></i></a></td>
        <td width="3%"><a title="Editovat novinku" href="<?=$n->aedit?>"><i class="fa fa-pencil"></i></a></td>
      </tr>
    <?}?>
    <tr> 
      <td colspan="5" align="center">
        <a class="PageAnchors" title="Předchozí strana" href="<?=$paginnator['prew'];?>" >
          <i class="fa fa-arrow-left"></i>
        </a>     
        <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
          <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
          <a <?if(getget('page','0')==($kp-1)){?>class="activeLink"<?}?> href="<?=$vp?>"><?=$kp?></a>
          <?}?>
        <?}}?>      
        <a class="PageAnchors" title="Následující strana" href="<?=$paginnator['next'];?>">
          <i class="fa fa-arrow-right"></i>
        </a>
      </td>       
    </tr>
  <?}?>
</table>
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">    
    Novinky se na webu zobrazují dle datumů od nejnovější po nejstarší, stejně tak jako zde v administraci. <br /><br /> 
  </div>
</div>
