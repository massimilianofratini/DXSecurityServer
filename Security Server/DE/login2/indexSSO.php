<?php
session_start();

include_once 'engineList.php';
include_once 'mylogger.php';

//get internal IP
#$deip=getInternalIP($_SERVER["SERVER_NAME"]);
$internalengine=getEngineInfoByExtName($_SERVER["SERVER_NAME"]);
mydebug("internal engine:".print_r($internalengine,true));
mydebug("_SERVER:".print_r($_SERVER,true));

mydebug("Checking if the profile is already in session");
if (! isset($_SESSION["profile"]))
{
	mydebug("profile not found in session");
	if  ( isset($_SERVER[Cfg::SM_UID]) )
		$_SESSION["profile"] = $_SERVER[Cfg::SM_UID];
	else 
	{
		mydebug("Cfg::SM_UID not found");
		if  ( isset($_COOKIE["profile"]) )
			$_SESSION["profile"] = $_COOKIE["profile"];
		else 
		{
			mydebug("COOKIE profile not found");
			echo "Session Closed";
			return;
		}
	}
}

//Get the real INDEX page
$ch=curl_init(); 
curl_setopt($ch,CURLOPT_URL,"http://".$internalengine["internalname"]."/login/index.html"); 
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine["internalname"]."/Server.html");

$result=curl_exec($ch); 
curl_close($ch);

//Inject auto post code
$code_search='dx.login.startLogin(browserDeprecated, browserUnsupported)';
$code_add=			'$("#loginForm").css("opacity","0,5");';
$code_add=$code_add.'$("#username").val("autologin");';
$code_add=$code_add.'$("#username").attr("disabled","disabled");';
$code_add=$code_add.'$("#password").attr("disabled","disabled");';
$code_add=$code_add.'$(\'input[type="submit"][value="Log In"]\').triggerHandler(\'click\');'; 
$resultnew=str_replace($code_search,$code_search.';'.$code_add,$result);
print $resultnew;

?>
