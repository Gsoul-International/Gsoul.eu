<div class="breadcrumb">
  <ul>
  <?
  foreach($breadcrumb as $pb){
    if($pb->show==1){?>
      <li><a href="<?=$pb->link?>" <?if($pb->new_window==1){?>target="_blank"<?}elseif($pb->new_window==2){?>onclick="window.open('<?=$pb->link;?>', '', 'width=1024, height=768');return false;"<?}?>><?=$pb->name;?></a></li>             
    <?}?>  
  <?}?>  
  </ul>
</div>
<h1><?=$systemTranslator['obecne_novinky'];?></h1>
<?if(count($news)>0){?>
  <div class="news-preview angled grayscale">
    <ul>
      <?foreach($news as $n){?>
        <li><a href="<?=$n->alink?>" title="<?=$n->nazev?>">
          <div class="news-preview-image">
            <?if($images[$n->id_obrazku]->je_youtube==1){
            parse_str(parse_url($images[$n->id_obrazku]->youtube_adresa,PHP_URL_QUERY),$httpArrayVars);
            ?>
            <img src="http://img.youtube.com/vi/<?=$httpArrayVars['v'];?>/hqdefault.jpg" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" />
          <?}else{?>
            <?if($images[$n->id_obrazku]->cesta!=''){?><img src="/<?=str_replace('xxxx','473x266',$images[$n->id_obrazku]->cesta)?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" /><?}}?>
          </div>
          <?if($n->zobrazovat_datum==1){?>
            <div class="news-preview-date">
							<div class="font-light"><?=strftime('%d/%m',$n->datum);?></div>
							<div class="font-bold"><?=strftime('%Y',$n->datum);?></div>
						</div>
					<?}?>
          <div class="news-preview-name"><?=$n->nazev?></div>            				        
        </a></li>                             
      <?}?>
    </ul>
  </div>
  <ul class="pagination align-center">
    <li><a title="<?=$systemTranslator['strankovani_predchozi_strana'];?>" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
    <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
      <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
      <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
      <?}?>
    <?}}?>      
    <li><a title="<?=$systemTranslator['strankovani_nasledujici_strana'];?>" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
  </ul>
<?}?>