<?php
session_start();

include_once 'sessionInfo.php';
include_once 'mylogger.php';
include_once 'crypto.php';
include_once 'delphixSession.php';


#check if logout is in progress
//if (isset($_SESSION["logoutinprogress"])) {
//    mydebug("logout in progress...");
//    header("refresh:0; url=/login2/logout.php");
//    echo "<html><body>User logged out. Redirecting to portal</body></html>";
//    return;
//}


//Get "secsrvsessionid" authentication cookie
$secsrvsession = $_COOKIE["secsrvsessionid"];
$sessionInfo = getSessionInfo($secsrvsession);
//check if session is found
if ($sessionInfo == false) {
    mydebug("session $secsrvsession not found in db, closing.");
    header("refresh:2; url=".Cfg::PORTAL_URL);
    echo "<html><body>Session closed, please login on <a href='".Cfg::PORTAL_URL."'>Security Server Portal</a></body></html>";
    return;
}
mydebug("Session info: ".print_r($sessionInfo, true));


//get internal engine name
//$internalengine = $sessionInfo["internalname"];
$internalengine = $sessionInfo["internalip"];
mydebug("internal engine:" . print_r($internalengine, true));
mydebug("Authenticating to: " . "http://" . $internalengine . "/login/index.html");

$key = myhex2bin(Crypto::SEED);
$de_username = $sessionInfo["de_username"];
$de_password = Crypto::decrypt($sessionInfo["de_password"], $key, true);
mydebug("decrypted password:" . $de_password);

//print delphixSession("http://" . $internalengine, $de_username, $de_password, $secsrvsession);

//Get the real INDEX page
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://".$internalengine."/login/index.html");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine."/Server.html");
mydebug("curl request: ".print_r($ch,true));

$result=curl_exec($ch);
curl_close($ch);

mydebug("curl result: ".print_r($result,true));

list($headers, $content) = explode("\r\n\r\n", $result, 2);
mydebug("response headers: ".print_r($headers,true));
mydebug("response content: ".print_r($content,true));

$headersArray = explode("\r\n", $headers);
foreach ($headersArray as $head)
{
    header($head);
}


//Inject auto post code
$code_search='dx.login.startLogin(browserDeprecated, browserUnsupported)';
$code_add=			'$("#loginForm").css("opacity","0,5");';
$code_add=$code_add.'$("#username").val("autologin");';
$code_add=$code_add.'$("#username").attr("disabled","disabled");';
$code_add=$code_add.'$("#password").attr("disabled","disabled");';
$code_add=$code_add.'$(\'input[type="submit"][value="Log In"]\').triggerHandler(\'click\');';
$contentnew=str_replace($code_search,$code_search.';'.$code_add,$content);
print $contentnew;
mydebug("modified page: ".print_r($contentnew,true));
?>
