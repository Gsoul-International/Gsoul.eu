<?if(count($data)>0){?>
  <ol <?if($parentID==0){?>class="sortable"<?}?> >
    <?foreach($data as $d){?>
      <li id="list_<?=$d->idp?>">      
        <div>
          <table>
            <tr> 
              <td>       
                <?if(file_exists('userfiles/langs/'.$d->id_jazyka.'.png')){?><img src="/<?='userfiles/langs/'.$d->id_jazyka.'.png';?>" /> &nbsp; &nbsp; <?}?>         
                <?if($d->zobrazovat==1){?>                
                  <strong><?=$d->nazev;?> </strong>
                <?}else{?>
                  <?=$d->nazev;?> <i> - skrytá stránka</i> 
                <?}?>              
              </td>
              <td align="right">
                <a href="<?=$d->adel?>" title="Smazat stránku" onclick="return confirm('Opravdu si přejete smazat tuto stránku? Budou smazány všechny její podstránky!');"><i class="fa fa-trash-o"></i></a> 
                &nbsp;
                <a href="<?=$d->afrontend?>" title="Zobrazit stránku na webu" target="_blank"><i class="fa fa-info"></i></a> 
                &nbsp;
                <a href="<?=$d->aedit?>" title="Editovat stránku"><i class="fa fa-pencil"></i></a>  
              </td>
            </tr>          
          </table>          
        </div>           
        <?if(trim($d->subtree)!=''){echo $d->subtree;}?>
      </li>
    <?}?>
  </ol>
<?}?>