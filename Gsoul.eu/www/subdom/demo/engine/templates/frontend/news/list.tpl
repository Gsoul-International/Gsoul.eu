<ul class="breadcrumb">
  <?
  foreach($breadcrumb as $pb){
    if($pb->show==1){?>
      <li><a href="<?=$pb->link?>" <?if($pb->new_window==1){?>target="_blank"<?}elseif($pb->new_window==2){?>onclick="window.open('<?=$pb->link;?>', '', 'width=1024, height=768');return false;"<?}?>><?=$pb->name;?></a></li>             
    <?}?>  
  <?}?>  
</ul>
<h1>Novinky</h1>
<?if(count($news)>0){?>
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
  <ul class="pagination align-center">
    <li><a title="Předchozí strana" href="<?=$paginnator['prew'];?>" ><em class="fa fa-angle-double-left"></em></a></li>    
    <?foreach($paginnator as $kp=>$vp){if($kp!='prew'&&$kp!='next'){?>
      <?if($kp>(getget('page','0')-3)&&$kp<(getget('page','0')+5)){?>
      <li <?if(getget('page','0')==($kp-1)){?>class="active"<?}?> ><a href="<?=$vp?>"><?=$kp?></a></li>
      <?}?>
    <?}}?>      
    <li><a title="Následující strana" href="<?=$paginnator['next'];?>"><em class="fa fa-angle-double-right"></em></a></li>
  </ul>
<?}?>