<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['cups_cups'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['cups_cups'];?></h1>
<?if(count($modulePlatforms)>0){?>
  <div class="news-preview angled grayscale">
    <ul>
      <?foreach($modulePlatforms as $g){?>
        <li><a href="<?=$g->alink?>" title="<?=$g->nazev?>">
          <div class="news-preview-image">
            <img src="/img/userfiles/platforms/<?if(file_exists('img/userfiles/platforms/'.$g->idgp.'.png')){?><?=$g->idgp;?>.png<?}else{?>default.jpg<?}?>" alt="<?=str_replace('"','',strip_tags($n->nazev));?>" />           
          </div>         
          <div class="news-preview-name"><?=$g->nazev?></div>            				        
        </a></li>                             
      <?}?>
    </ul>
  </div> 
<?}?>