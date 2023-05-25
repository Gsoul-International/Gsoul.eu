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
      <th>Text prvku</th>        
    </tr>
    <tr>
      <td><?=$text_1;?></td>
    </tr>
    <tr>
      <td align="right"><input type="submit" value="Uložit prvek" /></td>
    </tr>
</table>    