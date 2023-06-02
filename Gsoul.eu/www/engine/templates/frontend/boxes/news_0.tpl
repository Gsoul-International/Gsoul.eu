<?if(count($news)>0){?>
  <div class="news-preview bg-dark-blue">	
    <div class="wrap">
      <div class="size-35 font-bold white align-center"><?=$systemTranslator['obecne_novinky_big'];?></div>
			<?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><div class="size-25 font-bold yellow align-center"><?=$systemTranslator['obecne_nejnovejsi_novinky_z_herniho_sveta'];?></div><?}?>
      <ul>
        <?foreach($news as $n){?>
          <li><a href="<?=$n->alink?>" title="<?=$n->nazev?>">
            <?if($images[$n->id_obrazku]->je_youtube==1){
              parse_str(parse_url($images[$n->id_obrazku]->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
              ?>
              <div class="news-preview-image"><img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" /></div>
            <?}else{?>
              <?if($images[$n->id_obrazku]->cesta!=''){?>
                <div class="news-preview-image"><img src="/<?=str_replace('xxxx','359x213',$images[$n->id_obrazku]->cesta)?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" /></div>
              <?}?>
            <?}?> 
            <?if($n->zobrazovat_datum==1){?>
              <div class="news-preview-date">
  							<div class="font-light"><?=strftime('%d/%m',$n->datum);?></div>
  							<div class="font-bold"><?=strftime('%Y',$n->datum);?></div>
  						</div>
						<?}?>
            <div class="news-preview-name">
						  <?=$n->nazev?>
						</div>                            			       				      
          </a></li>                             
        <?}?>    
      </ul>
      <div class="align-center">
				<a href="<?=$linkArchive?>" class="button large btn-transparent"><?=$systemTranslator['obecne_vice_novinek'];?></a>
			</div>                            
    </div> 
  </div>      
<?}?>