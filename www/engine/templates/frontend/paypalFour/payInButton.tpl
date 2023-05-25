<p>
  <?=round($order->credit,2)?>$ <?=$systemTranslator['paypal_charges_including'];?> <?=round($order->cost,2)?>$.
</p>
<p>
  <div id="paypal-button"></div>
  <div id="paypal-confirm"></div>   
  <script src="https://www.paypal.com/sdk/js?client-id=<?=$clientId;?>&currency=<?=$order->mena?>"></script>
  <script>        
    paypal.Buttons({
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: '<?=round($order->cost,2)?>'
              }
            }]
          });
        },
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {     
          document.getElementById("paypal-confirm").innerHTML="<br /><br /><?=$systemTranslator['paypal_confirm_order_description'];?><br /><br /><a class=\"button large\" href=\"<?=$apaypalConfirm?>&paypal_id="+details.id+"\"><?=$systemTranslator['paypal_confirm_order'];?></a><br /><br />";           
          location.href='<?=$apaypalConfirm?>&paypal_id='+details.id;                      
          });
        }
      }).render('#paypal-button');
  </script>
</p>