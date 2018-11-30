<?php
session_start();
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('sessionInfo.php');
require_once_common('crypto.php');

//include_once 'delphixSession.php';

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


//get internal engine parameters
$BaseURL = "http://".$sessionInfo["internalip"].":".$sessionInfo["internalport"];
mydebug("Logging out from masking server: " . $BaseURL);


//logout from the Engine
$ch=curl_init(); 
$jsessionid=$_COOKIE["JSESSIONID"];
mydebug("JSESSIONID is : ".print_r($jsessionid,true));

curl_setopt($ch,CURLOPT_URL,$BaseURL."/dmsuite/logout.do");
curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$jsessionid);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//curl_setopt($ch, CURLOPT_REFERER, "http://".$internalengine."/Server.html");
mydebug("curl request: ".print_r($ch,true));

// Perform the request, returning the raw response
// (headers included) as a string:
$result = curl_exec($ch);

// Get the response status code:
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

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

//list($headers, $content) = explode("\r\n\r\n", $result, 2);
//mydebug("response headers: ".print_r($headers,true));
//mydebug("response content: ".print_r($content,true));
//
//mydebug("curl result: ".print_r($result,true));
//
//$headersArray = explode("\r\n", $headers);
//foreach ($headersArray as $head)
//{
//    header($head);
//}
//
//
//print $content;
//print $result;
?>
