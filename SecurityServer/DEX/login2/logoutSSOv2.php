<?php
session_start();
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('sessionInfo.php');


//include_once 'sessionInfo.php';
//include_once 'mylogger.php';
//include_once 'config.php';


#check if logout is in progress
//if (isset($_SESSION["logoutinprogress"])) {
//    mydebug("logout in progress...");
//    header("refresh:0; url=/login2/logout.php");
//    echo "<html><body>User logged out. Redirecting to portal</body></html>";
//    return;
//}


//Get "secsrvsessionid" authentication cookie
$secsrvsession = $_COOKIE[Cfg::SECSRVSESSIONID];
$sessionInfo = getSessionInfo($secsrvsession);
//check if session is found
if ($sessionInfo == false) {
    mydebug("session $secsrvsession not found in db, closing.");
    header("refresh:2; url=".Cfg::PORTAL_URL);
    echo "<html><body>Session closed, please login on <a href='".Cfg::PORTAL_URL."'>Security Server Portal</a></body></html>";
    return;
}
//get internal engine name
$internalengine = $sessionInfo["internalip"];
mydebug("Logging out from: " . "http://" . $internalengine . "/resources/json/delphix/logout");

//logout from the Engine
$ch=curl_init(); 
$jsessionid=$_COOKIE["JSESSIONID"];
curl_setopt($ch,CURLOPT_URL,"http://".$internalengine."/resources/json/delphix/logout");
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
curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine."/Server.html");

$result=curl_exec($ch); 
curl_close($ch);
#session_destroy();
mydebug("Executed logout from Server: ".$internalengine);


mydebug("LOGOUT URL IS: ".print_r($_SERVER["QUERY_STRING"],true));
//Check if a return url is specified, to see if this is just a pre-login logout
if (isset($_GET["NEXTPAGE"])) {
    //Redirect to requested url
    header("refresh:0; url=".urldecode(trim($_GET["NEXTPAGE"])));
    echo "<html><body>Connecting...</body></html>";
}
else {
    //Close SecServerSession
    closeSecServerSession($secsrvsession);

    //Redirect to the portal
    header("refresh:2; url=" . Cfg::PORTAL_URL);
    echo "<html><body>User logged out. Redirecting to portal</body></html>";
}
?>
