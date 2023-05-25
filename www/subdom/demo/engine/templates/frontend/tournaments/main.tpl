<ul class="breadcrumb">
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href="">Tournaments</a></li>     
</ul> 
<h1>Tournaments</h1>
<?if(getget('message','')=='tournament-not-found'){?><h2>Turnaj nenalezen.</h2><br><?}?>
<div class="grid grid-semipadded align-center grid-server gap-top-16">
  <div class="col col-header col-1-9">Hra</div><div class="col col-header col-1-9">Server</div><div class="col col-header col-1-9">Buy in</div><div class="col col-header col-1-9">Typ hry</div><div class="col col-header col-1-9">Mapa</div><div class="col col-header col-1-9">V čase</div><div class="col col-header col-1-9">Hráčů</div><div class="col col-header col-1-9">Dohráno</div><div class="col col-header col-1-9">Operace</div>
  <?foreach($tournaments as $ts){?>           
    <div class="grid grid-server-item">
      <div class="col col-1-9"><?=$games2[$ts->id_hry]?></div>
      <div class="col col-1-9"><?=$servers2[$ts->id_serveru]?></div>
      <div class="col col-1-9"><?=printcost($ts->cena)?></div>
      <div class="col col-1-9"><?=$types2[$ts->id_typu_hry]?></div>
      <div class="col col-1-9"><?=$maps2[$ts->id_mapy]?></div>
      <div class="col col-1-9"><?=strftime('%d.%m.<br>%H:%M',$ts->datum_cas_startu);?></div>
      <div class="col col-1-9"><?=$ts->minimalni_pocet_hracu?> až <?=$ts->maximalni_pocet_hracu?></div>
      <div class="col col-1-9"><?=$ts->dohrano==1?'Ano':'Ne';?></div>   
      <div class="col col-1-9"><?if($userID>0){?><a href="<?=$ts->aview?>">Zobrazit</a><?}?></div>        
    </div>  
  <?}?>
</div>
</div></div>
<div class="grid align-left grid-form grid-semipadded align-middle"> 
<?if(count($games)>0&&$userID>0){?>
  <div class="col col-1-1"><h2>Přidání nového turnaje</h2></div>
  <form method="post" action="<?=$anewpost?>">
    <div class="col col-1-3"><label for="idg">Zvolte hru:</label> <select name="idg" id="idg" /><?foreach($games as $g){?><option value="<?=$g->idg?>"><?=$g->nazev?></option><?}?></select></div>
    <div class="col col-1-3 align-center">&nbsp;<br><input type="submit" value="Vytvořit turnaj" /></div>
  </form>
<?}?>