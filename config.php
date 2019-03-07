<?php
    /*
        * Config for PayPal specific values
    */

    //Whether Sandbox environment is being used, Keep it true for testing
    define("SANDBOX_FLAG", true);
  

    //PayPal REST API endpoints
    define("SANDBOX_ENDPOINT", "https://api.sandbox.paypal.com");
    //define("SANDBOX_ENDPOINT", "https://api.stage2d0515.qa.paypal.com:11888");
    define("LIVE_ENDPOINT", "https://api.paypal.com");

    //Merchant ID
    define("MERCHANT_ID","E9GCL5FX4TU2C");

    //PayPal REST App SG(muru_sgbus@pp.com) SANDBOX Client Id and Client Secret
    define("SANDBOX_CLIENT_ID" , "AYPRSHXvddDzYq36L_Ya3Um0zMdKQ-RdYKvmq9PW-x5Nu1FWTAzoH95EHE5kA4rjzvmi-oT5EWR_caDu");
	define("SANDBOX_CLIENT_SECRET", "ED91SXDfqjwbOmEU9Op8O_k6ymKSA8fjtVuKcu1Xd4JNEtjleAh7ouwtXGYDna8-uSrBErDDE6Y7tSFF");
	

  //Goutham gg_aus@123.com 
   // define("SANDBOX_CLIENT_ID" , "AVKeZbTZSRYR9mDD5Q26qXNDwFrP-WL4okkzgpUIX1_dl21XwV-qEBpYxmD_BYieBOrV19Rg2Im_JK_T");
   //define("SANDBOX_CLIENT_SECRET", "EGbBxyQR4JP8yCIiNfZzKEOobaexl6chAgGyULnem7zP5lR2yro0Oz7XlCJTPMK2HRGABmmKGAUT3TVY");

	//US SB
	//define("SANDBOX_CLIENT_ID" , "ATWk3eZ8GJBdo7GVx6LCAxbfzSAB_9_ccn-MSHFmgXiR2Me_VJkedNwk3OC24k0qHxiUPMuGcUHHO-FN");
   // define("SANDBOX_CLIENT_SECRET", "EGVKX8odr19-U0LmBdqRYAHGoKl9H_ejGEbmUfBsgOlN9hhdQ6A6wAMeUfdXyifL2Sa8vI-OSrX6flPa");
	
	//PayPal REST App IN Stage Client Id and Client Secret
    //define("SANDBOX_CLIENT_ID" , "AXN-PpAoujGdz8DBq6s-wEGM0RRKOJWvFPBgPt5faKmk20RgqsoE2CghPOGlHf_F4jWNKJVaz6UHyhTy");
   // define("SANDBOX_CLIENT_SECRET", "EBTs6Y8jM99w_FhZW8C6hK-rDsRUE3A2SA7Z6Yw7C1ZPe_4NBtsdWbmiH012AMQ17glH_cEZd1AGyIpe");

    //Environments -Sandbox and Production/Live
    define("SANDBOX_ENV", "sandbox");
    define("LIVE_ENV", "production");

    //PayPal REST App SG(dl-pp-seatesting@paypal.com) Live Client Id and Client Secret
    //define("LIVE_CLIENT_ID" , "AbOqLzAB6a-CyqMbj6TGE8ZLreaBKNFNecBMM7xHobWI_KXErRgjW_b4NWEalCReUHsP4Nzv7sBlXn8B");
    //define("LIVE_CLIENT_SECRET" , "EGIQtUQj-nGxOX5kQ2KNJoeHm7TSjvAVOOATVa_mQHHwrky3Q3wipUk9gyK-ayojyTTAbkdnvkl-gIKx");

    //ButtonSource Tracker Code
    define("SBN_CODE","PP-ECJSv4Sample");

	$randomnumber=mt_rand();
	
	/*
	* Contains common useful functions

	* Purpose: 	To make a cURL call to REST API
	* Inputs:
	*		curlServiceUrl    : the service URL for the REST api
	*       curlHeader        : the header parameters specific to the REST api call
	*       curlPostData      : the post parameters encoded in the form required by the api (json_encode or http_build_query)
	* Returns:
	*		array["http_code"]   : the http status code   
	*		array["jason"]       : the response string
	*/
function curlCall($curlServiceUrl, $curlHeader, $curlPostData=NULL) {

	// response container
	$resp = array(
		"http_code" => 0,
		"jason"     => ""
	);

	//set the cURL parameters
	$ch = curl_init($curlServiceUrl);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_SSLVERSION , 'CURL_SSLVERSION_TLSv1_2');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeader);

	if(isset($curlPostData)){
	if(!is_null($curlPostData)) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPostData);
	}
	}else{
		curl_setopt($ch, CURLOPT_HTTPGET, true);
	}
	//getting response from server
	$response = curl_exec($ch);

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch); // close cURL handler
	
	// some kind of an error happened
	if (empty($response)) {
		return $resp;
	}
	
	$resp["http_code"] = $http_code;
	$resp["json"] = json_decode($response, true);
	
	return $resp;
}


/**
 * Prevents Cross-Site Scripting Forgery
 * @return boolean
 */
function verify_nonce() {
	if( isset($_GET['csrf']) && $_GET['csrf'] == $_SESSION['csrf'] ) {
		return true;
	}
	if( isset($_POST['csrf']) && $_POST['csrf'] == $_SESSION['csrf'] ) {
		return true;
	}
	return false;
}

/************** Access Token ****************/
function getAccessToken(){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v1/oauth2/token";
	$clientId = (SANDBOX_FLAG ? SANDBOX_CLIENT_ID : LIVE_CLIENT_ID);
	$clientSecret = (SANDBOX_FLAG ? SANDBOX_CLIENT_SECRET : LIVE_CLIENT_SECRET);
	$curlHeader = array(
		 "Content-type" => "application/json",
		 "Authorization: Basic ". base64_encode( $clientId .":". $clientSecret),
		 "PayPal-Partner-Attribution-Id" => SBN_CODE
		 );
	$postData = array(
		 "grant_type" => "client_credentials"
		 );
//var_dump($curlHeader);
	$curlPostData = http_build_query($postData);
	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $curlPostData);
	$access_token = $curlResponse['json']['access_token'];
	//$access_token = $curlResponse['access_token'];
    return $access_token; 
    
}

/************** WebProfileID *********/
function getWebProfileID($access_token, $postData){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v1/payment-experience/web-profiles";
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token);
	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $postData);
	$webprofileid = $curlResponse['json']['id'];
    return $webprofileid;
}

/************** STC Call *********
function callSTC($access_token, $postData){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "v1/risk/transaction-contexts/";
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token);
	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $postData);
	$webprofileid = $curlResponse['json']['id'];
    return $webprofileid;
}

/************** Get payment id *********/
function getApprovalURL($access_token, $postData){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v2/checkout/orders";
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token, "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $postData);
	$jsonResponse = $curlResponse['json'];
	return $jsonResponse;
}

/**************** Execute Payment ***********/
function executePayment($access_token, $postData,$PAYMENT_ID){
$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v2/checkout/orders/".$PAYMENT_ID."/capture";
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token, "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$curlResponse = curlCall($curlServiceUrl, $curlHeader, $postData);
	//$paymentid = $curlResponse['json']['id'];
   // return $paymentid;
	$jsonResponse = $curlResponse['json'];
	//print_r($jsonResponse);exit;
	return $jsonResponse;
}

/*************** Show Payment Details ***********/
function getPaymentDetails($access_token,$PAYMENT_ID){
	$curlServiceUrl = (SANDBOX_FLAG ? SANDBOX_ENDPOINT : LIVE_ENDPOINT);
	$curlServiceUrl = $curlServiceUrl. "/v2/checkout/orders/".$PAYMENT_ID;
	$curlHeader = array("Content-Type:application/json", "Authorization:Bearer ".$access_token, "PayPal-Partner-Attribution-Id:".SBN_CODE);

	$curlResponse = curlCall($curlServiceUrl, $curlHeader);
	$jsonResponse = $curlResponse['json'];
	return $jsonResponse;
}
?>