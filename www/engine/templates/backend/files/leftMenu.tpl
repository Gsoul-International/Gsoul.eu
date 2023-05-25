<ul class="BackendLeftMenu">
  <?foreach($menu as $km=>$vm){?>
    <li <?if($km==$active){?>class="active"<?}?> >
      <a href="<?=$km;?>">
        <?=$vm;?>
      </a> 
      <?if($km==$active){?>
        <ul>
          <?foreach($menu2 as $m2){?>
            <li <?if($m2->active==1){?>class="active"<?}?> >
              <a href="<?=$m2->aedit?>"><?=$m2->nazev?></a>
            </li>
          <?}?>
        </ul>
      <?}?>
    </li>  
  <?}?>
</ul>