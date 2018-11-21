<?php
// Wrapper for ws call
require_once('../common/secsrv_includes/config.php');
require_once_common('mylogger.php');


function httpGet($url) 
{
	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	//  curl_setopt($ch,CURLOPT_HEADER, false);

	$output=curl_exec($ch);

	curl_close($ch);
	return $output;
}



//-----------main
$NiamAdapterUrl=Cfg::NIAM_ADAPTERURL;
$params = $argv[1];
$fullQuery=$NiamAdapterUrl.'?'.urlencode($params);
mydebug('Full query: '.$fullQuery);
$result = httpGet($fullQuery);
mydebug($result);

if ($result="OK"){
	echo "OK";
	exit(0);
}
else {	
	echo "KO";
	exit(1);
}
?>