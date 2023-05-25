<h1>Základní nastavení</h1>
<?if($message=='setting-not-found'){?><p><b>Systémovou proměnnou s ID #<?=(int)getget('ids','0');?> se nepodařilo najít.</b></p><?}?>
<?foreach($settings as $s){?>
  <table>
    <tr>      
      <th colspan="3"><?=$s->popis?></th>
    </tr>
    <tr>
      <td width="30%"><b><?=$s->nazev?>:</b></td>
      <?if($s->typ=='ano_ne'){?>
        <td><?if($s->hodnota=='1'){?>Ano<?}else{?>Ne<?}?></td>
      <?}else{?>
        <td><?=strip_tags($s->hodnota);?></td>
      <?}?>
      <td align="center" width="5%"><a href="<?=$s->aedit?>" title="Editovat položku"><i class="fa fa-pencil"></i></a></td>
    </tr>  
  </table>
  <br />
<?}?>
<div class="BackendHelp">
  <a onclick="$('#BackendHelp').slideToggle('slow');">
    <div class="BackendHelpHeader">
      <i class="fa fa-question-circle"></i> Zobrazit / skrýt nápovědu 
    </div>
  </a>
  <div class="BackendHelpBody" id="BackendHelp">    
      <b>Zde nastavujete systémové proměnné, při editaci buďte obezřetní!</b> <br /><br /> 
      Systémové proměnné, které tu editujete, mají zásadní význam na nejdůležitější věci a je třeba být pečlivý a  opatrný při jejich editaci. U každé proměnné je k dispozici nápověda, k čemu slouží a jaký má v systému význam. V případě nejasností můžete kdykoli kontaktovat výrobce Vašich stránek.<br />
  </div>
</div>

