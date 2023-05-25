<h1>Stránky</h1>
<div class="BackendBack">
  &nbsp;<br>
  <a href="<?=$anew;?>"><i class="fa fa-plus"></i> Přidat stránku</a><br>  
</div>
<?if(getget('message','')=='page-not-found'){?><h2>Stránka s ID #<?=(int)getget('idp','0');?> v systému neexistuje!</h2><?}?>
<?if(getget('message','')=='order-saved'){?><h2>Struktura stránek byla úspěšně uložena.</h2><?}?>
<?if(getget('message','')=='order-is-same'){?><h2>Nezměnili jste strukturu stránek, není důvod ji ukládat.</h2><?}?>
<?if(getget('message','')=='page-deleted'){?><h2>Stránka s ID #<?=(int)getget('idp','0');?> byla smazána včetně všech podstránek.</h2><?}?>
<?=$tree;?>
<form method="post" action="<?=$asaveorder;?>">
  <input type="hidden" name="order" id="order" value="" /> 
  <table>
    <tr>
      <td>
        Stromovou strukturu měníte přetažením položek vertikálně a horizontálně.
      </td>
      <td align="right">
        <input type="submit" value="Uložit stromovou strukturu stránek" />
      </td>
  </table>
</form>
<br />
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">
    Data se uloží až po kliknutí na tlačítko "Uložit stromovou strukturu stránek", aby nedocházelo k nechtěnému posouvání obsahu webu. Pořadí stránek a podstránky můžete měnit pomocí přetažení položek vertikálním a&nbsp;horizontálním směrem. Vždy se Vám objeví rámeček s modrým ohraničením, který signalizuje budoucí umístnění stránky. Pokud změny nechcete uložit a chcete se vrátit do původního stavu, stačí kliknout na&nbsp;klávesnici na klávesu F5. Stránka se po kliku na tuto klávesu přenačte do původní podoby. <br /><br />
    Pokud smažete stránku, která má podstránky, tyto podstránky se smažou také.<br /><br /> 
    Pokud přesunete stránku, která má podstránky, tyto podstránky se přesunou také.<br /><br /> 
    Stránky, které se zobrazují na webu, mají tučné názvy. <br /><br /> 
  </div>
</div>
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
  isTree: true,
  expandOnHover: 700,
  startCollapsed: false,
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
          dumped_text += parent + "=" + value + ",";
          parent=value;
        }
      }
    } 
  return dumped_text;
  }     
</script>