<?if(count($images)>0){?>  
  <div class="wrap pad-0">
    <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h2 class="align-center no-print"><?=trim($box->nadpis);?></h2>
    <?}?>
    <ul class="slider-wrap no-print">
      <?foreach($images as $i){?>
        <li>                          
          <?if($i->je_youtube==1){
            parse_str(parse_url($i->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
            ?>
            <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=$i->nazev;?>" />
          <?}else{?>
            <img src="/<?=$i->sizes['1900x475'];?>" alt="<?=$i->nazev;?>" />
          <?}?>                        
        </li>                                                     
      <?}?>    
    </ul>
  </div>  
<?}?>