<?if(count($images)>0){?>  
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h2 class="align-center no-print"><?=trim($box->nadpis);?></h2>
  <?}?>
  <ul class="carousel-wrap referrer no-print">
    <?foreach($images as $i){?>
      <li><div class="item">          
          <?if($i->je_youtube==1){?>
            <a class="lightbox" rel="gallery_<?=$box->idb;?>" href="<?=$i->youtube_adresa;?>" title="<?=$i->popis;?>">
          <?}else{?>
            <a class="lightbox" rel="gallery_<?=$box->idb;?>" href="/<?=$i->sizes['maxi'];?>" title="<?=$i->popis;?>">
          <?}?>          
          <?if($i->je_youtube==1){
            parse_str(parse_url($i->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
            ?>
            <div class="img-wrap"><img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=$i->nazev;?>" /></div>
          <?}else{?>
            <div class="img-wrap"><img src="/<?=$i->sizes['300x170'];?>" alt="<?=$i->nazev;?>" /></div>
          <?}?>
          <div class="text-wrap"><?=$i->nazev;?></div>          
        </a>  
      </div></li>                                                     
    <?}?>    
  </ul>  
<?}?>