<?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h2 class="no-print"><?=trim($box->nadpis);?></h2>
<?}?>
<?if(count($tree)>0){?>  	
  <ul class="inline-menu small-menu">  
    <?foreach($tree as $t){
      $pos=strpos($_SERVER['REQUEST_URI'],$t->link);
      if($pos===false){}else{$t->active=1;}
      if(in_array($t->idp,$actives)){$t->active=1;}
      ?>
      <li <?if($t->active==1){?>class="active"<?}?> >
        <a href="<?=$t->link;?>" <?if($t->nove_okno==1){?>target="_blank"<?}elseif($t->nove_okno==2){?>onclick="window.open('<?=$t->link;?>', '', 'width=1024, height=768');return false;"<?}?> >
          <?=$t->nazev;?>
        </a>
      </li>
    <?}?>
  </ul>  
<?}?>