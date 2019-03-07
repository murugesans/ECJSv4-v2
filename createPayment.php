<?php
include('config.php');
$access_token = getAccessToken(); //echo $access_token;

/********** Create payment Pay Load ****/
$postData = '{
   "intent": "CAPTURE",
   "application_context": {
       "return_url": "https://www.example.com",
       "cancel_url": "https://www.example.com",
       "brand_name": "EXAMPLE INC",
       "landing_page": "LOGIN",
       "shipping_preference": "SET_PROVIDED_ADDRESS",
       "user_action": "PAY_NOW"
   },
   "purchase_units": [{
       "reference_id": "TEST1",
       "description": "Sporting Goods",
       "custom_id": "CUST-HighFashions",
       "soft_descriptor": "HighFashions",
       "amount": {
           "currency_code": "SGD",
           "value": "1.00",
           "breakdown": {
               "item_total": {
                   "currency_code": "SGD",
                   "value": "1.00"
               }
               }
       },
       "items": [{
           "name": "T-Shirt",
           "description": "Green XL",
           "sku": "sku01",
           "unit_amount": {
               "currency_code": "SGD",
               "value": "1.00"
           },
           "quantity": "1",
           "category": "PHYSICAL_GOODS"
       }],
       "shipping": {
           "method": "AU Postal Service",
           "address": {
               "address_line_1": "123 Townsend St",
               "address_line_2": "Floor 6",
               "admin_area_2": "Sydney",
               "admin_area_1": "Sydney",
               "postal_code": "2000",
               "country_code": "AU"
           }
       }
   }]
}';
//Create Payment method

$res = getApprovalURL($access_token, $postData);
print json_encode($res);
/*$approval_url = $res['links']['1']['href'];
$token = substr($approval_url, -20);
print json_encode($token); */
//var_dump($res); exit;
?>