<h1>Super admin - zákldaní nastavení</h1>
<?if(getget('message','')=='order-saved'){?><h2>Pořadí bylo úspěšně uloženo.</h2><?}?>
<?if(getget('message','')=='order-is-same'){?><h2>Nezměnili jste pořadí, není důvod jej ukládat.</h2><?}?>
<?if(getget('message','')=='created'){?><h2>Proměnná byla úspěšně vytvořena.</h2><?}?>
<?if(getget('message','')=='deleted'){?><h2>Proměnná úspěšně smazána.</h2><?}?>
<?if(getget('message','')=='setting-not-found'){?><h2>Tato proměnná neexistuje, proto nelze editovat.</h2><?}?>
<?if(count($tree)>0){?>
  <ol class="sortable">
    <?foreach($tree as $d){?>
    <li id="list_<?=$d->ids?>">
      <div>
        <table>
          <tr> 
            <td>              
              <?if($d->zobrazovat==1){?>                
                <strong><?='{'.$d->klic.'} '.(trim($d->nazev)==''?'- bez názvu -':$d->nazev);?> </strong>
              <?}else{?>
                <?='{'.$d->klic.'} '.(trim($d->nazev)==''?'bez názvu':$d->nazev);?> <i> - skrytá proměnná </i> 
              <?}?>              
            </td>
            <td align="right">
              <a href="<?=$d->adel?>" title="Smazat proměnnou" onclick="return confirm('Opravdu si přejete smazat tuto proměnnou?');"><i class="fa fa-trash-o"></i></a>               
              &nbsp;
              <a href="<?=$d->aedit?>" title="Editovat proměnnou"><i class="fa fa-pencil"></i></a>  
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
          <input type="submit" value="Uložit pořadí proměnných" />
        </td>
    </table>
  </form>
  <br />
<?}else{?>
  <p>
    <strong>Zatím zde nejsou vytvořeny žádné proměnné.</strong>
  </p>
<?}?>
<h2>Vytvoření nové proměnné</h2>
<form action="<?=$anew;?>" method="post">
  <table>
    <tr>
      <th width="25%">Název proměnné</th>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr> 
    <tr>
      <th width="25%">Klíč proměnné</th>
      <td><input type="text" size="35" class="width-350" maxlength="64" name="klic" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>   
    <tr>
      <th width="25%">Popis proměnné</th>
      <td><input type="text" size="35" class="width-350" name="popis" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>
    <tr>
      <th width="25%">Hodnota proměnné</th>
      <td><input type="text" size="35" class="width-350" name="hodnota" value="" /></td>
      <td width="10%"> &nbsp; </td>    
    </tr>                       
    <tr>
      <th>Typ proměnné</th>
      <td colspan="2">
        <select name="typ" class="width-350">        
          <option value="int">Celé číslo</option>          
          <option value="float">Desetinné číslo</option>
          <option value="ano_ne">Ano / ne</option>
          <option value="text">Text</option>
          <option value="textarea">TextArea</option>
          <option value="editor">Editor</option>        
        </select>
      </td>    
    </tr>   
    <tr>
      <th>Zobrazovat v administraci</th>
      <td colspan="2">
        <select name="zobrazovat" class="width-350">        
          <option value="1">Ano</option>          
          <option value="0">Ne</option>         
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