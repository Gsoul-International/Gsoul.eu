<?if(count($files)>0){?>
  <?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><div class="h3 align-center"><?=trim($box->nadpis);?></div>
  <?}?>  
  <div class="align-center gap-top-38"><?foreach($files as $i){?><a href="/<?=$i->cesta;?>" title="<?=$i->nazev;?>" target="_blank"><?=$i->nazev;?></a> <?}?></div>  
<?}?>