<div class="breadcrumb">
  <ul>
    <li><a href="/"><?=$mainpagename;?></a></li>    
    <li><a href="<?=$athis;?>"><?=$systemTranslator['cups_cups'];?></a></li>     
    <?foreach($modulePlatforms as $mp){if($mp->idgp==$idgp){?><li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li><?}}?> 
  </ul>
</div>     
<h1><?=$systemTranslator['cups_cups'];?></h1>
<?if(count($games)>0){?>
  <div class="news-preview angled grayscale">
    <ul>
      <?foreach($games as $g){?>
        <li><a href="<?=$g->alink?>" title="<?=$g->nazev?>">
          <div class="news-preview-image">
            <img src="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$g->idg.'.png')){?><?=$g->idg;?>.png<?}else{?>default.jpg<?}?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" />           
          </div>         
          <div class="news-preview-name"><?=$g->nazev?></div>            				        
        </a></li>                             
      <?}?>
    </ul>
  </div> 
<?}else{?>
  <p><?=$systemTranslator['turnaje_pro_tuto_platformu_zatim_priptavujeme_hry'];?></p>
<?}?>