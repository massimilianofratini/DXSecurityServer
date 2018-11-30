<?php
session_start();

require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once(__DIR__ . '/engineList.php');

#check if logout is in progress
if ( isset($_SESSION["logoutinprogress"]))
{
	mydebug("logout in progress...");
	header("refresh:0; url=/login2/logout.php");
	echo "<html><body>User logged out. Redirecting to portal</body></html>";
	return;
}


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

mydebug("Authenticating to: "."http://".$internalengine["internalname"]."/login/index.html");

//Get the real INDEX page
$ch=curl_init(); 
curl_setopt($ch,CURLOPT_URL,"http://".$internalengine["internalname"]."/login/index.html"); 
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine["internalname"]."/Server.html");
mydebug("curl request: ".print_r($ch,true));

$result=curl_exec($ch); 
curl_close($ch);

mydebug("curl result: ".print_r($result,true));

//Inject auto post code
$code_search='dx.login.startLogin(browserDeprecated, browserUnsupported)';
$code_add=			'$("#loginForm").css("opacity","0,5");';
$code_add=$code_add.'$("#username").val("autologin");';
$code_add=$code_add.'$("#username").attr("disabled","disabled");';
$code_add=$code_add.'$("#password").attr("disabled","disabled");';
$code_add=$code_add.'$(\'input[type="submit"][value="Log In"]\').triggerHandler(\'click\');'; 
$resultnew=str_replace($code_search,$code_search.';'.$code_add,$result);
print $resultnew;
mydebug("modified page: ".print_r($result,true));

?>
