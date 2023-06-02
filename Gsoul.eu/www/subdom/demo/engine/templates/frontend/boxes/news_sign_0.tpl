<?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><div class="h3 align-center"><?=trim($box->nadpis);?></div>
<?}?>
<?if($message=='antispam'){?><div class="h4 align-center">Musíte správně vyplnit odpověď na antispamovou otázku.</div><?}?>
<?if($message=='emailexist'){?><div class="h4 align-center">E-mail je již zaregistrován.</div><?}?>
<?if($message=='email'){?><div class="h4 align-center">Musíte vyplnit platný email.</div><?}?>
<?if($message=='done'){?><div class="h4 align-center">Registrace k odběru novinek proběhla v pořádku.</div><?}?>
<div class="contact-form">
  <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
    <div class="grid grid-spaced">
			<div class="col col-1-2">
				<label>
					* E-mail:
					<input type="email" name="news_sign_mail" value="<?=$mail;?>" required="" />    
				</label>
			</div>  			
			<div class="col col-1-2">
				<label>
					* Kolik je pět plus osm (číslem):
					<input type="text" name="news_sign_antispam" value="<?=$antispam;?>" required />    
				</label>  
			</div>			
			<div class="col col-1-1">
				<input type="hidden" name="box_action" value="news_sign_add" />
        <input type="hidden" name="box_identificator" value="<?=$box->idb;?>" />
        <button type="submit">Odebírat novinky</button>
			</div>
		</div> 
  </form>
</div>