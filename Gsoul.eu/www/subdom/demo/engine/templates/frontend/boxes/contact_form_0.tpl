<?if(trim($box->nadpis)!=''&&$box->zobrazovat_nadpis==1){?><h3 class="align-center no-print"><?=trim($box->nadpis);?></h3>
<?}?>
<?if($message_return=='antispam'){?><div class="h4 align-center autoscroll">Musíte správně vyplnit odpověď na antispamovou otázku.</div><?}?>
<?if($message_return=='data'){?><div class="h4 align-center autoscroll">Musíte vyplnit všechny povinné údaje.</div><?}?>
<?if($message_return=='ok'){?><div class="h4 align-center autoscroll">Zpráva úspěšně odeslána.</div><?}?>
<div class="contact-form no-print">  
	<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">  
		<div class="grid grid-spaced">
			<div class="col col-1-2">
				<label>
					* Jméno a příjmení:
					<input type="text" name="contact_form_name_surname" value="<?=$name_surname;?>" required />    
				</label>				
        <label>
					* E-mail:
					<input type="email" name="contact_form_mail" value="<?=$mail;?>" required />    
				</label>				
				<label>
					Telefon:
					<input type="text" name="contact_form_phone" value="<?=$phone;?>" />    
				</label>
			  <label>
					* Kolik je tři plus šest (číslem):
					<input type="text" name="contact_form_antispam" value="<?=$antispam;?>" required />    
				</label>  								
			</div>					
			<div class="col col-1-2">
				<label>
					* Zpráva:
					<textarea name="contact_form_message" required class="large"><?=$message;?></textarea>    
				</label>
				Položky označené * jsou povinné.
			</div>
			<div class="col col-1-1">
				<input type="hidden" name="box_action" value="contact_form_send" />
        <input type="hidden" name="box_identificator" value="<?=$box->idb;?>" />
				<button type="submit">Odeslat&nbsp;&nbsp;&nbsp;<em class="fa fa-angle-right"></em></button>
			</div>
		</div>
	</form>
</div>