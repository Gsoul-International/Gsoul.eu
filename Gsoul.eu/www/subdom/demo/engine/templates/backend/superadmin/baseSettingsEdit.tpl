<h1>Editace základní proměnné #<?=(int)getget('ids','0');?> </h1>
<?if($message=='saved'){?><p><b>Změny úspěšně uloženy.</b></p><?}?>
<div class="BackendBack">
  <br />
  <a href="<?=$aback;?>"><i class="fa fa-arrow-left"></i> Zpět na výpis základního nastavení</a><br>
</div>
<form method="post" action="<?=$asave?>">
  <table>
    <tr>
      <th width="25%">Název proměnné</th>
    </tr> 
    <tr>
      <td><input type="text" size="35" class="width-350" maxlength="127" name="nazev" value="<?=$setting->nazev?>" /></td>     
    </tr> 
    <tr>
      <th width="25%">Klíč proměnné</th>
    </tr> 
    <tr>
      <td><input type="text" size="35" class="width-350" maxlength="64" name="klic" value="<?=$setting->klic?>" /></td>  
    </tr>   
    <tr>
      <th width="25%">Popis proměnné</th>
    </tr> 
    <tr>
      <td><input type="text" size="35" class="width-740" name="popis" value="<?=$setting->popis?>" /></td>    
    </tr>    
    <?if($setting->typ=='int'){?>
      <tr><th>Zadejte celé číslo</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='float'){?>
      <tr><th>Zadejte desetinné číslo (můžete použít desetinnou čárku i desetinnou tečku)</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='ano_ne'){?>
      <tr><th>Zvolte hodnotu ano / ne</th></tr>
      <tr><td>
        <select name="hodnota">
          <option value="1">Ano</option>
          <option <?if($setting->hodnota=='0'){?>selected<?}?> value="0">Ne</option>
        </select>                                
      </td></tr>
    <?}?>
    <?if($setting->typ=='text'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><input class="width-740" size="80" type="text" name="hodnota" value="<?=$setting->hodnota;?>" /></td></tr>
    <?}?>
    <?if($setting->typ=='textarea'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><textarea class="width-740" cols="50" rows="5" name="hodnota"><?=$setting->hodnota;?></textarea></td></tr>
    <?}?>
    <?if($setting->typ=='editor'){?>
      <tr><th>Zadejte libovolnou textovou hodnotu</th></tr>
      <tr><td><?=$editor;?></td></tr>
    <?}?> 
    <tr>
      <th>Typ proměnné</th>
    </tr>
    <tr>
      <td colspan="2">
        <select name="typ" class="width-350">        
          <option <?if($setting->typ=='int'){?>selected<?}?> value="int">Celé číslo</option>          
          <option <?if($setting->typ=='float'){?>selected<?}?> value="float">Desetinné číslo</option>
          <option <?if($setting->typ=='ano_ne'){?>selected<?}?> value="ano_ne">Ano / ne</option>
          <option <?if($setting->typ=='text'){?>selected<?}?> value="text">Text</option>
          <option <?if($setting->typ=='textarea'){?>selected<?}?> value="textarea">TextArea</option>
          <option <?if($setting->typ=='editor'){?>selected<?}?> value="editor">Editor</option>        
        </select>
      </td>    
    </tr>   
    <tr>
      <th>Zobrazovat v administraci</th>
    </tr>
    <tr>
      <td>
        <select name="zobrazovat" class="width-350">        
          <option <?if($setting->zobrazovat==1){?>selected<?}?> value="1">Ano</option>          
          <option <?if($setting->zobrazovat==0){?>selected<?}?> value="0">Ne</option>         
        </select>
      </td>    
    </tr>       
    <tr><td align="right"><input type="submit" value="uložit" /></td></tr>      
  </table>
</form>