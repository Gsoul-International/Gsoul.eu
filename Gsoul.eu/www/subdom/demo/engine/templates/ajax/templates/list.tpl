<?if(count($templates)>0){?>
  <div class="templatesList">
    <?foreach($templates as $xt){?>
			<div class="item" data-html="<?=rawUrlEncode($xt->html);?>">
				<div class="ico"><i class="fa fa-file-code-o"></i></div>
				<div class="name"><?=$xt->nazev;?></div>
			</div>
    <?}?>
  </div>
<?}else{?>
  <p>Žádné šablony zatím nejsou vytvořeny.</p>
<?}?>