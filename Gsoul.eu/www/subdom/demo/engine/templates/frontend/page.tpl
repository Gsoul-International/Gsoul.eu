<?if($page->zobrazovat_navigaci==1){?>
  <ul class="breadcrumb">
    <?foreach($breadcrumb as $pb){
      if($pb->show==1){?>        
        <li><a href="<?=$pb->link?>" <?if($pb->new_window==1){?>target="_blank"<?}elseif($pb->new_window==2){?>onclick="window.open('<?=$pb->link;?>','','width=1024,height=768');return false;"<?}?>><?=$pb->name;?></a></li>                    
      <?}?>  
    <?}?>  
  </ul>  
<?}?>
<?if($page->zobrazovat_nadpis==1){?><h1><?=$page->nazev?></h1><?}?>
<?=$submenu?>
<?=$page->obsah?>