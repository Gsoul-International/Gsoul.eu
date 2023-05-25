<?if(count($images)>0){?>  
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h2 class="align-center no-print"><?=trim($box->nadpis);?></h2>
  <?}?>
  <ul class="carousel-wrap">
    <?foreach($images as $i){?>
      <li>        
          <?if($i->je_youtube==1){?>
            <a class="lightbox" rel="gallery_<?=$box->idb;?>" href="<?=$i->youtube_adresa;?>" title="<?=$i->popis;?>">
          <?}else{?>
            <a class="lightbox" rel="gallery_<?=$box->idb;?>" href="/<?=$i->sizes['maxi'];?>" title="<?=$i->popis;?>">
          <?}?>      
          <?if($i->je_youtube==1){
            parse_str(parse_url($i->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
            ?>
            <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=$i->nazev;?>" />
          <?}else{?>
            <img src="/<?=$i->sizes['400x225'];?>" alt="<?=$i->nazev;?>" />
          <?}?>
          <span><?=$i->nazev;?></span>          
        </a>  
      </li>                                                     
    <?}?>    
  </ul>  
<?}?>