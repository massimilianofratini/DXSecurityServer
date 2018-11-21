<?php 
session_start();

require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('crypto.php');
require_once(__DIR__ . '/engineList.php');
require_once(__DIR__ . '/getUserInfo.php');

//get internal IP
#$eng=$_SERVER["SERVER_NAME"];
#$deip=getInternalIP($eng);
$internalengine=getEngineInfoByExtName($_SERVER["SERVER_NAME"]);
$deip=$internalengine["internalip"];
mydebug("internal ip:".print_r($deip,true));

//signal logout in progress, to avoid relogin
$_SESSION["logoutinprogress"]=true;
header_remove("profile");
unset($_SERVER[Cfg::SM_UID]);
unset($_SESSION["profile"]);
unset($_COOKIE["profile"]);
setcookie("profile", "null", 1, "/", Cfg::INSTALL_DOMAIN);
mydebug("Nulled all profile info");


$ch=curl_init(); 
$jsessionid=$_COOKIE["JSESSIONID"];
curl_setopt($ch,CURLOPT_URL,"http://".$deip."/resources/json/delphix/logout"); 
curl_setopt($ch, CURLOPT_POST, 1);                                                                     
curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$jsessionid);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',
    'X-Browser-Session: true',                                                                           
    'Content-Length: 0',                                                                                
    'Accept: application/json, text/javascript, */*; q=0.01',
    'X-Requested-With: XMLHttpRequest')                                                                       
);                                 
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://".$deip."/Server.html");

$result=curl_exec($ch); 
curl_close($ch);
#session_destroy();
mydebug("Executed logout from Server: ".$deip);


//print $result;
header("refresh:2; url=/login2/securityportal.php");
echo "<html><body>User logged out. Redirecting to portal</body></html>";

?>
