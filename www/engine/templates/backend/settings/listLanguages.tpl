<h1>Jazyky</h1>
<?if($message=='language-not-found'){?><p><b>Jazyk s ID #<?=(int)getget('idl','0');?> se nepodařilo najít.</b></p><br /><?}?>
<?if($message=='created'){?><p><b>Jazyk úspěšně přidán.</b></p><br /><?}?>
<br />
<table>
  <tr>      
    <th>Jazyk</th>
    <th>Vlaječka</th>
    <th>Zobrazovat</th>
    <th colspan="2">Výchozí</th>
  </tr>
  <form method="post" action="<?=$aaddlang?>" enctype="multipart/form-data">
    <tr>
      <td><input type="text" name="nazev" value="" /></td>
      <td><input type="file" name="image" /></td>
      <td><input type="checkbox" name="aktivni" value="1" checked /></td>
      <td><input type="checkbox" name="vychozi" value="1" /></td>
      <td style="text-align:right"><input type="submit" value="Přidat jazyk" /></td>
    </tr>
  </form>
  <?foreach($langs as $s){?>
    <tr>
      <td width="30%"><b><?=$s->nazev?>:</b></td>
      <td><?if(file_exists('userfiles/langs/'.$s->idl.'.png')){?><img src="/<?='userfiles/langs/'.$s->idl.'.png';?>" /><?}else{?>-<?}?></td>      
      <td><?if($s->aktivni==1){?>Ano<?}else{?>Ne<?}?></td>
      <td><?if($s->vychozi==1){?>Ano<?}else{?>Ne<?}?></td>
      <td style="text-align:right"><a href="<?=$s->aedit?>" title="Editovat položku"><i class="fa fa-pencil"></i></a></td>
    </tr>    
  <?}?>
  <tr>
    <td colspan="5">Dbejte na to, aby byl výchozí jazyk vždy právě jeden a aby byl zároveň i zobrazen.</td>
  </tr>
</table>
<br />