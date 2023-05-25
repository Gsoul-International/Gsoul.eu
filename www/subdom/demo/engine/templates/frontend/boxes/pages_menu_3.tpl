<?if(count($tree)>0){?>
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h3 class="no-print"><?=trim($box->nadpis);?></h3>
  <?}?>  
    <ul class="referrer no-print">
      <?foreach($tree as $t){?>
        <li><div class="item"><a href="<?=$t->link;?>" <?if($t->nove_okno==1){?>target="_blank"<?}elseif($t->nove_okno==2){?>onclick="window.open('<?=$t->link;?>', '', 'width=1024, height=768');return false;"<?}?> title="<?=str_replace('"','',strip_tags($t->nazev));?>" >        
            <?if($images[$t->id_obrazku]->je_youtube==1){
              parse_str(parse_url($images[$t->id_obrazku]->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
              ?>
              <div class="img-wrap"><img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /></div>
            <?}else{?>
              <div class="img-wrap"><?if($images[$t->id_obrazku]->cesta!=''){?><img src="/<?=str_replace('xxxx','300x170',$images[$t->id_obrazku]->cesta)?>" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /><?}else{?><img src="/img/none.png" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /><?}?></div>
            <?}?>
            <div class="text-wrap"><?=$t->nazev;?></div>
        </a></div></li>                                                     
      <?}?>    
    </ul>
<?}?>                     