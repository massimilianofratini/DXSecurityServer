<?php 

session_start();
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');



echo DMSuiteCreateUser(
    $_GET['engineip'],
    $_GET['engineport'],
    $_GET['admuser'],
    $_GET['admpass'],
    $_GET['user'],
    $_GET['userpass'],
    $_GET['name'],
    $_GET['surname'],
    $_GET['email']
);
flush();

function tokenify ($number) {
    $tokenbuf = array();
    $charmap = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*$";
    $remainder = $number;
    while ($remainder > 0) {
        array_push($tokenbuf, $charmap{$remainder & 0x3F});
        $remainder = floor($remainder / 64);
    }
    return implode($tokenbuf);
}

function DMSuiteCreateUser($engineip, $engineport, $admuser, $admpass, $user, $userpass, $name, $surname, $email )
{

    $ch = curl_init();

    //------LOAD LOGIN PAGE AND GET JSESSIONID
    //get internal engine parameters
    $LoginURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/login.do";
    mydebug("Getting JSESSIONID from masking server: " . $LoginURL);

    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $LoginURL);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8')
    );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    // Perform the request, returning the raw response
    // (headers included) as a string:
    $result = curl_exec($ch);
    //mydebug("curl RESPONSE: " . print_r($result, true));
    // Get the response status code:
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    mydebug("curl STATUS CODE: " . print_r($status_code, true));

    if (curl_errno($ch)) {
        mydebug("Connect to engine $engineip failed");
        return ("Connect to engine $engineip failed");
    }

    //Now find jsessionid from cookies
    preg_match_all('/^Set-Cookie:\s*([^\r\n;]*)/mi', $result, $matches);
    $cookies = array();
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        list($cookiename, $cookievalue) = explode('=', $item, 2);
        $cookies[$cookiename] = $cookievalue;
    }
    $jsessionid = $cookies["JSESSIONID"];
    mydebug("Got JSESSIONID: " . $jsessionid);

    //------LOGIN TO MASKING ENGINE
    //get internal engine parameters
    $LoginURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/login.do";
    mydebug("Authenticating to masking server: " . $LoginURL);

    //url-ify the data for the POST
    $post_params = array('userName' => $admuser, 'password' => $admpass, 'action' => '');
    $fields_string = "";
    foreach ($post_params as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    curl_setopt($ch, CURLOPT_URL, $LoginURL);
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
    curl_setopt($ch, CURLOPT_REFERER, $LoginURL . ";jsessionid=$jsessionid");
    // Perform the request, returning the raw response
    // (headers included) as a string:
    $result = curl_exec($ch);
    mydebug("curl RESPONSE: " . print_r($result, true));
    // Get the response status code:
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    mydebug("curl STATUS CODE: " . print_r($status_code, true));

    if (curl_errno($ch)) {
        mydebug("Login to engine $engineip failed");
        return ("Login to engine $engineip failed");
    }


    //--------------- GET CSRF TOKEN
    //curl "http://ip:port/dmsuite/JavaScriptServlet" -H "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0" -H "Accept: */*" -H "Accept-Language: en-US,it;q=0.7,en;q=0.3" --compressed -H "Referer: http://10.13.223.180:8282/dmsuite/login.do;jsessionid=C18987544AB1B4259396AE9715DA14D6" -H "Cookie: JSESSIONID=C18987544AB1B4259396AE9715DA14D6" -H "Connection: keep-alive"
    //RETURN JavaScriptServlet to parse for the following code
    // this.setRequestHeader(\"X-Requested-With\", \"CSRFGuard\");
    //OR
    // this.setRequestHeader(\"CSRFTOKEN\", \"QNYF-65YM-60BA-XCPO-8X72-ZSJ3-GUXY-ASDD\");
    //OR
    // injectTokens(\"CSRFTOKEN\", \"QNYF-65YM-60BA-XCPO-8X72-ZSJ3-GUXY-ASDD\")
    $CSRFUrl = "http://" . $engineip . ":" . $engineport . "/dmsuite/JavaScriptServlet";
    mydebug("Getting CRSF Token from: " . $CSRFUrl);

// --- PHP_CURL BUG: transfer-encoding chunked not correctly managed, only first part is downloaded
//    //url-ify the data for the POST
//    curl_setopt($ch, CURLOPT_URL, $CSRFUrl);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 10 );
//    curl_setopt($ch, CURLOPT_ENCODING, '' );
//    curl_setopt($ch, CURLOPT_MAXREDIRS, 3 );
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
//    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
//    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=" . $jsessionid);
//    curl_setopt($ch, CURLOPT_HTTPGET, 1);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: */*',
//            'Accept-Language: en-US,it;q=0.7,en;q=0.3')
//    );
//    curl_setopt($ch, CURLOPT_HEADER, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//    curl_setopt($ch, CURLOPT_REFERER, $LoginURL . ";jsessionid=$jsessionid");
//    mydebug("curl request: " . print_r($ch, true));
//    // Perform the request, returning the raw response
//    // (headers included) as a string:
//    $result = curl_exec($ch);
//    mydebug("curl RESPONSE: " . print_r($result, true));
//    // Get the response status code:
//    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    mydebug("curl STATUS CODE: " . print_r($status_code, true));
//
//    if (curl_errno($ch)) {
//        mydebug("Error calling CRSF Servlet from engine $engineip");
//        return ("Error calling CRSF Servlet from engine $engineip");
//    }

    //--Workaround for bug on transfer-encoding chunked
    $command = "curl $CSRFUrl -H 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0' -H 'Accept: */*' -H 'Accept-Language: en-US,it;q=0.7,en;q=0.3' --compressed -H 'Referer: $LoginURL;jsessionid=$jsessionid' -H 'Cookie: JSESSIONID=$jsessionid' -H 'Connection: keep-alive'";
    mydebug("COMMAND is: " . print_r($command, true));
    $result=shell_exec($command);
    //mydebug("system curl RESPONSE: " . print_r($result, true));

    //Get token from response body
    if(preg_match('/injectTokens\("CSRFTOKEN",\s*"(.*)"\)/', $result, $matches)) {
        $CSRFToken = $matches[1];
        mydebug ("Got CRSF Token from engine $engineip: $CSRFToken");
    }
    else {
        mydebug ("Failure getting CRSF Token from engine $engineip, with result: $result");
        return ("Failure getting CRSF Token from engine $engineip, with result: $result");
    }


    //------NOW GENERATE SYSTEM ID
    //curl "http://ip:port/dmsuite/dwr/call/plaincall/__System.generateId.dwr"
    // -H "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0"
    // -H "Accept: */*" -H "Accept-Language: en-US,it;q=0.7,en;q=0.3"
    // -H "Referer: http://10.13.223.180:8282/dmsuite/userList.do"
    // -H "Content-Type: text/plain" -H "X-Requested-With: CSRFGuard"
    // -H "CSRFTOKEN: QNYF-65YM-60BA-XCPO-8X72-ZSJ3-GUXY-ASDD"
    // -H "Cookie: JSESSIONID=C18987544AB1B4259396AE9715DA14D6"
    // -H "Connection: keep-alive"
    // --data "callCount=1"^
    //"c0-scriptName=__System"^
    //"c0-methodName=generateId"^
    //"c0-id=0"^
    //"batchId=0"^
    //"instanceId=0"^
    //"page="%"2Fdmsuite"%"2FuserList.do"^
    //"scriptSessionId="^
    //""
    $GenerateIdURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/dwr/call/plaincall/__System.generateId.dwr";
    $postData = "callCount=1\nc0-scriptName=__System\nc0-methodName=generateId\nc0-id=0\ninstanceId=0\nbatchId=0\npage=%2Fdmsuite%2FuserList.do\nscriptSessionId=\n";

//callCount=1
//c0-scriptName=__System
//c0-methodName=generateId
//c0-id=0
//batchId=0
//instanceId=0
//page=%2Fdmsuite%2FuserList.do
//scriptSessionId=


    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $GenerateIdURL);
    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=" . $jsessionid);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/plain',
            'Accept: */*',
            'X-Requested-With: CSRFGuard',
            'CSRFTOKEN: '.$CSRFToken,
            'Accept-Language: en-US,it;q=0.7,en;q=0.3',
            'Content-Length: '. strlen($postData))
    );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://" . $engineip . ":" . $engineport . "/dmsuite/userList.do");

    mydebug("curl request: " . print_r($ch, true));
    // Perform the request, returning the raw response
    // (headers included) as a string:
    $result = curl_exec($ch);
    mydebug("curl RESPONSE: " . print_r($result, true));
    // Get the response status code:
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    mydebug("curl STATUS CODE: " . print_r($status_code, true));

    if (curl_errno($ch)) {
        mydebug("Error calling GenerateId from engine $engineip");
        return ("Error calling GenerateId from engine $engineip");
    }

    //Get token from response body
    if(preg_match('/handleCallback\(.*,.*,\s*"(.*)"\);/', $result, $matches)) {
        $scriptSessionID = $matches[1];
        mydebug ("SystemId created: $scriptSessionID");
    }
    else {
        mydebug ("Getting scriptSessionID from engine $engineip failed, with result: $result");
        return ("Getting scriptSessionID from engine $engineip failed, with result: $result");
    }


    //------NOW CREATE THE USER
    //curl 'http://maskpom180.delphix-collaudo.telecomitalia.local/dmsuite/dwr/call/plaincall/UserAjax.saveUser.dwr'
    //-H 'Pragma: no-cache'
    //-H 'Origin: http://maskpom180.delphix-collaudo.telecomitalia.local'
    //-H 'Accept-Encoding: gzip, deflate'
    //-H 'Accept-Language: en-US,en;q=0.9'
    //-H 'CSRFTOKEN: GVN1-6ENO-4XIZ-GRSH-C22V-5APK-17T3-K164'
    //-H 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
    //-H 'Content-Type: text/plain'
    //-H 'Accept: */*'
    //-H 'Cache-Control: no-cache'
    //-H 'X-Requested-With: CSRFGuard'
    //-H 'Cookie: JSESSIONID=9C7FA8E040A7CDAF13ECD66F1D8DE612; DWRSESSIONID=DfC65GJ1eGOF2GzzCUxzasIEwdkAhqRA!om; PHPSESSID=7peqfm55ttuc68rqemd38if276; secsrvsessionid=MjQ5MzkzN2EwZjA0YjdhNWI4ZjI5ZDgwMWI1NThiNDI%3D'
    //-H 'Connection: keep-alive'
    //-H 'Referer: http://maskpom180.delphix-collaudo.telecomitalia.local/dmsuite/userList.do?CSRFTOKEN=GVN1-6ENO-4XIZ-GRSH-C22V-5APK-17T3-K164'
    //--data-binary $'callCount=1\nnextReverseAjaxIndex=0\nc0-scriptName=UserAjax\nc0-methodName=saveUser\nc0-id=0\nc0-param0=string:NOME\nc0-param1=string:COGNOME\nc0-param2=string:USERNAME\nc0-param3=string:EMAIL%40abc.com\nc0-param4=string:Violino951.\nc0-param5=string:\nc0-param6=string:2\nc0-param7=boolean:false\nc0-param8=string:\nc0-e1=string:44\nc0-param9=array:[reference:c0-e1]\nc0-param10=boolean:false\nbatchId=0\ninstanceId=0\npage=%2Fdmsuite%2FuserList.do%3FCSRFTOKEN%3DGVN1-6ENO-4XIZ-GRSH-C22V-5APK-17T3-K164\nscriptSessionId=DfC65GJ1eGOF2GzzCUxzasIEwdkAhqRA!om/yheB*om-9IwXHUSip\n' --compressed

    $dwrSuffix=tokenify(round(microtime(true) * 1000))."-".tokenify(rand(0,1E16));


    $SaveUserURL = "http://" . $engineip . ":" . $engineport . "/dmsuite/dwr/call/plaincall/UserAjax.saveUser.dwr";
    $postData = "callCount=1\nnextReverseAjaxIndex=0\nc0-scriptName=UserAjax\nc0-methodName=saveUser\nc0-id=0\nc0-param0=string:_NOME_\nc0-param1=string:_COGNOME_\nc0-param2=string:_USERNAME_\nc0-param3=string:_EMAIL_\nc0-param4=string:_PASSWORD_\nc0-param5=string:\nc0-param6=string:2\nc0-param7=boolean:false\nc0-param8=string:\nc0-param9=null:null\nc0-param10=boolean:false\nbatchId=1\ninstanceId=0\npage=%2Fdmsuite%2FuserList.do\nCSRFTOKEN=_TOKEN_\nscriptSessionId=_SCRIPTSESSIONID_\n";
    //$postData = "callCount=1\nnextReverseAjaxIndex=0\nc0-scriptName=UserAjax\nc0-methodName=saveUser\nc0-id=0\nc0-param0=string:_NOME_\nc0-param1=string:_COGNOME_\nc0-param2=string:_USERNAME_\nc0-param3=string:_EMAIL_\nc0-param4=string:_PASSWORD_\nc0-param5=string:\nc0-param6=string:2\nc0-param7=boolean:false\nc0-param8=string:\nc0-e1=string:\nc0-param9=array:\nc0-param10=boolean:false\nbatchId=0\ninstanceId=0\n";//page=%2Fdmsuite%2FuserList.do%3FCSRFTOKEN%3_TOKEN_\nscriptSessionId=_SCRIPTSESSIONID_\n";
    $postData = str_replace("_NOME_", $name, $postData);
    $postData = str_replace("_COGNOME_", $surname, $postData);
    $postData = str_replace("_USERNAME_", $user, $postData);
    $postData = str_replace("_PASSWORD_", $userpass, $postData);
    $postData = str_replace("_EMAIL_", $email, $postData);
    $postData = str_replace("_TOKEN_", $CSRFToken, $postData);
    $postData = str_replace("_SCRIPTSESSIONID_", $scriptSessionID."/".$dwrSuffix, $postData);
    mydebug ("Posting create user request with data : $postData");

    curl_setopt($ch, CURLOPT_URL, $SaveUserURL);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=$jsessionid;DWRSESSIONID=$scriptSessionID");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: */*',
            'X-Requested-With: CSRFGuard',
            'CSRFTOKEN: '.$CSRFToken,
            'Accept-Language: en-US,it;q=0.7,en;q=0.3')
    );
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_REFERER, $LoginURL . ";jsessionid=$jsessionid");

    //$headers = array();
    //$headers[] = "Pragma: no-cache";
    //$headers[] = "Origin: http://maskpom180.delphix-collaudo.telecomitalia.local";
    //$headers[] = "Accept-Encoding: gzip, deflate";
    //$headers[] = "Accept-Language: en-US,en;q=0.9";
    //$headers[] = "Csrftoken: GVN1-6ENO-4XIZ-GRSH-C22V-5APK-17T3-K164";
    //$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
    //$headers[] = "Content-Type: text/plain";
    //$headers[] = "Accept: */*";
    //$headers[] = "Cache-Control: no-cache";
    //$headers[] = "X-Requested-With: CSRFGuard";
    //$headers[] = "Cookie: JSESSIONID=9C7FA8E040A7CDAF13ECD66F1D8DE612; DWRSESSIONID=DfC65GJ1eGOF2GzzCUxzasIEwdkAhqRA!om; PHPSESSID=7peqfm55ttuc68rqemd38if276; secsrvsessionid=MjQ5MzkzN2EwZjA0YjdhNWI4ZjI5ZDgwMWI1NThiNDI%3D";
    //$headers[] = "Connection: keep-alive";
    //$headers[] = "Referer: http://maskpom180.delphix-collaudo.telecomitalia.local/dmsuite/userList.do?CSRFTOKEN=GVN1-6ENO-4XIZ-GRSH-C22V-5APK-17T3-K164";

    //Extract headers from prev call
    //list($headersString, $content) = explode("\r\n\r\n", $result, 2);
    //mydebug("response headers: " . print_r($headersString, true));
    //mydebug("response content: " . print_r($content, true));
    //$headers=explode("\r\n", $headersString);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    list($headers, $content) = explode("\r\n\r\n", $result, 2);
    mydebug("response headers: " . print_r($headers, true));
    mydebug("response content: " . print_r($content, true));
    if (curl_errno($ch)) {
        mydebug("Create user $user on engine $engineip failed with reason $content");
        return ("Create user $user to engine $engineip failed with reason $content");
    }
    curl_close($ch);
    return $content;
}
?>
