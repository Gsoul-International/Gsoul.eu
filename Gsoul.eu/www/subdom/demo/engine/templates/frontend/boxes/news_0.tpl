<?if(count($news)>0){?>
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><div class="h2 align-center"><?=trim($box->nadpis);?></div>
  <?}?>
  <ul class="news">
    <?foreach($news as $n){?>
      <li><a href="<?=$n->alink?>" title="<?=$n->nazev?>">
        <div class="date"><?if($n->zobrazovat_datum==1){echo strftime('%d.%m.%Y',$n->datum);}else{echo '&nbsp;';}?></div>
  			<div class="header"><?=$n->nazev?></div>
  			<div class="perex"><?=$n->predtext?></div>
  			<?if($images[$n->id_obrazku]->je_youtube==1){
          parse_str(parse_url($images[$n->id_obrazku]->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
          ?>
          <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" />
        <?}else{?>
          <?if($images[$n->id_obrazku]->cesta!=''){?><img src="/<?=str_replace('xxxx','300x170',$images[$n->id_obrazku]->cesta)?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" /><?}?>
        <?}?>    
				        
      </a></li>                             
    <?}?>
  </ul>
  <div class="align-center no-print"><a href="<?=$linkArchive?>" >Více novinek <em class="fa fa-angle-right"></em></a></div>
<?}?>