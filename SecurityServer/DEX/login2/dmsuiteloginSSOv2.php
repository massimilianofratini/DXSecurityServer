<?php 

session_start();
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('sessionInfo.php');
require_once_common('crypto.php');

//include_once 'config.php';
//include_once 'sessionInfo.php';
//include_once 'mylogger.php';
//include_once 'crypto.php';
require_once(__DIR__ . '/delphixSession.php');


function loginUAOnDMSuite($engineip, $engineport, $user, $userpass)
{
    $ch = curl_init();

////------LOAD LOGIN PAGE AND GET JSESSIONID
////get internal engine parameters
//    $LoginURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/login.do";
//    mydebug("Getting JSESSIONID from masking server: " . $LoginURL);
//
//    curl_setopt($ch, CURLOPT_URL, $LoginURL);
//    curl_setopt($ch, CURLOPT_POST, 0);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Content-Type: application/x-www-form-urlencoded',
//            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
//    );
//    curl_setopt($ch, CURLOPT_HEADER, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//// Perform the request, returning the raw response
//// (headers included) as a string:
//    $result = curl_exec($ch);
////mydebug("curl RESPONSE: " . print_r($result, true));
//// Get the response status code:
//    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    mydebug("curl STATUS CODE: " . print_r($status_code, true));
//
////Now find jsessionid from cookies
//    preg_match_all('/^Set-Cookie:\s*([^\r\n;]*)/mi', $result, $matches);
//    $cookies = array();
//    foreach ($matches[1] as $item) {
//        parse_str($item, $cookie);
//        list($cookiename, $cookievalue) = explode('=', $item, 2);
//        $cookies[$cookiename] = $cookievalue;
//    }
//    $jsessionid = $cookies["JSESSIONID"];
//    mydebug("Got JSESSIONID: " . $jsessionid);


////--------------- GET CSRF TOKEN
////curl "http://ip:port/dmsuite/JavaScriptServlet" -H "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0" -H "Accept: */*" -H "Accept-Language: en-US,it;q=0.7,en;q=0.3" --compressed -H "Referer: http://10.13.223.180:8282/dmsuite/login.do;jsessionid=C18987544AB1B4259396AE9715DA14D6" -H "Cookie: JSESSIONID=C18987544AB1B4259396AE9715DA14D6" -H "Connection: keep-alive"
////RETURN JavaScriptServlet to parse for the following code
//// this.setRequestHeader(\"X-Requested-With\", \"CSRFGuard\");
////OR
//// this.setRequestHeader(\"CSRFTOKEN\", \"QNYF-65YM-60BA-XCPO-8X72-ZSJ3-GUXY-ASDD\");
////OR
//// injectTokens(\"CSRFTOKEN\", \"QNYF-65YM-60BA-XCPO-8X72-ZSJ3-GUXY-ASDD\")
//    $CSRFUrl = "http://" . $engineip . ":" . $engineport . "/dmsuite/JavaScriptServlet";
//    mydebug("Getting CRSF Token from: " . $CSRFUrl);
//
//// --- PHP_CURL BUG: transfer-encoding chunked not correctly managed, only first part is downloaded
////--Workaround for bug on transfer-encoding chunked
//    $command = "curl $CSRFUrl -H 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0' -H 'Accept: */*' -H 'Accept-Language: en-US,it;q=0.7,en;q=0.3' --compressed -H 'Referer: $LoginURL;jsessionid=$jsessionid' -H 'Cookie: JSESSIONID=$jsessionid' -H 'Connection: keep-alive'";
//    mydebug("COMMAND is: " . print_r($command, true));
//    $result = shell_exec($command);
////mydebug("system curl RESPONSE: " . print_r($result, true));
//
////Get token from response body
//    if (preg_match('/injectTokens\("CSRFTOKEN",\s*"(.*)"\)/', $result, $matches)) {
//        $CSRFToken = $matches[1];
//        mydebug("Got CRSF Token from engine $engineip: $CSRFToken");
//    } else {
//        mydebug("Failure getting CRSF Token from engine $engineip, with result: $result");
//        return ("Failure getting CRSF Token from engine $engineip, with result: $result");
//    }
//

//------LOGIN TO MASKING ENGINE
//get internal engine parameters

    //$LoginURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/login.do?CSRFTOKEN=$CSRFToken";
    $LoginURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/login.do";
    mydebug("Authenticating to masking server: " . $LoginURL);
    $jsessionid = "";
//url-ify the data for the POST
    //$post_params = array('userName' => $user, 'password' => $userpass, 'action' => '', 'CSRFTOKEN' => $CSRFToken);
    $post_params = array('userName' => $user, 'password' => $userpass, 'action' => '');
    $fields_string = "";
    foreach ($post_params as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    curl_setopt($ch, CURLOPT_URL, $LoginURL);
    //curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=" . $jsessionid);
    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=" . $jsessionid);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
    );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //curl_setopt($ch, CURLOPT_REFERER, $LoginURL . ";jsessionid=$jsessionid");
// Perform the request, returning the raw response
// (headers included) as a string:
    $result = curl_exec($ch);
    mydebug("curl RESPONSE: " . print_r($result, true), "TRACE", 10);
// Get the response status code:
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    mydebug("curl STATUS CODE: " . print_r($status_code, true));

//Check if login succeeded
    if (stripos($result, "Location: environmentList.do") === false) {
        mydebug("Login to engine $engineip failed");
        return ("Login to engine $engineip failed");
    } else {
        mydebug("Login to engine $engineip succeeded");
        return $result;
    }

}


function oldLoginDMSuite($sessionInfo) {
    //get internal engine parameters
    $BaseURL = "http://".$sessionInfo["internalip"].":".$sessionInfo["internalport"];
    mydebug("Authenticating to masking server: " . $BaseURL);

    $key = myhex2bin(Crypto::SEED);
    $engineip = $sessionInfo["internalip"];
    $engineport = $sessionInfo["internalport"];
    $de_username = $sessionInfo["de_username"];
    $de_password = Crypto::decrypt($sessionInfo["de_password"], $key, true);
    mydebug("username:".$de_username);
    mydebug("decrypted password:" . $de_password, "TRACE", 10);

    $ch=curl_init();

    //url-ify the data for the POST
    $post_params=array('userName'=>$de_username,'password'=>$de_password,'action'=>'');
    $fields_string="";
    foreach($post_params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');

    $jsessionid=$_COOKIE["JSESSIONID"];
    mydebug("JSESSIONID is : ".print_r($jsessionid,true));

    curl_setopt($ch,CURLOPT_URL,$BaseURL."/dmsuite/login.do");
    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$jsessionid);
    curl_setopt($ch,CURLOPT_POST, count($post_params));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
    );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_REFERER, $BaseURL."/dmsuite/login.do;jsessionid=$jsessionid");
    mydebug("curl request: ".print_r($ch,true));

    // Perform the request, returning the raw response
    // (headers included) as a string:
    $result = curl_exec($ch);
    mydebug("curl RESPONSE: ".print_r($result,true));

    // Get the response status code:
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    mydebug("curl STATUS CODE: ".print_r($status_code,true));

    curl_close($ch);
    return $result;
}

//-----MAIN


//Get "secsrvsessionid" authentication cookie
$secsrvsession = $_COOKIE[Cfg::SECSRVSESSIONID];
$sessionInfo = getSessionInfo($secsrvsession);
//check if session is found
if ($sessionInfo == false) {
    mydebug("session $secsrvsession not found in db, closing.");
    header("refresh:2; url=".Cfg::PORTAL_URL);
    echo "<html><body>Session closed, please login on <a href='".Cfg::PORTAL_URL."'>Security Server Portal</a>
          <script type=\"text/javascript\">setTimeout(\"window.close();\", 1000);</script>
          </body></html>";
    return;
}

//get internal engine parameters
//$BaseURL = "http://".$sessionInfo["internalip"].":".$sessionInfo["internalport"];
//mydebug("Authenticating to masking server: " . $BaseURL);

$key = myhex2bin(Crypto::SEED);
$engineip = $sessionInfo["internalip"];
$engineport = $sessionInfo["internalport"];
$de_username = $sessionInfo["de_username"];
$de_password = Crypto::decrypt($sessionInfo["de_password"], $key, true);
mydebug("username:".$de_username);
mydebug("decrypted password:" . $de_password, "TRACE", 10);

//$ch=curl_init();
//
////url-ify the data for the POST
//$post_params=array('userName'=>$de_username,'password'=>$de_password,'action'=>'');
//$fields_string="";
//foreach($post_params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
//rtrim($fields_string, '&');
//
//$jsessionid=$_COOKIE["JSESSIONID"];
//mydebug("JSESSIONID is : ".print_r($jsessionid,true));
//
//curl_setopt($ch,CURLOPT_URL,$BaseURL."/dmsuite/login.do");
//curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$jsessionid);
//curl_setopt($ch,CURLOPT_POST, count($post_params));
//curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//    'Content-Type: application/x-www-form-urlencoded',
//    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
//);
//curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//curl_setopt($ch, CURLOPT_REFERER, $BaseURL."/dmsuite/login.do;jsessionid=$jsessionid");
//mydebug("curl request: ".print_r($ch,true));
//
//// Perform the request, returning the raw response
//// (headers included) as a string:
//$result = curl_exec($ch);
//mydebug("curl RESPONSE: ".print_r($result,true));
//
//// Get the response status code:
//$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//mydebug("curl STATUS CODE: ".print_r($status_code,true));
//
//curl_close($ch);

//$loginResult = loginUAOnDMSuite($engineip,$engineport,$de_username,$de_password);
$loginResult = oldLoginDMSuite($sessionInfo);
mydebug("Login response : $loginResult", "TRACE", 10);
if (stripos($loginResult, "failed") !== false) {
    mydebug("Login to engine $engineip failed");
    return ("Login to engine $engineip failed");
}



list($headers, $content) = explode("\r\n\r\n", $loginResult, 2);
mydebug("response headers: ".print_r($headers,true));
mydebug("response content: ".print_r($content,true));

$headersArray = explode("\r\n", $headers);
foreach ($headersArray as $head)
{
    header($head);
}

print $content;
//print $result;


?>
