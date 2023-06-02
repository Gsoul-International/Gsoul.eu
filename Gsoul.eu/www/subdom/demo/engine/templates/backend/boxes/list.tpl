<h1>Prvky - <?=$boxesCategory->nazev;?></h1>
<p>
  <?=$boxesCategory->popis;?>
</p>
<?if(getget('message','')=='order-saved'){?><h2>Pořadí prvků bylo úspěšně uloženo.</h2><?}?>
<?if(getget('message','')=='order-is-same'){?><h2>Nezměnili jste pořadí prvků, není důvod jej ukládat.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Prvek úspěšně smazán.</h2><?}?>

<?if(count($list)>0){?>
  <ol class="sortable">
    <?foreach($list as $d){?>
    <li id="list_<?=$d->idb?>">
      <div>
        <table>
          <tr> 
            <td>              
              <?=$modules[$d->modul]?> - 
              <?if($d->zobrazovat==1){?>                
                <strong><?=(trim($d->nazev)==''?'- bez názvu -':$d->nazev);?> </strong>
              <?}else{?>
                <?=(trim($d->nazev)==''?'bez názvu':$d->nazev);?> <i> - skrytý prvek</i> 
              <?}?>              
            </td>
            <td align="right">
              <a href="<?=$d->adel?>" title="Smazat prvek" onclick="return confirm('Opravdu si přejete smazat tento prvek?');"><i class="fa fa-trash-o"></i></a>               
              &nbsp;
              <a href="<?=$d->aedit?>" title="Editovat prvek"><i class="fa fa-pencil"></i></a>  
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
          <input type="submit" value="Uložit pořadí prvků" />
        </td>
    </table>
  </form>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné prvky.</strong>
  </p>
<?}?>
<h2>Vytvoření nového prvku</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="25%">Název prvku</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>                    
    <tr>
      <th>Typ prvku</th>
      <td colspan="2">
        <select name="module" class="width-350">
          <?foreach($modules as $mk=>$mv){?>
            <option value="<?=$mk;?>"><?=$mv;?></option>
          <?}?>
        </select>
      </td>    
    </tr>           
    <tr>
      <td colspan="2">Název doporučujeme kvůli orientaci v administraci vyplnit, i když je nepovinný.</td>
      <td align="right"><input type="submit" value="Vytvořit prvek" /></td>    
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