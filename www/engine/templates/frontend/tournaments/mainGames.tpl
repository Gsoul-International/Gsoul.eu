<div class="breadcrumb">
  <ul>
    <li><a href="/"><?=$mainpagename;?></a></li>    
    <li><a href="<?=$athis;?>"><?=$systemTranslator['turnaje_turnaje2'];?></a></li>     
    <?foreach($modulePlatforms as $mp){if($mp->idgp==$idgp){?><li><a href="<?=$mp->alink;?>"><?=$mp->nazev;?></a></li><?}}?> 
  </ul>
</div>     
<h1><?=$systemTranslator['vypis_turnaju_vyber_hry_nadpis'];?></h1>
<?if(count($games)>0){?>
  <div class="news-preview angled grayscale">
    <ul>
      <?foreach($games as $g){?>
        <li><a href="<?=$g->alink?>" title="<?=$g->nazev?>">
          <div class="news-preview-image">
            <img src="/img/userfiles/games/<?if(file_exists('img/userfiles/games/'.$g->idg.'.png')){?><?=$g->idg;?>.png<?}else{?>default.jpg<?}?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" />           
          </div>         
          <div class="news-preview-name" style="margin-left:15px !important;padding-left:0 !important;background:none !important;">
            <?=$g->nazev?>
            <?if(count($g->platforms)>0){?>
              - <?=implode(', ',$g->platforms);?>
            <?}?>
          </div>            				        
        </a></li>                             
      <?}?>
    </ul>
  </div> 
<?}else{?>
  <p><?=$systemTranslator['turnaje_pro_tuto_platformu_zatim_priptavujeme_hry'];?></p>
<?}?>