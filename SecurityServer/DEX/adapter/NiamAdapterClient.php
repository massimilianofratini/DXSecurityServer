<?php
// Wrapper for ws call
include_once 'mylogger.php';

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

#$NiamAdapterUrl='http://127.0.0.1:8080/login2/niam/NiamAdapter.php';
$NiamAdapterUrl='http://127.0.0.1/login2/niam/NiamAdapter.php';
$params = $argv[1];
$fullQuery=$NiamAdapterUrl.'?'.'niamrequest='.urlencode($params);
mydebug('Full query: '.$fullQuery);
$result = trim(httpGet($fullQuery));
mydebug("WS result: [".$result."]");

if ($result=="OK"){
	echo "OK";
	exit(0);
}
else {	
	echo "KO";
	exit(1);
}
?>
