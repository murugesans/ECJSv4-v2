<?php
include('config.php');
$access_token = getAccessToken();
$PAYMENT_ID=$_POST['paymentID'];
//$PAYMENT_ID=$_GET['paymentId'];
$PAYER_ID=$_POST['payerID'];
//$PAYER_ID=$_GET['PayerID'];

$postData = '{
    "payer_id":"'.$PAYER_ID.'"
}';
//echo $PAYMENT_ID; exit;
$res = executePayment($access_token, $postData,$PAYMENT_ID);
print json_encode($res);	
?>