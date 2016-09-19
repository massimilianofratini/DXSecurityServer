<?php 
$cookie_jar = tempnam('/var/www/DE1/log/','mycookies');
//print $cookie_jar;

$ch=curl_init(); 

$post_params=array('type'=>'APISession','version'=>array('major'=>1,'micro'=>1,'minor'=>6,'type'=>'APIVersion'));
$json_post_params=json_encode($post_params);
//print $json_post_params;

curl_setopt($ch,CURLOPT_URL,'http://172.16.226.131/resources/json/delphix/session'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
//curl_setopt($ch_sess,CURLOPT_POST,1); 
curl_setopt($ch,CURLOPT_POSTFIELDS,$json_post_params);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($json_post_params),                                                                                
    'Accept: application/json, text/javascript, */*; q=0.01',
    'X-Requested-With: XMLHttpRequest')                                                                       
);                                                   
curl_setopt($ch, CURLOPT_REFERER, "http://172.16.226.131/login/index.html");
                                                                
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
$result = curl_exec($ch);
curl_close($ch);
//return;

// get cookie
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
//print $cookies["JSESSIONID"];
//var_dump($cookies);

//sleep(2);

$ch=curl_init(); 
$post_params=array('username'=>'massimiliano.fratini','password'=>'delphix','target'=>'DOMAIN','type'=>'LoginRequest');
$json_post_params=json_encode($post_params);
//print $json_post_params;

curl_setopt($ch,CURLOPT_URL,"http://172.16.226.131/resources/json/delphix/login"); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
//curl_setopt($ch,CURLOPT_POST,1); 
curl_setopt($ch,CURLOPT_POSTFIELDS,$json_post_params);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($json_post_params),                                                                                
    'Accept: application/json, text/javascript, */*; q=0.01',
    'X-Requested-With: XMLHttpRequest')                                                                       
);                                 
//curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$cookies["JSESSIONID"]);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_REFERER, "http://172.16.226.131/login/index.html");

$result=curl_exec($ch); 
//$info = curl_getinfo($ch);
//print_r($info);
//print_r($info['request_header']);
curl_close($ch); 


header("refresh: 5; url=/Server.html");
setcookie("JSESSIONID", $cookies["JSESSIONID"], time() + 60 * 60 * 24 * 100);
exit();
/*
$ch=curl_init(); 

curl_setopt($ch,CURLOPT_URL,'http://172.16.226.131/Server.html'); 
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_REFERER, "http://172.16.226.131/login/index.html");

curl_exec($ch); 
//$result=curl_exec($ch); 
//print $result;
*/


?>
