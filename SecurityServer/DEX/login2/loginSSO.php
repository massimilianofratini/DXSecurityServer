<?php 
session_start();

require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('crypto.php');

require_once(__DIR__ . '/engineList.php');
require_once(__DIR__ . '/getUserInfo.php');


$key = myhex2bin(Crypto::SEED);

//get internal IP
$eng=$_SERVER["SERVER_NAME"];
$engInfo=getEngineInfoByExtName($eng);
if ($engInfo==false) {
	mydebug("engine not found: ".$eng);
	return;
}

//$deip=getInternalIP($eng);
$deip=$engInfo['internalname'];
mydebug("engine info: ".print_r($engInfo,true));

if (! isset($_SESSION["profile"]))
{
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

//get user profile 
$username=$_SESSION["profile"];
mydebug("username:".$username);
$userprofile=get_user_info($username);
mydebug("user profile:".print_r($userprofile,true));

//check if exists and enabled, if yes store in session and set correct password, if not set wrong password to block
if (($userprofile!=false) && (strpos(strtoupper($userprofile["datacenter"]),strtoupper($engInfo["datacenter"])) !== false) )
{
	$_SESSION["userprofile"]=$userprofile;
	$de_username=$userprofile["de_username"];
	$de_password=$userprofile["de_password"];
	$userpassword=$userprofile["password"];
	mydebug("encrypted password:".$de_password);
}
else
{
	$de_username="wrongpassword";
	$de_password=Crypto::encrypt("wrongpassword", $key, true);
	$userpassword=Crypto::encrypt("wrongpassword", $key, true);
	mydebug("user not authorized for engine $eng, only for ".$userprofile['datacenter']);
}

#$userpassword=base64_decode($userpassword);
#$userpassword = Crypto::decrypt(($userpassword), $key);
#mydebug("decrypted password:".$userpassword);
#$de_password=base64_decode($de_password);
$de_password=Crypto::decrypt($de_password, $key, true);
mydebug("decrypted password:".$de_password);

$ch=curl_init(); 
#$post_params=array('username'=>$username,'password'=>$userpassword,'target'=>'DOMAIN','type'=>'LoginRequest');
$post_params=array('username'=>$de_username,'password'=>$de_password,'target'=>'DOMAIN','type'=>'LoginRequest');
$json_post_params=json_encode($post_params);

$jsessionid=$_COOKIE["JSESSIONID"];
curl_setopt($ch,CURLOPT_URL,"http://".$deip."/resources/json/delphix/login"); 
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
curl_setopt($ch, CURLOPT_REFERER, "http://".$deip."/login/index.html");

$result=curl_exec($ch); 
curl_close($ch); 
print $result;


?>
