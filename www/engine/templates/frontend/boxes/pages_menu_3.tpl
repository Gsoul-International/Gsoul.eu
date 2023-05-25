<?if(count($tree)>0){?>
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h3 class="no-print"><?=trim($box->nadpis);?></h3>
  <?}?> 
  <div class="news-preview angled grayscale"> 
    <ul>
      <?foreach($tree as $t){?>
        <li><a href="<?=$t->link;?>" <?if($t->nove_okno==1){?>target="_blank"<?}elseif($t->nove_okno==2){?>onclick="window.open('<?=$t->link;?>', '', 'width=1024, height=768');return false;"<?}?> title="<?=str_replace('"','',strip_tags($t->nazev));?>" >        
            <?if($images[$t->id_obrazku]->je_youtube==1){
              parse_str(parse_url($images[$t->id_obrazku]->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
              ?>
              <div class="news-preview-image"><img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /></div>
            <?}else{?>
              <div class="news-preview-image"><?if($images[$t->id_obrazku]->cesta!=''){?><img src="/<?=str_replace('xxxx','473x266',$images[$t->id_obrazku]->cesta)?>" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /><?}else{?><img src="/img/none.png" alt="<?=str_replace('"','',strip_tags($t->nazev));?>" /><?}?></div>
            <?}?>
            <div class="news-preview-name"><?=$t->nazev;?></div>
        </a></li>                                                     
      <?}?>    
    </ul>
  </div>
<?}?>                     