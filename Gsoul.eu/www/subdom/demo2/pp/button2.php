<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  
  <title></title>
  </head>
  <body>
    <div id="paypal-button"></div>
    <div id="paypal-confirm"></div>   
    <script src="https://www.paypal.com/sdk/js?client-id=AQAhdmNc617IELCpuuxoNyYTy4PGfSGLmebXQs76yRVGbvKboFO2WnkoC6Eg0p_7fjijeNyJSSzbiakr&currency=USD"></script>
    <script>        
      paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '1.0'
                }
              }]
            });
          },
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {     
            document.getElementById("paypal-confirm").innerHTML="<a href=\"/pp/order2.php?pid="+details.id+"\">Confirm order</a>";           
            location.href='/pp/order2.php?pid='+details.id;                      
            });
          }
        }).render('#paypal-button');
    </script>
  </body>
</html>
