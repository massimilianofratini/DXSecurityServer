<?php
require_once('../../../common/secsrv_includes/config.php');


// The URL to POST to
$url = Cfg::NIAM_WSURL;

// The value for the SOAPAction: header
$action = "My.Soap.Action";

// Get the SOAP data into a string, I am using HEREDOC syntax
// but how you do this is irrelevant, the point is just get the
// body of the request into a string
$mySOAP = <<<EOD
<?xml version="1.0" encoding="utf-8" ?>
<soap:Envelope>
  <!-- SOAP goes here, irrelevant so wont bother writing it out -->
</soap:Envelope>
EOD;

// The HTTP headers for the request (based on image above)
$headers = array(
		'Content-Type: text/xml; charset=utf-8',
		'Content-Length: '.strlen($mySOAP),
		'SOAPAction: '.$action
);

// Build the cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $mySOAP);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLS_RSA_WITH_RC4_128_MD5:TLS_RSA_WITH_AES_256_CBC_SHA:TLS_RSA_WITH_AES_128_CBC_SHA:TLS_RSA_WITH_3DES_EDE_CBC_SHA');
//curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLS_RSA_WITH_RC4_128_MD5');

// Send the request and check the response
if (($result = curl_exec($ch)) === FALSE) {
	die('cURL error: '.curl_error($ch)."<br />\n");
} else {
	echo "Success!<br />\n";
}
curl_close($ch);

// Handle the response from a successful request
$xmlobj = simplexml_load_string($result);
var_dump($xmlobj);

?>
