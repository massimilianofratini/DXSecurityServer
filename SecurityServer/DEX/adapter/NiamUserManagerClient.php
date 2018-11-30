<?php
// Wrapper for ws call
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');


function httpGet($url) 
{
	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	//  curl_setopt($ch,CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			

	$output=curl_exec($ch);

	curl_close($ch);
	return $output;
}



//**MAIN**
$NiamUMUrl='http://127.0.0.1/login2/niam/NiamUserManager.php';
$NiamAckUrl='http://127.0.0.1/login2/niam/NiamAcknowledge.php';
mydebug('Calling UserManager');
$result = trim(httpGet($NiamUMUrl));
mydebug("WS UM result: [".$result."]");

mydebug('Calling Acknowledge');
$result = trim(httpGet($NiamAckUrl));
mydebug("WS Ack result: [".$result."]");

?>
