<?if(count($data)>0){?>
  <ul>
    <?foreach($data as $t){?>
      <li <?if($t->active==1){?>class="active"<?}?> >
        <a href="<?=$t->link;?>" <?if($t->nove_okno==1){?>target="_blank"<?}elseif($t->nove_okno==2){?>onclick="window.open('<?=$t->link;?>', '', 'width=1024, height=768');return false;"<?}?> >
          <?=$t->nazev;?>
        </a>
        <?=$t->subtree;?>        
      </li>
    <?}?>
  </ul>
<?}?>