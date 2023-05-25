<?php
class PayPalCurl{
  public function getAccessToken($clientId,$clientSecret,$mode='production'){
    if($mode=='production'){$url='https://api.paypal.com/v1/oauth2/token';}else{$url='https://api.sandbox.paypal.com/v1/oauth2/token';}
    $requestHeaders=array('Accept: application/json','Accept-Language: en_US');
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,'grant_type=client_credentials');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$requestHeaders);
    curl_setopt($ch,CURLOPT_USERPWD,''.$clientId.':'.$clientSecret.'');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $responseBody=curl_exec($ch);    
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);    
    if($httpCode==200){
      $decoded=json_decode($responseBody);
      if(isset($decoded->access_token)){
        return $decoded->access_token;
      }else{
        return 'ERROR> No access token found.';
      }      
    }
    return 'ERROR> '.$httpCode.' - '.$responseBody;     
    }
  public function sendRequest($object,$access,$uri,$mode='production'){
    if($mode=='production'){$server='https://api.paypal.com/';}else{$server='https://api.sandbox.paypal.com/';}
    $requestHeaders=array('Content-Type: application/json','Authorization: Bearer '.$access /*,'PayPal-Partner-Attribution-Id: EXAMPLE_MP'*/ );
    $url=$server.$uri;
    $objectJson=($object);   //  json_encode
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$objectJson);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$requestHeaders);    
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_VERBOSE,TRUE);
    $responseBody=curl_exec($ch);    
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);    
    if($httpCode==200||$httpCode==201){
      $decoded=json_decode($responseBody);
      if(isset($decoded)){
        return $decoded;
      }else{
        return 'ERROR> No data found.';
      }      
    }
    return 'ERROR> '.$httpCode.$responseBody; 
    }

  }

$ppc=new PayPalCurl();
//$access=$ppc->getAccessToken('AQAhdmNc617IELCpuuxoNyYTy4PGfSGLmebXQs76yRVGbvKboFO2WnkoC6Eg0p_7fjijeNyJSSzbiakr','EGaLJfMpg070uD-ibXho5a4hIzacM9lIoMgqjgQ3fw0neCEMoZoqYt7twwphz1SaDdXcfmj7AwiJE8Cw','tester');//my
$access=$ppc->getAccessToken('ARxnP1qDbordUcsrGFkLM-pPLgUZZ1wtRj4LqgOU0DbpKLfn04Pfq6yT5TK9CT0XWf6mHqk5FGpfL2yk','EP5N_25EPZ0NzyrLB6mPqz33vmJILCbWZZKnf37WdkR8byPDTjEVNSBGl1g3DawuUgfyCwc-lgpLf0I1','tester');//gsoul
//$order=new stdClass();
$order='{
  "intent": "AUTHORIZE",
  "purchase_units": [
    {
      "amount": {
        "currency_code": "USD",
        "value": "1.00"
      }
    }
  ]
}';
$uri='v2/checkout/orders';
$returnData=$ppc->sendRequest($order,$access,$uri,'tester');
//var_dump($returnData);
if($returnData->status=='CREATED'){
  //header("Location: ".$returnData->links[0]->href);
  $uri2='v2/checkout/orders/'.$returnData->id.'';
  echo '----'.$uri2.'----';
  $order2='';
  $returnData2=$ppc->sendRequest($order2,$access,$uri2,'tester');
  var_dump($returnData2); 
  }

  var_dump($returnData);