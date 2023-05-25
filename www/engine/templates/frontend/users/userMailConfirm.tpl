<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>   
  <li><a href=""><?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?></a></li>     
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_uzivatelsky_ucet'];?></h1>
<div class="grid align-left grid-form grid-semipadded align-middle">
  <div class="col col-1-1">    
    <?if($message=='ok'){?><?=frontendMessage('green',$systemTranslator['uzivatel_vas_email_byl_potvrzen']);?><?}?>
    <?if($message=='ko'){?><?=frontendMessage('red',$systemTranslator['uzivatel_potvrzeni_emailu_se_nezdarilo']);?><?}?>
    <?if($message=='pok'){?><?=frontendMessage('green',$systemTranslator['uzivatel_vas_paypal_email_byl_potvrzen']);?><?}?>
    <?if($message=='pko'){?><?=frontendMessage('red',$systemTranslator['uzivatel_potvrzeni_paypal_emailu_se_nezdarilo']);?><?}?>   
  </div>  
</div>