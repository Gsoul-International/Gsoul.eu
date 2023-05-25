<h1>Super admin - Kategorie prvků</h1>
<?if(getget('message','')=='order-saved'){?><h2>Pořadí bylo úspěšně uloženo.</h2><?}?>
<?if(getget('message','')=='order-is-same'){?><h2>Nezměnili jste pořadí, není důvod jej ukládat.</h2><?}?>
<?if(getget('message','')=='created'){?><h2>Kategorie byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Kategorie úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='category-not-found'){?><h2>Tato kategorie neexistuje, proto nelze editovat.</h2><?}?>
<?if(count($tree)>0){?>
  <ol class="sortable">
    <?foreach($tree as $d){?>
    <li id="list_<?=$d->idbc?>">
      <div>
        <table>
          <tr> 
            <td>              
              <?if($d->zobrazovat_admin==1){?>                
                <strong><?='{'.$d->interni_nazev.'} '.(trim($d->nazev)==''?'- bez názvu -':$d->nazev);?> (<?=$d->boxesCount?> prvků) </strong>
              <?}else{?>
                <?='{'.$d->interni_nazev.'} '.(trim($d->nazev)==''?'bez názvu':$d->nazev);?> (<?=$d->boxesCount?> prvků) <i> - skrytá kategorie pro administraci </i> 
              <?}?>              
            </td>
            <td align="right">
              <?if($d->boxesCount<1){?>
                <a href="<?=$d->adel?>" title="Smazat kategorii" onclick="return confirm('Opravdu si přejete smazat tuto kategorii?');"><i class="fa fa-trash-o"></i></a>
              <?}else{?>
                <a href="#" title="Tato kategorie nelze smazat, protože obsahuje prvky." onclick="alert('Tato kategorie nelze smazat, protože obsahuje prvky.');return false;"><i class="fa fa-trash-o"></i></a>
              <?}?>              
              &nbsp;
              <a href="<?=$d->aedit?>" title="Editovat kategorii"><i class="fa fa-pencil"></i></a>  
            </td>
          </tr>          
        </table>          
      </div>          
    </li>
    <?}?>
  </ol>
  <form method="post" action="<?=$asaveorder;?>">
    <input type="hidden" name="order" id="order" value="" /> 
    <table>
      <tr>
        <td>
          Pořadí měníte přetažením položek horizontálně.
        </td>
        <td align="right">
          <input type="submit" value="Uložit pořadí kategorií" />
        </td>
    </table>
  </form>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné kategorie.</strong>
  </p>
<?}?>
<h2>Vytvoření nové kategorie prvků</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="35%">Název kategorie</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr> 
    <tr>
      <th width="35%">Interní název kategorie</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="interni_nazev" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>   
    <tr>
      <th width="35%">Popis kategorie pro&nbsp;administrátora</th>
      <td><input type="text" size="35" class="width-350" name="popis" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>
    <tr>
      <th width="35%">Zobrazovat kategorii v&nbsp;administraci</th>
      <td colspan="2">
        <select name="zobrazovat_admin" class="width-350">        
          <option value="1">Ano</option>          
          <option value="0">Ne</option>         
        </select>
      </td>       
    </tr>                                    
    <tr>
      <td colspan="2">Název doporučujeme kvůli orientaci v administraci vyplnit, i když je nepovinný.</td>
      <td align="right"><input type="submit" value="Vytvořit kategorii" /></td>    
    </tr>           
  </table>
</form>
<script>
$('ol.sortable').nestedSortable({
  forcePlaceholderSize: true,
  handle: 'div',
  helper:	'clone',      
  items: 'li',
  opacity: .6,
  placeholder: 'placeholder',
  revert: 250,
  tabSize: 25,
  tolerance: 'pointer',
  toleranceElement: '> div',			
  isTree: false,
  expandOnHover: 700,
  startCollapsed: false,
  maxLevels: 1,
  update: function(event, ui) {         
    array = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
    $('#order').val(prepare_tree_data(array,0));              
    }  
  });    
function prepare_tree_data(arr,parent) {
  var dumped_text = "";
  if(typeof(arr) == 'object') {
    for(var item in arr) {
      var value = arr[item];
        if(typeof(value) == 'object') { 
          dumped_text += prepare_tree_data(value,parent);
        } else {
          dumped_text += /*parent + "=" +*/ value + ",";
          parent=value;
        }
      }
    } 
  return dumped_text;
  }     
</script>