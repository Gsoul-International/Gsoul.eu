<?if(count($images)>0){?>  
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h2 class="align-center no-print"><?=trim($box->nadpis);?></h2>
  <?}?>
  <ul class="carousel-wrap hover-bg">
    <?foreach($images as $i){?>
      <li>        
          <?if($i->je_youtube==1){?>
            <a href="<?=$i->popis;?>" title="<?=$i->nazev;?>">
          <?}else{?>
            <a href="/<?=$i->popis;?>" title="<?=$i->nazev;?>">
          <?}?>          
          <?if($i->je_youtube==1){
            parse_str(parse_url($i->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
            ?>
            <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" data-fullview-src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=$i->nazev;?>" />
          <?}else{?>
            <img src="/<?=$i->sizes['400x225'];?>" data-fullview-src="/<?=$i->sizes['maxi'];?>" alt="<?=$i->nazev;?>" />
          <?}?>
          <span><?=$i->nazev;?></span>          
        </a>  
      </li>                                                     
    <?}?>    
  </ul> 
  <?
	echo '<div style="width:1px;height:1px;overflow:hidden">';
	foreach($images as $i){
		if($i->je_youtube==0){     
			echo '<img src="/'.$i->sizes['maxi'].'" alt="'.$i->nazev.'" width="1" height="1" style="max-height:1px !important;max-width:1px !important;" />';
		}
	}
	echo '</div>';
	?> 
<?}?>