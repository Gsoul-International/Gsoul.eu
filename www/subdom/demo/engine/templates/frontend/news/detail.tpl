<?if($page->zobrazovat_navigaci==1){?>
  <ul class="breadcrumb">
    <?  
    foreach($breadcrumb as $pb){
      if($pb->show==1){?>
        <li><a href="<?=$pb->link?>" <?if($pb->new_window==1){?>target="_blank"<?}elseif($pb->new_window==2){?>onclick="window.open('<?=$pb->link;?>', '', 'width=1024, height=768');return false;"<?}?>><?=$pb->name;?></a></li>             
      <?}?>  
    <?}?>  
  </ul>
<?}?>
<?if($page->zobrazovat_nadpis==1){?><h1><?=$page->nazev?></h1><?}?>
<?if(isset($image->idi)&&$image->idi>0){?>
  <?if($image->je_youtube==1){
    parse_str(parse_url($image->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
    ?>
    <a class="lightbox float-left pad-top-16 pad-bottom-16 pad-right-16" href="<?=$image->youtube_adresa?>" title="<?=str_replace('"','',strip_tags($image->popis));?>">
      <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($image->nazev));?>" />
    </a>
  <?}else{?>
    <?if($image->cesta!=''){?>
      <a class="lightbox float-left pad-top-16 pad-bottom-16 pad-right-16" href="/<?=str_replace('xxxx','1920x1080',$image->cesta)?>" title="<?=str_replace('"','',strip_tags($image->popis));?>">
        <img src="/<?=str_replace('xxxx','300x170',$image->cesta)?>" alt="<?=str_replace('"','',strip_tags($image->nazev));?>" />
      </a>
    <?}?>
  <?}?>
<?}?>
<p><?if($page->zobrazovat_datum==1){?><b><?=strftime('%d.%m.%Y',$page->datum)?> - </b> <?}?><b><?=$page->predtext?></b></p>
<?=$page->obsah?>
<br clear="both" />
<div class="align-center"><a href="<?=$aback;?>">Zobrazit všechny novinky <em class="fa fa-angle-right"></em></a></div>