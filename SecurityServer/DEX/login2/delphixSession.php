<?php
#
# Delphix Engine Configuration Parameters ...
#
//DMIP="172.16.160.195"             # include port if required, "172.16.160.195:80" or :443
//DMUSER=delphix_admin
//DMPASS=delphix
//COOKIE="~/cookies.txt"
//COOKIE=`eval echo $COOKIE`
//CONTENT_TYPE="Content-Type: application/json"
//DELAYTIMESEC=10
//BaseURL="http://${DMIP}/resources/json/delphix"
//#
//# Timestamp Value for Filename Backups ...
//#
//DT=`date '+%Y%m%d%H%M%S'`


function delphixSession($BaseURL, $de_username, $de_password, $secsrvsession )
{


    $json_post_params = '
    {
      "type": "APISession",
      "version": {
          "type": "APIVersion",
          "major": 1,
          "minor": 7,
          "micro": 0
      }
    }';
    $CONTENT_TYPE = array(
        'Content-Type: application/json',
        'X-Browser-Session: true',
        'Content-Length: ' . strlen($json_post_params),
        'Accept: application/json, text/javascript, */*; q=0.01',
        'X-Requested-With: XMLHttpRequest');


    //Open Delphix Session
    $ch = curl_init();
    //$cookie_file = dirname(__FILE__) . '/cookie'.escapeshellarg($secsrvsession).'.txt';
    $cookie_file = dirname(__FILE__) . '/cookie.txt';

    curl_setopt($ch, CURLOPT_URL, $BaseURL . "/resources/json/delphix/session");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post_params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $CONTENT_TYPE );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    mydebug("Open Session request: " . print_r($ch, true));

    $result = curl_exec($ch);
    mydebug("...Open Session result: " . print_r($result, true));

    //Now login on the Delphix Engine
    $post_params = array('username' => $de_username, 'password' => $de_password, 'type' => 'LoginRequest');
    $json_post_params = json_encode($post_params);
    $CONTENT_TYPE = array(
        'Content-Type: application/json',
        'X-Browser-Session: true',
        'Content-Length: ' . strlen($json_post_params),
        'Accept: application/json, text/javascript, */*; q=0.01',
        'X-Requested-With: XMLHttpRequest');

    curl_setopt($ch, CURLOPT_URL, $BaseURL . "/resources/json/delphix/login");
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post_params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $CONTENT_TYPE );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    mydebug("Delphix Login request: " . print_r($ch, true));

    $result = curl_exec($ch);
    mydebug("...Delphix Login result: " . print_r($result, true));


    curl_close($ch);
    return $result;
}

//  STATUS = `curl -s -X POST -k --data @- $BaseURL/session -c "${COOKIE}" -H "${CONTENT_TYPE}" <<EOF
//  {
//      "type": "APISession",
//      "version": {
//          "type": "APIVersion",
//          "major": 1,
//          "minor": 7,
//          "micro": 0
//      }
//  }
//  EOF
//  `
//
//     #echo "Session: ${STATUS}"
//     RESULTS = $( jqParse "${STATUS}" "status" )
//
//     STATUS = `curl -s -X POST -k --data @- $BaseURL/login -b "${COOKIE}" -H "${CONTENT_TYPE}" <<EOF
//  {
//      "type": "LoginRequest",
//      "username": "${DMUSER}",
//      "password": "${DMPASS}"
//  }
//  EOF
//  `
//
//     #echo "Login: ${STATUS}"
//     RESULTS = $( jqParse "${STATUS}" "status" )
//
//     echo $RESULTS