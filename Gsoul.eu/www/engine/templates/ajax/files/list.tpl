<h1>Soubory</h1>
<table>
  <tr>
    <td valign="top">
      <?if(count($submenu)>0){?>
        <ul>
          <?foreach($submenu as $s){?>
            <li <?if($s->active==1){?>class="active"<?}?> >
              <a href="<?=$s->aview?>"><?=$s->nazev?></a>
            </li>
          <?}?>
        </ul>
      <?}else{?>
        <p>Nejsou vytvořeny žádné kategorie souborů.</p>
      <?}?>
    </td>
    <td valign="top">
      <?if(count($files)>0){?>
				<div class="sort">
					Řazení:
					<a <?if($getOrder=='name'){?>class="activeLink"<?}?> href="<?=$aorders['name'];?>" title="Řadit dle názvu vzestupně">
						<i class="fa fa-sort-alpha-asc"></i>
					</a>
					<a <?if($getOrder=='namedesc'){?>class="activeLink"<?}?> href="<?=$aorders['namedesc'];?>" title="Řadit dle názvu sestupně">
						<i class="fa fa-sort-alpha-desc"></i>
					</a>
					<a <?if($getOrder=='time'){?>class="activeLink"<?}?> href="<?=$aorders['time'];?>" title="Řadit dle času nahrání vzestupně">
						<i class="fa fa-sort-amount-asc"></i>
					</a>
					<a <?if($getOrder=='timedesc'){?>class="activeLink"<?}?> href="<?=$aorders['timedesc'];?>" title="Řadit dle času nahrání sestupně">
						<i class="fa fa-sort-amount-desc"></i>
					</a>   
				</div>
				<?foreach($files as $f){?>
						<a href="<?=$f->areturn?>" class="file">					
							<i class="fa fa-file-text-o"></i>								
							<div class="date"><?=strftime('%d.%m.%Y %H:%M',$f->vytvoreni_timestamp);?></div>
							<div class="name"><?=$f->nazev?></div>
						</a>
				<?}?>
      <?}else{?>
        <br/><p style="text-align:center">V kategorii <?=$s->nazev?> nejsou nahrány žádné soubory.</p>
      <?}?>
    </td>
  </tr>
</table>
