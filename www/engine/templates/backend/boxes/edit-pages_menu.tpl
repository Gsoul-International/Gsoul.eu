<br />
<table>
    <tr>
      <th>Šablona zobrazení</th>        
    </tr>
    <tr>
      <td>
        <select name="sablona" class="width-740">
          <?foreach($sablony as $sk=>$sv){?>
            <option <?if($sk==$box->sablona){?>selected<?}?> value="<?=$sk;?>"><?=$sv;?></option>
          <?}?>
        </select>
      </td>        
    </tr>
    <tr>
      <th>Zobrazovat stránky</th>        
    </tr>
    <tr>
      <td>
        <select name="int_1" class="width-740">
          <?=$tree_select;?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right"><input type="submit" value="Uložit prvek" /></td>
    </tr>
</table>    