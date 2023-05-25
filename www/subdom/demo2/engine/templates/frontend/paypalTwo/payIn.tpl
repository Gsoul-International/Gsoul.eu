<div class="breadcrumb">
  <ul>
  <li><a href="/"><?=$mainpagename;?></a></li>
  <li><a href="<?=$apaypal?>"><?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?></a></li>          
  </ul>
</div> 
<h1><?=$systemTranslator['uzivatel_dobijeni_a_vyplaceni_kreditu'];?> - <?=$systemTranslator['paypal_dobiti_kreditu'];?></h1>
<p class="align-center">
  <div id="paypal-button"></div>
  <script src="https://www.paypalobjects.com/api/checkout.js"></script>
  <script>
    paypal.Button.render({
      env: '<?if($mode=='tester'){?>sandbox<?}else{?>production<?}?>',
      client: {            
        sandbox:    '<?=$clientIdTester;?>',
        production: '<?=$clientIdProduction?>'
        },
      payment: function() {
        var env    = this.props.env;
        var client = this.props.client;
        return paypal.rest.payment.create(env, client, {
          transactions: [{
            amount: { total: '<?=str_replace(',','.',$amountP);?>', currency: '<?=$currencyP?>' }
            }]
          });
        },
      commit: true,
      onAuthorize: function(data, actions) {          
        return actions.payment.execute().then(function() {
          alert('payment executing'+data.body.id);
          });
        }
    },'#paypal-button');
  </script>



</p>