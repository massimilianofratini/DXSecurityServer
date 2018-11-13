<?php

/**
 * Class SoapClientCurl extends SoapClient __doRequest method with curl powered method
 * 
 * EXAMPLE
 * require_once(' SoapClientCurl.php');
 *
 * //Define options
 * $options = array(
 *    'wsdlfile'=>$wsdlfile,
 *    'sslversion'=>0/3, 0=disabled
 *    'sslcertfile'=>$certfile,
 *    'sslkeyfile'=>$keyfile,
 *    'wsurl'=>$url,
 *    'sslpassphrase'=>$passphrase
 * );
 *
 * //Init client
 * $client = new SoapClientCurl($wsdlfile,$options);
 *
 * //Request:
 * $client->Method($data);
 * 
 */
require_once '../mylogger.php';

class SoapClientCurl extends SoapClient{

	//Required variables
	public $wsurl       = null;
	public $sslversion	= null;
	public $sslcertfile    = null;
	public $sslkeyfile     = null;
	public $sslpassphrase  = null;

	//Overwrite constructor and add our variables
	public function __construct($wsdl, $options = array()){

		parent::__construct($wsdl, $options);

		foreach($options as $field => $value){
			if(!isset($this->$field)){
				$this->$field = $value;
			}
			mydebug("Inside SoapClientCurl:".print_r($field,true)." = ".print_r($value,true));
			
		}
	}

	/*
	 * Overwrite __doRequest and replace with cURL. Return XML body to be parsed with SoapClient
	 */
	public function __doRequest ($request, $location, $action, $version, $one_way = 0) {

		mydebug("Inside SoapClientCurl, request is :\n".$request);
		
		//Patch CDATA encoding
		$request = str_replace("&lt;", "<", $request);
		$request = str_replace("&gt;", ">", $request);
		
		//Basic curl setup for SOAP call
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->wsurl); //Load from datasource
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'SOAPAction: ""'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		//SSL
		if (isset($this->sslversion) && ($this->sslversion!=0)) {
			curl_setopt($ch, CURLOPT_SSLVERSION, $this->sslversion);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			//curl_setopt($ch, CURLOPT_SSLCERT, $this->certfile);
			//curl_setopt($ch, CURLOPT_SSLKEY, $this->keyfile);
			//curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
			//curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->passphrase); //Load from datasource
		}
		
		//Parse cURL response
		$response            = curl_exec ($ch);
		$this->curl_errorno  = curl_errno($ch);
		mydebug("Inside SoapClientCurl:curl_exec returned with errno".$this->curl_errorno);
		
		if ($this->curl_errorno == CURLE_OK) {
			$this->curl_statuscode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		}
		$this->curl_errormsg  = curl_error($ch);

		//Close connection
		curl_close($ch);

		//Return response info
		mydebug("Inside SoapClientCurl:returned response".$response);
		return $response;
	}
}

?>
