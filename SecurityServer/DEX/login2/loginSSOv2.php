<?php 

session_start();
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('sessionInfo.php');
require_once_common('crypto.php');

require_once(__DIR__ . '/delphixSession.php');


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
    echo "<html><body>\"Session unauthorized, please login first on Security Server Portal\"</body></html>";
    return;
}

//get internal engine name
$internalengine = $sessionInfo["internalip"];
mydebug("internal engine:" . print_r($internalengine, true));
mydebug("Authenticating to: " . "http://" . $internalengine . "/login/index.html");



$key = myhex2bin(Crypto::SEED);
$de_username = $sessionInfo["de_username"];
$de_password = Crypto::decrypt($sessionInfo["de_password"], $key, true);
mydebug("username:".$de_username);
mydebug("decrypted password:" . $de_password);

$ch=curl_init();
$post_params=array('username'=>$de_username,'password'=>$de_password,'target'=>'DOMAIN','type'=>'LoginRequest');
$json_post_params=json_encode($post_params);

$jsessionid=$_COOKIE["JSESSIONID"];
curl_setopt($ch,CURLOPT_URL,"http://".$internalengine."/resources/json/delphix/login");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$jsessionid);
curl_setopt($ch,CURLOPT_POSTFIELDS,$json_post_params);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',
    'X-Browser-Session: true',                                                                           
    'Content-Length: ' . strlen($json_post_params),                                                                                
    'Accept: application/json, text/javascript, */*; q=0.01',
    'X-Requested-With: XMLHttpRequest')                                                                       
);                                 
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine."/login/index.html");

$result=curl_exec($ch); 
curl_close($ch); 
print $result;


?>
