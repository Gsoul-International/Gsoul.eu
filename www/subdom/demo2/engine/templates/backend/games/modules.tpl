<h1>Hraní - Moduly</h1>
<br>
<?if(getget('message','')=='not-found'){?><h2>Modul neexistuje.</h2><br><?}?>
<table>
  <tr><th>Název</th><th>Interní název</th><th colspan="2">Počet her</th></tr>
  <?if(count($list)>0){
    foreach($list as $l){?>  
      <tr>
        <td width="10%"><b><?=$l->nazev?></b></td>              
        <td>#<?=$l->interni_nazev?></td>
        <td><?=$l->xcnt?></td>          
        <td width="3%"><a title="Editovat modul" href="<?=$l->aedit?>"><i class="fa fa-pencil"></i></a></td>         
      </tr>              
    <?}?>    
  <?}?>
</table>